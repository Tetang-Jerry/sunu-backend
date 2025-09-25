@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="font-heading text-2xl text-gray-900">Coachs</h1>
      <p class="text-gray-500">Gestion des coachs et spécialités</p>
    </div>
    <div class="flex items-center gap-2">
      <input type="text" placeholder="Rechercher…" class="hidden md:block rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" />
      <a href="{{ route('coaches.create') }}" class="bg-primary hover:bg-primary-700 text-white rounded-lg px-4 py-2">Nouveau coach</a>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
    <div class="p-4 border-b flex items-center justify-between">
      <div class="text-sm text-gray-600">Liste des coachs</div>
      @if(session('success'))
        <div class="text-sm text-emerald-700 bg-emerald-50 px-3 py-1 rounded-lg">{{ session('success') }}</div>
      @endif
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Spécialité</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Disponibilités</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bio</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse($coaches as $coach)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2 text-sm text-gray-900">{{ optional($coach->user)->first_name }} {{ optional($coach->user)->last_name }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ $coach->specialty }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">
              @php($av = $coach->availability_json ?? [])
              @if(is_array($av) && count($av))
                @php($days = collect($av)->pluck('date')->unique()->sort()->values())
                <span class="px-2 py-1 text-xs rounded-full bg-emerald-50 text-emerald-700">{{ $days->count() }} jour(s)</span>
                @if($days->count())
                  <span class="ml-2 text-xs text-gray-500">dès {{ \Carbon\Carbon::parse($days->first())->format('d/m') }}</span>
                @endif
              @else
                —
              @endif
            </td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($coach->bio, 80) }}</td>
            <td class="px-4 py-2 text-sm text-right">
              <div class="flex items-center justify-end gap-3">
                <a class="text-primary hover:text-primary-700" href="{{ route('coaches.show', $coach) }}">Détails</a>
                <span class="text-gray-300">|</span>
                <a class="text-emerald-700 hover:text-emerald-600" href="{{ route('coaches.edit', $coach) }}">Modifier</a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Aucun coach pour l'instant</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if(method_exists($coaches, 'hasPages') && $coaches->hasPages())
      <div class="px-6 py-4 border-t border-gray-200">
        {{ $coaches->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
