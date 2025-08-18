@extends('layouts.admin')

@section('title', 'Stocks - ' . $entrepot->nom)

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Gestion des Stocks</h1>
                <p class="text-black">Entrepôt : {{ $entrepot->nom }}</p>
                <div class="mt-2 flex items-center space-x-4">
                    <div class="flex items-center">
                        <span class="text-sm text-black">Capacité totale : {{ number_format($entrepot->capacite_stockage, 0, ',', ' ') }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-black">Utilisation : 
                            @php
                                $totalStock = $stocks->sum('quantite_stock');
                                $pourcentageUtilisation = $entrepot->capacite_stockage > 0 ? ($totalStock / $entrepot->capacite_stockage) * 100 : 0;
                            @endphp
                            <span class="font-semibold {{ $pourcentageUtilisation > 80 ? 'text-red-600' : ($pourcentageUtilisation > 60 ? 'text-orange-600' : 'text-green-600') }}">
                                {{ number_format($pourcentageUtilisation, 1) }}%
                            </span>
                            ({{ number_format($totalStock, 0, ',', ' ') }} / {{ number_format($entrepot->capacite_stockage, 0, ',', ' ') }})
                        </span>
                    </div>
                </div>
                
                <!-- Barre de progression de l'utilisation -->
                <div class="mt-3">
                    <div class="flex justify-between text-xs text-black mb-1">
                        <span>0%</span>
                        <span>100%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $pourcentageUtilisation > 80 ? 'bg-red-500' : ($pourcentageUtilisation > 60 ? 'bg-orange-500' : 'bg-green-500') }}" 
                             style="width: {{ min($pourcentageUtilisation, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-black mt-1">
                        <span>Vide</span>
                        <span>Plein</span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.entrepots.stocks.create', $entrepot->id) }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un stock
                </a>
                <a href="{{ route('admin.entrepots.show', $entrepot->id) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à l'entrepôt
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0 md:space-x-4">
            <!-- Recherche par produit -->
            <div class="flex-1 w-full md:w-96">
                <label for="search" class="block text-sm font-medium text-black mb-2">Rechercher un produit</label>
                <div class="relative">
                    <input type="text" id="search" placeholder="Nom du produit..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="flex space-x-2">
                <button id="filter-critique" class="bg-red-600 hover:bg-red-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Stocks critiques (< 50)
                </button>
                <button id="filter-faible" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Stocks faibles
                </button>
                <button id="filter-all" class="bg-green-600 hover:bg-green-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-check-circle mr-2"></i>
                    Tous les stocks
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-boxes text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Total produits</p>
                    <p class="text-2xl font-semibold text-black">{{ $stocks->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-check-circle text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Stocks OK</p>
                    <p class="text-2xl font-semibold text-black">{{ $stocks->filter(function($stock) { return $stock->quantite_stock > $stock->stock_minimum; })->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Stock faible</p>
                    <p class="text-2xl font-semibold text-black">{{ $stocks->filter(function($stock) { return $stock->quantite_stock <= $stock->stock_minimum && $stock->quantite_stock > 50; })->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Stock critique</p>
                    <p class="text-2xl font-semibold text-black">{{ $stocks->filter(function($stock) { return $stock->quantite_stock <= 50; })->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des stocks -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-black">Liste des stock</h3>
        </div>
        
        @if($stocks->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="stocks-table">
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
                        <tr class="stock-row" data-product="{{ strtolower($stock->produit->nom) }}" data-stock="{{ $stock->quantite_stock }}" data-minimum="{{ $stock->stock_minimum }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-black">{{ $stock->produit->nom }}</div>
                                    <div class="text-sm text-gray-500">{{ $stock->produit->description }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ $stock->produit->categorie_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                <span class="font-semibold">{{ number_format($stock->quantite_stock, 2) }}</span>
                                <span class="text-gray-500">{{ $stock->produit->unite_mesure }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ number_format($stock->stock_minimum, 2) }} {{ $stock->produit->unite_mesure }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($stock->quantite_stock <= 50)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Critique
                                    </span>
                                @elseif($stock->quantite_stock <= $stock->stock_minimum)
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
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.entrepots.stocks.show', [$entrepot->id, $stock->produit_id]) }}" 
                                       class="text-blue-600 hover:text-blue-700" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.entrepots.stocks.edit', [$entrepot->id, $stock->id]) }}" 
                                       class="text-orange-600 hover:text-orange-700" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                                                         <button type="button" class="text-green-600 hover:text-green-700" 
                                             onclick="openAddModal({{ $stock->produit_id }}, '{{ $stock->produit->nom }}', {{ $stock->quantite_stock }}, '{{ $stock->produit->unite_mesure }}')" 
                                             title="Ajouter du stock">
                                         <i class="fas fa-plus"></i>
                                     </button>
                                     <button type="button" class="text-red-600 hover:text-red-700" 
                                             onclick="openRemoveModal({{ $stock->produit_id }}, '{{ $stock->produit->nom }}', {{ $stock->quantite_stock }}, '{{ $stock->produit->unite_mesure }}')" 
                                             title="Retirer du stock">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                     <button type="button" class="text-purple-600 hover:text-purple-700" 
                                             onclick="openEditModal({{ $stock->id }}, '{{ $stock->produit->nom }}', {{ $stock->quantite_stock }}, {{ $stock->stock_minimum }}, '{{ $stock->produit->unite_mesure }}')" 
                                             title="Modifier le stock">
                                         <i class="fas fa-cog"></i>
                                     </button>
                                </div>
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
                <h3 class="text-lg font-medium text-black mb-2">Aucun stock enregistré</h3>
                <p class="text-black mb-4">Aucun produit n'a de stock défini dans cet entrepôt.</p>
                <a href="{{ route('admin.entrepots.stocks.create', $entrepot->id) }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter le premier stock
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal Ajouter Stock -->
<div id="addStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-black mb-4">Ajouter du stock</h3>
            <form id="addStockForm" method="POST">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-black"><strong>Produit :</strong> <span id="addProductName"></span></p>
                    <p class="text-sm text-black"><strong>Stock actuel :</strong> <span id="addCurrentStock"></span></p>
                </div>
                <div class="mb-4">
                    <label for="addQuantity" class="block text-sm font-medium text-black mb-2">Quantité à ajouter</label>
                    <input type="number" step="0.01" min="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" 
                           id="addQuantity" name="quantite" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-black rounded-md hover:bg-green-700 transition duration-300">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Retirer Stock -->
<div id="removeStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-black mb-4">Retirer du stock</h3>
            <form id="removeStockForm" method="POST">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-black"><strong>Produit :</strong> <span id="removeProductName"></span></p>
                    <p class="text-sm text-black"><strong>Stock actuel :</strong> <span id="removeCurrentStock"></span></p>
                </div>
                <div class="mb-4">
                    <label for="removeQuantity" class="block text-sm font-medium text-black mb-2">Quantité à retirer</label>
                    <input type="number" step="0.01" min="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" 
                           id="removeQuantity" name="quantite" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRemoveModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-black rounded-md hover:bg-red-700 transition duration-300">
                        Retirer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Stock -->
<div id="editStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-black mb-4">Modifier le stock</h3>
            <form id="editStockForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <p class="text-sm text-black"><strong>Produit :</strong> <span id="editProductName"></span></p>
                </div>
                <div class="mb-4">
                    <label for="editCurrentStock" class="block text-sm font-medium text-black mb-2">Stock actuel</label>
                    <input type="number" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" 
                           id="editCurrentStock" name="quantite_stock" required>
                </div>
                <div class="mb-4">
                    <label for="editMinimumStock" class="block text-sm font-medium text-black mb-2">Stock minimum</label>
                    <input type="number" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" 
                           id="editMinimumStock" name="stock_minimum" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-black rounded-md hover:bg-purple-700 transition duration-300">
                        Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const stockRows = document.querySelectorAll('.stock-row');
    const filterCritique = document.getElementById('filter-critique');
    const filterFaible = document.getElementById('filter-faible');
    const filterAll = document.getElementById('filter-all');

    // Recherche par produit
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        stockRows.forEach(row => {
            const productName = row.getAttribute('data-product');
            if (productName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Filtre stocks critiques (< 50)
    filterCritique.addEventListener('click', function() {
        stockRows.forEach(row => {
            const stock = parseFloat(row.getAttribute('data-stock'));
            if (stock <= 50) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Filtre stocks faibles
    filterFaible.addEventListener('click', function() {
        stockRows.forEach(row => {
            const stock = parseFloat(row.getAttribute('data-stock'));
            const minimum = parseFloat(row.getAttribute('data-minimum'));
            if (stock <= minimum && stock > 50) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Filtre tous les stocks
    filterAll.addEventListener('click', function() {
        stockRows.forEach(row => {
            row.style.display = '';
        });
    });
});

// Fonctions pour les modals
function openAddModal(produitId, productName, currentStock, unit) {
    document.getElementById('addProductName').textContent = productName;
    document.getElementById('addCurrentStock').textContent = currentStock + ' ' + unit;
    document.getElementById('addStockForm').action = '{{ route("admin.entrepots.stocks.ajouter", ["entrepot" => $entrepot->id, "produit" => ":produit"]) }}'.replace(':produit', produitId);
    document.getElementById('addStockModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addStockModal').classList.add('hidden');
    document.getElementById('addQuantity').value = '';
}

function openRemoveModal(produitId, productName, currentStock, unit) {
    document.getElementById('removeProductName').textContent = productName;
    document.getElementById('removeCurrentStock').textContent = currentStock + ' ' + unit;
    document.getElementById('removeQuantity').max = currentStock;
    document.getElementById('removeStockForm').action = '{{ route("admin.entrepots.stocks.retirer", ["entrepot" => $entrepot->id, "produit" => ":produit"]) }}'.replace(':produit', produitId);
    document.getElementById('removeStockModal').classList.remove('hidden');
}

function closeRemoveModal() {
    document.getElementById('removeStockModal').classList.add('hidden');
    document.getElementById('removeQuantity').value = '';
}

function openEditModal(stockId, productName, currentStock, minimumStock, unit) {
    document.getElementById('editProductName').textContent = productName;
    document.getElementById('editCurrentStock').value = currentStock;
    document.getElementById('editMinimumStock').value = minimumStock;
    document.getElementById('editStockForm').action = '{{ route("admin.entrepots.stocks.update", ["entrepot" => $entrepot->id, "stock" => ":stock"]) }}'.replace(':stock', stockId);
    document.getElementById('editStockModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editStockModal').classList.add('hidden');
    document.getElementById('editCurrentStock').value = '';
    document.getElementById('editMinimumStock').value = '';
}
</script>
@endsection

