<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Driv'n Cook - Espace Client</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header style="background-color: #f97316; color: white; padding: 1rem 0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: bold; color: white;">Driv'n Cook</h1>
                    <p style="color: #fed7aa; margin: 0;">Votre food truck préféré</p>
                </div>
                <nav style="display: flex; align-items: center; gap: 1.5rem;">
                    <a href="{{ route('client.index') }}" style="color: white; text-decoration: none; font-weight: 500; transition: color 0.2s;">
                        <i class="fas fa-home" style="margin-right: 0.5rem;"></i>
                        Accueil
                    </a>
                    <a href="{{ route('client.evenements.index') }}" style="color: white; text-decoration: none; font-weight: 500; transition: color 0.2s;">
                        <i class="fas fa-calendar" style="margin-right: 0.5rem;"></i>
                        Événements
                    </a>
                    <a href="{{ route('client.panier') }}" style="color: white; text-decoration: none; font-weight: 500; transition: color 0.2s; position: relative;">
                        <i class="fas fa-shopping-cart" style="margin-right: 0.5rem;"></i>
                        Panier
                        <span id="panier-compteur" style="position: absolute; top: -8px; right: -8px; background-color: #ef4444; color: white; font-size: 0.75rem; border-radius: 50%; height: 20px; width: 20px; display: none; align-items: center; justify-content: center;">0</span>
                    </a>
                    <a href="{{ route('client.profile') }}" style="color: white; text-decoration: none; font-weight: 500; transition: color 0.2s;">
                        <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                        Profil
                    </a>
                    <a href="{{ route('client.commandes') }}" style="color: white; text-decoration: none; font-weight: 500; transition: color 0.2s;">
                        <i class="fas fa-list" style="margin-right: 0.5rem;"></i>
                        Mes commandes
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="color: white; text-decoration: none; font-weight: 500; transition: color 0.2s; background: none; border: none; cursor: pointer; font-size: inherit;">
                            <i class="fas fa-sign-out-alt" style="margin-right: 0.5rem;"></i>
                            Déconnexion
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </header>
    
    @yield('content')
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Driv'n Cook</h3>
                    <p class="text-gray-300">Les meilleurs burgers de Paris, livrés directement chez vous !</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <p class="text-gray-300">
                        <i class="fas fa-phone mr-2"></i>01 23 45 67 89<br>
                        <i class="fas fa-envelope mr-2"></i>contact@drivncook.com
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Suivez-nous</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; 2025 Driv'n Cook. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
    // Fonction pour mettre à jour le compteur du panier
    function mettreAJourCompteurPanier(panier) {
        const totalItems = panier.reduce((total, item) => total + item.quantite, 0);
        const compteurElement = document.getElementById('panier-compteur');
        
        if (compteurElement) {
            compteurElement.textContent = totalItems;
            compteurElement.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }

    // Initialisation du compteur au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        const panier = JSON.parse(localStorage.getItem('panier') || '[]');
        mettreAJourCompteurPanier(panier);
    });
    </script>
</body>
</html>
