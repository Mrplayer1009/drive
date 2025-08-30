@extends('layouts.client')

@section('title', 'Test Fidélité - Driv\'n Cook')

<!-- Inclure le service de fidélité JavaScript -->
<script src="{{ asset('js/fidelite.js') }}"></script>

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Test de la Fidélité JavaScript</h1>
            <p class="text-gray-600">Page de test pour vérifier le fonctionnement du système de fidélité côté client</p>
        </div>

        <!-- Contrôles de test -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-cogs text-orange-600 mr-2"></i>
                Contrôles de test
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ajouter des points</label>
                    <div class="flex">
                        <input type="number" id="points-to-add" value="100" min="1" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <button onclick="ajouterPointsTest()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-r-md transition duration-200">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Simuler une commande</label>
                    <div class="flex">
                        <input type="number" id="montant-commande" value="25" min="1" step="0.01" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <button onclick="simulerCommande()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-md transition duration-200">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Actions</label>
                    <div class="flex space-x-2">
                        <button onclick="resetFidelite()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-200">
                            <i class="fas fa-trash"></i> Reset
                        </button>
                        <button onclick="afficherInfos()" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md transition duration-200">
                            <i class="fas fa-info"></i> Infos
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affichage des informations -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-line text-orange-600 mr-2"></i>
                Informations de fidélité
            </h2>
            
            <div id="infos-fidelite" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Les informations seront affichées ici par JavaScript -->
            </div>
        </div>

        <!-- Test de réduction -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-percentage text-orange-600 mr-2"></i>
                Test de réduction
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant de la commande (€)</label>
                    <input type="number" id="montant-test" value="50" min="1" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Réduction à appliquer (€)</label>
                    <input type="number" id="reduction-test" value="5" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
            </div>
            
            <div class="mt-4">
                <button onclick="testerReduction()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md transition duration-200">
                    <i class="fas fa-check"></i> Tester la réduction
                </button>
            </div>
            
            <div id="resultat-test" class="mt-4 p-4 rounded-lg" style="display: none;">
                <!-- Le résultat sera affiché ici -->
            </div>
        </div>
    </div>
</div>

<script>
// Fonctions de test
function ajouterPointsTest() {
    const points = parseInt(document.getElementById('points-to-add').value);
    if (points > 0 && window.fideliteService) {
        const fidelite = JSON.parse(localStorage.getItem('drivncook_fidelite') || '{}');
        fidelite.points = (fidelite.points || 0) + points;
        fidelite.niveau = window.fideliteService.calculerNiveau(fidelite.points);
        fidelite.lastUpdate = new Date().toISOString();
        localStorage.setItem('drivncook_fidelite', JSON.stringify(fidelite));
        
        afficherInfos();
        afficherNotification(`+${points} points ajoutés !`);
    }
}

function simulerCommande() {
    const montant = parseFloat(document.getElementById('montant-commande').value);
    if (montant > 0 && window.fideliteService) {
        const pointsGagnes = window.fideliteService.ajouterPoints(montant);
        afficherInfos();
        afficherNotification(`Commande simulée : +${pointsGagnes} points gagnés !`);
    }
}

function resetFidelite() {
    if (window.fideliteService) {
        window.fideliteService.reset();
        afficherInfos();
        afficherNotification('Fidélité réinitialisée !');
    }
}

function afficherInfos() {
    if (!window.fideliteService) return;
    
    const infos = window.fideliteService.getInfosFidelite();
    const container = document.getElementById('infos-fidelite');
    
    container.innerHTML = `
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="font-bold text-blue-800 mb-2">Points</h3>
            <p class="text-2xl font-bold text-blue-600">${infos.points}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <h3 class="font-bold text-green-800 mb-2">Niveau</h3>
            <p class="text-2xl font-bold text-green-600">${infos.niveau_nom}</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="font-bold text-yellow-800 mb-2">Réduction disponible</h3>
            <p class="text-2xl font-bold text-yellow-600">${window.fideliteService.formaterPrix(infos.reduction_disponible)}</p>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <h3 class="font-bold text-purple-800 mb-2">Réduction cumulée</h3>
            <p class="text-2xl font-bold text-purple-600">${window.fideliteService.formaterPrix(infos.reduction_cumulee)}</p>
        </div>
    `;
}

function testerReduction() {
    if (!window.fideliteService) return;
    
    const montantCommande = parseFloat(document.getElementById('montant-test').value);
    const reductionDemandee = parseFloat(document.getElementById('reduction-test').value);
    
    const validation = window.fideliteService.peutAppliquerReduction(reductionDemandee, montantCommande);
    const resultatDiv = document.getElementById('resultat-test');
    
    if (validation.valide) {
        try {
            const resultat = window.fideliteService.utiliserPoints(reductionDemandee);
            resultatDiv.className = 'mt-4 p-4 rounded-lg bg-green-50 border border-green-200';
            resultatDiv.innerHTML = `
                <h3 class="font-bold text-green-800 mb-2">✅ Réduction appliquée avec succès !</h3>
                <p class="text-green-700">Points utilisés : ${resultat.pointsUtilises}</p>
                <p class="text-green-700">Points restants : ${resultat.pointsRestants}</p>
                <p class="text-green-700">Réduction cumulée : ${window.fideliteService.formaterPrix(resultat.reductionCumulee)}</p>
            `;
            resultatDiv.style.display = 'block';
            afficherInfos();
        } catch (error) {
            resultatDiv.className = 'mt-4 p-4 rounded-lg bg-red-50 border border-red-200';
            resultatDiv.innerHTML = `<p class="text-red-700">❌ Erreur : ${error.message}</p>`;
            resultatDiv.style.display = 'block';
        }
    } else {
        resultatDiv.className = 'mt-4 p-4 rounded-lg bg-red-50 border border-red-200';
        resultatDiv.innerHTML = `<p class="text-red-700">❌ ${validation.message}</p>`;
        resultatDiv.style.display = 'block';
    }
}

function afficherNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300';
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Initialiser l'affichage au chargement
document.addEventListener('DOMContentLoaded', function() {
    afficherInfos();
});
</script>
@endsection
