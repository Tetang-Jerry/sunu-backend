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
      <div class="md:col-span-2">
        <label class="block text-sm text-gray-700 mb-2">Disponibilités</label>
        <div class="space-y-3">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
              <label class="text-xs text-gray-500">Date</label>
              <input type="date" id="avail-date" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" min="{{ now()->format('Y-m-d') }}">
            </div>
            <div>
              <label class="text-xs text-gray-500">Heure début</label>
              <input type="time" id="avail-start" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" value="09:00">
            </div>
            <div>
              <label class="text-xs text-gray-500">Heure fin</label>
              <input type="time" id="avail-end" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" value="12:00">
            </div>
            <div class="flex items-end">
              <button type="button" id="add-slot" class="w-full px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Ajouter</button>
            </div>
          </div>
          <div>
            <div class="text-sm text-gray-600 mb-1">Créneaux ajoutés</div>
            <div id="slots-list" class="border rounded-lg divide-y"></div>
          </div>
        </div>
        <input type="hidden" name="availability_json" id="availability_json" value='@json(old('availability_json') ? json_decode(old('availability_json'), true) : ($coach->availability_json ?? []))'>
        @error('availability_json')<div class="text-sm text-red-600 mt-2">{{ $message }}</div>@enderror
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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const dateEl = document.getElementById('avail-date');
    const startEl = document.getElementById('avail-start');
    const endEl = document.getElementById('avail-end');
    const addBtn = document.getElementById('add-slot');
    const listEl = document.getElementById('slots-list');
    const hiddenEl = document.getElementById('availability_json');

    let data = [];
    try { data = JSON.parse(hiddenEl.value || '[]'); } catch(e) { data = []; }

    function render() {
      listEl.innerHTML = '';
      const byDate = {};
      data.forEach(item => {
        if (!byDate[item.date]) byDate[item.date] = [];
        (item.periods || []).forEach(p => byDate[item.date].push(p));
      });
      Object.keys(byDate).sort().forEach(date => {
        const wrap = document.createElement('div');
        wrap.className = 'p-3';
        const title = document.createElement('div');
        title.className = 'text-sm font-medium text-gray-800 mb-2';
        title.textContent = new Date(date).toLocaleDateString();
        wrap.appendChild(title);
        const ul = document.createElement('div');
        ul.className = 'flex flex-wrap gap-2';
        byDate[date].forEach((slot) => {
          const btnWrap = document.createElement('span');
          btnWrap.className = 'inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs';
          btnWrap.textContent = slot + ' ';
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'text-red-600 hover:text-red-700';
          btn.textContent = '×';
          btn.onclick = () => {
            data = data.map(it => it.date === date ? { ...it, periods: (it.periods||[]).filter(p => p !== slot) } : it)
                       .filter(it => (it.periods||[]).length > 0);
            hiddenEl.value = JSON.stringify(data);
            render();
          };
          btnWrap.appendChild(btn);
          ul.appendChild(btnWrap);
        });
        wrap.appendChild(ul);
        listEl.appendChild(wrap);
      });
      hiddenEl.value = JSON.stringify(data);
    }

    addBtn.addEventListener('click', () => {
      const d = dateEl.value;
      const s = startEl.value;
      const e = endEl.value;
      if (!d || !s || !e) return;
      if (e <= s) return;
      const period = `${s}-${e}`;
      const idx = data.findIndex(it => it.date === d);
      if (idx === -1) data.push({ date: d, periods: [period] });
      else {
        const periods = data[idx].periods || [];
        if (!periods.includes(period)) periods.push(period);
        data[idx].periods = periods;
      }
      render();
    });

    render();
  });
</script>
@endsection
