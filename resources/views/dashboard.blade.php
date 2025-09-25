@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <div>
    <h1 class="font-heading text-2xl text-gray-900">Tableau de bord</h1>
    <p class="text-gray-500">Vue d'ensemble des activités Fitness</p>
  </div>
  
  @if(isset($authUser) && ($authUser->role ?? null) === 'coach')
  @php
    $coachModel = \App\Models\Coach::where('user_id', $authUser->id)->first();
  @endphp
  @if($coachModel)
    @php
      $now = \Carbon\Carbon::now();
      $startOfMonth = $now->copy()->startOfMonth();
      $endOfMonth = $now->copy()->endOfMonth();
      $coachMonthSessions = \App\Models\TrainingSession::with('user')
          ->where('coach_id', $coachModel->id)
          ->whereBetween('date_time', [$startOfMonth, $endOfMonth])
          ->orderBy('date_time','asc')
          ->get();
      $daysWithSessions = $coachMonthSessions->map(function($s){ return $s->date_time->format('Y-m-d'); })->unique()->values()->toArray();
      $firstDayWeekIndex = (int) $startOfMonth->copy()->isoWeekday();
      $daysInMonth = (int) $now->daysInMonth;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Calendar -->
      <div class="lg:col-span-2 bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b flex items-center justify-between">
          <div>
            <div class="text-sm text-gray-500">Calendrier</div>
            <div class="text-lg font-heading text-gray-900">{{ $now->locale('fr')->isoFormat('MMMM YYYY') }}</div>
          </div>
        </div>
        <div class="p-4">
          <div class="grid grid-cols-7 text-center text-xs font-medium text-gray-500">
            <div>Lun</div>
            <div>Mar</div>
            <div>Mer</div>
            <div>Jeu</div>
            <div>Ven</div>
            <div>Sam</div>
            <div>Dim</div>
          </div>
          
          <div class="mt-2 grid grid-cols-7 gap-2">
            @for($i=1;$i<$firstDayWeekIndex;$i++)
              <div class="h-20 rounded-xl bg-gray-50"></div>
            @endfor
            @for($d=1;$d<=$daysInMonth;$d++)
              @php($dateStr = $startOfMonth->copy()->day($d)->format('Y-m-d'))
              @php($has = in_array($dateStr, $daysWithSessions, true))
              <div class="h-20 rounded-xl border {{ $has ? 'border-emerald-300 bg-emerald-50' : 'border-gray-200 bg-white' }} p-2 flex flex-col">
                <div class="text-xs font-medium {{ $has ? 'text-emerald-700' : 'text-gray-600' }}">{{ $d }}</div>
                @if($has)
                  <div class="mt-auto text-[10px] inline-flex items-center gap-1 text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Séance(s)
                  </div>
                @endif
              </div>
            @endfor
          </div>
        </div>
      </div>

      <!-- Sessions overview (well style) -->
      <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b">
          <div class="text-sm text-gray-500">Aperçu du mois</div>
          <div class="text-lg font-heading text-gray-900">Vos séances</div>
        </div>
        <div class="p-4 space-y-3">
          @forelse($coachMonthSessions as $s)
            <div class="rounded-xl border border-gray-200 p-3 hover:bg-gray-50 transition">
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-sm font-medium text-gray-900">{{ $s->date_time->format('d/m H:i') }}</div>
                  <div class="text-xs text-gray-500">Avec {{ optional($s->user)->first_name }} {{ optional($s->user)->last_name }}</div>
                </div>
                <div class="text-right">
                  <div class="text-xs text-gray-500">{{ $s->duration }} min</div>
                  <span class="mt-1 inline-block px-2 py-0.5 text-[10px] rounded-full {{ $s->status === 'complétée' ? 'bg-emerald-50 text-emerald-700' : ($s->status === 'annulée' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700') }}">{{ $s->status }}</span>
                </div>
              </div>
              @if($s->location)
                <div class="mt-2 text-xs text-gray-500">{{ $s->location }}</div>
              @endif
            </div>
          @empty
            <div class="text-sm text-gray-500">Aucune séance ce mois.</div>
          @endforelse
        </div>
      </div>
    </div>
  @endif
  @endif

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
