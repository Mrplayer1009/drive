@extends('layouts.franchise')

@section('title', 'Demander un Camion')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Demander un Camion</h1>
                <p class="text-black">Formulaire de demande d'attribution de camion</p>
            </div>
            <a href="{{ route('franchise.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Formulaire de demande -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Formulaire de demande</h3>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('franchise.camions.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="type_camion" class="block text-sm font-medium text-black mb-2">Type de camion souhaité</label>
                    <select id="type_camion" name="type_camion" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir un type...</option>
                        <option value="petit">Petit camion (3-5 tonnes)</option>
                        <option value="moyen">Camion moyen (5-10 tonnes)</option>
                        <option value="grand">Grand camion (10+ tonnes)</option>
                        <option value="refrigere">Camion réfrigéré</option>
                        <option value="plateau">Camion plateau</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="localisation_souhaitee" class="block text-sm font-medium text-black mb-2">Localisation souhaitée</label>
                    <input type="text" id="localisation_souhaitee" name="localisation_souhaitee" value="{{ old('localisation_souhaitee') }}" placeholder="Ville ou zone géographique" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="date_debut_souhaitee" class="block text-sm font-medium text-black mb-2">Date de début souhaitée</label>
                    <input type="date" id="date_debut_souhaitee" name="date_debut_souhaitee" value="{{ old('date_debut_souhaitee') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="duree_attribution" class="block text-sm font-medium text-black mb-2">Durée d'attribution souhaitée</label>
                    <select id="duree_attribution" name="duree_attribution" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir une durée...</option>
                        <option value="temporaire">Attribution temporaire (1-7 jours)</option>
                        <option value="semaine">Une semaine</option>
                        <option value="mois">Un mois</option>
                        <option value="permanent">Attribution permanente</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="motif" class="block text-sm font-medium text-black mb-2">Motif de la demande</label>
                    <textarea id="motif" name="motif" rows="4" placeholder="Expliquez pourquoi vous avez besoin d'un camion..." required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('motif') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="urgent" class="flex items-center">
                        <input type="checkbox" id="urgent" name="urgent" value="1" {{ old('urgent') ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Demande urgente</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('franchise.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-6 py-2 rounded-lg transition duration-300">
                        Annuler
                    </a>
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-6 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Envoyer la demande
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations et aide -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations et aide</h3>
            
            <div class="p-4 bg-orange-50 rounded-lg border border-orange-200 mb-4">
                <h4 class="text-sm font-medium text-black mb-2">
                    <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                    Processus de demande
                </h4>
                <div class="text-xs text-black space-y-1">
                    <p>1. Remplissez le formulaire avec vos besoins</p>
                    <p>2. Votre demande sera transmise à l'administrateur</p>
                    <p>3. Vous recevrez une notification de la décision</p>
                    <p>4. En cas d'approbation, le camion vous sera attribué</p>
                </div>
            </div>

            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 mb-4">
                <h4 class="text-sm font-medium text-black mb-2">
                    <i class="fas fa-lightbulb text-blue-600 mr-1"></i>
                    Conseils
                </h4>
                <div class="text-xs text-black space-y-1">
                    <p>• Précisez bien vos besoins pour une attribution optimale</p>
                    <p>• Les demandes urgentes sont traitées en priorité</p>
                    <p>• Un camion peut être partagé entre plusieurs franchisés</p>
                    <p>• Contactez l'admin en cas de problème urgent</p>
                </div>
            </div>

            <!-- Statistiques actuelles -->
            <div class="mt-6">
                <h4 class="text-sm font-medium text-black mb-3">Vos camions actuels</h4>
                <div class="space-y-2">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Camions attribués :</span>
                        <span class="text-sm font-medium text-black">{{ Auth::user()->camions->count() }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Demandes en cours :</span>
                        <span class="text-sm font-medium text-black">{{ $demandes_en_cours ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Demandes approuvées :</span>
                        <span class="text-sm font-medium text-black">{{ $demandes_approuvees ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
