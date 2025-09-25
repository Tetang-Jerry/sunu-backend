@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-3xl">
  <div>
    <h1 class="font-heading text-2xl text-gray-900">Nouvelle séance</h1>
    <p class="text-gray-500">Planifiez une nouvelle séance de travail.</p>
  </div>

  <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
    <form action="{{ route('sessions.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @csrf
      <div>
        <label class="block text-sm text-gray-700 mb-1">Coach</label>
        <select name="coach_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          <option value="">Sélectionner…</option>
          @foreach($coaches as $c)
            <option value="{{ $c->id }}" {{ old('coach_id')==$c->id ? 'selected' : '' }}>{{ optional($c->user)->first_name }} {{ optional($c->user)->last_name }}</option>
          @endforeach
        </select>
        @error('coach_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Membre</label>
        <select name="user_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          <option value="">Sélectionner…</option>
          @foreach($members as $m)
            <option value="{{ $m->id }}" {{ old('user_id')==$m->id ? 'selected' : '' }}>{{ $m->first_name }} {{ $m->last_name }} — {{ $m->email }}</option>
          @endforeach
        </select>
        @error('user_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Date & Heure</label>
        <input type="datetime-local" name="date_time" value="{{ old('date_time') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
        @error('date_time')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Durée (minutes)</label>
        <input type="number" name="duration" value="{{ old('duration', 60) }}" min="1" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
        @error('duration')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Statut</label>
        <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          @php($st = old('status','à venir'))
          <option value="à venir" {{ $st==='à venir' ? 'selected' : '' }}>À venir</option>
          <option value="complétée" {{ $st==='complétée' ? 'selected' : '' }}>Complétée</option>
          <option value="annulée" {{ $st==='annulée' ? 'selected' : '' }}>Annulée</option>
        </select>
        @error('status')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Lieu</label>
        <input type="text" name="location" value="{{ old('location') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="En ligne, en salle…">
        @error('location')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2 flex items-center justify-end gap-2 pt-2">
        <a href="{{ route('sessions.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Créer</button>
      </div>
    </form>
  </div>
</div>
@endsection
