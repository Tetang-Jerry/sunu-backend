@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-3xl">
  <div>
    <h1 class="font-heading text-2xl text-gray-900">Nouveau coach</h1>
    <p class="text-gray-500">Associez un utilisateur et définissez sa spécialité.</p>
  </div>

  <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
    <form action="{{ route('coaches.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @csrf
      <div class="md:col-span-2">
        <label class="block text-sm text-gray-700 mb-1">Utilisateur</label>
        <select name="user_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          <option value="">Sélectionner un utilisateur…</option>
          @foreach($users as $u)
            <option value="{{ $u->id }}" {{ old('user_id')==$u->id ? 'selected' : '' }}>{{ $u->first_name }} {{ $u->last_name }} — {{ $u->email }} ({{ $u->role }})</option>
          @endforeach
        </select>
        @error('user_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Spécialité</label>
        <input type="text" name="specialty" value="{{ old('specialty') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
        @error('specialty')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Disponibilités</label>
        <input type="text" name="availability" value="{{ old('availability') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Ex: Lun-Ven">
        @error('availability')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="md:col-span-2">
        <label class="block text-sm text-gray-700 mb-1">Bio</label>
        <textarea name="bio" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('bio') }}</textarea>
        @error('bio')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="md:col-span-2 flex items-center justify-end gap-2 pt-2">
        <a href="{{ route('coaches.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Créer</button>
      </div>
    </form>
  </div>
</div>
@endsection
