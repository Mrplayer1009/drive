@extends('layouts.client')

@section('title', 'Paiement - Driv\'n Cook')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Finaliser votre commande</h1>
            <p class="text-gray-600">Paiement sécurisé avec Stripe</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Résumé de la commande -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Résumé de votre commande</h2>
                
                <div class="space-y-4">
                    @foreach($panierData as $item)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $item['nom'] }}</h3>
                            <p class="text-sm text-gray-600">Quantité: {{ $item['quantite'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">{{ number_format($item['prix'] * $item['quantite'], 2, ',', ' ') }} €</p>
                            <p class="text-sm text-gray-600">{{ number_format($item['prix'], 2, ',', ' ') }} € l'unité</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Sous-total :</span>
                            <span class="font-medium">{{ number_format(round($sousTotal, 2), 2, ',', ' ') }} €</span>
                        </div>
                        
                        @if($reductionFidelite > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Réduction fidélité :</span>
                            <span class="font-medium text-green-600">-{{ number_format(round($reductionFidelite, 2), 2, ',', ' ') }} €</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-orange-600">{{ number_format(round($total, 2), 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>

                <!-- Informations client -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h3 class="font-medium text-gray-900 mb-2">Informations client</h3>
                    <div class="text-sm text-gray-600">
                        <p><strong>Nom:</strong> {{ auth('client')->user()->nom }}</p>
                        <p><strong>Email:</strong> {{ auth('client')->user()->email }}</p>
                        <p><strong>Téléphone:</strong> {{ auth('client')->user()->telephone }}</p>
                    </div>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations de paiement</h2>
                
                <form id="payment-form" class="space-y-6">
                    <div>
                        <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">
                            Carte de crédit ou de débit
                        </label>
                        <div id="card-element" class="p-3 border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-orange-500 focus-within:border-orange-500">
                            <!-- Stripe Elements sera inséré ici -->
                        </div>
                        <div id="card-errors" class="mt-2 text-sm text-red-600" role="alert"></div>
                    </div>

                    <button type="submit" id="submit-button" 
                            class="w-full bg-orange-600 text-white py-3 px-4 rounded-md font-medium hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition duration-200"
                            style="background-color: #f97316 !important;"
                            onmouseover="this.style.backgroundColor='#ea580c'"
                            onmouseout="this.style.backgroundColor='#f97316'">
                        <span id="button-text">Payer {{ number_format($total, 2, ',', ' ') }} €</span>
                        <div id="spinner" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </form>

                <!-- Informations de sécurité -->
                <div class="mt-6 p-4 bg-gray-50 rounded-md">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm text-gray-600">Paiement sécurisé par Stripe</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Stripe -->
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Stripe
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();
    
    // Debug: vérifier les variables
    console.log('Stripe Key:', '{{ config("services.stripe.key") }}');
    console.log('Client Secret:', '{{ $clientSecret }}');
    console.log('Route success:', '{{ route("client.stripe.success") }}');
    
    // Créer l'élément de carte
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });
    
    cardElement.mount('#card-element');
    
    // Gérer les erreurs de validation en temps réel
    cardElement.addEventListener('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Gérer la soumission du formulaire
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Vider le panier localStorage immédiatement
        localStorage.removeItem('panier');
        console.log('Panier vidé du localStorage');
        
        // Mettre à jour le compteur du panier dans le header
        const panierCount = document.querySelector('.panier-count');
        if (panierCount) {
            panierCount.textContent = '0';
            panierCount.style.display = 'none';
        }
        
        // Désactiver le bouton et afficher le spinner
        submitButton.disabled = true;
        buttonText.style.display = 'none';
        spinner.classList.remove('hidden');
        
        try {
            console.log('Tentative de confirmation du paiement avec clientSecret:', '{{ $clientSecret }}');
            
            // Confirmer le paiement
            const result = await stripe.confirmCardPayment('{{ $clientSecret }}', {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: '{{ auth("client")->user()->nom }}',
                        email: '{{ auth("client")->user()->email }}',
                    },
                }
            });
            
            console.log('Résultat du paiement:', result);
            
            if (result.error) {
                console.log('Erreur de paiement:', result.error);
                // Afficher l'erreur
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                
                // Réactiver le bouton
                submitButton.disabled = false;
                buttonText.style.display = 'block';
                spinner.classList.add('hidden');
            } else {
                console.log('Paiement réussi!');
                console.log('PaymentIntent ID:', result.paymentIntent.id);
                console.log('Status:', result.paymentIntent.status);
                
                // Paiement réussi, vider le panier de la session et rediriger
                fetch('{{ route("client.panier.vider") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                }).then(() => {
                    // Rediriger vers la page de succès
                    const successUrl = '{{ route("client.stripe.success") }}?payment_intent=' + result.paymentIntent.id;
                    console.log('Redirection vers:', successUrl);
                    
                    // Utiliser setTimeout pour s'assurer que les logs sont affichés
                    setTimeout(() => {
                        window.location.href = successUrl;
                    }, 1000);
                }).catch(error => {
                    console.error('Erreur lors du vidage du panier:', error);
                    // Rediriger quand même
                    const successUrl = '{{ route("client.stripe.success") }}?payment_intent=' + result.paymentIntent.id;
                    window.location.href = successUrl;
                });
            }
        } catch (error) {
            console.error('Erreur:', error);
            
            // Afficher l'erreur
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = 'Une erreur est survenue lors du paiement. Veuillez réessayer.';
            
            // Réactiver le bouton
            submitButton.disabled = false;
            buttonText.style.display = 'block';
            spinner.classList.add('hidden');
        }
    });
});
</script>
@endsection
