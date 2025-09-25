@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <!-- Header Section -->
  <div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
      <div class="mb-4 md:mb-0">
        <div class="flex items-center">
          <div class="mr-3 p-2 bg-emerald-100 rounded-lg">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div>
            <h1 class="font-heading text-2xl font-bold text-gray-900">Séance de coaching</h1>
            <p class="text-gray-500 text-sm">Détails complets de la séance programmée</p>
          </div>
        </div>
      </div>
      
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('sessions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Retour à la liste
        </a>
        <a href="{{ route('sessions.edit', $session) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors duration-200">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Modifier
        </a>
        <form action="{{ route('sessions.destroy', $session) }}" method="POST" class="inline delete-form">
          @csrf
          @method('DELETE')
          <button type="button" class="delete-btn inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200" data-session-label="{{ optional(optional($session->coach)->user)->first_name }} {{ optional(optional($session->coach)->user)->last_name }} — {{ optional($session->date_time)->format('d/m/Y H:i') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Supprimer
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Session Details Card -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-50 to-gray-50 px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-800">Informations de la séance</h2>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
              <div>
                <div class="flex items-center text-sm text-gray-500 mb-1">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  Coach
                </div>
                <div class="text-gray-900 font-medium text-lg">{{ optional(optional($session->coach)->user)->first_name }} {{ optional(optional($session->coach)->user)->last_name }}</div>
                <div class="text-gray-600 text-sm">{{ optional(optional($session->coach)->user)->email }}</div>
              </div>
              
              <div>
                <div class="flex items-center text-sm text-gray-500 mb-1">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  Date & Heure
                </div>
                <div class="text-gray-900 font-medium">{{ optional($session->date_time)->format('d/m/Y H:i') }}</div>
              </div>
              
              <div>
                <div class="flex items-center text-sm text-gray-500 mb-1">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  Lieu
                </div>
                <div class="text-gray-900">{{ $session->location ?: 'Non spécifié' }}</div>
              </div>
            </div>
            
            <div class="space-y-6">
              <div>
                <div class="flex items-center text-sm text-gray-500 mb-1">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  Membre
                </div>
                <div class="text-gray-900 font-medium text-lg">{{ optional($session->user)->first_name }} {{ optional($session->user)->last_name }}</div>
                <div class="text-gray-600 text-sm">{{ optional($session->user)->email }}</div>
              </div>
              
              <div>
                <div class="flex items-center text-sm text-gray-500 mb-1">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  Durée
                </div>
                <div class="text-gray-900 font-medium">{{ $session->duration }} minutes</div>
              </div>
              
              <div>
                <div class="flex items-center text-sm text-gray-500 mb-1">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  Statut
                </div>
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                  @if($session->status === 'completed') bg-green-100 text-green-800
                  @elseif($session->status === 'cancelled') bg-red-100 text-red-800
                  @elseif($session->status === 'scheduled') bg-blue-100 text-blue-800
                  @else bg-gray-100 text-gray-800 @endif">
                  {{ ucfirst($session->status) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Additional Info Card -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-800">Résumé</h2>
        </div>
        <div class="p-6">
          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Prochaine séance</span>
              <span class="font-medium text-gray-900">
                @if($session->date_time > now())
                Dans {{ $session->date_time->diffForHumans() }}
                @else
                Séance passée
                @endif
              </span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Créée le</span>
              <span class="font-medium text-gray-900">{{ $session->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Dernière modification</span>
              <span class="font-medium text-gray-900">{{ $session->updated_at->format('d/m/Y') }}</span>
            </div>
          </div>
          
      
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4 z-50" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl w-full max-w-lg transform transition-all">
    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
      <div class="sm:flex sm:items-start">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
          <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Supprimer la séance</h3>
          <div class="mt-2">
            <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette séance ? Cette action est irréversible.</p>
            <p class="text-sm font-medium text-gray-700 mt-1" id="sessionLabel"></p>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
      <button type="button" id="confirmDelete" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">Confirmer la suppression</button>
      <button type="button" id="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">Annuler</button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteModal');
    const labelSpan = document.getElementById('sessionLabel');
    const cancelBtn = document.getElementById('cancelDelete');
    const confirmBtn = document.getElementById('confirmDelete');
    let currentForm = null;
    const csrfToken = '{{ csrf_token() }}';

    document.querySelectorAll('.delete-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const label = this.getAttribute('data-session-label') || '';
        currentForm = this.closest('.delete-form');
        labelSpan.textContent = label;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      });
    });

    cancelBtn.addEventListener('click', function () {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    });

    confirmBtn.addEventListener('click', async function () {
      if (!currentForm) return;
      try {
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
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
          window.location.href = '{{ route('sessions.index') }}';
        } else {
          currentForm.submit();
        }
      } catch (e) {
        currentForm.submit();
      }
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
    });
  });
</script>
@endsection