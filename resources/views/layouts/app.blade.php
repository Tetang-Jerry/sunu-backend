<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Backoffice Fitness')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --primary: #16a34a; /* green-600 */
      --primary-700: #15803d;
      --primary-50: #ecfdf5;
    }
    .bg-primary { background-color: var(--primary); }
    .bg-primary-700 { background-color: var(--primary-700); }
    .text-primary { color: var(--primary); }
    .hover\:bg-primary-700:hover { background-color: var(--primary-700); }
    .hover\:text-primary:hover { color: var(--primary); }
    .ring-primary-500 { --tw-ring-color: #22c55e; }
  </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="hidden md:flex md:flex-col w-64 bg-white border-r border-gray-200">
      <div class="h-16 flex items-center px-4 border-b">
       
          <img src="{{ asset('img/sunu.png') }}" alt="Logo" class="h-full w-full">
       
       
      </div>
      <nav class="flex-1 p-3 space-y-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 font-medium' : '' }}">
          <span>Tableau de bord</span>
        </a>
        <div class="mt-3 text-xs uppercase text-gray-400 px-3">Gestion</div>
        @if(isset($authUser) && $authUser && $authUser->role === 'admin')
          <a href="{{ route('users.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('users*') ? 'bg-gray-100 font-medium' : '' }}">
            <span>Utilisateurs</span>
          </a>
        @endif
        <a href="{{ route('sessions.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('sessions*') ? 'bg-gray-100 font-medium' : '' }}">
          <span>Séances</span>
        </a>
        <a href="{{ route('payments.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('payments*') ? 'bg-gray-100 font-medium' : '' }}">
          <span>Paiements</span>
        </a>
        <a href="{{ route('subscriptions.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('subscriptions*') ? 'bg-gray-100 font-medium' : '' }}">
          <span>Abonnements</span>
        </a>
        <a href="{{ route('coaches.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('coaches*') ? 'bg-gray-100 font-medium' : '' }}">
          <span>Coachs</span>
        </a>
      </nav>
      <div class="p-4 border-t">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="w-full text-left text-sm text-gray-600 hover:text-primary">Se déconnecter</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Topbar -->
      <header class="h-16 bg-white border-b flex items-center justify-between px-4">
        <div class="flex items-center gap-2 md:hidden">
          <div class="h-9 w-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold">F</div>
          <span class="font-heading font-medium">Fitness Admin</span>
        </div>
        <div class="flex-1"></div>
        <div class="flex items-center gap-3">
          <span class="hidden sm:block text-sm text-gray-600">
            {{ trim(($authUser->first_name ?? '') . ' ' . ($authUser->last_name ?? '')) ?: 'Admin' }}
          </span>
          <div class="h-9 w-9 rounded-full bg-primary/10 flex items-center justify-center text-primary">
            {{ \Illuminate\Support\Str::upper((\Illuminate\Support\Str::substr($authUser->first_name ?? '', 0, 1)) . (\Illuminate\Support\Str::substr($authUser->last_name ?? '', 0, 1))) ?: 'A' }}
          </div>
        </div>
      </header>

      <!-- Content -->
      <main class="p-4 md:p-6">
        @yield('content')
      </main>
    </div>
  </div>
</body>
</html>
