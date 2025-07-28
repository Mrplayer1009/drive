@extends('layouts.franchise')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Nouvelle Commande</h1>
                <p class="text-black">Créez une nouvelle commande de stock</p>
            </div>
            <a href="{{ route('franchise.commandes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('franchise.commandes.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations de la commande -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-black mb-4">Informations de la commande</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="entrepot_id" class="block text-sm font-medium text-black mb-2">Entrepôt</label>
                            <select id="entrepot_id" name="entrepot_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                                <option value="">Sélectionnez un entrepôt</option>
                                @foreach($entrepots as $entrepot)
                                <option value="{{ $entrepot->id }}">{{ $entrepot->nom }} - {{ $entrepot->ville }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-black mb-2">Notes (optionnel)</label>
                            <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Informations supplémentaires..."></textarea>
                        </div>

                        <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                            <h4 class="text-sm font-medium text-black mb-2">
                                <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                                Règle 80/20
                            </h4>
                            <div class="text-xs text-black space-y-1">
                                <p>• 80% de produits obligatoires</p>
                                <p>• 20% de produits au choix</p>
                                <p>• Les produits marqués "Obligatoire" sont imposés</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des produits -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-black mb-4">Produits</h3>
                    
                    <div class="space-y-4" id="produits-container">
                        <!-- Template pour un produit -->
                        <div class="produit-item border border-gray-200 rounded-lg p-4">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <div class="col-span-4">
                                    <label class="block text-sm font-medium text-black mb-1">Produit</label>
                                    <select name="produits[0][produit_id]" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                                        <option value="">Sélectionnez un produit</option>
                                        @foreach($produits as $produit)
                                        <option value="{{ $produit->id }}" data-prix="{{ $produit->prix_unitaire }}" data-obligatoire="{{ $produit->obligatoire ? '1' : '0' }}">
                                            {{ $produit->nom }} - {{ $produit->prix_unitaire }}€/{{ $produit->unite_mesure }}
                                            @if($produit->obligatoire)
                                                <span class="text-red-600">(Obligatoire)</span>
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-black mb-1">Quantité</label>
                                    <input type="number" name="produits[0][quantite]" min="1" value="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-black mb-1">Prix unitaire</label>
                                    <input type="number" step="0.01" readonly class="prix-unitaire w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-black mb-1">Total</label>
                                    <input type="number" step="0.01" readonly class="prix-total w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                </div>
                                <div class="col-span-2 flex items-end">
                                    <button type="button" class="supprimer-produit bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md transition duration-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="button" id="ajouter-produit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter un produit
                        </button>
                    </div>

                    <!-- Résumé de la commande -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-lg font-medium text-black mb-4">Résumé de la commande</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-black">Total obligatoire</p>
                                <p class="text-lg font-semibold text-black" id="total-obligatoire">0,00 €</p>
                            </div>
                            <div>
                                <p class="text-sm text-black">Total libre</p>
                                <p class="text-lg font-semibold text-black" id="total-libre">0,00 €</p>
                            </div>
                            <div>
                                <p class="text-sm text-black">Total général</p>
                                <p class="text-lg font-semibold text-black" id="total-general">0,00 €</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('franchise.commandes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-300">
                Annuler
            </a>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition duration-300">
                <i class="fas fa-save mr-2"></i>
                Créer la commande
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let produitIndex = 1;

    // Ajouter un produit
    document.getElementById('ajouter-produit').addEventListener('click', function() {
        const container = document.getElementById('produits-container');
        const template = container.querySelector('.produit-item').cloneNode(true);
        
        // Mettre à jour les noms des champs
        template.querySelectorAll('select, input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace('[0]', '[' + produitIndex + ']');
            }
        });
        
        // Réinitialiser les valeurs
        template.querySelectorAll('input').forEach(input => {
            if (input.type === 'number') {
                input.value = input.name.includes('quantite') ? '1' : '';
            }
        });
        
        template.querySelector('select').selectedIndex = 0;
        
        container.appendChild(template);
        produitIndex++;
    });

    // Supprimer un produit
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('supprimer-produit')) {
            const produits = document.querySelectorAll('.produit-item');
            if (produits.length > 1) {
                e.target.closest('.produit-item').remove();
                calculerTotaux();
            }
        }
    });

    // Calculer les totaux
    function calculerTotaux() {
        let totalObligatoire = 0;
        let totalLibre = 0;
        
        document.querySelectorAll('.produit-item').forEach(item => {
            const select = item.querySelector('select');
            const quantite = parseFloat(item.querySelector('input[name*="quantite"]').value) || 0;
            const prixUnitaire = parseFloat(item.querySelector('.prix-unitaire').value) || 0;
            const total = quantite * prixUnitaire;
            
            if (select.selectedOptions[0] && select.selectedOptions[0].dataset.obligatoire === '1') {
                totalObligatoire += total;
            } else {
                totalLibre += total;
            }
            
            item.querySelector('.prix-total').value = total.toFixed(2);
        });
        
        document.getElementById('total-obligatoire').textContent = totalObligatoire.toFixed(2) + ' €';
        document.getElementById('total-libre').textContent = totalLibre.toFixed(2) + ' €';
        document.getElementById('total-general').textContent = (totalObligatoire + totalLibre).toFixed(2) + ' €';
    }

    // Écouter les changements
    document.addEventListener('change', function(e) {
        if (e.target.name && (e.target.name.includes('produit_id') || e.target.name.includes('quantite'))) {
            const item = e.target.closest('.produit-item');
            const select = item.querySelector('select');
            const quantite = parseFloat(item.querySelector('input[name*="quantite"]').value) || 0;
            
            if (select.selectedOptions[0]) {
                const prix = parseFloat(select.selectedOptions[0].dataset.prix) || 0;
                item.querySelector('.prix-unitaire').value = prix.toFixed(2);
                item.querySelector('.prix-total').value = (prix * quantite).toFixed(2);
            }
            
            calculerTotaux();
        }
    });

    // Initialiser les totaux
    calculerTotaux();
});
</script>
@endsection 