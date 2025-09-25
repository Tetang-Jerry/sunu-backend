@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-3xl">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="font-heading text-2xl text-gray-900">Modifier coach</h1>
      <p class="text-gray-500">Mettez à jour les informations du coach.</p>
    </div>
    <div>
      <a href="{{ route('coaches.show', $coach) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
    <form action="{{ route('coaches.update', $coach) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @csrf
      @method('PUT')
      <div class="md:col-span-2">
        <label class="block text-sm text-gray-700 mb-1">Utilisateur</label>
        <select name="user_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          @foreach($users as $u)
            <option value="{{ $u->id }}" {{ (old('user_id', $coach->user_id) == $u->id) ? 'selected' : '' }}>{{ $u->first_name }} {{ $u->last_name }} — {{ $u->email }} ({{ $u->role }})</option>
          @endforeach
        </select>
        @error('user_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Spécialité</label>
        <input type="text" name="specialty" value="{{ old('specialty', $coach->specialty) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
        @error('specialty')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Disponibilités</label>
        <input type="text" name="availability" value="{{ old('availability', $coach->availability) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Ex: Lun-Ven">
        @error('availability')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="md:col-span-2">
        <label class="block text-sm text-gray-700 mb-1">Bio</label>
        <textarea name="bio" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('bio', $coach->bio) }}</textarea>
        @error('bio')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="md:col-span-2 flex items-center justify-end gap-2 pt-2">
        <a href="{{ route('coaches.show', $coach) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Retour</a>
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Mettre à jour</button>
      </div>
    </form>
  </div>
</div>
@endsection
