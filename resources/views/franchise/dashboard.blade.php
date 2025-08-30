@extends('layouts.franchise')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-black mb-2">Tableau de bord</h1>
        <p class="text-black">Bienvenue {{ Auth::user()->nom_complet }}</p>
    </div>

    <!-- Alerte compte inactif -->
    @if(Auth::user()->statut === 'inactif')
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <div>
                <p class="font-medium">Compte en attente d'activation</p>
                <p class="text-sm">Votre compte est actuellement en attente de validation par l'administrateur. Vous pourrez accéder à toutes les fonctionnalités une fois votre compte activé.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-euro-sign text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Ventes du mois</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($stats['total_ventes_mois'], 2, ',', ' ') }} €</p>
                    <p class="text-xs text-green-600">{{ number_format($stats['total_reverse_mois'], 2, ',', ' ') }} € reversés</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-truck text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Camions actifs</p>
                    <p class="text-2xl font-semibold text-black">{{ $stats['nombre_camions'] }}</p>
                    <p class="text-xs text-green-600">En service</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Commandes en attente</p>
                    <p class="text-2xl font-semibold text-black">{{ $stats['commandes_en_attente'] }}</p>
                    <p class="text-xs text-orange-600">À traiter</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-chart-line text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Pourcentage</p>
                    <p class="text-2xl font-semibold text-black">{{ Auth::user()->pourcentage_ventes }}%</p>
                    <p class="text-xs text-purple-600">Des ventes</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Commandes clients</p>
                    <p class="text-2xl font-semibold text-black">{{ $stats['commandes_clients_en_attente'] }}</p>
                    <p class="text-xs text-red-600">{{ $stats['commandes_clients_total'] }} total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Ventes récentes -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Ventes récentes</h3>
            <div class="space-y-4">
                @forelse($ventes_recentes as $vente)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-black">{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</p>
                        <p class="text-sm text-black">{{ $vente->nombre_commandes }} commandes</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-black">{{ $vente->montant_total_formate }}</p>
                        <p class="text-xs text-green-600">{{ $vente->montant_reverse_formate }} reversés</p>
                    </div>
                </div>
                @empty
                <p class="text-black text-center py-4">Aucune vente récente</p>
                @endforelse
            </div>
            <div class="mt-4">
                @if(Auth::user()->statut === 'inactif')
                    <span class="text-gray-400 text-sm font-medium cursor-not-allowed">
                        Voir toutes les ventes →
                    </span>
                @else
                    <a href="{{ route('franchise.ventes.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                        Voir toutes les ventes →
                    </a>
                @endif
            </div>
        </div>

        <!-- Commandes récentes -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Commandes récentes</h3>
            <div class="space-y-4">
                @forelse($commandes_recentes as $commande)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-black">{{ $commande->entrepot->nom }}</p>
                        <p class="text-sm text-black">{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-black">{{ $commande->total_formate }}</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $commande->statut === 'livree' ? 'bg-green-100 text-green-800' : 
                               ($commande->statut === 'validee' ? 'bg-blue-100 text-blue-800' : 
                               ($commande->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                            {{ $commande->statut_label }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-black text-center py-4">Aucune commande récente</p>
                @endforelse
            </div>
            <div class="mt-4">
                @if(Auth::user()->statut === 'inactif')
                    <span class="text-gray-400 text-sm font-medium cursor-not-allowed">
                        Voir toutes les commandes →
                    </span>
                @else
                    <a href="{{ route('franchise.commandes.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                        Voir toutes les commandes →
                    </a>
                @endif
            </div>
        </div>

        <!-- Commandes clients récentes -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Commandes clients récentes</h3>
            <div class="space-y-4">
                @forelse($commandes_clients_recentes as $commande)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-black">{{ $commande->client->nom }} {{ $commande->client->prenom }}</p>
                        <p class="text-sm text-black">{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-black">{{ number_format($commande->montant_final, 2, ',', ' ') }} €</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $commande->statut === 'livree' ? 'bg-green-100 text-green-800' : 
                               ($commande->statut === 'confirmee' ? 'bg-blue-100 text-blue-800' : 
                               ($commande->statut === 'en_preparation' ? 'bg-purple-100 text-purple-800' :
                               ($commande->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'))) }}">
                            {{ ucfirst($commande->statut) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-black text-center py-4">Aucune commande client récente</p>
                @endforelse
            </div>
            <div class="mt-4">
                @if(Auth::user()->statut === 'inactif')
                    <span class="text-gray-400 text-sm font-medium cursor-not-allowed">
                        Voir toutes les commandes clients →
                    </span>
                @else
                    <a href="{{ route('franchise.commandes-clients.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                        Voir toutes les commandes clients →
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Camions actifs -->
    @if($camions->count() > 0)
    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Mes camions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($camions as $camion)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-black">{{ $camion->immatriculation }}</h4>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        Actif
                    </span>
                </div>
                <p class="text-sm text-black">{{ $camion->marque }} {{ $camion->modele }}</p>
                <p class="text-sm text-black">{{ $camion->ville_localisation }}</p>
                <p class="text-xs text-gray-500">Attribué le {{ \Carbon\Carbon::parse($camion->pivot->date_attribution)->format('d/m/Y') }}</p>
            </div>
            @endforeach
        </div>
        <div class="mt-4">
            @if(Auth::user()->statut === 'inactif')
                <span class="text-gray-400 text-sm font-medium cursor-not-allowed">
                    Voir tous mes camions →
                </span>
            @else
                <a href="{{ route('franchise.camions.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                    Voir tous mes camions →
                </a>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection 
