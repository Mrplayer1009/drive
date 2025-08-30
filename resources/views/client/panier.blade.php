@extends('layouts.client')

@section('content')
<!-- Inclure le service de fidélité JavaScript -->
<script src="{{ asset('js/fidelite.js') }}"></script>
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50">

    <!-- Contenu du Panier -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div id="panier-vide" class="text-center py-12" style="display: none;">
            <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-600 mb-4">Votre panier est vide</h2>
            <p class="text-gray-500 mb-6">Découvrez nos délicieux menus et commencez vos achats !</p>
            <a href="{{ route('client.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg transition duration-300">
                <i class="fas fa-utensils mr-2"></i>
                Voir les Menus
            </a>
        </div>

        <div id="panier-contenu" style="display: none;">
            <!-- Liste des articles -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Articles dans votre panier</h2>
                <div id="liste-articles" class="space-y-4">
                    <!-- Les articles seront ajoutés ici dynamiquement -->
                </div>
            </div>

            <!-- Résumé de la commande -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Résumé de la commande</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sous-total :</span>
                        <span id="sous-total" class="font-semibold">0,00 €</span>
                    </div>
                    
                    <!-- Section Fidélité -->
                    <div id="section-fidelite" class="border-t pt-3" style="display: none;">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <i class="fas fa-id-card text-yellow-600 mr-2"></i>
                                    <span class="font-semibold text-yellow-800">Votre cagnotte fidélité</span>
                                </div>
                                <span id="cagnotte-disponible" class="font-bold text-yellow-600">0 €</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-yellow-700">Points disponibles : <span id="points-disponibles">0</span></span>
                                <button id="btn-utiliser-fidelite" onclick="utiliserFidelite()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300">
                                    <i class="fas fa-gift mr-1"></i>
                                    Utiliser ma cagnotte
                                </button>
                            </div>
                        </div>
                        
                        <div id="reduction-appliquee" class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3" style="display: none;">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <span class="text-green-800 font-semibold">Réduction fidélité appliquée</span>
                                </div>
                                <div class="text-right">
                                    <span id="montant-reduction" class="font-bold text-green-600">-0,00 €</span>
                                    <button onclick="annulerReduction()" class="block text-sm text-green-600 hover:text-green-800 mt-1">
                                        <i class="fas fa-times mr-1"></i>Annuler
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="text-lg font-bold text-gray-800">Total :</span>
                            <span id="total-commande" class="text-lg font-bold text-orange-600">0,00 €</span>
                        </div>
                    </div>
                </div>



                <!-- Sélection du food truck -->
                <div id="food-truck-selection" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">
                        <i class="fas fa-truck mr-2"></i>
                        Food Truck sélectionné
                    </h3>
                    <div id="selected-food-truck-info" class="hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-blue-900" id="food-truck-name"></p>
                                <p class="text-sm text-blue-700" id="food-truck-address"></p>
                            </div>
                            <button onclick="changeFoodTruck()" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-edit mr-1"></i>Changer
                            </button>
                        </div>
                    </div>
                    <div id="no-food-truck-selected" class="text-center">
                        <p class="text-blue-700 mb-3">Vous devez sélectionner un food truck avant de pouvoir payer</p>
                        <a href="{{ route('client.select-food-truck-page') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Choisir un Food Truck
                        </a>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                    <button onclick="viderPanier()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-300">
                        <i class="fas fa-trash mr-2"></i>
                        Vider le Panier
                    </button>
                    <button id="pay-button" onclick="payerAvecStripe()" class="flex-2 bg-gray-400 text-white px-6 py-3 rounded-lg transition duration-300 text-center cursor-not-allowed" disabled>
                        <i class="fab fa-stripe mr-2"></i>
                        Sélectionnez un Food Truck d'abord
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour la fidélité
let reductionAppliquee = 0;

// Fonction pour formater les prix
function formaterPrix(prix) {
    // Arrondir à 2 décimales pour éviter les problèmes de précision
    const prixArrondi = Math.round(prix * 100) / 100;
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(prixArrondi);
}

