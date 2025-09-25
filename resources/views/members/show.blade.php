@extends('layouts.app')

@section('content')
    <div class="space-y-6 max-w-3xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading text-2xl text-gray-900">Membre — {{ $member->first_name }} {{ $member->last_name }}
                </h1>
                <p class="text-gray-500">Détails du membre</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('members.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Retour</a>
                <a href="{{ route('members.edit', $member) }}"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Modifier</a>
                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="delete-btn px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg"
                        data-member-name="{{ $member->first_name }} {{ $member->last_name }}">Supprimer</button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 space-y-6">
            @if (session('success'))
                <div class="text-sm text-emerald-700 bg-emerald-50 px-3 py-1 rounded-lg">{{ session('success') }}</div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm text-gray-500 mb-1">Prénom</div>
                    <div class="text-gray-900 font-medium">{{ $member->first_name ?: '—' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500 mb-1">Nom</div>
                    <div class="text-gray-900 font-medium">{{ $member->last_name ?: '—' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500 mb-1">Email</div>
                    <div class="text-gray-900">{{ $member->email }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500 mb-1">Téléphone</div>
                    <div class="text-gray-900">{{ $member->phone ?: '—' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500 mb-1">Adresse</div>
                    <div class="text-gray-900">{{ $member->address ?: '—' }}</div>
                </div>

            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4"
        aria-hidden="true" role="dialog" aria-modal="true">
        <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl w-full max-w-lg">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Supprimer l'utilisateur</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="userName"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirmDelete"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Confirmer</button>
                <button type="button" id="cancelDelete"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annuler</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('deleteModal');
            const nameSpan = document.getElementById('userName');
            const cancelBtn = document.getElementById('cancelDelete');
            const confirmBtn = document.getElementById('confirmDelete');
            let currentForm = null;
            const csrfToken = '{{ csrf_token() }}';

            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const label = this.getAttribute('data-member-name') || '';
                    currentForm = this.closest('.delete-form');
                    nameSpan.textContent = label;
                    modal.classList.remove('hidden');
                });
            });

            cancelBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            confirmBtn.addEventListener('click', async function() {
                if (!currentForm) return;
                try {
                    confirmBtn.innerHTML =
                        '<svg class="animate-spin h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                    confirmBtn.disabled = true;
                    const action = currentForm.getAttribute('action');
                    const resp = await fetch(action, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    });
                    if (resp.ok || resp.status === 204) {
                        window.location.href = '{{ route('members.index') }}';
                    } else {
                        currentForm.submit();
                    }
                } catch (e) {
                    currentForm.submit();
                }
            });
        });
    </script>
@endsection
