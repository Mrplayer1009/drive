@extends('layouts.admin')

@section('title', 'Détails du Camion')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Détails du Camion</h1>
                <p class="text-black">{{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.camions.edit', $camion) }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
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

        <!-- Franchisé assigné -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Franchisé assigné</h3>
            
            @if($camion->franchise_actuel)
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-black">{{ $camion->franchise_actuel->nom_complet }}</h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Actif
                        </span>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-black">Email :</span>
                            <span class="text-black">{{ $camion->franchise_actuel->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-black">Téléphone :</span>
                            <span class="text-black">{{ $camion->franchise_actuel->telephone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-black">Ville :</span>
                            <span class="text-black">{{ $camion->franchise_actuel->ville }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-black">Attribué le :</span>
                            <span class="text-black">{{ \Carbon\Carbon::parse($camion->franchises->first()->pivot->date_attribution)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                    <i class="fas fa-user-slash text-gray-400 text-3xl mb-2"></i>
                    <p class="text-black">Aucun franchisé assigné</p>
                    <p class="text-sm text-gray-500 mt-1">Ce camion est disponible pour attribution</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Maintenance -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Maintenance</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-medium text-black mb-2">Dernière maintenance</h4>
                @if($camion->derniere_maintenance)
                    <p class="text-sm text-black">{{ $camion->derniere_maintenance->format('d/m/Y') }}</p>
                @else
                    <p class="text-sm text-gray-500">Aucune maintenance enregistrée</p>
                @endif
            </div>
            
            <div class="p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-medium text-black mb-2">Prochaine maintenance</h4>
                @if($camion->prochaine_maintenance)
                    <p class="text-sm text-black">{{ $camion->prochaine_maintenance_formatee }}</p>
                @else
                    <p class="text-sm text-gray-500">Non programmée</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Ventes associées -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Ventes associées</h3>
        
        @if($camion->ventes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Commandes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant reversé</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($camion->ventes->take(10) as $vente)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $vente->date_vente->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $vente->franchise->nom_complet }}
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
            <p class="text-black text-center py-4">Aucune vente enregistrée pour ce camion</p>
        @endif
    </div>
</div>
@endsection 