// Fonction pour calculer les totaux
function calculerTotaux() {
    const panier = JSON.parse(localStorage.getItem('panier') || '[]');
    const sousTotal = panier.reduce((total, item) => total + (item.prix * item.quantite), 0);
    const total = sousTotal - reductionAppliquee; // Appliquer la réduction fidélité
    
    document.getElementById('sous-total').textContent = formaterPrix(sousTotal);
    document.getElementById('total-commande').textContent = formaterPrix(total);
    
    return { sousTotal, total };
}

// Fonction pour afficher les articles
function afficherArticles() {
    const panier = JSON.parse(localStorage.getItem('panier') || '[]');
    const container = document.getElementById('liste-articles');
    const panierVide = document.getElementById('panier-vide');
    const panierContenu = document.getElementById('panier-contenu');
    
    if (panier.length === 0) {
        panierVide.style.display = 'block';
        panierContenu.style.display = 'none';
        return;
    }
    
    panierVide.style.display = 'none';
    panierContenu.style.display = 'block';
    
    container.innerHTML = '';
    
    panier.forEach((item, index) => {
        const articleDiv = document.createElement('div');
        articleDiv.className = 'flex items-center justify-between p-4 border border-gray-200 rounded-lg';
        articleDiv.innerHTML = `
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-200 to-red-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-utensils text-2xl text-orange-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">${item.nom}</h3>
                    <p class="text-sm text-gray-600">${formaterPrix(item.prix)} l'unité</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <button onclick="modifierQuantite(${index}, -1)" class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center transition duration-200">
                        <i class="fas fa-minus text-sm"></i>
                    </button>
                    <span class="w-12 text-center font-semibold">${item.quantite}</span>
                    <button onclick="modifierQuantite(${index}, 1)" class="w-8 h-8 bg-orange-200 hover:bg-orange-300 rounded-full flex items-center justify-center transition duration-200">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-800">${formaterPrix(item.prix * item.quantite)}</p>
                    <button onclick="supprimerArticle(${index})" class="text-red-500 hover:text-red-700 text-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(articleDiv);
    });
    
    calculerTotaux();
}

// Fonction pour modifier la quantité
function modifierQuantite(index, delta) {
    const panier = JSON.parse(localStorage.getItem('panier') || '[]');
    
    if (panier[index]) {
        panier[index].quantite += delta;
        
        if (panier[index].quantite <= 0) {
            panier.splice(index, 1);
        }
        
        localStorage.setItem('panier', JSON.stringify(panier));
        afficherArticles();
    }
}

// Fonction pour supprimer un article
function supprimerArticle(index) {
    const panier = JSON.parse(localStorage.getItem('panier') || '[]');
    panier.splice(index, 1);
    localStorage.setItem('panier', JSON.stringify(panier));
    afficherArticles();
}

// Fonction pour vider le panier
function viderPanier() {
    if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
        localStorage.removeItem('panier');
        afficherArticles();
    }
}

// Fonction pour charger les informations de fidélité
function chargerInfosFidelite() {
    const infosFidelite = window.fideliteService.getInfosFidelite();
    afficherSectionFidelite(infosFidelite);
}

// Fonction pour afficher la section fidélité
function afficherSectionFidelite(infosFidelite) {
    const sectionFidelite = document.getElementById('section-fidelite');
    const cagnotteDisponible = document.getElementById('cagnotte-disponible');
    const pointsDisponibles = document.getElementById('points-disponibles');
    const btnUtiliserFidelite = document.getElementById('btn-utiliser-fidelite');
    
    if (infosFidelite && infosFidelite.reduction_disponible > 0) {
        sectionFidelite.style.display = 'block';
        cagnotteDisponible.textContent = window.fideliteService.formaterPrix(infosFidelite.reduction_disponible);
        pointsDisponibles.textContent = infosFidelite.points;
        
        // Activer le bouton seulement si il y a des points
        btnUtiliserFidelite.disabled = false;
        btnUtiliserFidelite.className = 'bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300';
    } else {
        sectionFidelite.style.display = 'none';
    }
}

// Fonction pour utiliser la fidélité
async function utiliserFidelite() {
    const infosFidelite = window.fideliteService.getInfosFidelite();
    
    if (!infosFidelite || infosFidelite.reduction_disponible <= 0) {
        alert('Aucune réduction disponible');
        return;
    }
    
    const panier = JSON.parse(localStorage.getItem('panier') || '[]');
    const sousTotal = panier.reduce((total, item) => total + (item.prix * item.quantite), 0);
    
    // Calculer la réduction maximale (50% du montant)
    const reductionMaximale = Math.min(infosFidelite.reduction_disponible, sousTotal * 0.5);
    
    // Créer une modal moderne pour choisir le montant
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-gift text-2xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Utiliser votre cagnotte</h3>
                <p class="text-gray-600">Choisissez le montant de réduction à appliquer</p>
            </div>
            
            <div class="space-y-4 mb-6">
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-yellow-800">Votre cagnotte :</span>
                        <span class="font-bold text-yellow-900">${window.fideliteService.formaterPrix(infosFidelite.reduction_disponible)}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-yellow-800">Montant commande :</span>
                        <span class="font-bold text-yellow-900">${window.fideliteService.formaterPrix(sousTotal)}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-yellow-800">Réduction max :</span>
                        <span class="font-bold text-green-600">${window.fideliteService.formaterPrix(reductionMaximale)}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Montant de réduction (€)
                    </label>
                    <input type="number" id="reduction-input" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                           min="0" max="${reductionMaximale}" step="0.01" 
                           value="${reductionMaximale.toFixed(2)}">
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button id="btn-annuler" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium transition duration-200">
                    Annuler
                </button>
                <button id="btn-confirmer" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-check mr-2"></i>Confirmer
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Focus sur l'input
    const input = modal.querySelector('#reduction-input');
    input.focus();
    input.select();
    
    // Gestion des événements
    const montantUtilise = await new Promise((resolve) => {
        modal.querySelector('#btn-annuler').onclick = () => {
            document.body.removeChild(modal);
            resolve(null);
        };
        
        modal.querySelector('#btn-confirmer').onclick = () => {
            const montant = parseFloat(input.value);
            document.body.removeChild(modal);
            resolve(montant);
        };
        
        // Fermer avec Escape
        modal.onkeydown = (e) => {
            if (e.key === 'Escape') {
                document.body.removeChild(modal);
                resolve(null);
            }
        };
        
        // Validation en temps réel
        input.oninput = () => {
            const val = parseFloat(input.value);
            const btnConfirmer = modal.querySelector('#btn-confirmer');
            
            if (isNaN(val) || val < 0 || val > reductionMaximale) {
                btnConfirmer.disabled = true;
                btnConfirmer.className = 'flex-1 bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-medium cursor-not-allowed';
            } else {
                btnConfirmer.disabled = false;
                btnConfirmer.className = 'flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200';
            }
        };
    });
    
    if (montantUtilise === null) return; // Annulé
    
    const montant = parseFloat(montantUtilise);
    
    if (isNaN(montant) || montant <= 0) {
        alert('Montant invalide');
        return;
    }
    
    // Vérifier si la réduction peut être appliquée
    const validation = window.fideliteService.peutAppliquerReduction(montant, sousTotal);
    if (!validation.valide) {
        alert(validation.message);
        return;
    }
    
    try {
        // Utiliser les points
        const resultat = window.fideliteService.utiliserPoints(montant);
        
        // Appliquer la réduction
        reductionAppliquee = montant;
        afficherReductionAppliquee();
        calculerTotaux();
        
        // Mettre à jour l'affichage de la fidélité
        chargerInfosFidelite();
        
        console.log(`Points utilisés : ${resultat.pointsUtilises}, Points restants : ${resultat.pointsRestants}`);
    } catch (error) {
        alert('Erreur lors de l\'application de la réduction : ' + error.message);
    }
}

// Fonction pour afficher la réduction appliquée
function afficherReductionAppliquee() {
    const reductionDiv = document.getElementById('reduction-appliquee');
    const montantReduction = document.getElementById('montant-reduction');
    
    if (reductionAppliquee > 0) {
        reductionDiv.style.display = 'block';
        montantReduction.textContent = `-${window.fideliteService.formaterPrix(reductionAppliquee)}`;
        
        // Afficher une notification de succès
        afficherNotificationSucces(`Réduction fidélité de ${window.fideliteService.formaterPrix(reductionAppliquee)} appliquée avec succès !`);
    } else {
        reductionDiv.style.display = 'none';
    }
}

// Fonction pour afficher une notification de succès
function afficherNotificationSucces(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Animation de sortie après 3 secondes
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Fonction pour annuler la réduction
function annulerReduction() {
    reductionAppliquee = 0;
    afficherReductionAppliquee();
    calculerTotaux();
}



// Fonction pour payer avec Stripe
function payerAvecStripe() {
    const panier = JSON.parse(localStorage.getItem('panier') || '[]');
    
    if (panier.length === 0) {
        alert('Votre panier est vide');
        return;
    }
    
    // Vérifier qu'un food truck est sélectionné
    const selectedFoodTruckId = localStorage.getItem('selectedFoodTruckId');
    if (!selectedFoodTruckId) {
        alert('Veuillez d\'abord sélectionner un food truck');
        window.location.href = '{{ route("client.select-food-truck-page") }}';
        return;
    }
    
    // Calculer le montant final pour les points
    const sousTotal = panier.reduce((total, item) => total + (item.prix * item.quantite), 0);
    const montantFinal = sousTotal - reductionAppliquee;
    
    // Stocker le montant final pour l'ajout de points après paiement
    localStorage.setItem('montant_final_commande', montantFinal);
    
    // Synchroniser le food truck sélectionné avec la session
    fetch('{{ route("client.select-food-truck") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            food_truck_id: selectedFoodTruckId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Envoyer le panier au serveur via AJAX avec la réduction fidélité
            return fetch('{{ route("client.panier.ajouter") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    panier_data: panier,
                    reduction_fidelite: reductionAppliquee
                })
            });
        } else {
            throw new Error(data.message || 'Erreur lors de la sélection du food truck');
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Rediriger vers Stripe
            window.location.href = '{{ route("client.stripe.checkout") }}';
        } else {
            alert('Erreur lors de la préparation du paiement');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la préparation du paiement: ' + error.message);
    });
}

// Fonction pour changer de food truck
function changeFoodTruck() {
    localStorage.removeItem('selectedFoodTruckId');
    localStorage.removeItem('selectedFoodTruckName');
    localStorage.removeItem('selectedFoodTruckAddress');
    window.location.href = '{{ route("client.select-food-truck-page") }}';
}

// Fonction pour vérifier et afficher le food truck sélectionné
function checkSelectedFoodTruck() {
    const selectedFoodTruckId = localStorage.getItem('selectedFoodTruckId');
    const selectedFoodTruckName = localStorage.getItem('selectedFoodTruckName');
    const selectedFoodTruckAddress = localStorage.getItem('selectedFoodTruckAddress');
    
    const selectedInfo = document.getElementById('selected-food-truck-info');
    const noSelected = document.getElementById('no-food-truck-selected');
    const payButton = document.getElementById('pay-button');
    
    if (selectedFoodTruckId && selectedFoodTruckName) {
        // Food truck sélectionné
        document.getElementById('food-truck-name').textContent = selectedFoodTruckName;
        document.getElementById('food-truck-address').textContent = selectedFoodTruckAddress || '';
        
        selectedInfo.classList.remove('hidden');
        noSelected.classList.add('hidden');
        
        // Activer le bouton de paiement
        payButton.disabled = false;
        payButton.className = 'flex-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-300 text-center';
        payButton.innerHTML = '<i class="fab fa-stripe mr-2"></i>Payer avec Stripe';
    } else {
        // Aucun food truck sélectionné
        selectedInfo.classList.add('hidden');
        noSelected.classList.remove('hidden');
        
        // Désactiver le bouton de paiement
        payButton.disabled = true;
        payButton.className = 'flex-2 bg-gray-400 text-white px-6 py-3 rounded-lg transition duration-300 text-center cursor-not-allowed';
        payButton.innerHTML = '<i class="fab fa-stripe mr-2"></i>Sélectionnez un Food Truck d\'abord';
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    afficherArticles();
    checkSelectedFoodTruck();
    chargerInfosFidelite(); // Charger les infos de fidélité
});
</script>
@endsection
