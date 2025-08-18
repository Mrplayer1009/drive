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
                                 <a href="{{ route('admin.statistiques.export-pdf') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300 inline-flex items-center">
                     <i class="fas fa-download mr-2"></i>
                     Exporter PDF
                 </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Filtres</h3>
        <form method="GET" action="{{ route('admin.statistiques') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Période -->
            <div>
                <label for="periode" class="block text-sm font-medium text-black mb-2">Période</label>
                <select id="periode" name="periode" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="tout" {{ request('periode') == 'tout' ? 'selected' : '' }}>Toute la période</option>
                    <option value="mois" {{ request('periode') == 'mois' ? 'selected' : '' }}>Ce mois</option>
                    <option value="trimestre" {{ request('periode') == 'trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                    <option value="annee" {{ request('periode') == 'annee' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>



            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrer
                </button>
                <a href="{{ route('admin.statistiques') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-times mr-2"></i>
                    Effacer
                </a>
            </div>
        </form>
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

    <!-- Évolution des ventes -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Évolution des ventes</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Ce mois</p>
                <p class="text-2xl font-bold text-black">{{ number_format($stats['ca_mois_courant'], 0, ',', ' ') }} €</p>
                <div class="flex items-center justify-center mt-2">
                    @if($stats['croissance_ca'] > 0)
                        <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                        <span class="text-green-600 text-sm">+{{ $stats['croissance_ca'] }}%</span>
                    @elseif($stats['croissance_ca'] < 0)
                        <i class="fas fa-arrow-down text-red-600 mr-1"></i>
                        <span class="text-red-600 text-sm">{{ $stats['croissance_ca'] }}%</span>
                    @else
                        <i class="fas fa-minus text-gray-600 mr-1"></i>
                        <span class="text-gray-600 text-sm">0%</span>
                    @endif
                </div>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Mois dernier</p>
                <p class="text-2xl font-bold text-gray-700">{{ number_format($stats['ca_mois_precedent'], 0, ',', ' ') }} €</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Moyenne 6 mois</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['ca_moyenne_6mois'], 0, ',', ' ') }} €</p>
            </div>
        </div>
    </div>

    <!-- Performance par région -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Performance par région</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stats_par_region as $region => $stats_region)
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-black mb-2">{{ $region }}</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">CA ce mois:</span>
                        <span class="font-medium text-black">{{ number_format($stats_region['ca'], 0, ',', ' ') }} €</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Franchisés:</span>
                        <span class="font-medium text-black">{{ $stats_region['franchises'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Évolution:</span>
                        <div class="flex items-center">
                            @if($stats_region['croissance'] > 0)
                                <i class="fas fa-arrow-up text-green-600 mr-1 text-xs"></i>
                                <span class="text-green-600 text-sm">+{{ $stats_region['croissance'] }}%</span>
                            @elseif($stats_region['croissance'] < 0)
                                <i class="fas fa-arrow-down text-red-600 mr-1 text-xs"></i>
                                <span class="text-red-600 text-sm">{{ $stats_region['croissance'] }}%</span>
                            @else
                                <i class="fas fa-minus text-gray-600 mr-1 text-xs"></i>
                                <span class="text-gray-600 text-sm">0%</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Reversements -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Reversements</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total reversements:</span>
                    <span class="text-2xl font-bold text-green-600">{{ number_format($stats['total_reverse'], 0, ',', ' ') }} €</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">% du CA:</span>
                    <span class="text-lg font-medium text-black">{{ $stats['pourcentage_reverse'] }}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Évolution vs mois dernier:</span>
                    <div class="flex items-center">
                        @if($stats['evolution_reverse'] > 0)
                            <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                            <span class="text-green-600">+{{ $stats['evolution_reverse'] }}%</span>
                        @elseif($stats['evolution_reverse'] < 0)
                            <i class="fas fa-arrow-down text-red-600 mr-1"></i>
                            <span class="text-red-600">{{ $stats['evolution_reverse'] }}%</span>
                        @else
                            <i class="fas fa-minus text-gray-600 mr-1"></i>
                            <span class="text-gray-600">0%</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Commandes</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total commandes:</span>
                    <span class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_commandes'], 0, ',', ' ') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">En attente:</span>
                    <span class="text-lg font-medium text-orange-600">{{ number_format($stats['commandes_en_attente'], 0, ',', ' ') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Évolution vs mois dernier:</span>
                    <div class="flex items-center">
                        @if($stats['evolution_commandes'] > 0)
                            <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                            <span class="text-green-600">+{{ $stats['evolution_commandes'] }}%</span>
                        @elseif($stats['evolution_commandes'] < 0)
                            <i class="fas fa-arrow-down text-red-600 mr-1"></i>
                            <span class="text-red-600">{{ $stats['evolution_commandes'] }}%</span>
                        @else
                            <i class="fas fa-minus text-gray-600 mr-1"></i>
                            <span class="text-gray-600">0%</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sélection des franchisés actifs -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-black">Sélection des franchisés actifs</h3>
            <div class="flex space-x-2">
                <button onclick="selectAll()" class="bg-blue-600 hover:bg-blue-700 text-black px-3 py-1 rounded text-sm">
                    <i class="fas fa-check-square mr-1"></i>
                    Tout sélectionner
                </button>
                <button onclick="deselectAll()" class="bg-gray-600 hover:bg-gray-700 text-black px-3 py-1 rounded text-sm">
                    <i class="fas fa-square mr-1"></i>
                    Tout désélectionner
                </button>
            </div>
        </div>

        <div class="mb-4">
            <input type="text" id="searchFranchises" 
                   placeholder="Rechercher un franchisé par nom..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                   onkeyup="filterFranchises()">
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4" id="franchisesGrid">
            @foreach($active_franchises as $franchise)
            <div class="franchise-card border border-gray-200 rounded-lg p-3" data-name="{{ strtolower($franchise->nom_complet) }}">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="selected_franchises[]" value="{{ $franchise->id }}" 
                           class="franchise-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                            data-franchise="{{ json_encode([
                                'id' => $franchise->id,
                                'nom' => $franchise->nom_complet,
                                'ville' => $franchise->ville,
                                'ca_total' => number_format($franchise->ventes->sum('montant_total'), 0, ',', ' '),
                                'ventes_count' => $franchise->ventes->count(),
                                'reversements' => number_format($franchise->ventes->sum('montant_reverse'), 0, ',', ' ')
                            ]) }}"
                           onchange="updateSelectedFranchises()">
                    <div class="flex-1">
                        <p class="font-medium text-black">{{ $franchise->nom_complet }}</p>
                        <p class="text-sm text-gray-600">{{ $franchise->ville }}</p>
                        <p class="text-xs text-green-600 font-medium">Actif</p>
                    </div>
                </label>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Détails des franchisés sélectionnés -->
    <div id="selectedFranchisesDetails" class="bg-white shadow rounded-lg p-6" style="display: none;">
        <h3 class="text-lg font-medium text-black mb-4">Détails des franchisés sélectionnés</h3>
        <div id="franchisesDetailsContent"></div>
    </div>

    <!-- Top franchisés -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Top 10 des franchisés</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-16 px-3 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Rang</th>
                        <th class="w-1/4 px-3 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                        <th class="w-1/6 px-3 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Ville</th>
                        <th class="w-20 px-3 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                        <th class="w-1/6 px-3 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">CA Total</th>
                        <th class="w-1/6 px-3 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Reversements</th>
                        <th class="w-24 px-3 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Ventes</th>
                        <th class="w-16 px-3 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($top_franchises as $index => $franchise)
                    <tr>
                        <td class="px-3 py-4 text-sm text-black">
                            <span class="font-bold">#{{ $index + 1 }}</span>
                        </td>
                        <td class="px-3 py-4 text-sm font-medium text-black truncate" title="{{ $franchise->nom_complet }}">
                            {{ $franchise->nom_complet }}
                        </td>
                        <td class="px-3 py-4 text-sm text-black truncate" title="{{ $franchise->ville }}">
                            {{ $franchise->ville }}
                        </td>
                        <td class="px-3 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $franchise->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($franchise->statut) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm text-black">
                            {{ number_format($franchise->ventes_sum_montant_total ?? 0, 0, ',', ' ') }} €
                        </td>
                        <td class="px-3 py-4 text-sm text-black">
                            <span class="text-green-600">{{ number_format($franchise->ventes_sum_montant_reverse ?? 0, 0, ',', ' ') }} €</span>
                        </td>
                        <td class="px-3 py-4 text-sm text-black text-center">
                            {{ $franchise->ventes_count ?? $franchise->ventes->count() }}
                        </td>
                        <td class="px-3 py-4 text-sm font-medium text-center">
                            <a href="{{ route('admin.franchises.show', $franchise) }}" class="text-blue-600 hover:text-blue-700" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-3 py-4 text-center text-black">Aucun franchisé trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Produits les plus commandés -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Produits les plus commandés</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($produits_populaires as $produit)
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-black mb-2">{{ $produit->nom }}</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Commandes:</span>
                        <span class="font-medium text-black">{{ $produit->commandes_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Prix:</span>
                        <span class="font-medium text-orange-600">{{ $produit->prix_formate }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Catégorie:</span>
                        <span class="font-medium text-black">{{ $produit->categorie_label }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>



<!-- JavaScript pour les fonctionnalités -->
<script>
// Fonctions pour la sélection des franchisés
function selectAll() {
    document.querySelectorAll('.franchise-checkbox:not([style*="display: none"])').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedFranchises();
}

function deselectAll() {
    document.querySelectorAll('.franchise-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedFranchises();
}

function filterFranchises() {
    const searchTerm = document.getElementById('searchFranchises').value.toLowerCase();
    const franchiseCards = document.querySelectorAll('.franchise-card');
    
    franchiseCards.forEach(card => {
        const franchiseName = card.getAttribute('data-name');
        if (franchiseName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function updateSelectedFranchises() {
    const selectedCheckboxes = document.querySelectorAll('.franchise-checkbox:checked');
    const detailsDiv = document.getElementById('selectedFranchisesDetails');
    const contentDiv = document.getElementById('franchisesDetailsContent');
    
    if (selectedCheckboxes.length > 0) {
        detailsDiv.style.display = 'block';
        
        let content = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';
        
        selectedCheckboxes.forEach(checkbox => {
            const franchiseData = JSON.parse(checkbox.getAttribute('data-franchise'));
            
            content += `
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-black mb-2">${franchiseData.nom}</h4>
                    <p class="text-sm text-gray-600 mb-2">${franchiseData.ville}</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">CA Total:</span>
                            <span class="font-medium text-black">${franchiseData.ca_total} €</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nombre de ventes:</span>
                            <span class="font-medium text-purple-600">${franchiseData.ventes_count}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Reversements:</span>
                            <span class="font-medium text-green-600">${franchiseData.reversements} €</span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                        </div>
                    </div>
                </div>
            `;
        });
        
        content += '</div>';
        contentDiv.innerHTML = content;
    } else {
        detailsDiv.style.display = 'none';
    }
}

// Fonction pour la génération de rapport
function generateReport() {
    alert('Fonctionnalité de génération de rapport en cours de développement');
}
</script>
@endsection 
