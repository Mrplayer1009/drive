@extends('layouts.client')

@section('title', 'Paiement réussi - Driv\'n Cook')

<!-- Inclure le service de fidélité JavaScript -->
<script src="{{ asset('js/fidelite.js') }}"></script>

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête de succès -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Paiement réussi !</h1>
            <p class="text-gray-600">Votre commande a été confirmée et sera préparée rapidement.</p>
        </div>

        <!-- Détails de la commande -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Détails de votre commande</h2>
            
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Numéro de commande :</span>
                    <span class="font-medium text-gray-900">#{{ $commande->id }}</span>
                </div>
                
                @php
                    $sousTotal = 0;
                    foreach($commande->menus as $menu) {
                        $sousTotal += $menu->pivot->prix_total;
                    }
                @endphp
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Sous-total :</span>
                    <span class="font-medium">{{ number_format(round($commande->montant_total, 2), 2, ',', ' ') }} €</span>
                </div>
                
                @if($commande->reduction_fidelite > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Réduction fidélité :</span>
                    <span class="font-medium text-green-600">-{{ number_format(round($commande->reduction_fidelite, 2), 2, ',', ' ') }} €</span>
                </div>
                @endif
                
                <div class="flex justify-between pt-2 border-t border-gray-200">
                    <span class="text-gray-900 font-semibold">Montant final :</span>
                    <span class="font-bold text-orange-600">{{ number_format(round($commande->montant_final, 2), 2, ',', ' ') }} €</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Méthode de paiement :</span>
                    <span class="font-medium text-gray-900">{{ ucfirst($commande->mode_paiement) }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Référence de paiement :</span>
                    <span class="font-medium text-gray-900">{{ $commande->reference_paiement }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Statut :</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        {{ ucfirst($commande->statut) }}
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Date de commande :</span>
                    <span class="font-medium text-gray-900">{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Articles commandés -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Articles commandés</h3>
            
            <div class="space-y-3">
                @foreach($commande->menus as $menu)
                <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $menu->nom }}</h4>
                        <p class="text-sm text-gray-600">Quantité: {{ $menu->pivot->quantite }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">{{ number_format($menu->pivot->prix_total, 2, ',', ' ') }} €</p>
                        <p class="text-sm text-gray-600">{{ number_format($menu->pivot->prix_unitaire, 2, ',', ' ') }} € l'unité</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Informations de récupération -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de récupération</h3>
            
            <div class="space-y-2 text-gray-600">
                <p><strong>Nom :</strong> {{ $commande->client->nom }}</p>
                <p><strong>Email :</strong> {{ $commande->client->email }}</p>
                <p><strong>Food Truck :</strong> {{ $commande->franchise->prenom }} {{ $commande->franchise->nom }}</p>
                <p><strong>Adresse :</strong> {{ $commande->adresse_livraison }}</p>
                <p><strong>Téléphone :</strong> {{ $commande->telephone_contact }}</p>
            </div>
        </div>

        <!-- Prochaines étapes -->
        <div class="bg-blue-50 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Prochaines étapes</h3>
            <div class="space-y-2 text-blue-800">
                @if(isset($redirection) && $redirection)
                    <!-- Client avec compte -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-600">1</span>
                        </div>
                        <p>Vous recevrez un email de confirmation avec les détails de votre commande</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-600">2</span>
                        </div>
                        <p>Votre commande sera préparée et vous serez contacté pour le retrait de la commande</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-600">3</span>
                        </div>
                        <p>Vous pouvez suivre l'état de votre commande dans votre espace client</p>
                    </div>
                @else
                    <!-- Client sans compte -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-600">1</span>
                        </div>
                        <p>Votre commande a été confirmée et sera préparée par le food truck</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-600">2</span>
                        </div>
                        <p>Quand votre commande sera prête, vous recevrez un email avec un code de récupération</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-600">3</span>
                        </div>
                        <p>Présentez ce code au food truck pour récupérer votre commande</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4">
            @if(isset($redirection) && $redirection)
                <!-- Client avec compte -->
                <a href="{{ route('client.commandes') }}" 
                   class="flex-1 bg-orange-600 text-white py-3 px-4 rounded-md font-medium hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-200 text-center"
                   style="background-color: #f97316 !important;"
                   onmouseover="this.style.backgroundColor='#ea580c'"
                   onmouseout="this.style.backgroundColor='#f97316'">
                    Voir mes commandes
                </a>
                <a href="{{ route('client.index') }}" 
                   class="flex-1 bg-gray-600 text-white py-3 px-4 rounded-md font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 text-center">
                    Continuer mes achats
                </a>
            @else
                <!-- Client sans compte - boutons avec popup -->
                <button onclick="showCreateAccountPopup()" 
                        class="flex-1 bg-orange-600 text-white py-3 px-4 rounded-md font-medium hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-200 text-center"
                        style="background-color: #f97316 !important;"
                        onmouseover="this.style.backgroundColor='#ea580c'"
                        onmouseout="this.style.backgroundColor='#f97316'">
                    Voir mes commandes
                </button>
                <button onclick="showCreateAccountPopup()" 
                        class="flex-1 bg-gray-600 text-white py-3 px-4 rounded-md font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 text-center">
                    Continuer mes achats
                </button>
            @endif
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-8 text-center text-sm text-gray-500">
            @if(isset($redirection) && $redirection)
                <p>Un email de confirmation a été envoyé à <strong>{{ $commande->client->email }}</strong></p>
                <p class="mt-1">Si vous avez des questions, n'hésitez pas à nous contacter.</p>
            @else
                <p>Votre commande a été confirmée et sera préparée par <strong>{{ $commande->franchise->prenom }} {{ $commande->franchise->nom }}</strong></p>
                <p class="mt-1">Vous recevrez un email avec le code de récupération quand votre commande sera prête.</p>
            @endif
        </div>
    </div>
</div>

<!-- Popup de création de compte -->
<div id="createAccountPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="popup-content">
        <div class="text-center">
            <!-- Icône -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-user-plus text-3xl text-blue-600"></i>
            </div>
            
            <!-- Titre -->
            <h3 class="text-xl font-bold text-gray-900 mb-2">Créer un compte</h3>
            
            <!-- Message -->
            <p class="text-gray-600 mb-6">Pour accéder à vos commandes et continuer vos achats, veuillez créer un compte avec votre email : <strong>{{ $commande->client->email }}</strong></p>
            
            <!-- Boutons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('register') }}?email={{ $commande->client->email }}" 
                   class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Créer un compte
                </a>
                <button onclick="closeCreateAccountPopup()" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script pour vider le panier localStorage et ajouter les points -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vider le panier localStorage après un paiement réussi
    localStorage.removeItem('panier');
    
    // Mettre à jour le compteur du panier dans le header
    const panierCount = document.querySelector('.panier-count');
    if (panierCount) {
        panierCount.textContent = '0';
        panierCount.style.display = 'none';
    }
    
    console.log('Panier vidé avec succès après paiement');
    
    // Ajouter les points de fidélité après un paiement réussi
    const montantFinal = localStorage.getItem('montant_final_commande');
    
    if (montantFinal && window.fideliteService) {
        try {
            const pointsGagnes = window.fideliteService.ajouterPoints(parseFloat(montantFinal));
            
            // Afficher une notification de points gagnés
            if (pointsGagnes > 0) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300';
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-star mr-2"></i>
                        <span>+${pointsGagnes} points de fidélité gagnés !</span>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Animation de sortie après 5 secondes
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }, 300);
                }, 5000);
            }
            
            // Nettoyer le localStorage
            localStorage.removeItem('montant_final_commande');
            
        } catch (error) {
            console.error('Erreur lors de l\'ajout des points:', error);
        }
    }
});

// Fonctions pour la popup de création de compte
function showCreateAccountPopup() {
    const popup = document.getElementById('createAccountPopup');
    const popupContent = document.getElementById('popup-content');
    
    popup.style.display = 'flex';
    
    setTimeout(() => {
        popupContent.classList.remove('scale-95', 'opacity-0');
        popupContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeCreateAccountPopup() {
    const popup = document.getElementById('createAccountPopup');
    const popupContent = document.getElementById('popup-content');
    
    popupContent.classList.remove('scale-100', 'opacity-100');
    popupContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        popup.style.display = 'none';
    }, 300);
}

// Fermer la popup en cliquant à l'extérieur
document.addEventListener('click', function(event) {
    const popup = document.getElementById('createAccountPopup');
    const popupContent = document.getElementById('popup-content');
    
    if (event.target === popup) {
        closeCreateAccountPopup();
    }
});

// Fermer la popup avec la touche Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const popup = document.getElementById('createAccountPopup');
        if (popup.style.display === 'flex') {
            closeCreateAccountPopup();
        }
    }
});
</script>
@endsection
