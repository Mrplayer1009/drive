@extends('layouts.admin')

@section('title', 'Détails du Produit')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Détails du Produit</h1>
                <p class="text-black">{{ $produit->nom }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.produits.edit', $produit) }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.produits.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations du produit -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations du produit</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Nom</p>
                        <p class="text-sm text-black">{{ $produit->nom }}</p>
                    </div>
                    <i class="fas fa-box text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Catégorie</p>
                        <p class="text-sm text-black">{{ $produit->categorie_label }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $produit->categorie === 'ingredients' ? 'bg-blue-100 text-blue-800' : 
                           ($produit->categorie === 'plats' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                        {{ $produit->categorie_label }}
                    </span>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Prix unitaire</p>
                        <p class="text-sm text-black">{{ $produit->prix_formate }}</p>
                    </div>
                    <i class="fas fa-euro-sign text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Unité de mesure</p>
                        <p class="text-sm text-black">{{ $produit->unite_mesure }}</p>
                    </div>
                    <i class="fas fa-ruler text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Statut</p>
                        <p class="text-sm text-black">{{ $produit->obligatoire ? 'Obligatoire' : 'Optionnel' }}</p>
                    </div>
                    @if($produit->obligatoire)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            Obligatoire
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                            Optionnel
                        </span>
                    @endif
                </div>
            </div>

            @if($produit->description)
            <div class="mt-6">
                <h4 class="text-sm font-medium text-black mb-2">Description</h4>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-black">{{ $produit->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Statistiques -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Statistiques</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div>
                        <p class="text-sm font-medium text-black">Commandes totales</p>
                        <p class="text-2xl font-semibold text-black">{{ $produit->commandes->count() }}</p>
                    </div>
                    <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border border-green-200">
                    <div>
                        <p class="text-sm font-medium text-black">Quantité totale commandée</p>
                        <p class="text-2xl font-semibold text-black">{{ $produit->commandes->sum('pivot.quantite') }} {{ $produit->unite_mesure }}</p>
                    </div>
                    <i class="fas fa-cubes text-green-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg border border-orange-200">
                    <div>
                        <p class="text-sm font-medium text-black">Valeur totale</p>
                        <p class="text-2xl font-semibold text-black">{{ number_format($produit->commandes->sum('pivot.prix_total'), 2, ',', ' ') }} €</p>
                    </div>
                    <i class="fas fa-euro-sign text-orange-600 text-2xl"></i>
                </div>
            </div>

            <!-- Produits similaires -->
            <div class="mt-6">
                <h4 class="text-sm font-medium text-black mb-3">Produits similaires</h4>
                <div class="space-y-2">
                    @foreach($produits_similaires ?? [] as $produit_similaire)
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">{{ $produit_similaire->nom }}</span>
                        <span class="text-sm text-black">{{ $produit_similaire->prix_formate }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des commandes -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Historique des commandes</h3>
        
        @if($produit->commandes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Commande</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix unitaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($produit->commandes->take(10) as $commande)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                #{{ $commande->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $commande->franchise->nom_complet }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $commande->pivot->quantite ?? 0 }} {{ $produit->unite_mesure }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ number_format($commande->pivot->prix_unitaire ?? 0, 2, ',', ' ') }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ number_format($commande->pivot->prix_total ?? 0, 2, ',', ' ') }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-black text-center py-4">Aucune commande pour ce produit</p>
        @endif
    </div>
</div>
@endsection 
