@extends('layouts.admin')

@section('title', 'Détails de la Panne')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Détails de la Panne #{{ $notification->id }}</h1>
                <p class="text-black">Camion : {{ $notification->camion->immatriculation }} ({{ $notification->camion->marque }} {{ $notification->camion->modele }})</p>
            </div>
            <a href="{{ route('admin.notifications-pannes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations de la panne -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations de la panne</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Statut :</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $notification->statut === 'resolue' ? 'bg-green-100 text-green-800' : 
                           ($notification->statut === 'ignoree' ? 'bg-gray-100 text-gray-800' : 
                           ($notification->statut === 'en_maintenance' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                        {{ $notification->statut_label }}
                    </span>
                </div>

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Type de panne :</span>
                    <span class="text-sm text-black">{{ $notification->type_panne_label }}</span>
                </div>

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Gravité :</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $notification->gravite_color }}">
                        {{ $notification->gravite_label }}
                    </span>
                </div>

                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Date de signalement :</span>
                    <span class="text-sm text-black">{{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y à H:i') }}</span>
                </div>

                @if($notification->date_resolution)
                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black">Date de résolution :</span>
                    <span class="text-sm text-black">{{ \Carbon\Carbon::parse($notification->date_resolution)->format('d/m/Y à H:i') }}</span>
                </div>
                @endif

                <div class="p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black block mb-2">Description de la panne :</span>
                    <p class="text-sm text-black">{{ $notification->description_panne }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded">
                    <span class="text-sm font-medium text-black block mb-2">Symptômes observés :</span>
                    <p class="text-sm text-black">{{ $notification->symptomes }}</p>
                </div>

                @if($notification->commentaire_admin)
                <div class="p-3 bg-blue-50 rounded border border-blue-200">
                    <span class="text-sm font-medium text-black block mb-2">Commentaire admin :</span>
                    <p class="text-sm text-black">{{ $notification->commentaire_admin }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Informations du franchisé et du camion -->
        <div class="space-y-6">
            <!-- Informations du franchisé -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Franchisé</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Nom :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->franchise->nom_complet }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Email :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->franchise->email }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Téléphone :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->franchise->telephone }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Ville :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->franchise->ville }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.franchises.show', $notification->franchise) }}" class="text-orange-600 hover:text-orange-700 text-sm">
                        <i class="fas fa-eye mr-1"></i>
                        Voir le profil du franchisé
                    </a>
                </div>
            </div>

            <!-- Informations du camion -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Camion</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Immatriculation :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->camion->immatriculation }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Marque/Modèle :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->camion->marque }} {{ $notification->camion->modele }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Année :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->camion->annee }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Statut :</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $notification->camion->statut === 'en_utilisation' ? 'bg-green-100 text-green-800' : 
                               ($notification->camion->statut === 'en_maintenance' ? 'bg-red-100 text-red-800' : 
                               ($notification->camion->statut === 'disponible' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($notification->camion->statut) }}
                        </span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Dernière maintenance :</span>
                        <span class="text-sm font-medium text-black">
                            {{ $notification->camion->derniere_maintenance ? \Carbon\Carbon::parse($notification->camion->derniere_maintenance)->format('d/m/Y') : 'Aucune' }}
                        </span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Prochaine maintenance :</span>
                        <span class="text-sm font-medium text-black">
                            {{ $notification->camion->prochaine_maintenance ? \Carbon\Carbon::parse($notification->camion->prochaine_maintenance)->format('d/m/Y') : 'Non programmée' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.camions.show', $notification->camion) }}" class="text-orange-600 hover:text-orange-700 text-sm">
                        <i class="fas fa-eye mr-1"></i>
                        Voir les détails du camion
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    @if($notification->statut === 'signalee')
    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Actions</h3>
        
        <div class="flex space-x-4">
            <a href="{{ route('admin.notifications-pannes.edit', $notification) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Traiter cette panne
            </a>
            
            @if($notification->gravite === 'critique' || $notification->gravite === 'grave')
            <a href="{{ route('admin.demandes-camions.create', ['franchise' => $notification->franchise_id, 'urgence' => 'panne']) }}" class="btn-success">
                <i class="fas fa-exchange-alt mr-2"></i>
                Attribuer un camion de remplacement
            </a>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection 
