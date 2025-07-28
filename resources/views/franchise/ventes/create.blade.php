@extends('layouts.franchise')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Nouvelle Vente</h1>
                <p class="text-black">Enregistrez une nouvelle vente</p>
            </div>
            <a href="{{ route('franchise.ventes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('franchise.ventes.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations de la vente -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations de la vente</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="date_vente" class="block text-sm font-medium text-black mb-2">Date de vente</label>
                        <input type="date" id="date_vente" name="date_vente" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="camion_id" class="block text-sm font-medium text-black mb-2">Camion utilisé</label>
                        <select id="camion_id" name="camion_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Sélectionnez un camion (optionnel)</option>
                            @foreach(Auth::user()->camionsActifs as $camion)
                            <option value="{{ $camion->id }}">{{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="montant_total" class="block text-sm font-medium text-black mb-2">Montant total (€)</label>
                        <input type="number" id="montant_total" name="montant_total" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="0.00">
                    </div>

                    <div>
                        <label for="nombre_commandes" class="block text-sm font-medium text-black mb-2">Nombre de commandes</label>
                        <input type="number" id="nombre_commandes" name="nombre_commandes" min="1" value="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-black mb-2">Notes (optionnel)</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Informations supplémentaires sur la vente..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Calcul automatique -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Calcul automatique</h3>
                
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-black">Montant total</span>
                            <span class="text-sm text-black" id="montant-total-display">0,00 €</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-black">Pourcentage reversé ({{ Auth::user()->pourcentage_ventes }}%)</span>
                            <span class="text-sm text-green-600" id="pourcentage-display">0,00 €</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-black">Montant net</span>
                                <span class="text-sm font-semibold text-black" id="montant-net-display">0,00 €</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                        <h4 class="text-sm font-medium text-black mb-2">
                            <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                            Informations importantes
                        </h4>
                        <div class="text-xs text-black space-y-1">
                            <p>• Le pourcentage de {{ Auth::user()->pourcentage_ventes }}% est automatiquement calculé</p>
                            <p>• Le montant reversé est déduit automatiquement</p>
                            <p>• Un PDF sera généré pour chaque vente</p>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="text-sm font-medium text-black mb-2">
                            <i class="fas fa-lightbulb text-blue-600 mr-1"></i>
                            Conseils
                        </h4>
                        <div class="text-xs text-black space-y-1">
                            <p>• Enregistrez vos ventes quotidiennement</p>
                            <p>• Indiquez le camion utilisé si applicable</p>
                            <p>• Ajoutez des notes pour le suivi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('franchise.ventes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-6 py-2 rounded-lg transition duration-300">
                Annuler
            </a>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-6 py-2 rounded-lg transition duration-300">
                <i class="fas fa-save mr-2"></i>
                Enregistrer la vente
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const montantTotalInput = document.getElementById('montant_total');
    const montantTotalDisplay = document.getElementById('montant-total-display');
    const pourcentageDisplay = document.getElementById('pourcentage-display');
    const montantNetDisplay = document.getElementById('montant-net-display');
    const pourcentage = {{ Auth::user()->pourcentage_ventes }};

    function calculerMontants() {
        const montantTotal = parseFloat(montantTotalInput.value) || 0;
        const montantReverse = montantTotal * (pourcentage / 100);
        const montantNet = montantTotal - montantReverse;

        montantTotalDisplay.textContent = montantTotal.toFixed(2) + ' €';
        pourcentageDisplay.textContent = montantReverse.toFixed(2) + ' €';
        montantNetDisplay.textContent = montantNet.toFixed(2) + ' €';
    }

    montantTotalInput.addEventListener('input', calculerMontants);
    
    // Calculer les montants au chargement
    calculerMontants();
});
</script>
@endsection 
