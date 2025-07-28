@extends('layouts.franchise')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-black mb-2">Mes Camions</h1>
        <p class="text-black">Consultez vos camions attribués</p>
    </div>

    @if($camions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($camions as $camion)
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-black">{{ $camion->immatriculation }}</h3>
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        Actif
                    </span>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-black">Marque/Modèle</span>
                        <span class="text-sm font-medium text-black">{{ $camion->marque }} {{ $camion->modele }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-black">Année</span>
                        <span class="text-sm font-medium text-black">{{ $camion->annee }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-black">Localisation</span>
                        <span class="text-sm font-medium text-black">{{ $camion->ville_localisation }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-black">Statut</span>
                        <span class="text-sm font-medium text-black">{{ ucfirst($camion->statut) }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-black">Attribué le</span>
                        <span class="text-sm font-medium text-black">{{ \Carbon\Carbon::parse($camion->pivot->date_attribution)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-black">Dernière maintenance :</span>
                        <span class="text-sm font-medium text-black">{{ $camion->derniere_maintenance ? \Carbon\Carbon::parse($camion->derniere_maintenance)->format('d/m/Y') : 'Aucune' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-black">Prochaine maintenance :</span>
                        <span class="text-sm font-medium text-black">{{ $camion->prochaine_maintenance ? \Carbon\Carbon::parse($camion->prochaine_maintenance)->format('d/m/Y') : 'Non programmée' }}</span>
                    </div>
                </div>

                @if($camion->notes)
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-black">
                        <strong>Notes :</strong> {{ $camion->notes }}
                    </p>
                </div>
                @endif

                <!-- Actions -->
                <div class="mt-4 flex justify-between items-center">
                    <a href="{{ route('franchise.camions.show', $camion) }}" class="text-orange-600 hover:text-orange-700 text-sm">
                        <i class="fas fa-eye mr-1"></i>
                        Détails
                    </a>
                    <div class="flex space-x-2">
                        <a href="{{ route('franchise.camions.signaler-panne', $camion) }}" class="btn-danger text-xs">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Signaler panne
                        </a>
                        <a href="{{ route('franchise.camions.demander-remplacement', $camion) }}" class="btn-primary text-xs">
                            <i class="fas fa-exchange-alt mr-1"></i>
                            Demander remplacement
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-8 text-center">
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gray-100 mb-4">
                <i class="fas fa-truck text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-black mb-2">Aucun camion attribué</h3>
            <p class="text-black">Vous n'avez pas encore de camion attribué. Contactez l'administrateur pour en obtenir un.</p>
        </div>
    @endif

    <!-- Informations -->
    <div class="mt-8 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">
            <i class="fas fa-info-circle text-orange-600 mr-2"></i>
            Informations sur les camions
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-black">
            <div>
                <h4 class="font-medium mb-2">Gestion des camions</h4>
                <ul class="space-y-1">
                    <li>• Les camions sont attribués par l'administrateur</li>
                    <li>• La maintenance est gérée automatiquement</li>
                    <li>• La localisation est mise à jour régulièrement</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium mb-2">En cas de problème</h4>
                <ul class="space-y-1">
                    <li>• Contactez l'administrateur pour les pannes</li>
                    <li>• Signalez les problèmes de maintenance</li>
                    <li>• Consultez les notes pour les détails</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 