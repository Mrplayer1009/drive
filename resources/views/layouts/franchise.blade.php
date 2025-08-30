<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driv'n Cook - Franchisé</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
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
                    <span class="text-black">{{ Auth::user()->nom_complet }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-black px-4 py-2 rounded-lg transition duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <nav class="mt-5 px-2">
                <a href="{{ route('franchise.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('franchise.dashboard') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }}">
                    <i class="fas fa-tachometer-alt mr-4 text-lg"></i>
                    Tableau de bord
                </a>
                
                <a href="{{ route('franchise.profile') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('franchise.dashboard') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }}">
                    <i class="fas fa-user mr-4 text-lg"></i>
                    Mon profil
                </a>
                
                <a href="{{ Auth::user()->statut === 'inactif' ? '#' : route('franchise.camions.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('franchise.camions.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }} {{ Auth::user()->statut === 'inactif' ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-truck mr-4 text-lg"></i>
                    Mes camions
                </a>
                
                <a href="{{ Auth::user()->statut === 'inactif' ? '#' : route('franchise.commandes.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('franchise.commandes.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }} {{ Auth::user()->statut === 'inactif' ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-shopping-cart mr-4 text-lg"></i>
                    Mes commandes
                </a>
                
                <a href="{{ Auth::user()->statut === 'inactif' ? '#' : route('franchise.commandes-clients.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('franchise.commandes-clients.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }} {{ Auth::user()->statut === 'inactif' ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-users mr-4 text-lg"></i>
                    Commandes clients
                </a>
                
                <a href="{{ Auth::user()->statut === 'inactif' ? '#' : route('franchise.commande-sur-place') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('franchise.commande-sur-place.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }} {{ Auth::user()->statut === 'inactif' ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-utensils mr-4 text-lg"></i>
                    Commande sur place
                </a>
                
                <a href="{{ Auth::user()->statut === 'inactif' ? '#' : route('franchise.ventes.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('franchise.ventes.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }} {{ Auth::user()->statut === 'inactif' ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-chart-line mr-4 text-lg"></i>
                    Mes ventes
                </a>
                
                <a href="{{ Auth::user()->statut === 'inactif' ? '#' : route('franchise.evenements.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('franchise.evenements.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }} {{ Auth::user()->statut === 'inactif' ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-calendar mr-4 text-lg"></i>
                    Événements
                </a>
            </nav>
        </div>

        <div class="flex-1 bg-gray-100">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded m-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded m-4">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html> 
