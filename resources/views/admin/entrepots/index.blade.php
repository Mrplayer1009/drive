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
            <a href="{{ route('admin.entrepots.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouvel entrepôt
            </a>
        </div>
    </div>

    <!-- Liste des entrepôts -->
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
                    <p class="text-sm text-black">{{ $entrepot->telephone }}</p>
                </div>

                <div class="flex items-center">
                    <i class="fas fa-boxes text-orange-600 mr-3"></i>
                    <p class="text-sm text-black">Capacité : {{ $entrepot->capacite }} m³</p>
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
                <a href="{{ route('admin.entrepots.edit', $entrepot) }}" class="text-orange-600 hover:text-orange-700">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('admin.entrepots.show', $entrepot) }}" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-eye"></i>
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
                        <i class="fas fa-warehouse text-white"></i>
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
                        <i class="fas fa-utensils text-white"></i>
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
                        <i class="fas fa-shopping-cart text-white"></i>
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
                        <i class="fas fa-cube text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Capacité totale</p>
                    <p class="text-2xl font-semibold text-black">{{ $entrepots->sum('capacite') }} m³</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 