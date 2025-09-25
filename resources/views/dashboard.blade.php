@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <div>
    <h1 class="font-heading text-2xl text-gray-900">Tableau de bord</h1>
    <p class="text-gray-500">Vue d'ensemble des activités Fitness</p>
  </div>

  <!-- Stat cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-4">
      <div class="text-sm text-gray-500">Membres</div>
      <div class="mt-2 flex items-baseline gap-2">
        <div class="text-2xl font-heading text-gray-900">{{ number_format($membersCount ?? 0) }}</div>
      </div>
    </div>
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-4">
      <div class="text-sm text-gray-500">Séances aujourd'hui</div>
      <div class="mt-2 flex items-baseline gap-2">
        <div class="text-2xl font-heading text-gray-900">{{ $sessionsToday ?? 0 }}</div>
      </div>
    </div>
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-4">
      <div class="text-sm text-gray-500">Séances (30j)</div>
      <div class="mt-2 flex items-baseline gap-2">
        <div class="text-2xl font-heading text-gray-900">{{ $sessions30d ?? 0 }}</div>
      </div>
    </div>
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-4">
      <div class="text-sm text-gray-500">Abonnements actifs</div>
      <div class="mt-2 flex items-baseline gap-2">
        <div class="text-2xl font-heading text-gray-900">{{ $coachesCount ?? 0 }}</div>
      </div>
    </div>
  </div>

  <!-- Recent sessions -->
  <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
    <div class="p-4 border-b flex items-center justify-between">
      <div class="text-sm text-gray-600">Séances à venir</div>
      <a href="{{ route('sessions.index') }}" class="text-sm text-primary hover:text-primary-700">Voir tout</a>
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
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse(($upcomingSessions ?? []) as $s)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2 text-sm text-gray-900">{{ optional(optional($s->coach)->user)->first_name }} {{ optional(optional($s->coach)->user)->last_name }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ optional($s->user)->first_name }} {{ optional($s->user)->last_name }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ optional($s->date_time)->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ $s->duration }} min</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ $s->location }}</td>
            <td class="px-4 py-2 text-sm">
              <span class="px-2 py-1 text-xs rounded-full {{ $s->status === 'complétée' ? 'bg-emerald-50 text-emerald-700' : ($s->status === 'annulée' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700') }}">{{ $s->status }}</span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune séance à venir</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
