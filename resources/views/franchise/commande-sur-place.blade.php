@extends('layouts.franchise')

@section('title', 'Commande sur place - Driv\'n Cook')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📝 Commande sur place</h1>
            <p class="text-gray-600">Enregistrez une commande pour un client présent sur place</p>
        </div>

                 @if(session('success'))
             <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg mb-6 shadow-sm">
                 <div class="flex items-start">
                     <div class="flex-shrink-0">
                         <i class="fas fa-check-circle text-green-500 text-xl"></i>
                     </div>
                     <div class="ml-3">
                         <div class="text-sm font-medium text-green-800 whitespace-pre-line">
                             {{ session('success') }}
                         </div>
                     </div>
                 </div>
             </div>
         @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('franchise.commande-sur-place.envoyer') }}" method="POST" id="commandeForm">
            @csrf
            
            <!-- Informations client -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">👤 Informations client</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="nom_client" class="block text-sm font-medium text-gray-700 mb-2">Nom du client *</label>
                        <input type="text" id="nom_client" name="nom_client" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="Nom du client">
                    </div>
                    
                    <div>
                        <label for="email_client" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" id="email_client" name="email_client" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="email@exemple.com">
                    </div>
                    
                    <div>
                        <label for="telephone_client" class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                        <input type="tel" id="telephone_client" name="telephone_client" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="06 12 34 56 78">
                    </div>
                </div>
            </div>

            <!-- Menu et sélection d'articles -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">🍽️ Sélection des articles</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @foreach($menus as $menu)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $menu->nom }}</h3>
                            <span class="text-lg font-bold text-orange-600">{{ number_format($menu->prix, 2, ',', ' ') }} €</span>
                        </div>
                        
                        @if($menu->description)
                            <p class="text-sm text-gray-600 mb-3">{{ $menu->description }}</p>
                        @endif
                        
                                                 <div class="flex items-center justify-between">
                             <label class="text-sm font-medium text-gray-700">Quantité:</label>
                             <div class="flex items-center space-x-2">
                                 <button type="button" class="btn-quantite-minus w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 text-sm font-bold border border-gray-300" data-menu-id="{{ $menu->id }}" style="min-width: 32px; min-height: 32px;">−</button>
                                 <input type="number" name="articles[{{ $menu->id }}][quantite]" value="0" min="0" max="99" 
                                        class="quantite-input w-12 text-center border border-gray-300 rounded py-1" 
                                        data-menu-id="{{ $menu->id }}" data-prix="{{ $menu->prix }}">
                                 <button type="button" class="btn-quantite-plus w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white hover:bg-orange-600 text-sm font-bold border border-orange-600" data-menu-id="{{ $menu->id }}" style="min-width: 32px; min-height: 32px;">+</button>
                             </div>
                         </div>
                        
                        <input type="hidden" name="articles[{{ $menu->id }}][menu_id]" value="{{ $menu->id }}">
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Résumé de la commande -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">📋 Résumé de la commande</h2>
                
                <div id="resume-commandes" class="space-y-3 mb-4">
                    <p class="text-gray-500 italic">Aucun article sélectionné</p>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total :</span>
                        <span id="total-commande" class="text-orange-600">0,00 €</span>
                    </div>
                </div>
            </div>

                         <!-- Informations avant envoi -->
             <div class="bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-lg mb-6">
                 <div class="flex items-start">
                     <div class="flex-shrink-0">
                         <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                     </div>
                     <div class="ml-3">
                         <h4 class="text-sm font-medium text-blue-800 mb-2">Comment fonctionne la commande sur place ?</h4>
                         <ul class="text-sm text-blue-700 space-y-1">
                             <li>• Vous sélectionnez les articles et remplissez les informations client</li>
                             <li>• Un email sera envoyé au client avec le récapitulatif et un lien de paiement</li>
                             <li>• Le client clique sur le lien et paie en ligne via Stripe</li>
                             <li>• Une fois payée, la commande apparaîtra dans vos commandes clients</li>
                         </ul>
                     </div>
                 </div>
             </div>

                           <!-- Bouton d'envoi -->
              <div class="flex justify-end">
                  <button type="submit" id="btn-envoyer" disabled
                          class="bg-blue-600 text-white px-10 py-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center space-x-3 text-lg border-2 border-blue-500">
                      <i class="fas fa-paper-plane text-xl"></i>
                      <span>📧 Envoyer l'email au client</span>
                      <div id="spinner" class="hidden">
                          <i class="fas fa-spinner fa-spin text-xl"></i>
                      </div>
                  </button>
              </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DÉBUT DU SCRIPT ===');
    
    // Récupération des éléments
    const quantiteInputs = document.querySelectorAll('.quantite-input');
    const btnPlus = document.querySelectorAll('.btn-quantite-plus');
    const btnMinus = document.querySelectorAll('.btn-quantite-minus');
    const resumeCommandes = document.getElementById('resume-commandes');
    const totalCommande = document.getElementById('total-commande');
    const btnEnvoyer = document.getElementById('btn-envoyer');
    
    console.log('Éléments trouvés:', {
        quantiteInputs: quantiteInputs.length,
        btnPlus: btnPlus.length,
        btnMinus: btnMinus.length,
        resumeCommandes: resumeCommandes ? 'trouvé' : 'non trouvé',
        totalCommande: totalCommande ? 'trouvé' : 'non trouvé',
        btnEnvoyer: btnEnvoyer ? 'trouvé' : 'non trouvé'
    });

    // Fonction pour mettre à jour le résumé
    function updateResume() {
        console.log('=== UPDATE RESUME DÉBUT ===');
        let total = 0;
        let articles = [];
        
        quantiteInputs.forEach((input, index) => {
            const quantite = parseInt(input.value) || 0;
            const prix = parseFloat(input.dataset.prix);
            const menuId = input.dataset.menuId;
            
            console.log(`Input ${index}: menuId=${menuId}, quantite=${quantite}, prix=${prix}`);
            
            if (quantite > 0) {
                const sousTotal = quantite * prix;
                total += sousTotal;
                
                // Trouver le nom du menu
                const menuCard = input.closest('.border');
                const menuNameElement = menuCard.querySelector('h3');
                const menuName = menuNameElement ? menuNameElement.textContent.trim() : `Menu ${menuId}`;
                
                console.log(`Article trouvé: ${menuName} (${quantite}x${prix}€ = ${sousTotal}€)`);
                
                articles.push({
                    nom: menuName,
                    quantite: quantite,
                    prix: prix,
                    sousTotal: sousTotal
                });
            }
        });
        
        console.log('Articles trouvés:', articles);
        console.log('Total calculé:', total);
        
        // Mettre à jour l'affichage du résumé
        if (articles.length > 0) {
            let html = '';
            articles.forEach(article => {
                html += `
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-900">${article.nom} × ${article.quantite}</span>
                        <span class="font-medium text-gray-900">${article.sousTotal.toFixed(2).replace('.', ',')} €</span>
                    </div>
                `;
            });
            resumeCommandes.innerHTML = html;
            console.log('Résumé HTML généré:', html);
        } else {
            resumeCommandes.innerHTML = '<p class="text-gray-500 italic">Aucun article sélectionné</p>';
            console.log('Aucun article, message par défaut affiché');
        }
        
        // Mettre à jour le total
        totalCommande.textContent = total.toFixed(2).replace('.', ',') + ' €';
        
        // Activer/désactiver le bouton d'envoi
        btnEnvoyer.disabled = total === 0;
        
        console.log('Total affiché:', totalCommande.textContent);
        console.log('Bouton envoyé disabled:', btnEnvoyer.disabled);
        console.log('=== UPDATE RESUME FIN ===');
    }

    // Événements pour les boutons plus
    btnPlus.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const menuId = this.dataset.menuId;
            const input = document.querySelector(`input[data-menu-id="${menuId}"]`);
            
            if (input) {
                const currentValue = parseInt(input.value) || 0;
                input.value = currentValue + 1;
                console.log(`Bouton + ${index} cliqué pour menu ${menuId}, nouvelle valeur: ${input.value}`);
                updateResume();
            } else {
                console.error(`Input non trouvé pour menuId: ${menuId}`);
            }
        });
    });

    // Événements pour les boutons moins
    btnMinus.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const menuId = this.dataset.menuId;
            const input = document.querySelector(`input[data-menu-id="${menuId}"]`);
            
            if (input) {
                const currentValue = parseInt(input.value) || 0;
                if (currentValue > 0) {
                    input.value = currentValue - 1;
                    console.log(`Bouton - ${index} cliqué pour menu ${menuId}, nouvelle valeur: ${input.value}`);
                    updateResume();
                }
            } else {
                console.error(`Input non trouvé pour menuId: ${menuId}`);
            }
        });
    });

    // Événements pour les inputs de quantité
    quantiteInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            console.log(`Input ${index} changé pour menu ${this.dataset.menuId}, nouvelle valeur: ${this.value}`);
            updateResume();
        });
        
        input.addEventListener('input', function() {
            console.log(`Input ${index} modifié pour menu ${this.dataset.menuId}, nouvelle valeur: ${this.value}`);
            updateResume();
        });
    });

         // Validation du formulaire
     document.getElementById('commandeForm').addEventListener('submit', function(e) {
         const total = parseFloat(totalCommande.textContent.replace(' €', '').replace(',', '.')) || 0;
         
         if (total === 0) {
             e.preventDefault();
             alert('Veuillez sélectionner au moins un article.');
             return false;
         }
         
         const email = document.getElementById('email_client').value;
         const nom = document.getElementById('nom_client').value;
         const telephone = document.getElementById('telephone_client').value;
         
         if (!email || !nom || !telephone) {
             e.preventDefault();
             alert('Veuillez remplir toutes les informations client.');
             return false;
         }
         
         // Afficher le spinner et désactiver le bouton
         const submitButton = document.getElementById('btn-envoyer');
         const spinner = document.getElementById('spinner');
         const buttonText = submitButton.querySelector('span');
         
         submitButton.disabled = true;
         spinner.classList.remove('hidden');
         buttonText.textContent = 'Envoi en cours...';
         
         console.log('Formulaire soumis avec succès');
     });

    // Test manuel des boutons
    console.log('Test des boutons:');
    btnPlus.forEach((btn, index) => {
        console.log(`Bouton + ${index}:`, {
            menuId: btn.dataset.menuId,
            textContent: btn.textContent,
            classes: btn.className
        });
    });
    
    btnMinus.forEach((btn, index) => {
        console.log(`Bouton - ${index}:`, {
            menuId: btn.dataset.menuId,
            textContent: btn.textContent,
            classes: btn.className
        });
    });

    // Mise à jour initiale
    console.log('Mise à jour initiale...');
    updateResume();
    
    console.log('=== FIN DU SCRIPT ===');
});
</script>
@endsection
