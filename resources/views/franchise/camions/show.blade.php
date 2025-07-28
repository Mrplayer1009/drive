@extends('layouts.franchise')

@section('title', 'Détails du Camion')

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
                <h1 class="text-3xl font-bold text-black mb-2">Détails du Camion</h1>
                <p class="text-black">{{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }}</p>
            </div>
            <a href="{{ route('franchise.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations du camion -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations du camion</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Immatriculation</p>
                        <p class="text-sm text-black">{{ $camion->immatriculation }}</p>
                    </div>
                    <i class="fas fa-id-card text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Marque et modèle</p>
                        <p class="text-sm text-black">{{ $camion->marque }} {{ $camion->modele }}</p>
                    </div>
                    <i class="fas fa-truck text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Année</p>
                        <p class="text-sm text-black">{{ $camion->annee }}</p>
                    </div>
                    <i class="fas fa-calendar text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Statut</p>
                        <p class="text-sm text-black">{{ ucfirst(str_replace('_', ' ', $camion->statut)) }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $camion->statut === 'disponible' ? 'bg-green-100 text-green-800' : 
                           ($camion->statut === 'en_utilisation' ? 'bg-blue-100 text-blue-800' : 
                           ($camion->statut === 'en_maintenance' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) }}">
                        {{ ucfirst(str_replace('_', ' ', $camion->statut)) }}
                    </span>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Localisation</p>
                        <p class="text-sm text-black">{{ $camion->localisation_complete }}</p>
                    </div>
                    <i class="fas fa-map-marker-alt text-orange-600"></i>
                </div>
            </div>

            @if($camion->notes)
            <div class="mt-6">
                <h4 class="text-sm font-medium text-black mb-2">Notes</h4>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-black">{{ $camion->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Maintenance -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Maintenance</h3>
            
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-black mb-2">Dernière maintenance</h4>
                    @if($camion->derniere_maintenance)
                        <p class="text-sm text-black">{{ \Carbon\Carbon::parse($camion->derniere_maintenance)->format('d/m/Y') }}</p>
                    @else
                        <p class="text-sm text-gray-500">Aucune maintenance enregistrée</p>
                    @endif
                </div>
                
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-black mb-2">Prochaine maintenance</h4>
                    @if($camion->prochaine_maintenance)
                        <p class="text-sm text-black">{{ \Carbon\Carbon::parse($camion->prochaine_maintenance)->format('d/m/Y') }}</p>
                    @else
                        <p class="text-sm text-gray-500">Non programmée</p>
                    @endif
                </div>
            </div>

            <div class="mt-6 p-4 bg-orange-50 rounded-lg border border-orange-200">
                <h4 class="text-sm font-medium text-black mb-2">
                    <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                    Informations importantes
                </h4>
                <div class="text-xs text-black space-y-1">
                    <p>• Contactez l'administrateur pour signaler un problème</p>
                    <p>• Les maintenances sont programmées automatiquement</p>
                    <p>• Vérifiez régulièrement l'état du camion</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventes associées -->
    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Ventes réalisées avec ce camion</h3>
        
        @if($camion->ventes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Commandes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant reversé</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($camion->ventes->take(10) as $vente)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $vente->nombre_commandes }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $vente->montant_total_formate }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                <span class="text-green-600">{{ $vente->montant_reverse_formate }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-black text-center py-4">Aucune vente enregistrée avec ce camion</p>
        @endif
    </div>
</div>
@endif
@endsection 
