@extends('layouts.admin')

@section('title', 'Traiter la Demande')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Traiter la Demande #{{ $demande->id }}</h1>
                <p class="text-black">Franchisé : {{ $demande->franchise->nom_complet }}</p>
            </div>
            <a href="{{ route('admin.demandes-camions.show', $demande) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Formulaire de traitement -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Traitement de la demande</h3>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.demandes-camions.update', $demande) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="statut" class="block text-sm font-medium text-black mb-2">Décision</label>
                    <select id="statut" name="statut" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="en_attente" {{ $demande->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="approuvee" {{ $demande->statut === 'approuvee' ? 'selected' : '' }}>Approuver</option>
                        <option value="refusee" {{ $demande->statut === 'refusee' ? 'selected' : '' }}>Refuser</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="camion_attribue" class="block text-sm font-medium text-black mb-2">Camion à attribuer (si approuvé)</label>
                    <select id="camion_attribue" name="camion_attribue" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Sélectionner un camion...</option>
                        @foreach($camionsDisponibles as $camion)
                        <option value="{{ $camion->id }}" {{ old('camion_attribue') == $camion->id ? 'selected' : '' }}>
                            {{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }} ({{ $camion->annee }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="commentaire_admin" class="block text-sm font-medium text-black mb-2">Commentaire admin</label>
                    <textarea id="commentaire_admin" name="commentaire_admin" rows="4" placeholder="Ajoutez un commentaire sur votre décision..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('commentaire_admin', $demande->commentaire_admin) }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.demandes-camions.show', $demande) }}" class="btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer la décision
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations de la demande -->
        <div class="space-y-6">
            <!-- Détails de la demande -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Détails de la demande</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Type :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->type_demande_label }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Camion souhaité :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->type_camion_souhaite_label }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Localisation :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->localisation_souhaitee }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Date souhaitée :</span>
                        <span class="text-sm font-medium text-black">{{ \Carbon\Carbon::parse($demande->date_debut_souhaitee)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Durée :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->duree_attribution_label }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Urgent :</span>
                        <span class="text-sm text-black">
                            @if($demande->urgent)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Oui
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                    Non
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="p-2 bg-gray-50 rounded">
                        <span class="text-sm font-medium text-black block mb-1">Motif :</span>
                        <p class="text-sm text-black">{{ $demande->motif }}</p>
                    </div>
                </div>
            </div>

            <!-- Informations du franchisé -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Franchisé</h3>
                
                <div class="space-y-2">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Nom :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->franchise->nom_complet }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Email :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->franchise->email }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Téléphone :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->franchise->telephone }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Ville :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->franchise->ville }}</span>
                    </div>
                </div>
            </div>

            <!-- Camions actuellement attribués -->
            @if($demande->franchise->camions->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Camions actuellement attribués</h3>
                
                <div class="space-y-2">
                    @foreach($demande->franchise->camions as $camion)
                    <div class="flex justify-between items-center p-2 bg-blue-50 rounded border border-blue-200">
                        <div>
                            <span class="text-sm font-medium text-black">{{ $camion->immatriculation }}</span>
                            <br>
                            <span class="text-xs text-gray-600">{{ $camion->marque }} {{ $camion->modele }}</span>
                        </div>
                        <span class="text-xs text-blue-600 font-medium">Attribué</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statutSelect = document.getElementById('statut');
    const camionSelect = document.getElementById('camion_attribue');
    
    // Si on approuve, rendre obligatoire la sélection d'un camion
    statutSelect.addEventListener('change', function() {
        if (this.value === 'approuvee') {
            camionSelect.required = true;
        } else {
            camionSelect.required = false;
        }
    });
});
</script>
@endsection 