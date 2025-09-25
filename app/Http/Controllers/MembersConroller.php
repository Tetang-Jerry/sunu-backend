<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MembersConroller extends Controller
{
    public function index()
    {
        $members = Member::query()->latest()->paginate(12);
        return view('Members.index', compact('members'));
    }

    public function create()
    {
        return view('Members.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name'  => ['required','string','max:100'],
            'email'      => ['required','email','max:255', Rule::unique('members','email')],
            'password'   => ['required','string','min:6'],
            'phone'      => ['nullable','string','max:50'],
            'address'    => ['nullable','string','max:255'],
        ]);

        $Member = new Member();
        $Member->first_name = $data['first_name'];
        $Member->last_name  = $data['last_name'];
        $Member->email      = $data['email'];
        $Member->password   = Hash::make($data['password']);
        $Member->phone      = $data['phone'] ?? null;
        $Member->address    = $data['address'] ?? null;
        $Member->save();

        return redirect()->route('members.index')->with('success', 'Utilisateur créé avec succès');
    }

    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name'  => ['required','string','max:100'],
            'email'      => ['required','email','max:255', Rule::unique('Members','email')->ignore($member->id)],
            'password'   => ['nullable','string','min:6'],
            'phone'      => ['nullable','string','max:50'],
            'address'    => ['nullable','string','max:255'],
        ]);

        $member->first_name = $data['first_name'];
        $member->last_name  = $data['last_name'];
        $member->email      = $data['email'];
        if (!empty($data['password'])) {
            $member->password = Hash::make($data['password']);
        }
        $member->phone      = $data['phone'] ?? null;
        $member->address    = $data['address'] ?? null;
        $member->save();

        return redirect()->route('members.show', $member)->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return response()->json(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
    }
}
