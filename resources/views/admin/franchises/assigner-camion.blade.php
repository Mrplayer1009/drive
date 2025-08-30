@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Assigner un camion</h1>
                    <p class="text-gray-600">Assigner un camion au franchisé {{ $franchise->nom_complet }}</p>
                </div>
                <a href="{{ route('admin.franchises.show', $franchise) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations du franchisé -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations du franchisé</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-user text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Nom complet</p>
                            <p class="text-sm text-gray-600">{{ $franchise->nom_complet }}</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email</p>
                            <p class="text-sm text-gray-600">{{ $franchise->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Téléphone</p>
                            <p class="text-sm text-gray-600">{{ $franchise->telephone }}</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-truck text-gray-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Camion actuel</p>
                            @if($camionActuel)
                                <p class="text-sm text-green-600">{{ $camionActuel->immatriculation }} - {{ $camionActuel->modele }}</p>
                            @else
                                <p class="text-sm text-red-600">Aucun camion assigné</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-gray-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Adresse</p>
                            <p class="text-sm text-gray-600">{{ $franchise->adresse }}, {{ $franchise->ville }} {{ $franchise->code_postal }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignation de camion -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Assigner un camion</h2>

                @if($camionActuel)
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Attention</h3>
                            <p class="text-sm text-yellow-700 mt-1">
                                Ce franchisé a déjà un camion assigné. L'assignation d'un nouveau camion remplacera l'ancien.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                @if($camionsDisponibles->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-truck text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun camion disponible</h3>
                    <p class="text-gray-600">Tous les camions sont actuellement assignés ou en maintenance.</p>
                </div>
                @else
                <form action="{{ route('admin.franchises.assigner-camion.store', $franchise) }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="camion_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Sélectionner un camion
                        </label>
                        <select name="camion_id" id="camion_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Choisir un camion...</option>
                            @foreach($camionsDisponibles as $camion)
                            <option value="{{ $camion->id }}">
                                {{ $camion->immatriculation }} - {{ $camion->modele }} ({{ $camion->annee }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            <i class="fas fa-truck mr-2"></i>
                            Assigner le camion
                        </button>
                    </div>
                </form>
                @endif

                @if($camionActuel)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Retirer le camion actuel</h3>
                    <form action="{{ route('admin.franchises.retirer-camion', $franchise) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer le camion de ce franchisé ?')">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            <i class="fas fa-times mr-2"></i>
                            Retirer le camion
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Liste des camions disponibles -->
        @if($camionsDisponibles->isNotEmpty())
        <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Camions disponibles</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($camionsDisponibles as $camion)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-medium text-gray-900">{{ $camion->immatriculation }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Disponible
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">{{ $camion->modele }} ({{ $camion->annee }})</p>
                    <p class="text-sm text-gray-500">{{ $camion->capacite }} tonnes</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

