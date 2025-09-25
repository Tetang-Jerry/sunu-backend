<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::query()->latest()->paginate(12);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name'  => ['required','string','max:100'],
            'email'      => ['required','email','max:255', Rule::unique('users','email')],
            'password'   => ['required','string','min:6'],
            'phone'      => ['nullable','string','max:50'],
            'address'    => ['nullable','string','max:255'],
            'date_of_birth' => ['nullable','date'],
            'role'       => ['required','string','in:membre,coach,admin'],
        ]);

        $user = new User();
        $user->first_name = $data['first_name'];
        $user->last_name  = $data['last_name'];
        $user->email      = $data['email'];
        $user->password   = Hash::make($data['password']);
        $user->phone      = $data['phone'] ?? null;
        $user->address    = $data['address'] ?? null;
        $user->date_of_birth = $data['date_of_birth'] ?? null;
        $user->role       = $data['role'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name'  => ['required','string','max:100'],
            'email'      => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password'   => ['nullable','string','min:6'],
            'phone'      => ['nullable','string','max:50'],
            'address'    => ['nullable','string','max:255'],
            'date_of_birth' => ['nullable','date'],
            'role'       => ['required','string','in:membre,coach,admin'],
        ]);

        $user->first_name = $data['first_name'];
        $user->last_name  = $data['last_name'];
        $user->email      = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->phone      = $data['phone'] ?? null;
        $user->address    = $data['address'] ?? null;
        $user->date_of_birth = $data['date_of_birth'] ?? null;
        $user->role       = $data['role'];
        $user->save();

        return redirect()->route('users.show', $user)->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
    }
}
