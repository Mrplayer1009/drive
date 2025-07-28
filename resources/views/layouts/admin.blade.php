<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Driv\'n Cook')</title>
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
                            Driv'n Cook Admin
                        </h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-black">Administrateur</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300">
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
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }}">
                    <i class="fas fa-tachometer-alt mr-4 text-lg"></i>
                    Tableau de bord
                </a>
                
                <a href="{{ route('admin.franchises.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.franchises.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }}">
                    <i class="fas fa-users mr-4 text-lg"></i>
                    Franchisés
                </a>
                
                <a href="{{ route('admin.entrepots.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.entrepots.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }}">
                    <i class="fas fa-warehouse mr-4 text-lg"></i>
                    Entrepôts
                </a>
                
                <a href="{{ route('admin.camions.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-black rounded-md hover:bg-orange-100 hover:text-orange-700">
                    <i class="fas fa-truck mr-3"></i>
                    Camions
                </a>
                
                <a href="{{ route('admin.notifications-pannes.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-black rounded-md hover:bg-orange-100 hover:text-orange-700">
                    <i class="fas fa-exclamation-triangle mr-3"></i>
                    Pannes signalées
                </a>
                    
                <a href="{{ route('admin.demandes-camions.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-black rounded-md hover:bg-orange-100 hover:text-orange-700">
                    <i class="fas fa-truck mr-3"></i>
                    Demandes de camions
                </a>
                
                <a href="{{ route('admin.ventes.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.ventes.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }}">
                    <i class="fas fa-chart-line mr-4 text-lg"></i>
                    Ventes
                </a>
                
                <a href="{{ route('admin.commandes.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.commandes.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }}">
                    <i class="fas fa-shopping-cart mr-4 text-lg"></i>
                    Commandes
                </a>
                
                <a href="{{ route('admin.produits.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.produits.*') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }}">
                    <i class="fas fa-box mr-4 text-lg"></i>
                    Produits
                </a>
                
                <a href="{{ route('admin.statistiques') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.statistiques') ? 'bg-orange-100 text-orange-900' : 'text-black hover:bg-gray-50 hover:text-black' }}">
                    <i class="fas fa-chart-bar mr-4 text-lg"></i>
                    Statistiques
                </a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 bg-gray-100">
            <main class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html> 