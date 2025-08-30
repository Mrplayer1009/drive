@extends('layouts.franchise')

@section('title', 'Créer un événement - Driv\'n Cook')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Créer un événement</h1>
                    <p class="mt-2 text-gray-600">Organisez une dégustation ou un événement spécial</p>
                </div>
                <a href="{{ route('franchise.evenements.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('franchise.evenements.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div class="md:col-span-2">
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de l'événement *
                        </label>
                        <input type="text" 
                               name="titre" 
                               id="titre" 
                               value="{{ old('titre') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Ex: Dégustation de burgers gourmets"
                               required>
                        @error('titre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description *
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Décrivez votre événement, les produits qui seront présentés, etc."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date et heure -->
                    <div>
                        <label for="date_evenement" class="block text-sm font-medium text-gray-700 mb-2">
                            Date et heure *
                        </label>
                        <input type="datetime-local" 
                               name="date_evenement" 
                               id="date_evenement" 
                               value="{{ old('date_evenement') }}"
                               min="{{ now()->addDay()->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               required>
                        @error('date_evenement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lieu -->
                    <div>
                        <label for="lieu" class="block text-sm font-medium text-gray-700 mb-2">
                            Lieu
                        </label>
                        <input type="text" 
                               name="lieu" 
                               id="lieu" 
                               value="{{ old('lieu') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Ex: Place du marché, Food truck mobile">
                        @error('lieu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix en points -->
                    <div>
                        <label for="prix_points" class="block text-sm font-medium text-gray-700 mb-2">
                            Prix en points de fidélité *
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="prix_points" 
                                   id="prix_points" 
                                   value="{{ old('prix_points') }}"
                                   min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="50"
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 text-sm">
                                    <i class="fas fa-star text-yellow-500"></i>
                                </span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">100 points = 5€ de réduction</p>
                        @error('prix_points')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre max de participants -->
                    <div>
                        <label for="nombre_max_participants" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre maximum de participants *
                        </label>
                        <input type="number" 
                               name="nombre_max_participants" 
                               id="nombre_max_participants" 
                               value="{{ old('nombre_max_participants') }}"
                               min="1"
                               max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="20"
                               required>
                        @error('nombre_max_participants')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informations importantes</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Les clients paieront avec leurs points de fidélité</li>
                                    <li>Vous pouvez annuler l'événement à tout moment (remboursement automatique)</li>
                                    <li>Les clients peuvent se désinscrire jusqu'à 24h avant l'événement</li>
                                    <li>L'événement doit avoir lieu au minimum 1 jour après sa création</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('franchise.evenements.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Créer l'événement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
