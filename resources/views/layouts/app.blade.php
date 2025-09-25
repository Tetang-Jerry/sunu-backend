<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Backoffice Fitness')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #16a34a;
            /* green-600 */
            --primary-700: #15803d;
            --primary-50: #ecfdf5;
        }

        .bg-primary {
            background-color: var(--primary);
        }

        .bg-primary-700 {
            background-color: var(--primary-700);
        }

        .text-primary {
            color: var(--primary);
        }

        .hover\:bg-primary-700:hover {
            background-color: var(--primary-700);
        }

        .hover\:text-primary:hover {
            color: var(--primary);
        }

        .ring-primary-500 {
            --tw-ring-color: #22c55e;
        }
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
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 font-medium' : '' }}">
                    <span>Tableau de bord</span>
                </a>
                <div class="mt-3 text-xs uppercase text-gray-400 px-3">Gestion</div>
                @if (isset($authUser) && $authUser && $authUser->role === 'admin')
                    <a href="{{ route('users.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('users*') ? 'bg-gray-100 font-medium' : '' }}">
                        <span>Utilisateurs</span>
                    </a>
                @endif
                @if (isset($authUser) && $authUser && $authUser->role === 'admin')
                    <a href="{{ route('members.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('members*') ? 'bg-gray-100 font-medium' : '' }}">
                        <span>Membres</span>
                    </a>
                @endif
                <a href="{{ route('sessions.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('sessions*') ? 'bg-gray-100 font-medium' : '' }}">
                    <span>Séances</span>
                </a>
                <a href="{{ route('payments.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('payments*') ? 'bg-gray-100 font-medium' : '' }}">
                    <span>Paiements</span>
                </a>
                <a href="{{ route('subscriptions.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('subscriptions*') ? 'bg-gray-100 font-medium' : '' }}">
                    <span>Abonnements</span>
                </a>
                <a href="{{ route('coaches.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->is('coaches*') ? 'bg-gray-100 font-medium' : '' }}">
                    <span>Coachs</span>
                </a>
            </nav>
            <div class="p-4 border-t">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-gray-600 hover:text-primary">Se
                        déconnecter</button>
                </form>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b flex items-center justify-between px-4">
                <div class="flex items-center gap-2 md:hidden">
                    <div
                        class="h-9 w-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold">
                        F</div>
                    <span class="font-heading font-medium">Fitness Admin</span>
                </div>
                <div class="flex-1"></div>
                <div class="flex items-center gap-3 relative">
                    <!-- Notifications Bell -->
                    <button id="notifBell" class="relative h-9 w-9 rounded-full hover:bg-gray-100 flex items-center justify-center" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5 text-gray-700"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span id="notifCount" class="absolute -top-0.5 -right-0.5 text-[10px] px-1.5 py-0.5 rounded-full bg-red-600 text-white hidden">0</span>
                    </button>
                    <!-- Dropdown -->
                    <div id="notifDropdown" class="hidden absolute right-16 top-12 w-96 bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden z-50">
                        <div class="px-4 py-3 border-b flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-900">Notifications</div>
                            <button id="markAllReadBtn" class="text-xs text-primary hover:text-primary-700">Tout marquer comme lu</button>
                        </div>
                        <div id="notifList" class="max-h-80 overflow-auto divide-y divide-gray-100">
                            <div class="p-4 text-sm text-gray-500">Chargement…</div>
                        </div>
                        <div class="px-4 py-2 text-xs text-gray-400 border-t">Cliquez pour voir le détail</div>
                    </div>
                    <span class="hidden sm:block text-sm text-gray-600">
                        {{ trim(($authUser->first_name ?? '') . ' ' . ($authUser->last_name ?? '')) ?: 'Admin' }}
                    </span>
                    <div class="h-9 w-9 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($authUser->first_name ?? '', 0, 1) . \Illuminate\Support\Str::substr($authUser->last_name ?? '', 0, 1)) ?: 'A' }}
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        (function(){
            const bell = document.getElementById('notifBell');
            const dd = document.getElementById('notifDropdown');
            const list = document.getElementById('notifList');
            const countBadge = document.getElementById('notifCount');
            const markAllBtn = document.getElementById('markAllReadBtn');
            let open = false;

            async function fetchNotifications(){
                try {
                    const res = await fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json' } });
                    if(!res.ok) throw new Error('req failed');
                    const data = await res.json();
                    const items = data.items || [];
                    const unread = data.unread_count || 0;
                    if(unread > 0){
                        countBadge.textContent = unread; countBadge.classList.remove('hidden');
                    } else {
                        countBadge.classList.add('hidden');
                    }
                    if(items.length === 0){
                        list.innerHTML = '<div class="p-4 text-sm text-gray-500">Aucune notification</div>';
                    } else {
                        list.innerHTML = '';
                        items.forEach(n => {
                            const row = document.createElement('button');
                            row.type = 'button';
                            row.className = 'w-full text-left p-3 hover:bg-gray-50 flex items-start gap-3';
                            row.innerHTML = `
                                <span class="mt-1 h-2 w-2 rounded-full ${n.read_at ? 'bg-gray-300' : 'bg-emerald-500'}"></span>
                                <span class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">${escapeHtml(n.title || '')}</div>
                                    <div class="text-xs text-gray-600 mt-0.5">${escapeHtml(n.body || '')}</div>
                                    <div class="text-[10px] text-gray-400 mt-1">${escapeHtml(n.time || '')}</div>
                                </span>
                                <span class="text-xs text-primary hover:text-primary-700">Voir</span>
                            `;
                            row.addEventListener('click', async () => {
                                try {
                                    await fetch(`{{ url('/notifications') }}/${n.id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                                } catch(e) {}
                                if(n.url){ window.location.href = n.url; }
                            });
                            list.appendChild(row);
                        });
                    }
                } catch(e){
                    list.innerHTML = '<div class="p-4 text-sm text-gray-500">Erreur de chargement</div>';
                }
            }

            function escapeHtml(s){
                return (s||'').replace(/[&<>"]+/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
            }

            function toggle(){
                open = !open;
                if(open){ dd.classList.remove('hidden'); fetchNotifications(); }
                else { dd.classList.add('hidden'); }
            }
            if(bell){ bell.addEventListener('click', toggle); }
            document.addEventListener('click', (e) => {
                if(!open) return;
                if(!dd.contains(e.target) && !bell.contains(e.target)){
                    dd.classList.add('hidden'); open = false;
                }
            });
            if(markAllBtn){
                markAllBtn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    try {
                        await fetch('{{ route('notifications.readAll') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                        countBadge.classList.add('hidden');
                        fetchNotifications();
                    } catch(e) {}
                });
            }
        })();
    </script>
</body>

</html>
