@extends('layouts.admin')

@section('title', 'Modifier le Produit')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Modifier le Produit</h1>
                <p class="text-black">{{ $produit->nom }}</p>
            </div>
            <a href="{{ route('admin.produits.show', $produit) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.produits.update', $produit) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations du produit -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations du produit</h3>
                
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
                    <label for="nom" class="block text-sm font-medium text-black mb-2">Nom du produit</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $produit->nom) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-black mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('description', $produit->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="categorie" class="block text-sm font-medium text-black mb-2">Catégorie</label>
                    <select id="categorie" name="categorie" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="ingredients" {{ old('categorie', $produit->categorie) === 'ingredients' ? 'selected' : '' }}>Ingrédients</option>
                        <option value="plats" {{ old('categorie', $produit->categorie) === 'plats' ? 'selected' : '' }}>Plats</option>
                        <option value="boissons" {{ old('categorie', $produit->categorie) === 'boissons' ? 'selected' : '' }}>Boissons</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="prix_unitaire" class="block text-sm font-medium text-black mb-2">Prix unitaire (€)</label>
                    <input type="number" id="prix_unitaire" name="prix_unitaire" value="{{ old('prix_unitaire', $produit->prix_unitaire) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="unite_mesure" class="block text-sm font-medium text-black mb-2">Unité de mesure</label>
                    <input type="text" id="unite_mesure" name="unite_mesure" value="{{ old('unite_mesure', $produit->unite_mesure) }}" maxlength="50" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="obligatoire" value="1" {{ old('obligatoire', $produit->obligatoire) ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Produit obligatoire (80% minimum)</span>
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
                        <p>• Les produits obligatoires doivent représenter 80% minimum des commandes</p>
                        <p>• Les produits optionnels représentent les 20% restants</p>
                        <p>• La modification d'un produit peut affecter les commandes existantes</p>
                    </div>
                </div>

                <!-- Statistiques actuelles -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Statistiques actuelles</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-black">Commandes totales :</span>
                            <span class="text-sm font-medium text-black">{{ $produit->commandes->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-black">Quantité totale commandée</p>
                            </div>
                            <span class="text-sm font-medium text-black">{{ $produit->commandes->sum('pivot.quantite') }} {{ $produit->unite_mesure }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-black">Valeur totale commandée</p>
                            </div>
                            <span class="text-sm font-medium text-black">{{ number_format($produit->commandes->sum('pivot.prix_total'), 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>

                <!-- Catégories d'exemples -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Exemples par catégorie</h4>
                    <div class="text-xs text-black space-y-1">
                        <p><strong>Ingrédients :</strong> Viande, légumes, épices, farine...</p>
                        <p><strong>Plats :</strong> Burger, pizza, salade, dessert...</p>
                        <p><strong>Boissons :</strong> Soda, jus, café, thé...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.produits.show', $produit) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-6 py-2 rounded-lg transition duration-300">
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
