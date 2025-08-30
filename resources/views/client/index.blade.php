@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50">



    <!-- Hero Section -->
     <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 3rem 0;">
        <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem; text-align: center;">
            <h2 style="font-size: 2.25rem; font-weight: bold; margin-bottom: 1rem; color: white;">Nos Délicieux Menus</h2>
            <p style="font-size: 1.25rem; color: white; margin-bottom: 2rem;">Découvrez notre sélection de burgers, boissons et desserts</p>
            
            <!-- Bouton pour choisir un food truck -->
            <a href="{{ route('client.select-food-truck-page') }}" 
               style="background: white; color: #f97316; padding: 0.75rem 2rem; border-radius: 0.5rem; text-decoration: none; font-weight: bold; transition: all 0.3s; display: inline-block; margin-bottom: 1rem;">
                <i class="fas fa-map-marker-alt mr-2"></i>
                Choisir un Food Truck
            </a>
            
            <p style="font-size: 1rem; color: white; opacity: 0.9;">Sélectionnez le food truck le plus proche de chez vous</p>
        </div>
    </div>

    <!-- Menu Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @foreach($categories as $categorie => $menus)
        <div class="mb-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                @switch($categorie)
                    @case('burger')
                        <i class="fas fa-hamburger text-orange-600 mr-3"></i>
                        @break
                    @case('boisson')
                        <i class="fas fa-glass-martini text-blue-600 mr-3"></i>
                        @break
                    @case('dessert')
                        <i class="fas fa-ice-cream text-pink-600 mr-3"></i>
                        @break
                    @case('accompagnement')
                        <i class="fas fa-french-fries text-yellow-600 mr-3"></i>
                        @break
                    @default
                        <i class="fas fa-utensils text-gray-600 mr-3"></i>
                @endswitch
                {{ ucfirst($categorie) }}s
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($menus as $menu)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->nom }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-orange-200 to-red-200 flex items-center justify-center">
                            <i class="fas fa-utensils text-4xl text-orange-600"></i>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-xl font-bold text-gray-800">{{ $menu->nom }}</h4>
                            @if($menu->special)
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Spécial</span>
                            @endif
                        </div>
                        
                        <p class="text-gray-600 mb-4">{{ $menu->description }}</p>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-orange-600">{{ $menu->prix_formate }}</span>
                            <button onclick="ajouterAuPanier({{ $menu->id }}, '{{ addslashes($menu->nom) }}', {{ $menu->prix }}, this)" 
                                    style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s ease;">
                                <i class="fas fa-plus mr-2"></i>
                                Ajouter
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Popup d'ajout au panier -->
<div id="popup-ajout" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="popup-content">
        <div class="text-center">
            <!-- Icône de succès -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check text-3xl text-green-600"></i>
            </div>
            
            <!-- Titre -->
            <h3 class="text-xl font-bold text-gray-900 mb-2">Article ajouté !</h3>
            
            <!-- Message -->
            <p class="text-gray-600 mb-6" id="popup-message">L'article a été ajouté à votre panier avec succès.</p>
            
            <!-- Boutons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="voirPanier()" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center" style="background-color: #f97316;">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Voir mon panier
                </button>
                <button onclick="fermerPopup()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Continuer mes achats
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour ajouter au panier avec popup
function ajouterAuPanier(menuId, nom, prix, buttonElement) {
    console.log('Tentative d\'ajout:', { menuId, nom, prix });
    
    // Validation des paramètres
    if (!menuId || !nom || !prix) {
        console.error('Paramètres manquants:', { menuId, nom, prix });
        alert('Erreur: données manquantes');
        return;
    }
    
    try {
        // Effet de feedback visuel sur le bouton
        if (buttonElement) {
            buttonElement.style.transform = 'scale(0.95)';
            buttonElement.style.background = 'linear-gradient(135deg, #059669 0%, #047857 100%)';
            buttonElement.innerHTML = '<i class="fas fa-check mr-2"></i>Ajouté !';
            
            setTimeout(() => {
                buttonElement.style.transform = 'scale(1)';
                buttonElement.style.background = 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)';
                buttonElement.innerHTML = '<i class="fas fa-plus mr-2"></i>Ajouter';
            }, 1000);
        }
        
        // Récupérer le panier
        let panier = JSON.parse(localStorage.getItem('panier') || '[]');
        console.log('Panier actuel:', panier);
        
        // Chercher si l'article existe déjà
        let articleExistant = panier.find(item => item.menu_id == menuId);
        
        if (articleExistant) {
            // Incrémenter la quantité
            articleExistant.quantite += 1;
            console.log('Quantité incrémentée pour:', nom);
        } else {
            // Ajouter nouvel article
            panier.push({
                menu_id: parseInt(menuId),
                nom: nom,
                prix: parseFloat(prix),
                quantite: 1
            });
            console.log('Nouvel article ajouté:', nom);
        }
        
        // Sauvegarder
        localStorage.setItem('panier', JSON.stringify(panier));
        console.log('Panier sauvegardé:', panier);
        
        // Mettre à jour le compteur
        mettreAJourCompteurPanier(panier);
        
        // Afficher le popup
        afficherPopup(nom);
        
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'ajout au panier');
    }
}

// Fonction pour afficher le popup
function afficherPopup(nom) {
    const popup = document.getElementById('popup-ajout');
    const popupContent = document.getElementById('popup-content');
    const message = document.getElementById('popup-message');
    
    // Mettre à jour le message
    message.textContent = `"${nom}" a été ajouté à votre panier avec succès.`;
    
    // Afficher le popup
    popup.style.display = 'flex';
    
    // Animation d'entrée
    setTimeout(() => {
        popupContent.classList.remove('scale-95', 'opacity-0');
        popupContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

// Fonction pour fermer le popup
function fermerPopup() {
    const popup = document.getElementById('popup-ajout');
    const popupContent = document.getElementById('popup-content');
    
    // Animation de sortie
    popupContent.classList.remove('scale-100', 'opacity-100');
    popupContent.classList.add('scale-95', 'opacity-0');
    
    // Masquer le popup après l'animation
    setTimeout(() => {
        popup.style.display = 'none';
    }, 300);
}

// Fonction pour aller au panier
function voirPanier() {
    window.location.href = '{{ route("client.panier") }}';
}

function mettreAJourCompteurPanier(panier) {
    const totalItems = panier.reduce((total, item) => total + item.quantite, 0);
    const compteurElement = document.getElementById('panier-compteur');
    
    if (compteurElement) {
        compteurElement.textContent = totalItems;
        compteurElement.style.display = totalItems > 0 ? 'block' : 'none';
    }
}

// Fermer le popup en cliquant à l'extérieur
document.addEventListener('click', function(event) {
    const popup = document.getElementById('popup-ajout');
    const popupContent = document.getElementById('popup-content');
    
    if (event.target === popup) {
        fermerPopup();
    }
});

// Fermer le popup avec la touche Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const popup = document.getElementById('popup-ajout');
        if (popup.style.display === 'flex') {
            fermerPopup();
        }
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    const panier = JSON.parse(localStorage.getItem('panier') || '[]');
    mettreAJourCompteurPanier(panier);
    console.log('Page chargée, panier initial:', panier);
});
</script>
@endsection
