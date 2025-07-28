@extends('layouts.franchise')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-black mb-2">Mon Profil</h1>
        <p class="text-black">Gérez vos informations personnelles</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations personnelles -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations personnelles</h3>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('franchise.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="prenom" class="block text-sm font-medium text-black mb-2">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="{{ $franchise->prenom }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="nom" class="block text-sm font-medium text-black mb-2">Nom</label>
                        <input type="text" id="nom" name="nom" value="{{ $franchise->nom }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-black mb-2">Email</label>
                    <input type="email" id="email" value="{{ $franchise->email }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                    <p class="text-xs text-gray-500 mt-1">L'email ne peut pas être modifié</p>
                </div>

                <div class="mb-4">
                    <label for="telephone" class="block text-sm font-medium text-black mb-2">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="{{ $franchise->telephone }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="adresse" class="block text-sm font-medium text-black mb-2">Adresse</label>
                    <textarea id="adresse" name="adresse" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ $franchise->adresse }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="ville" class="block text-sm font-medium text-black mb-2">Ville</label>
                        <input type="text" id="ville" name="ville" value="{{ $franchise->ville }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-black mb-2">Code postal</label>
                        <input type="text" id="code_postal" name="code_postal" value="{{ $franchise->code_postal }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-6 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-save mr-2"></i>
                    Sauvegarder les modifications
                </button>
            </form>
        </div>

        <!-- Informations de compte -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations de compte</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Statut du compte</p>
                        <p class="text-sm text-black">{{ ucfirst($franchise->statut) }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                        {{ $franchise->statut === 'actif' ? 'bg-green-100 text-green-800' : 
                           ($franchise->statut === 'inactif' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($franchise->statut) }}
                    </span>
                </div>

                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Date d'entrée</p>
                        <p class="text-sm text-black">{{ \Carbon\Carbon::parse($franchise->date_entree)->format('d/m/Y') }}</p>
                    </div>
                    <i class="fas fa-calendar text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Droits d'entrée</p>
                        <p class="text-sm text-black">{{ number_format($franchise->droits_entree, 2, ',', ' ') }} €</p>
                    </div>
                    <i class="fas fa-euro-sign text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Pourcentage de ventes</p>
                        <p class="text-sm text-black">{{ $franchise->pourcentage_ventes }}%</p>
                    </div>
                    <i class="fas fa-percentage text-orange-600"></i>
                </div>
            </div>

            <div class="mt-6 p-4 bg-orange-50 rounded-lg border border-orange-200">
                <h4 class="text-sm font-medium text-black mb-2">
                    <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                    Informations importantes
                </h4>
                <div class="text-xs text-black space-y-1">
                    <p>• Votre compte doit être validé par un administrateur</p>
                    <p>• Le pourcentage de 4% est automatiquement reversé</p>
                    <p>• Contactez le support pour toute question</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
