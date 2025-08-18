@extends('layouts.admin')

@section('title', 'Détails de l\'Entrepôt')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Détails de l'Entrepôt</h1>
                <p class="text-black">{{ $entrepot->nom }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.entrepots.edit', $entrepot) }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.entrepots.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations de l'entrepôt -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations de l'entrepôt</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Nom</p>
                        <p class="text-sm text-black">{{ $entrepot->nom }}</p>
                    </div>
                    <i class="fas fa-warehouse text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Adresse complète</p>
                        <p class="text-sm text-black">{{ $entrepot->adresse_complete }}</p>
                    </div>
                    <i class="fas fa-map-marker-alt text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Téléphone</p>
                        <p class="text-sm text-black">{{ $entrepot->telephone ?? 'Non renseigné' }}</p>
                    </div>
                    <i class="fas fa-phone text-orange-600"></i>
                </div>

                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-black">Capacité</p>
                            <p class="text-sm text-black">{{ number_format($entrepot->capacite_stockage, 0, ',', ' ') }}</p>
                        </div>
                        <i class="fas fa-cubes text-orange-600"></i>
                    </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Cuisine</p>
                        <p class="text-sm text-black">{{ $entrepot->cuisine ? 'Oui' : 'Non' }}</p>
                    </div>
                    @if($entrepot->cuisine)
                        <i class="fas fa-utensils text-green-600"></i>
                    @else
                        <i class="fas fa-times text-gray-400"></i>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Statistiques</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div>
                        <p class="text-sm font-medium text-black">Commandes totales</p>
                        <p class="text-2xl font-semibold text-black">{{ $entrepot->commandes->count() }}</p>
                    </div>
                    <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border border-green-200">
                    <div>
                        <p class="text-sm font-medium text-black">Commandes validées</p>
                        <p class="text-2xl font-semibold text-black">{{ $entrepot->commandes->where('statut', 'validee')->count() }}</p>
                    </div>
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg border border-orange-200">
                    <div>
                        <p class="text-sm font-medium text-black">Commandes en attente</p>
                        <p class="text-2xl font-semibold text-black">{{ $entrepot->commandes->where('statut', 'en_attente')->count() }}</p>
                    </div>
                    <i class="fas fa-clock text-orange-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg border border-purple-200">
                    <div>
                        <p class="text-sm font-medium text-black">Valeur totale</p>
                        <p class="text-2xl font-semibold text-black">{{ number_format($entrepot->commandes->sum('total_commande'), 2, ',', ' ') }} €</p>
                    </div>
                    <i class="fas fa-euro-sign text-purple-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                    <div>
                        <p class="text-sm font-medium text-black">Produits en stock</p>
                        <p class="text-2xl font-semibold text-black">{{ $stocks->count() }}</p>
                    </div>
                    <i class="fas fa-boxes text-indigo-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg border border-red-200">
                    <div>
                        <p class="text-sm font-medium text-black">Produits en rupture</p>
                        <p class="text-2xl font-semibold text-black">{{ $stocks->where('quantite_stock', '<=', 'stock_minimum')->count() }}</p>
                    </div>
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Produits en stock -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-black">Produits en stock</h3>
            <a href="{{ route('admin.entrepots.stocks.index', $entrepot) }}" class="bg-orange-600 hover:bg-orange-700 text-black px-3 py-1 rounded-lg text-sm transition duration-300">
                <i class="fas fa-cog mr-1"></i>
                Gérer les stocks
            </a>
        </div>
        
        @if($stocks->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Stock actuel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Stock minimum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stocks as $stock)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-black">{{ $stock->produit->nom }}</div>
                                    <div class="text-sm text-gray-500">{{ $stock->produit->description }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $stock->produit->categorie_label }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $stock->quantite_stock }} {{ $stock->produit->unite_mesure }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $stock->stock_minimum }} {{ $stock->produit->unite_mesure }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($stock->quantite_stock <= $stock->stock_minimum)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Rupture
                                    </span>
                                @elseif($stock->quantite_stock <= ($stock->stock_minimum * 1.5))
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Faible
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        OK
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.entrepots.stocks.edit', [$entrepot, $stock]) }}" class="text-orange-600 hover:text-orange-700 mr-3" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.entrepots.stocks.show', [$entrepot, $stock]) }}" class="text-blue-600 hover:text-blue-700" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-boxes text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-black mb-2">Aucun produit en stock</h3>
                <p class="text-black mb-4">Cet entrepôt n'a pas encore de produits en stock.</p>
                <a href="{{ route('admin.entrepots.stocks.create', $entrepot) }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un produit
                </a>
            </div>
        @endif
    </div>

    <!-- Commandes récentes -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Commandes récentes</h3>
        
        @if($entrepot->commandes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Commande</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($entrepot->commandes->take(10) as $commande)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                #{{ $commande->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $commande->franchise->nom_complet }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $commande->date_commande->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $commande->statut === 'validee' ? 'bg-green-100 text-green-800' : 
                                       ($commande->statut === 'en_attente' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $commande->statut_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $commande->total_formate }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-black text-center py-4">Aucune commande pour cet entrepôt</p>
        @endif
    </div>
</div>
@endsection 
