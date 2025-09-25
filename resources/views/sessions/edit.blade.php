@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-3xl">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="font-heading text-2xl text-gray-900">Modifier la séance</h1>
      <p class="text-gray-500">Mettez à jour les informations de la séance.</p>
    </div>
    <div>
      <a href="{{ route('sessions.show', $session) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
    <form action="{{ route('sessions.update', $session) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @csrf
      @method('PUT')
      <div>
        <label class="block text-sm text-gray-700 mb-1">Coach</label>
        <select name="coach_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          @foreach($coaches as $c)
            <option value="{{ $c->id }}" {{ (old('coach_id', $session->coach_id) == $c->id) ? 'selected' : '' }}>{{ optional($c->user)->first_name }} {{ optional($c->user)->last_name }}</option>
          @endforeach
        </select>
        @error('coach_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Membre</label>
        <select name="user_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          @foreach($members as $m)
            <option value="{{ $m->id }}" {{ (old('user_id', $session->user_id) == $m->id) ? 'selected' : '' }}>{{ $m->first_name }} {{ $m->last_name }} — {{ $m->email }}</option>
          @endforeach
        </select>
        @error('user_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Date</label>
        <select name="slot_date" id="slot_date" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          <option value="">Choisir une date…</option>
        </select>
        @error('slot_date')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Créneau</label>
        <select name="slot_period" id="slot_period" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          <option value="">Choisir un créneau…</option>
        </select>
        @error('slot_period')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Statut</label>
        @php($st = old('status', $session->status))
        <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" required>
          <option value="à venir" {{ $st==='à venir' ? 'selected' : '' }}>À venir</option>
          <option value="complétée" {{ $st==='complétée' ? 'selected' : '' }}>Complétée</option>
          <option value="annulée" {{ $st==='annulée' ? 'selected' : '' }}>Annulée</option>
        </select>
        @error('status')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm text-gray-700 mb-1">Lieu</label>
        <input type="text" name="location" value="{{ old('location', $session->location) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="En ligne, en salle…">
        @error('location')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2 flex items-center justify-end gap-2 pt-2">
        <a href="{{ route('sessions.show', $session) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Retour</a>
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Mettre à jour</button>
      </div>
    </form>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const coachSelect = document.querySelector('select[name="coach_id"]');
    const dateSelect = document.getElementById('slot_date');
    const periodSelect = document.getElementById('slot_period');

    const availabilityByCoach = {!! json_encode($availabilityByCoach ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
    const preDate = @json(old('slot_date', optional($session->slot_date)->format('Y-m-d')));
    const prePeriod = @json(old('slot_period', $session->slot_period));

    function resetDates() {
      dateSelect.innerHTML = '<option value="">Choisir une date…</option>';
      periodSelect.innerHTML = '<option value="">Choisir un créneau…</option>';
    }

    function resetPeriods() {
      periodSelect.innerHTML = '<option value="">Choisir un créneau…</option>';
    }

    function populateDates(coachId) {
      resetDates();
      const av = availabilityByCoach[coachId] || [];
      const uniqueDates = Array.from(new Set(av.map(x => x.date))).sort();
      uniqueDates.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d;
        const dd = new Date(d);
        opt.textContent = dd.toLocaleDateString();
        dateSelect.appendChild(opt);
      });
      if (preDate) {
        dateSelect.value = preDate;
        populatePeriods(coachId, preDate);
        if (prePeriod) periodSelect.value = prePeriod;
      }
    }

    function populatePeriods(coachId, date) {
      resetPeriods();
      const av = availabilityByCoach[coachId] || [];
      const periods = [];
      av.filter(x => x.date === date).forEach(x => {
        (x.periods || []).forEach(p => periods.push(p));
      });
      periods.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p; opt.textContent = p;
        periodSelect.appendChild(opt);
      });
    }

    coachSelect.addEventListener('change', () => {
      resetDates(); resetPeriods();
      const id = coachSelect.value;
      if (id) populateDates(id);
    });
    dateSelect.addEventListener('change', () => {
      resetPeriods();
      const id = coachSelect.value; const d = dateSelect.value;
      if (id && d) populatePeriods(id, d);
    });

    if (coachSelect.value) populateDates(coachSelect.value); else resetDates();
  });
</script>
@endsection
