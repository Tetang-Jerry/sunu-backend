<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use App\Models\Coach;
use App\Models\User;
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
        $members = User::where('role', 'membre')->orderBy('first_name')->orderBy('last_name')->get();
        return view('sessions.create', compact('coaches','members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'coach_id' => ['required', Rule::exists('coaches','id')],
            'user_id'  => ['required', Rule::exists('users','id')],
            'date_time'=> ['required', 'date'],
            'duration' => ['required', 'integer', 'min:1'],
            'status'   => ['required', Rule::in(['à venir','complétée','annulée'])],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $session = TrainingSession::create($data);
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
        $members = User::whereIn('role', ['membre','coach','admin'])->orderBy('first_name')->orderBy('last_name')->get();
        return view('sessions.edit', compact('session','coaches','members'));
    }

    public function update(Request $request, TrainingSession $session)
    {
        $data = $request->validate([
            'coach_id' => ['required', Rule::exists('coaches','id')],
            'user_id'  => ['required', Rule::exists('users','id')],
            'date_time'=> ['required', 'date'],
            'duration' => ['required', 'integer', 'min:1'],
            'status'   => ['required', Rule::in(['à venir','complétée','annulée'])],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $session->update($data);
        return redirect()->route('sessions.show', $session)->with('success', 'Séance mise à jour avec succès');
    }

    public function destroy(TrainingSession $session)
    {
        $session->delete();
        return response()->json(['success' => true, 'message' => 'Séance supprimée avec succès']);
    }
}
