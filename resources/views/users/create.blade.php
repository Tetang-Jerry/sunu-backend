@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-3xl">
  <div>
    <h1 class="font-heading text-2xl text-gray-900">Nouvel utilisateur</h1>
    <p class="text-gray-500">Ajoutez un membre, un coach ou un admin.</p>
  </div>

  <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
    <form action="{{ route('users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @csrf
      <div>
        <label class="block text-sm text-gray-700 mb-1">Prénom</label>
        <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
        @error('first_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Nom</label>
        <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
        @error('last_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="md:col-span-2">
        <label class="block text-sm text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
        @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Mot de passe</label>
        <input type="password" name="password" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
        @error('password')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Téléphone</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
        @error('phone')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="md:col-span-2">
        <label class="block text-sm text-gray-700 mb-1">Adresse</label>
        <input type="text" name="address" value="{{ old('address') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
        @error('address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Date de naissance</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
        @error('date_of_birth')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Rôle</label>
        <select name="role" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          <option value="membre" {{ old('role')==='membre' ? 'selected' : '' }}>Membre</option>
          <option value="coach" {{ old('role')==='coach' ? 'selected' : '' }}>Coach</option>
          <option value="admin" {{ old('role')==='admin' ? 'selected' : '' }}>Admin</option>
        </select>
        @error('role')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="md:col-span-2 flex items-center justify-end gap-2 pt-2">
        <a href="{{ route('users.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Créer</button>
      </div>
    </form>
  </div>
</div>
@endsection
