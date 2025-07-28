@extends('layouts.admin')

@section('title', 'Créer une Demande de Camion')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Créer une Demande de Camion</h1>
                <p class="text-black">Créer une demande de camion pour un franchisé</p>
            </div>
            <a href="{{ route('admin.demandes-camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Formulaire de création -->
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

            <form action="{{ route('admin.demandes-camions.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="franchise_id" class="block text-sm font-medium text-black mb-2">Franchisé</label>
                    <select id="franchise_id" name="franchise_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir un franchisé...</option>
                        @foreach($franchises as $f)
                        <option value="{{ $f->id }}" {{ (old('franchise_id', $franchise?->id) == $f->id) ? 'selected' : '' }}>
                            {{ $f->nom_complet }} - {{ $f->ville }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="type_demande" class="block text-sm font-medium text-black mb-2">Type de demande</label>
                    <select id="type_demande" name="type_demande" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir un type...</option>
                        <option value="nouveau" {{ old('type_demande') === 'nouveau' ? 'selected' : '' }}>Nouveau camion</option>
                        <option value="remplacement" {{ old('type_demande') === 'remplacement' ? 'selected' : '' }}>Remplacement</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="type_camion_souhaite" class="block text-sm font-medium text-black mb-2">Type de camion souhaité</label>
                    <select id="type_camion_souhaite" name="type_camion_souhaite" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir un type...</option>
                        <option value="petit" {{ old('type_camion_souhaite') === 'petit' ? 'selected' : '' }}>Petit camion (3-5 tonnes)</option>
                        <option value="moyen" {{ old('type_camion_souhaite') === 'moyen' ? 'selected' : '' }}>Camion moyen (5-10 tonnes)</option>
                        <option value="grand" {{ old('type_camion_souhaite') === 'grand' ? 'selected' : '' }}>Grand camion (10+ tonnes)</option>
                        <option value="refrigere" {{ old('type_camion_souhaite') === 'refrigere' ? 'selected' : '' }}>Camion réfrigéré</option>
                        <option value="plateau" {{ old('type_camion_souhaite') === 'plateau' ? 'selected' : '' }}>Camion plateau</option>
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
                        <option value="temporaire" {{ old('duree_attribution') === 'temporaire' ? 'selected' : '' }}>Attribution temporaire (1-7 jours)</option>
                        <option value="semaine" {{ old('duree_attribution') === 'semaine' ? 'selected' : '' }}>Une semaine</option>
                        <option value="mois" {{ old('duree_attribution') === 'mois' ? 'selected' : '' }}>Un mois</option>
                        <option value="permanent" {{ old('duree_attribution') === 'permanent' ? 'selected' : '' }}>Attribution permanente</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="motif" class="block text-sm font-medium text-black mb-2">Motif de la demande</label>
                    <textarea id="motif" name="motif" rows="4" placeholder="Expliquez le motif de cette demande..." required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('motif') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="urgent" class="flex items-center">
                        <input type="checkbox" id="urgent" name="urgent" value="1" {{ old('urgent') ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Demande urgente</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.demandes-camions.index') }}" class="btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Créer la demande
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations et aide -->
        <div class="space-y-6">
            <!-- Aide -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Aide</h3>
                
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="text-sm font-medium text-black mb-2">
                            <i class="fas fa-info-circle text-blue-600 mr-1"></i>
                            Processus de création
                        </h4>
                        <div class="text-xs text-black space-y-1">
                            <p>1. Sélectionnez le franchisé concerné</p>
                            <p>2. Choisissez le type de demande</p>
                            <p>3. Précisez le type de camion souhaité</p>
                            <p>4. Indiquez la localisation et les dates</p>
                            <p>5. Expliquez le motif de la demande</p>
                        </div>
                    </div>

                    @if($urgence === 'panne')
                    <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                        <h4 class="text-sm font-medium text-black mb-2">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-1"></i>
                            Demande urgente - Panne signalée
                        </h4>
                        <div class="text-xs text-black space-y-1">
                            <p>Cette demande est créée suite à une panne critique ou grave.</p>
                            <p>Le franchisé a besoin d'un camion de remplacement rapidement.</p>
                        </div>
                    </div>
                    @endif

                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <h4 class="text-sm font-medium text-black mb-2">
                            <i class="fas fa-lightbulb text-green-600 mr-1"></i>
                            Conseils
                        </h4>
                        <div class="text-xs text-black space-y-1">
                            <p>• Vérifiez la disponibilité des camions avant de créer la demande</p>
                            <p>• Les demandes urgentes sont traitées en priorité</p>
                            <p>• Précisez bien les besoins pour une attribution optimale</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Camions disponibles -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Camions disponibles</h3>
                
                @if($camionsDisponibles->count() > 0)
                <div class="space-y-2">
                    @foreach($camionsDisponibles as $camion)
                    <div class="flex justify-between items-center p-2 bg-green-50 rounded border border-green-200">
                        <div>
                            <span class="text-sm font-medium text-black">{{ $camion->immatriculation }}</span>
                            <br>
                            <span class="text-xs text-gray-600">{{ $camion->marque }} {{ $camion->modele }} ({{ $camion->annee }})</span>
                        </div>
                        <span class="text-xs text-green-600 font-medium">Disponible</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-3 bg-yellow-50 rounded border border-yellow-200">
                    <p class="text-sm text-yellow-800">Aucun camion disponible actuellement</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeDemandeSelect = document.getElementById('type_demande');
    const urgentCheckbox = document.getElementById('urgent');
    
    // Si c'est une demande urgente (panne), cocher automatiquement
    @if($urgence === 'panne')
    urgentCheckbox.checked = true;
    @endif
});
</script>
@endsection 
