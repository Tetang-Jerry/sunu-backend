<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CoachesController extends Controller
{
    public function index()
    {
        $coaches = Coach::with('user')->latest()->paginate(12);
        return view('coaches.index', compact('coaches'));
    }

    public function create()
    {
        // Only allow users with role 'admin'
        $users = User::where('role', 'coach')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id','first_name','last_name','email','role']);
        return view('coaches.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', Rule::exists('users','id')->where('role','coach')],
            'specialty' => ['nullable','string','max:255'],
            'availability_json' => ['nullable','string'],
            'bio' => ['nullable','string'],
        ]);

        // prevent duplicate coach per user
        $exists = Coach::where('user_id', $data['user_id'])->exists();
        if ($exists) {
            return back()->withErrors(['user_id' => 'Ce membre est déjà enregistré comme coach.'])->withInput();
        }

        // prepare availability_json
        $payload = [
            'user_id' => $data['user_id'],
            'specialty' => $data['specialty'] ?? null,
            'bio' => $data['bio'] ?? null,
        ];
        if (!empty($data['availability_json'])) {
            $decoded = json_decode($data['availability_json'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $payload['availability_json'] = $decoded;
            } else {
                return back()->withErrors(['availability_json' => 'Le format des disponibilités est invalide.'])->withInput();
            }
        }

        $coach = Coach::create($payload);
        return redirect()->route('coaches.show', $coach)->with('success', 'Coach créé avec succès');
    }

    public function show(Coach $coach)
    {
        $coach->load('user');
        return view('coaches.show', compact('coach'));
    }

    public function edit(Coach $coach)
    {
        // Allow switching to another 'coach' role user, if needed
        $users = User::where('role', 'coach')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id','first_name','last_name','email','role']);
        return view('coaches.edit', compact('coach','users'));
    }

    public function update(Request $request, Coach $coach)
    {
        $data = $request->validate([
            'user_id' => ['required', Rule::exists('users','id')->where('role','coach')],
            'specialty' => ['nullable','string','max:255'],
            'availability_json' => ['nullable','string'],
            'bio' => ['nullable','string'],
        ]);

        // If user is changed, ensure uniqueness (one coach per user)
        $exists = Coach::where('user_id', $data['user_id'])
            ->where('id', '!=', $coach->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['user_id' => 'Ce membre est déjà enregistré comme coach.'])->withInput();
        }

        $payload = [
            'user_id' => $data['user_id'],
            'specialty' => $data['specialty'] ?? null,
            'bio' => $data['bio'] ?? null,
        ];
        if (!empty($data['availability_json'])) {
            $decoded = json_decode($data['availability_json'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $payload['availability_json'] = $decoded;
            } else {
                return back()->withErrors(['availability_json' => 'Le format des disponibilités est invalide.'])->withInput();
            }
        } else {
            $payload['availability_json'] = null;
        }

        $coach->update($payload);
        return redirect()->route('coaches.show', $coach)->with('success', 'Coach mis à jour avec succès');
    }

    public function destroy(Coach $coach)
    {
        $coach->delete();

        
       return response()->json([
           'success' => true,
           'message' => 'Coach supprimé avec succès',
       ]);
    }
}
