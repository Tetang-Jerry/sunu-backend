<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\TrainingSession;
use App\Models\Coach;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SessionsController extends Controller
{
    public function index()
    {
        $sessions = TrainingSession::with(['coach.user','user'])->latest('date_time')->paginate(12);
        return view('sessions.index', compact('sessions'));
    }

    public function create()
    {
        $coaches = Coach::with('user')->orderBy('id','desc')->get();
        $members = Member::all()->sortBy(function($m) {
            return $m->first_name . ' ' . $m->last_name;
        })->values();
        $availabilityByCoach = $coaches->map(function($c){
            return [
                'id' => $c->id,
                'availability' => $c->availability_json ?? [],
            ];
        })->keyBy('id')->map(function($x){ return $x['availability']; })->toArray();
        return view('sessions.create', compact('coaches','members','availabilityByCoach'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'coach_id'    => ['required', Rule::exists('coaches','id')],
            'user_id'     => ['required', Rule::exists('members','id')],
            'slot_date'   => ['required','date'],
            'slot_period' => ['required','regex:/^\d{2}:\d{2}-\d{2}:\d{2}$/'],
            'status'      => ['required', Rule::in(['à venir','complétée','annulée'])],
            'location'    => ['nullable', 'string', 'max:255'],
        ]);

        // Ensure selected slot exists for coach
        $coach = Coach::findOrFail($data['coach_id']);
        $availability = collect($coach->availability_json ?? []);
        $validPeriods = $availability->where('date', $data['slot_date'])
            ->pluck('periods')->flatten()->values()->all();
        if (!in_array($data['slot_period'], $validPeriods, true)) {
            return back()->withErrors(['slot_period' => "Ce créneau n'est pas disponible pour ce coach."])->withInput();
        }

        // Compute date_time and duration from period
        [$start, $end] = explode('-', $data['slot_period']);
        $dateTime = \Carbon\Carbon::parse($data['slot_date']." ".$start);
        $endTime  = \Carbon\Carbon::parse($data['slot_date']." ".$end);
        if ($endTime->lessThanOrEqualTo($dateTime)) {
            return back()->withErrors(['slot_period' => 'La fin doit être après le début.'])->withInput();
        }
        $duration = $dateTime->diffInMinutes($endTime);

        $session = TrainingSession::create([
            'coach_id'    => $data['coach_id'],
            'user_id'     => $data['user_id'],
            'slot_date'   => $data['slot_date'],
            'slot_period' => $data['slot_period'],
            'date_time'   => $dateTime,
            'duration'    => $duration,
            'status'      => $data['status'],
            'location'    => $data['location'] ?? null,
        ]);

        // Send in-app notifications to coach (user) and admins
        try {
            $coachUserId = optional(Coach::with('user')->find($session->coach_id))->user->id ?? null;
            $adminIds = User::where('role','admin')->pluck('id')->all();
            $recipientIds = collect([$coachUserId])->filter()->merge($adminIds)->unique()->values();
            if ($recipientIds->count()) {
                $title = 'Nouvelle séance planifiée';
                $member = optional(User::find($session->user_id));
                $body = 'Séance le '. $session->date_time->format('d/m/Y H:i') . ' avec ' . trim(($member->first_name ?? '').' '.($member->last_name ?? ''));
                $url = route('sessions.show', $session);
                foreach ($recipientIds as $uid) {
                    Notification::create([
                        'user_id' => $uid,
                        'title'   => $title,
                        'body'    => $body,
                        'url'     => $url,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // swallow notification errors so session creation still succeeds
        }
        return redirect()->route('sessions.show', $session)->with('success', 'Séance créée avec succès');
    }

    public function show(TrainingSession $session)
    {
        $session->load(['coach.user','user']);
        return view('sessions.show', compact('session'));
    }

    public function edit(TrainingSession $session)
    {
        $coaches = Coach::with('user')->orderBy('id','desc')->get();
         $members = Member::all()->sortBy(function($m) {
            return $m->first_name . ' ' . $m->last_name;
        })->values();
        $availabilityByCoach = $coaches->map(function($c){
            return [
                'id' => $c->id,
                'availability' => $c->availability_json ?? [],
            ];
        })->keyBy('id')->map(function($x){ return $x['availability']; })->toArray();
        return view('sessions.edit', compact('session','coaches','members','availabilityByCoach'));
    }

    public function update(Request $request, TrainingSession $session)
    {
        $data = $request->validate([
            'coach_id'    => ['required', Rule::exists('coaches','id')],
            'user_id'     => ['required', Rule::exists('members','id')],
            'slot_date'   => ['required','date'],
            'slot_period' => ['required','regex:/^\d{2}:\d{2}-\d{2}:\d{2}$/'],
            'status'      => ['required', Rule::in(['à venir','complétée','annulée'])],
            'location'    => ['nullable', 'string', 'max:255'],
        ]);
        // Ensure selected slot exists for coach
        $coach = Coach::findOrFail($data['coach_id']);
        $availability = collect($coach->availability_json ?? []);
        $validPeriods = $availability->where('date', $data['slot_date'])
            ->pluck('periods')->flatten()->values()->all();
        if (!in_array($data['slot_period'], $validPeriods, true)) {
            return back()->withErrors(['slot_period' => "Ce créneau n'est pas disponible pour ce coach."])->withInput();
        }

        [$start, $end] = explode('-', $data['slot_period']);
        $dateTime = \Carbon\Carbon::parse($data['slot_date']." ".$start);
        $endTime  = \Carbon\Carbon::parse($data['slot_date']." ".$end);
        if ($endTime->lessThanOrEqualTo($dateTime)) {
            return back()->withErrors(['slot_period' => 'La fin doit être après le début.'])->withInput();
        }
        $duration = $dateTime->diffInMinutes($endTime);

        $session->update([
            'coach_id'    => $data['coach_id'],
            'user_id'     => $data['user_id'],
            'slot_date'   => $data['slot_date'],
            'slot_period' => $data['slot_period'],
            'date_time'   => $dateTime,
            'duration'    => $duration,
            'status'      => $data['status'],
            'location'    => $data['location'] ?? null,
        ]);
        return redirect()->route('sessions.show', $session)->with('success', 'Séance mise à jour avec succès');
    }

    public function destroy(TrainingSession $session)
    {
        $session->delete();
        return response()->json(['success' => true, 'message' => 'Séance supprimée avec succès']);
    }
}
