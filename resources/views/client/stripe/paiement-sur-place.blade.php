<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Commande #{{ $commande->id }} - Driv'n Cook</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- En-tête -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-4">
                    <i class="fas fa-credit-card text-2xl text-orange-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Paiement de votre commande</h1>
                <p class="text-gray-600">Commande #{{ $commande->id }} - {{ $commande->franchise->prenom }} {{ $commande->franchise->nom }}</p>
            </div>

            <!-- Détails de la commande -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">📋 Récapitulatif de votre commande</h2>
                
                <div class="space-y-3 mb-4">
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
                
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total à payer :</span>
                        <span class="text-orange-600">{{ number_format($commande->montant_final, 2, ',', ' ') }} €</span>
                    </div>
                </div>
            </div>

            <!-- Informations client -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">👤 Vos informations</h3>
                
                <div class="space-y-2 text-gray-600">
                    <p><strong>Nom :</strong> {{ $commande->client->prenom }} {{ $commande->client->nom }}</p>
                    <p><strong>Email :</strong> {{ $commande->client->email }}</p>
                    <p><strong>Téléphone :</strong> {{ $commande->telephone_contact }}</p>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">💳 Informations de paiement</h3>
                
                <form id="payment-form">
                    <div id="payment-element" class="mb-6">
                        <!-- Stripe Elements sera inséré ici -->
                    </div>
                    
                    <div id="payment-message" class="hidden mb-4 p-4 rounded-md"></div>
                    
                    <button id="submit-button" type="submit" 
                            class="w-full bg-orange-600 text-white py-3 px-4 rounded-md font-medium hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="button-text">Payer {{ number_format($commande->montant_final, 2, ',', ' ') }} €</span>
                        <div id="spinner" class="hidden">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Informations de sécurité -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p><i class="fas fa-lock mr-1"></i> Paiement sécurisé par Stripe</p>
                <p class="mt-1">Vos informations de paiement sont protégées par un chiffrement SSL</p>
            </div>
        </div>
    </div>

    <script>
        // Configuration Stripe
        const stripe = Stripe('{{ $stripeKey }}');
        const clientSecret = '{{ $clientSecret }}';
        const elements = stripe.elements({ 
            clientSecret,
            appearance: {
                theme: 'stripe',
            }
        });

        // Créer l'élément de carte bancaire uniquement
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
        cardElement.mount('#payment-element');

        // Gestion du formulaire
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');
        const paymentMessage = document.getElementById('payment-message');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Désactiver le bouton et afficher le spinner
            submitButton.disabled = true;
            buttonText.style.display = 'none';
            spinner.classList.remove('hidden');
            
            // Masquer les messages d'erreur précédents
            paymentMessage.classList.add('hidden');

            // Confirmer le paiement avec carte bancaire
            const result = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: '{{ $commande->client->nom }}',
                        email: '{{ $commande->client->email }}',
                    },
                }
            });

            if (result.error) {
                // Afficher l'erreur
                paymentMessage.textContent = result.error.message;
                paymentMessage.classList.remove('hidden');
                paymentMessage.className = 'mb-4 p-4 rounded-md bg-red-100 text-red-700';
                
                // Réactiver le bouton
                submitButton.disabled = false;
                buttonText.style.display = 'inline';
                spinner.classList.add('hidden');
            } else {
                // Paiement réussi, rediriger vers la page de succès
                window.location.href = '{{ route("client.stripe.success") }}?payment_intent=' + result.paymentIntent.id + '&commande_id={{ $commande->id }}&token={{ $token }}';
            }
        });
    </script>
</body>
</html>
