@extends('layouts.admin')

@section('title', 'Modifier l\'Entrepôt')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Modifier l'Entrepôt</h1>
                <p class="text-black">{{ $entrepot->nom }}</p>
            </div>
            <a href="{{ route('admin.entrepots.show', $entrepot) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.entrepots.update', $entrepot) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations de l'entrepôt -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations de l'entrepôt</h3>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-black mb-2">Nom de l'entrepôt</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $entrepot->nom) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="adresse" class="block text-sm font-medium text-black mb-2">Adresse</label>
                    <textarea id="adresse" name="adresse" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('adresse', $entrepot->adresse) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="ville" class="block text-sm font-medium text-black mb-2">Ville</label>
                        <input type="text" id="ville" name="ville" value="{{ old('ville', $entrepot->ville) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-black mb-2">Code postal</label>
                        <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal', $entrepot->code_postal) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="telephone" class="block text-sm font-medium text-black mb-2">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $entrepot->telephone) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="capacite" class="block text-sm font-medium text-black mb-2">Capacité (m³)</label>
                    <input type="number" id="capacite" name="capacite" value="{{ old('capacite', $entrepot->capacite) }}" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="cuisine" value="1" {{ old('cuisine', $entrepot->cuisine) ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Équipé d'une cuisine</span>
                    </label>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations complémentaires</h3>
                
                <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <h4 class="text-sm font-medium text-black mb-2">
                        <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                        Règles importantes
                    </h4>
                    <div class="text-xs text-black space-y-1">
                        <p>• Les entrepôts avec cuisine peuvent préparer des plats</p>
                        <p>• Les entrepôts sans cuisine stockent uniquement des ingrédients</p>
                        <p>• La capacité détermine le volume de stockage disponible</p>
                    </div>
                </div>

                <!-- Statistiques actuelles -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Statistiques actuelles</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-black">Commandes totales :</span>
                            <span class="text-sm font-medium text-black">{{ $entrepot->commandes->count() }}</span>
                        </div>
                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-black">Commandes validées :</span>
                            <span class="text-sm font-medium text-black">{{ $entrepot->commandes->where('statut', 'validee')->count() }}</span>
                        </div>
                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-black">Valeur totale :</span>
                            <span class="text-sm font-medium text-black">{{ number_format($entrepot->commandes->sum('total_commande'), 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>

                <!-- Types d'entrepôts -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Types d'entrepôts</h4>
                    <div class="text-xs text-black space-y-1">
                        <p><strong>Avec cuisine :</strong> Peut préparer des plats et stocker des ingrédients</p>
                        <p><strong>Sans cuisine :</strong> Stockage uniquement d'ingrédients et boissons</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.entrepots.show', $entrepot) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-6 py-2 rounded-lg transition duration-300">
                Annuler
            </a>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-6 py-2 rounded-lg transition duration-300">
                <i class="fas fa-save mr-2"></i>
                Sauvegarder les modifications
            </button>
        </div>
    </form>
</div>
@endsection 
