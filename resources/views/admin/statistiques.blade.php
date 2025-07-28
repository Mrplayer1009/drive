@extends('layouts.admin')

@section('title', 'Statistiques')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Statistiques</h1>
                <p class="text-black">Vue d'ensemble des performances Driv'n Cook</p>
            </div>
            <div class="flex space-x-2">
                <button class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-download mr-2"></i>
                    Exporter PDF
                </button>
                <button class="bg-green-600 hover:bg-green-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Générer rapport
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Franchisés</p>
                    <p class="text-2xl font-semibold text-black">{{ $stats['total_franchises'] }}</p>
                    <p class="text-xs text-green-600">{{ $stats['franchises_actifs'] }} actifs</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-euro-sign text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">CA Total</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($stats['ca_total'], 2, ',', ' ') }} €</p>
                    <p class="text-xs text-green-600">+{{ $stats['croissance_ca'] }}% ce mois</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-chart-line text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Reversements</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($stats['total_reverse'], 2, ',', ' ') }} €</p>
                    <p class="text-xs text-green-600">{{ $stats['pourcentage_reverse'] }}% du CA</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Commandes</p>
                    <p class="text-2xl font-semibold text-black">{{ $stats['total_commandes'] }}</p>
                    <p class="text-xs text-orange-600">{{ $stats['commandes_en_attente'] }} en attente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Évolution des ventes -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Évolution des ventes</h3>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <p class="text-black">Graphique des ventes (à implémenter)</p>
            </div>
        </div>

        <!-- Répartition des franchisés -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Répartition des franchisés</h3>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <p class="text-black">Graphique des franchisés (à implémenter)</p>
            </div>
        </div>
    </div>

    <!-- Top franchisés -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Top 10 des franchisés</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Rang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Ville</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">CA Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Reversements</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Nombre de ventes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($top_franchises as $index => $franchise)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <span class="font-bold">#{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black">
                            {{ $franchise->nom_complet }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $franchise->ville }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ number_format($franchise->ventes->sum('montant_total'), 2, ',', ' ') }} €
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <span class="text-green-600">{{ number_format($franchise->ventes->sum('montant_reverse'), 2, ',', ' ') }} €</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $franchise->ventes->count() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-black">Aucun franchisé trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Statistiques par région -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Performance par région -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Performance par région</h3>
            <div class="space-y-4">
                @foreach($stats_par_region as $region => $stats_region)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-black">{{ $region }}</p>
                        <p class="text-sm text-black">{{ $stats_region['franchises'] }} franchisés</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-black">{{ number_format($stats_region['ca'], 2, ',', ' ') }} €</p>
                        <p class="text-xs text-green-600">{{ $stats_region['croissance'] }}% vs mois dernier</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Produits les plus commandés -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Produits les plus commandés</h3>
            <div class="space-y-4">
                @foreach($produits_populaires as $produit)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-black">{{ $produit->nom }}</p>
                        <p class="text-sm text-black">{{ $produit->categorie_label }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-black">{{ $produit->commandes_count }} commandes</p>
                        <p class="text-xs text-orange-600">{{ $produit->prix_formate }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Rapports périodiques -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Rapports périodiques</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 border border-gray-200 rounded-lg">
                <h4 class="font-medium text-black mb-2">Rapport mensuel</h4>
                <p class="text-sm text-black mb-3">Analyse complète des performances du mois</p>
                <button class="bg-blue-600 hover:bg-blue-700 text-black px-3 py-1 rounded text-sm">
                    Générer
                </button>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <h4 class="font-medium text-black mb-2">Rapport trimestriel</h4>
                <p class="text-sm text-black mb-3">Bilan trimestriel avec projections</p>
                <button class="bg-green-600 hover:bg-green-700 text-black px-3 py-1 rounded text-sm">
                    Générer
                </button>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <h4 class="font-medium text-black mb-2">Rapport annuel</h4>
                <p class="text-sm text-black mb-3">Rapport complet de l'année</p>
                <button class="bg-purple-600 hover:bg-purple-700 text-black px-3 py-1 rounded text-sm">
                    Générer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection 
