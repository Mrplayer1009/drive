@extends('layouts.admin')

@section('title', 'Détails de la Demande')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Demande de Camion #{{ $demande->id }}</h1>
                <p class="text-black">Franchisé : {{ $demande->franchise->nom_complet }}</p>
            </div>
            <a href="{{ route('admin.demandes-camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations de la demande -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations de la demande</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Statut :</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $demande->statut === 'approuvee' ? 'bg-green-100 text-green-800' : 
                           ($demande->statut === 'refusee' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ $demande->statut_label }}
                    </span>
                </div>

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Type de demande :</span>
                    <span class="text-sm text-black">{{ $demande->type_demande_label }}</span>
                </div>

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Camion souhaité :</span>
                    <span class="text-sm text-black">{{ $demande->type_camion_souhaite_label }}</span>
                </div>

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Localisation souhaitée :</span>
                    <span class="text-sm text-black">{{ $demande->localisation_souhaitee }}</span>
                </div>

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Date de début souhaitée :</span>
                    <span class="text-sm text-black">{{ \Carbon\Carbon::parse($demande->date_debut_souhaitee)->format('d/m/Y') }}</span>
                </div>

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Durée d'attribution :</span>
                    <span class="text-sm text-black">{{ $demande->duree_attribution_label }}</span>
                </div>

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Urgent :</span>
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

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Date de demande :</span>
                    <span class="text-sm text-black">{{ \Carbon\Carbon::parse($demande->created_at)->format('d/m/Y à H:i') }}</span>
                </div>

                @if($demande->date_reponse)
                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Date de réponse :</span>
                    <span class="text-sm text-black">{{ \Carbon\Carbon::parse($demande->date_reponse)->format('d/m/Y à H:i') }}</span>
                </div>
                @endif

                <div class="p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black block mb-2">Motif de la demande :</span>
                    <p class="text-sm text-black">{{ $demande->motif }}</p>
                </div>

                @if($demande->commentaire_admin)
                <div class="p-3 bg-blue-50 rounded border border-blue-200">
                    <span class="text-sm font-medium text-black block mb-2">Commentaire admin :</span>
                    <p class="text-sm text-black">{{ $demande->commentaire_admin }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Informations du franchisé et camion actuel -->
        <div class="space-y-6">
            <!-- Informations du franchisé -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Franchisé</h3>
                
                <div class="space-y-3">
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
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Date d'entrée :</span>
                        <span class="text-sm font-medium text-black">{{ \Carbon\Carbon::parse($demande->franchise->date_entree)->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.franchises.show', $demande->franchise) }}" class="text-orange-600 hover:text-orange-700 text-sm">
                        <i class="fas fa-eye mr-1"></i>
                        Voir le profil du franchisé
                    </a>
                </div>
            </div>

            <!-- Camion actuel (si remplacement) -->
            @if($demande->camion)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Camion actuel</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Immatriculation :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->camion->immatriculation }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Marque/Modèle :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->camion->marque }} {{ $demande->camion->modele }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Année :</span>
                        <span class="text-sm font-medium text-black">{{ $demande->camion->annee }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Statut :</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $demande->camion->statut === 'en_service' ? 'bg-green-100 text-green-800' : 
                               ($demande->camion->statut === 'en_maintenance' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($demande->camion->statut) }}
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.camions.show', $demande->camion) }}" class="text-orange-600 hover:text-orange-700 text-sm">
                        <i class="fas fa-eye mr-1"></i>
                        Voir les détails du camion
                    </a>
                </div>
            </div>
            @endif

            <!-- Camions disponibles -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Camions disponibles</h3>
                
                @php
                    $camionsDisponibles = \App\Models\Camion::where('statut', 'disponible')->get();
                @endphp
                
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

    <!-- Actions -->
    @if($demande->statut === 'en_attente')
    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Actions</h3>
        
        <div class="flex space-x-4">
            <a href="{{ route('admin.demandes-camions.edit', $demande) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Traiter cette demande
            </a>
            
            @if($demande->urgent)
            <span class="alert-warning px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Demande urgente
            </span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection 