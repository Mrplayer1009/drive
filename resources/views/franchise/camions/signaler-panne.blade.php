@extends('layouts.franchise')

@section('title', 'Signaler une Panne')

@section('content')
@if(!isset($camion) || !$camion)
    <div class="p-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <p>Camion non trouvé ou accès non autorisé.</p>
            <a href="{{ route('franchise.camions.index') }}" class="text-red-600 hover:text-red-700 underline">Retour à la liste des camions</a>
        </div>
    </div>
@else
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Signaler une Panne</h1>
                <p class="text-black">Camion : {{ $camion->immatriculation }} ({{ $camion->marque }} {{ $camion->modele }})</p>
            </div>
            <a href="{{ route('franchise.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Formulaire de signalement -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Formulaire de signalement</h3>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('franchise.camions.store-panne', $camion) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="type_panne" class="block text-sm font-medium text-black mb-2">Type de panne</label>
                    <select id="type_panne" name="type_panne" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir un type...</option>
                        <option value="mecanique" {{ old('type_panne') === 'mecanique' ? 'selected' : '' }}>Mécanique</option>
                        <option value="electrique" {{ old('type_panne') === 'electrique' ? 'selected' : '' }}>Électrique</option>
                        <option value="pneumatique" {{ old('type_panne') === 'pneumatique' ? 'selected' : '' }}>Pneumatique</option>
                        <option value="autre" {{ old('type_panne') === 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="gravite" class="block text-sm font-medium text-black mb-2">Gravité de la panne</label>
                    <select id="gravite" name="gravite" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir la gravité...</option>
                        <option value="legere" {{ old('gravite') === 'legere' ? 'selected' : '' }}>Légère (peut continuer à fonctionner)</option>
                        <option value="moderee" {{ old('gravite') === 'moderee' ? 'selected' : '' }}>Modérée (fonctionnement limité)</option>
                        <option value="grave" {{ old('gravite') === 'grave' ? 'selected' : '' }}>Grave (arrêt recommandé)</option>
                        <option value="critique" {{ old('gravite') === 'critique' ? 'selected' : '' }}>Critique (arrêt immédiat nécessaire)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description_panne" class="block text-sm font-medium text-black mb-2">Description de la panne</label>
                    <textarea id="description_panne" name="description_panne" rows="4" placeholder="Décrivez en détail la panne observée..." required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('description_panne') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="symptomes" class="block text-sm font-medium text-black mb-2">Symptômes observés</label>
                    <textarea id="symptomes" name="symptomes" rows="3" placeholder="Bruits anormaux, comportements étranges, messages d'erreur..." required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('symptomes') }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('franchise.camions.index') }}" class="btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Signaler la panne
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations et aide -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations et aide</h3>
            
            <div class="p-4 bg-red-50 rounded-lg border border-red-200 mb-4">
                <h4 class="text-sm font-medium text-black mb-2">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-1"></i>
                    Urgences
                </h4>
                <div class="text-xs text-black space-y-1">
                    <p>• Pannes critiques : Contactez immédiatement l'administrateur</p>
                    <p>• Pannes graves : Arrêtez le camion et attendez les instructions</p>
                    <p>• Pannes légères : Continuez avec précaution</p>
                </div>
            </div>

            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 mb-4">
                <h4 class="text-sm font-medium text-black mb-2">
                    <i class="fas fa-info-circle text-blue-600 mr-1"></i>
                    Processus de signalement
                </h4>
                <div class="text-xs text-black space-y-1">
                    <p>1. Remplissez le formulaire avec précision</p>
                    <p>2. Votre signalement sera transmis à l'administrateur</p>
                    <p>3. Vous recevrez une notification de la décision</p>
                    <p>4. Un camion de remplacement peut vous être attribué</p>
                </div>
            </div>

            <!-- Informations du camion -->
            <div class="mt-6">
                <h4 class="text-sm font-medium text-black mb-3">Informations du camion</h4>
                <div class="space-y-2">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Immatriculation :</span>
                        <span class="text-sm font-medium text-black">{{ $camion->immatriculation }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Marque/Modèle :</span>
                        <span class="text-sm font-medium text-black">{{ $camion->marque }} {{ $camion->modele }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Année :</span>
                        <span class="text-sm font-medium text-black">{{ $camion->annee }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Dernière maintenance :</span>
                        <span class="text-sm font-medium text-black">
                            {{ $camion->derniere_maintenance ? \Carbon\Carbon::parse($camion->derniere_maintenance)->format('d/m/Y') : 'Aucune' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection 
