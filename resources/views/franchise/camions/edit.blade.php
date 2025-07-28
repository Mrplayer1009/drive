@extends('layouts.franchise')

@section('title', 'Modifier la Demande de Camion')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Modifier la Demande</h1>
                <p class="text-black">Demande #{{ $demande->id ?? 'N/A' }}</p>
            </div>
            <a href="{{ route('franchise.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Formulaire de modification -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Modifier la demande</h3>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('franchise.camions.update', $demande ?? 1) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="type_camion" class="block text-sm font-medium text-black mb-2">Type de camion souhaité</label>
                    <select id="type_camion" name="type_camion" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir un type...</option>
                        <option value="petit" {{ old('type_camion', $demande->type_camion ?? '') === 'petit' ? 'selected' : '' }}>Petit camion (3-5 tonnes)</option>
                        <option value="moyen" {{ old('type_camion', $demande->type_camion ?? '') === 'moyen' ? 'selected' : '' }}>Camion moyen (5-10 tonnes)</option>
                        <option value="grand" {{ old('type_camion', $demande->type_camion ?? '') === 'grand' ? 'selected' : '' }}>Grand camion (10+ tonnes)</option>
                        <option value="refrigere" {{ old('type_camion', $demande->type_camion ?? '') === 'refrigere' ? 'selected' : '' }}>Camion réfrigéré</option>
                        <option value="plateau" {{ old('type_camion', $demande->type_camion ?? '') === 'plateau' ? 'selected' : '' }}>Camion plateau</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="localisation_souhaitee" class="block text-sm font-medium text-black mb-2">Localisation souhaitée</label>
                    <input type="text" id="localisation_souhaitee" name="localisation_souhaitee" value="{{ old('localisation_souhaitee', $demande->localisation_souhaitee ?? '') }}" placeholder="Ville ou zone géographique" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="date_debut_souhaitee" class="block text-sm font-medium text-black mb-2">Date de début souhaitée</label>
                    <input type="date" id="date_debut_souhaitee" name="date_debut_souhaitee" value="{{ old('date_debut_souhaitee', $demande->date_debut_souhaitee ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="duree_attribution" class="block text-sm font-medium text-black mb-2">Durée d'attribution souhaitée</label>
                    <select id="duree_attribution" name="duree_attribution" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir une durée...</option>
                        <option value="temporaire" {{ old('duree_attribution', $demande->duree_attribution ?? '') === 'temporaire' ? 'selected' : '' }}>Attribution temporaire (1-7 jours)</option>
                        <option value="semaine" {{ old('duree_attribution', $demande->duree_attribution ?? '') === 'semaine' ? 'selected' : '' }}>Une semaine</option>
                        <option value="mois" {{ old('duree_attribution', $demande->duree_attribution ?? '') === 'mois' ? 'selected' : '' }}>Un mois</option>
                        <option value="permanent" {{ old('duree_attribution', $demande->duree_attribution ?? '') === 'permanent' ? 'selected' : '' }}>Attribution permanente</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="motif" class="block text-sm font-medium text-black mb-2">Motif de la demande</label>
                    <textarea id="motif" name="motif" rows="4" placeholder="Expliquez pourquoi vous avez besoin d'un camion..." required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('motif', $demande->motif ?? '') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="urgent" class="flex items-center">
                        <input type="checkbox" id="urgent" name="urgent" value="1" {{ old('urgent', $demande->urgent ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Demande urgente</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('franchise.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-300">
                        Annuler
                    </a>
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-save mr-2"></i>
                        Sauvegarder les modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Statut de la demande -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Statut de la demande</h3>
            
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-black mb-2">Statut actuel</h4>
                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                        {{ ($demande->statut ?? 'en_attente') === 'approuvee' ? 'bg-green-100 text-green-800' : 
                           (($demande->statut ?? 'en_attente') === 'refusee' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800') }}">
                        {{ ucfirst($demande->statut ?? 'en_attente') }}
                    </span>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-black mb-2">Date de création</h4>
                    <p class="text-sm text-black">{{ \Carbon\Carbon::parse($demande->created_at ?? now())->format('d/m/Y H:i') }}</p>
                </div>

                @if($demande->date_reponse ?? false)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-black mb-2">Date de réponse</h4>
                    <p class="text-sm text-black">{{ \Carbon\Carbon::parse($demande->date_reponse)->format('d/m/Y H:i') }}</p>
                </div>
                @endif

                @if($demande->commentaire_admin ?? false)
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="text-sm font-medium text-black mb-2">Commentaire de l'administrateur</h4>
                    <p class="text-sm text-black">{{ $demande->commentaire_admin }}</p>
                </div>
                @endif
            </div>

            <div class="mt-6 p-4 bg-orange-50 rounded-lg border border-orange-200">
                <h4 class="text-sm font-medium text-black mb-2">
                    <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                    Informations importantes
                </h4>
                <div class="text-xs text-black space-y-1">
                    <p>• Les demandes en attente peuvent être modifiées</p>
                    <p>• Les demandes approuvées ou refusées ne peuvent plus être modifiées</p>
                    <p>• Contactez l'administrateur pour toute question</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 