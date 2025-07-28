@extends('layouts.admin')

@section('title', 'Nouveau Franchisé')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Nouveau Franchisé</h1>
                <p class="text-black">Ajoutez un nouveau franchisé au réseau Driv'n Cook</p>
            </div>
            <a href="{{ route('admin.franchises.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.franchises.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations personnelles -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations personnelles</h3>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="prenom" class="block text-sm font-medium text-black mb-2">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="nom" class="block text-sm font-medium text-black mb-2">Nom</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-black mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="telephone" class="block text-sm font-medium text-black mb-2">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="adresse" class="block text-sm font-medium text-black mb-2">Adresse</label>
                    <textarea id="adresse" name="adresse" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('adresse') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="ville" class="block text-sm font-medium text-black mb-2">Ville</label>
                        <input type="text" id="ville" name="ville" value="{{ old('ville') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-black mb-2">Code postal</label>
                        <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="date_entree" class="block text-sm font-medium text-black mb-2">Date d'entrée</label>
                    <input type="date" id="date_entree" name="date_entree" value="{{ old('date_entree', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>

            <!-- Informations de compte -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations de compte</h3>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-black mb-2">Mot de passe</label>
                    <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-black mb-2">Confirmation du mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="statut" class="block text-sm font-medium text-black mb-2">Statut</label>
                    <select id="statut" name="statut" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="actif" {{ old('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="suspendu" {{ old('statut') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="droits_entree" class="block text-sm font-medium text-black mb-2">Droits d'entrée (€)</label>
                    <input type="number" id="droits_entree" name="droits_entree" value="{{ old('droits_entree', 50000.00) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="pourcentage_ventes" class="block text-sm font-medium text-black mb-2">Pourcentage de ventes (%)</label>
                    <input type="number" id="pourcentage_ventes" name="pourcentage_ventes" value="{{ old('pourcentage_ventes', 4.00) }}" step="0.01" min="0" max="100" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <h4 class="text-sm font-medium text-black mb-2">
                        <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                        Informations importantes
                    </h4>
                    <div class="text-xs text-black space-y-1">
                        <p>• Le franchisé recevra un email de bienvenue</p>
                        <p>• Les droits d'entrée sont fixés à 50 000€ par défaut</p>
                        <p>• Le pourcentage de ventes est fixé à 4% par défaut</p>
                        <p>• Un camion sera attribué automatiquement</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.franchises.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-6 py-2 rounded-lg transition duration-300">
                Annuler
            </a>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-6 py-2 rounded-lg transition duration-300">
                <i class="fas fa-save mr-2"></i>
                Créer le franchisé
            </button>
        </div>
    </form>
</div>
@endsection 
