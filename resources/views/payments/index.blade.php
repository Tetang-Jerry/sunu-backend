@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="font-heading text-2xl text-gray-900">Paiements</h1>
      <p class="text-gray-500">Suivi des paiements et factures</p>
    </div>
    <div class="flex items-center gap-2">
      <input type="text" placeholder="Rechercher…" class="hidden md:block rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" />
      <button class="bg-primary hover:bg-primary-700 text-white rounded-lg px-4 py-2">Nouveau paiement</button>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
    <div class="p-4 border-b flex items-center justify-between">
      <div class="text-sm text-gray-600">Historique des paiements</div>
      <div class="text-sm text-gray-500">Démo</div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Méthode</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2 text-sm text-gray-900">John Doe</td>
            <td class="px-4 py-2 text-sm text-gray-900">49,99 €</td>
            <td class="px-4 py-2 text-sm text-gray-700">CB</td>
            <td class="px-4 py-2 text-sm"><span class="px-2 py-1 text-xs rounded-full bg-emerald-50 text-emerald-700">Payé</span></td>
            <td class="px-4 py-2 text-sm text-gray-700">24/09/2025</td>
            <td class="px-4 py-2 text-sm text-right"><a class="text-primary hover:text-primary-700" href="#">Détails</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
