@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="font-heading text-2xl text-gray-900">Séances</h1>
      <p class="text-gray-500">Planification des séances de travail</p>
    </div>
    <div class="flex items-center gap-2">
      <input type="text" placeholder="Rechercher…" class="hidden md:block rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" />
      <a href="{{ route('sessions.create') }}" class="bg-primary hover:bg-primary-700 text-white rounded-lg px-4 py-2">Nouvelle séance</a>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
    <div class="p-4 border-b flex items-center justify-between">
      <div class="text-sm text-gray-600">Séances programmées</div>
      @if(session('success'))
        <div class="text-sm text-emerald-700 bg-emerald-50 px-3 py-1 rounded-lg">{{ session('success') }}</div>
      @endif
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Coach</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Membre</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date & Heure</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Durée</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Lieu</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse($sessions as $s)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2 text-sm text-gray-900">{{ optional(optional($s->coach)->user)->first_name }} {{ optional(optional($s->coach)->user)->last_name }}</td>
            <td class="px-4 py-2 text-sm text-gray-900">{{ optional($s->user)->first_name }} {{ optional($s->user)->last_name }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ optional($s->date_time)->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ $s->duration }} min</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ $s->location }}</td>
            <td class="px-4 py-2 text-sm"><span class="px-2 py-1 text-xs rounded-full {{ $s->status === 'complétée' ? 'bg-emerald-50 text-emerald-700' : ($s->status === 'annulée' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700') }}">{{ $s->status }}</span></td>
            <td class="px-4 py-2 text-sm text-right">
              <div class="flex items-center justify-end gap-3">
                <a class="text-primary hover:text-primary-700" href="{{ route('sessions.show', $s) }}">Détails</a>
                <span class="text-gray-300">|</span>
                <a class="text-emerald-700 hover:text-emerald-600" href="{{ route('sessions.edit', $s) }}">Modifier</a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-4 py-8 text-center text-gray-500">Aucune séance</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
