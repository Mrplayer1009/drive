@extends('layouts.admin')

@section('title', 'Ajouter un Stock - ' . $entrepot->nom)

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Ajouter un Stock</h1>
                <p class="text-black">Entrepôt : {{ $entrepot->nom }}</p>
            </div>
            <a href="{{ route('admin.entrepots.stocks.index', $entrepot->id) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour aux stocks
            </a>
        </div>
    </div>

    <form action="{{ route('admin.entrepots.stocks.store', $entrepot->id) }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sélection du produit -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Sélection du produit</h3>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Recherche de produit -->
                <div class="mb-4">
                    <label for="search_produit" class="block text-sm font-medium text-black mb-2">Rechercher un produit</label>
                    <div class="relative">
                        <input type="text" id="search_produit" placeholder="Tapez le nom du produit..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="produit_id" class="block text-sm font-medium text-black mb-2">Produit *</label>
                    <select id="produit_id" name="produit_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Sélectionner un produit</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->id }}" {{ old('produit_id') == $produit->id ? 'selected' : '' }}
                                    data-prix="{{ $produit->prix_unitaire }}"
                                    data-unite="{{ $produit->unite_mesure }}"
                                    data-categorie="{{ $produit->categorie }}"
                                    data-nom="{{ strtolower($produit->nom) }}">
                                {{ $produit->nom }} ({{ $produit->categorie_label }}) - {{ $produit->prix_formate }}/{{ $produit->unite_mesure }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">{{ $produits->count() }} produit(s) disponible(s) pour cet entrepôt</p>
                </div>

                <!-- Informations du produit sélectionné -->
                <div id="produit-info" class="hidden p-4 bg-gray-50 rounded-lg mb-4">
                    <h4 class="text-sm font-medium text-black mb-2">Informations du produit</h4>
                    <div class="space-y-2 text-sm text-black">
                        <div class="flex justify-between">
                            <span>Catégorie :</span>
                            <span id="info-categorie" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Prix unitaire :</span>
                            <span id="info-prix" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Unité :</span>
                            <span id="info-unite" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Stock actuel :</span>
                            <span id="info-stock-actuel" class="font-medium">-</span>
                        </div>
                    </div>
                </div>

                <!-- Produits déjà en stock -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Produits déjà en stock dans cet entrepôt</h4>
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-xs text-black space-y-1">
                            @php
                                $produitsEnStock = $entrepot->stocksProduits()->with('produit')->get();
                            @endphp
                            @if($produitsEnStock->count() > 0)
                                @foreach($produitsEnStock as $stock)
                                    <div class="flex justify-between items-center py-1">
                                        <span>{{ $stock->produit->nom }}</span>
                                        <span class="font-medium">{{ $stock->quantite_stock }} {{ $stock->produit->unite_mesure }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500">Aucun produit en stock pour le moment</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration du stock -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Configuration du stock</h3>
                
                <div class="mb-4">
                    <label for="quantite_stock" class="block text-sm font-medium text-black mb-2">Quantité en stock *</label>
                    <input type="number" id="quantite_stock" name="quantite_stock" value="{{ old('quantite_stock') }}" 
                           step="0.01" min="0" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                           placeholder="0.00">
                    <p class="text-xs text-gray-500 mt-1">Quantité actuellement disponible</p>
                </div>

                <div class="mb-4">
                    <label for="stock_minimum" class="block text-sm font-medium text-black mb-2">Stock minimum *</label>
                    <input type="number" id="stock_minimum" name="stock_minimum" value="{{ old('stock_minimum') }}" 
                           step="0.01" min="0" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                           placeholder="0.00">
                    <p class="text-xs text-gray-500 mt-1">Seuil d'alerte pour les stocks faibles</p>
                </div>

                <!-- Aperçu du stock -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Aperçu du stock</h4>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-black">Produit :</span>
                                <span class="text-sm font-medium text-black" id="preview-produit">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-black">Quantité :</span>
                                <span class="text-sm font-medium text-black" id="preview-quantite">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-black">Stock minimum :</span>
                                <span class="text-sm font-medium text-black" id="preview-minimum">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-black">Statut :</span>
                                <span class="text-sm font-medium text-black" id="preview-statut">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Règles et informations -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Règles et informations</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <h4 class="text-sm font-medium text-black mb-2">
                        <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                        Seuils d'alerte
                    </h4>
                    <div class="text-xs text-black space-y-1">
                        <p>• <strong>Stock critique</strong> : ≤ 50 unités</p>
                        <p>• <strong>Stock faible</strong> : ≤ stock minimum</p>
                        <p>• <strong>Stock OK</strong> : > stock minimum</p>
                    </div>
                </div>

                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="text-sm font-medium text-black mb-2">
                        <i class="fas fa-lightbulb text-blue-600 mr-1"></i>
                        Conseils
                    </h4>
                    <div class="text-xs text-black space-y-1">
                        <p>• Définissez un stock minimum réaliste</p>
                        <p>• Surveillez les stocks critiques</p>
                        <p>• Mettez à jour régulièrement les quantités</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.entrepots.stocks.index', $entrepot->id) }}" 
                   class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-orange-600 text-black rounded-md hover:bg-orange-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>
                    Créer le stock
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const produitSelect = document.getElementById('produit_id');
    const quantiteInput = document.getElementById('quantite_stock');
    const minimumInput = document.getElementById('stock_minimum');
    const produitInfo = document.getElementById('produit-info');
    const searchInput = document.getElementById('search_produit');

    // Fonction pour mettre à jour l'aperçu
    function updatePreview() {
        const selectedOption = produitSelect.options[produitSelect.selectedIndex];
        const quantite = quantiteInput.value || '0';
        const minimum = minimumInput.value || '0';
        
        if (selectedOption && selectedOption.value) {
            const produitNom = selectedOption.text.split(' (')[0];
            const unite = selectedOption.dataset.unite || '-';
            
            document.getElementById('preview-produit').textContent = produitNom;
            document.getElementById('preview-quantite').textContent = quantite + ' ' + unite;
            document.getElementById('preview-minimum').textContent = minimum + ' ' + unite;
            
            // Déterminer le statut
            let statut = 'OK';
            let statutClass = 'text-green-600';
            
            if (parseFloat(quantite) <= 50) {
                statut = 'Critique';
                statutClass = 'text-red-600';
            } else if (parseFloat(quantite) <= parseFloat(minimum)) {
                statut = 'Faible';
                statutClass = 'text-orange-600';
            }
            
            const statutElement = document.getElementById('preview-statut');
            statutElement.textContent = statut;
            statutElement.className = 'text-sm font-medium ' + statutClass;
        } else {
            document.getElementById('preview-produit').textContent = '-';
            document.getElementById('preview-quantite').textContent = '-';
            document.getElementById('preview-minimum').textContent = '-';
            document.getElementById('preview-statut').textContent = '-';
        }
    }

    // Fonction pour mettre à jour les informations du produit
    function updateProduitInfo() {
        const selectedOption = produitSelect.options[produitSelect.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const prix = selectedOption.dataset.prix || '-';
            const unite = selectedOption.dataset.unite || '-';
            const categorie = selectedOption.dataset.categorie || '-';
            
            // Récupérer le stock actuel via AJAX ou utiliser une valeur par défaut
            const stockActuel = '0'; // À remplacer par une requête AJAX si nécessaire
            
            document.getElementById('info-categorie').textContent = categorie;
            document.getElementById('info-prix').textContent = prix + ' €';
            document.getElementById('info-unite').textContent = unite;
            document.getElementById('info-stock-actuel').textContent = stockActuel + ' ' + unite;
            
            produitInfo.classList.remove('hidden');
        } else {
            produitInfo.classList.add('hidden');
        }
    }

    // Fonction de recherche
    function filterProduits(searchTerm) {
        const options = produitSelect.querySelectorAll('option');
        let visibleCount = 0;
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block'; // Toujours afficher l'option par défaut
                return;
            }
            
            const nom = option.dataset.nom || '';
            if (nom.includes(searchTerm.toLowerCase())) {
                option.style.display = 'block';
                visibleCount++;
            } else {
                option.style.display = 'none';
            }
        });
        
        // Mettre à jour le compteur
        const counterElement = produitSelect.parentNode.querySelector('p');
        if (counterElement) {
            counterElement.textContent = `${visibleCount} produit(s) trouvé(s)`;
        }
    }

    // Écouter les changements
    produitSelect.addEventListener('change', function() {
        updateProduitInfo();
        updatePreview();
    });
    
    quantiteInput.addEventListener('input', updatePreview);
    minimumInput.addEventListener('input', updatePreview);
    
    // Écouter la recherche
    searchInput.addEventListener('input', function() {
        filterProduits(this.value);
    });

    // Initialiser l'aperçu
    updatePreview();
});
</script>
@endsection
