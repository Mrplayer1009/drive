<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driv'n Cook - Gestion des Franchisés</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-orange-600">
                            <i class="fas fa-truck text-3xl mr-2"></i>
                            Driv'n Cook
                        </h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-black px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-user-plus mr-2"></i>
                        S'inscrire
                    </a>
                    <a href="{{ route('login') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Connexion
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-gradient-to-br from-orange-50 to-red-50 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-black sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Gestion des</span>
                            <span class="block text-orange-600 xl:inline">Franchisés</span>
                        </h1>
                        <p class="mt-3 text-base text-black sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Plateforme complète de gestion pour les franchisés Driv'n Cook. 
                            Suivez vos ventes, gérez vos commandes et optimisez votre activité.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start space-x-4">
                            <div class="rounded-md shadow">
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-black bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    S'inscrire
                                </a>
                            </div>
                            <div class="rounded-md shadow">
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-black bg-orange-600 hover:bg-orange-700 md:py-4 md:text-lg md:px-10">
                                    <i class="fas fa-rocket mr-2"></i>
                                    Se connecter
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-orange-600 font-semibold tracking-wide uppercase">Fonctionnalités</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-black sm:text-4xl">
                    Tout ce dont vous avez besoin
                </p>
            </div>

            <div class="mt-10">
                <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-black">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-black">Gestion des Franchisés</p>
                        <p class="mt-2 ml-16 text-base text-black">
                            Enregistrement, modification et suivi des franchisés avec leurs droits d'entrée et pourcentages de ventes.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-black">
                            <i class="fas fa-truck text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-black">Parc de Camions</p>
                        <p class="mt-2 ml-16 text-base text-black">
                            Attribution et suivi des camions, maintenance, localisation et disponibilités.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-black">
                            <i class="fas fa-warehouse text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-black">Entrepôts & Stock</p>
                        <p class="mt-2 ml-16 text-base text-black">
                            Gestion des entrepôts, commandes de stock avec contrôle automatique de la règle 80/20.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-black">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-black">Ventes & Rapports</p>
                        <p class="mt-2 ml-16 text-base text-black">
                            Suivi des ventes, calcul automatique des pourcentages et génération de PDF.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-black mb-4">
                    <i class="fas fa-truck text-orange-500 mr-2"></i>
                    Driv'n Cook
                </h3>
                <p class="text-gray-300">
                    Plateforme de gestion des franchisés - Tous droits réservés © 2024
                </p>
            </div>
        </div>
    </footer>
</body>
</html>