@extends('layouts.admin')

@section('title', 'Gestion des Entrepôts')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Gestion des Entrepôts</h1>
                <p class="text-black">Gérez les entrepôts Driv'n Cook</p>
            </div>
            <a href="{{ route('admin.entrepots.create') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouvel entrepôt
            </a>
        </div>
    </div>

    <!-- Recherche -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('admin.entrepots.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-black mb-2">Rechercher un entrepôt</label>
                <div class="relative">
                    <input type="text" id="search" name="search" 
                           value="{{ request('search') }}"
                           placeholder="Nom de l'entrepôt..." 
                           class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-search mr-2"></i>
                    Rechercher
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.entrepots.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-times mr-2"></i>
                        Effacer
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Liste des entrepôts -->
    @if(request('search'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-search mr-2"></i>
            Résultats de recherche pour "{{ request('search') }}" : {{ $entrepots->count() }} entrepôt(s) trouvé(s)
        </div>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($entrepots as $entrepot)
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-black">{{ $entrepot->nom }}</h3>
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                    Actif
                </span>
            </div>

            <div class="space-y-3">
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-orange-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-black">{{ $entrepot->adresse_complete }}</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <i class="fas fa-phone text-orange-600 mr-3"></i>
                    <p class="text-sm text-black">{{ $entrepot->telephone ?? 'Non renseigné' }}</p>
                </div>

                <div class="flex items-center">
                    <i class="fas fa-boxes text-orange-600 mr-3"></i>
                    <p class="text-sm text-black">Capacité : {{ number_format($entrepot->capacite_stockage, 0, ',', ' ') }}</p>
                </div>

                @if($entrepot->cuisine)
                <div class="flex items-center">
                    <i class="fas fa-utensils text-orange-600 mr-3"></i>
                    <p class="text-sm text-black">Cuisine équipée</p>
                </div>
                @endif

                <div class="flex items-center">
                    <i class="fas fa-shopping-cart text-orange-600 mr-3"></i>
                    <p class="text-sm text-black">{{ $entrepot->commandes->count() }} commandes traitées</p>
                </div>
            </div>

            <div class="mt-4 flex space-x-2">
                <a href="{{ route('admin.entrepots.edit', $entrepot) }}" class="text-orange-600 hover:text-orange-700" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('admin.entrepots.show', $entrepot) }}" class="text-blue-600 hover:text-blue-700" title="Voir">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.entrepots.stocks.index', $entrepot) }}" class="text-green-600 hover:text-green-700" title="Gérer les stocks">
                    <i class="fas fa-boxes"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-warehouse text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-black mb-2">Aucun entrepôt</h3>
                <p class="text-black">Aucun entrepôt n'a été créé pour le moment.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-warehouse text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Total entrepôts</p>
                    <p class="text-2xl font-semibold text-black">{{ $entrepots->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-utensils text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Avec cuisine</p>
                    <p class="text-2xl font-semibold text-black">{{ $entrepots->where('cuisine', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Commandes totales</p>
                    <p class="text-2xl font-semibold text-black">{{ $entrepots->sum(function($e) { return $e->commandes->count(); }) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-cube text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Capacité totale</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($entrepots->sum('capacite_stockage'), 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
