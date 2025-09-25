@extends('layouts.app')

@section('content')
    <div class="space-y-6 max-w-3xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading text-2xl text-gray-900">Modifier le membre</h1>
                <p class="text-gray-500">Mettez à jour les informations du membre.</p>
            </div>
            <div>
                <a href="{{ route('members.show', $member) }}"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
            <form action="{{ route('members.update', $member) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Prénom</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500"
                        required>
                    @error('first_name')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Nom</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500"
                        required>
                    @error('last_name')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $member->email) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500"
                        required>
                    @error('email')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" name="password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('password')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $member->phone) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('phone')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-700 mb-1">Adresse</label>
                    <input type="text" name="address" value="{{ old('address', $member->address) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('address')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>


                <div class="md:col-span-2 flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('members.show', $member) }}"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50">Retour</a>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection
