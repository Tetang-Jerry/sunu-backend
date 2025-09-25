@extends('layouts.auth')

@section('content')
<div class="min-h-screen grid grid-cols-1 md:grid-cols-2">
  <!-- Left: branding/illustration -->
  <div class="hidden md:flex flex-col justify-center bg-gradient-to-br from-[rgba(33,173,40,1)] to-[rgba(93,206,99,1)] p-10 text-white">
    <div class="max-w-md">
      <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center text-white font-bold text-xl mb-6">F</div>
      <h1 class="text-3xl font-heading text-white mb-3">SUNU FITNESS</h1>
      <p class="text-white/90">Gérez utilisateurs, séances, abonnements et paiements dans un espace propre et moderne.</p>
      <div class="mt-8 grid grid-cols-2 gap-4 text-sm">
        <div class="bg-white/20 rounded-xl p-4">
          <div class="text-white/80">Séances</div>
          <div class="text-2xl font-heading text-white mt-1">36</div>
        </div>
        <div class="bg-white/20 rounded-xl p-4">
          <div class="text-white/80">Abonnements actifs</div>
          <div class="text-2xl font-heading text-white mt-1">872</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: form -->
  <div class="flex items-center justify-center p-6 md:p-10">
    <div class="w-full max-w-md">
      <div class="md:hidden mb-6">
        <div class="h-10 w-10 rounded-lg bg-emerald-600/10 flex items-center justify-center text-emerald-700 font-bold">F</div>
      </div>
      <h2 class="font-heading text-2xl text-gray-900 mb-2">Connexion</h2>
      <p class="text-gray-500 mb-6">Accédez au backoffice Fitness</p>

      <form class="space-y-4" action="{{ route('login.attempt') }}" method="POST">
        @csrf
        <div>
          <label class="block text-sm text-gray-700 mb-1">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="ex. admin@fitness.com" required />
          @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="block text-sm text-gray-700 mb-1">Mot de passe</label>
          <input type="password" name="password" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="••••••••" required />
          @error('password')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex items-center justify-between text-sm">
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" />
            <span class="text-gray-600">Se souvenir de moi</span>
          </label>
          <a href="#" class="text-emerald-700 hover:text-emerald-600">Mot de passe oublié ?</a>
        </div>
        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2">Se connecter</button>
      </form>

      <p class="mt-6 text-xs text-gray-500">Astuce: cette page est une maquette UI. Branchez Laravel Breeze/Fortify lorsque vous serez prêt à activer l'authentification réelle.</p>
    </div>
  </div>
</div>
@endsection
