@extends('layouts.admin')

@section('title', 'Tableau de bord - Admin')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-3xl font-bold text-black mb-2">Tableau de bord</h1>
        <p class="text-black">Vue d'ensemble de l'activité Driv'n Cook</p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Franchisés</p>
                    <p class="text-2xl font-semibold text-black">{{ $stats['total_franchises'] }}</p>
                    <p class="text-xs text-green-600">{{ $stats['franchises_actifs'] }} actifs</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-truck text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Camions</p>
                    <p class="text-2xl font-semibold text-black">{{ $stats['total_camions'] }}</p>
                    <p class="text-xs text-green-600">{{ $stats['camions_disponibles'] }} disponibles</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-euro-sign text-white"></i>
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
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Commandes en attente</p>
                    <p class="text-2xl font-semibold text-black">{{ $stats['commandes_en_attente'] }}</p>
                    <p class="text-xs text-orange-600">À traiter</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Franchisés récents -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Franchisés récents</h3>
            <div class="space-y-4">
                @forelse($franchises_recentes as $franchise)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-black">{{ $franchise->nom_complet }}</p>
                        <p class="text-sm text-black">{{ $franchise->email }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $franchise->statut === 'actif' ? 'bg-green-100 text-green-800' : 
                           ($franchise->statut === 'inactif' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($franchise->statut) }}
                    </span>
                </div>
                @empty
                <p class="text-black text-center py-4">Aucun franchisé récent</p>
                @endforelse
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.franchises.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                    Voir tous les franchisés →
                </a>
            </div>
        </div>

        <!-- Ventes récentes -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Ventes récentes</h3>
            <div class="space-y-4">
                @forelse($ventes_recentes as $vente)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-black">{{ $vente->franchise->nom_complet }}</p>
                        <p class="text-sm text-black">{{ $vente->date_vente_formatee }}</p>
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
                <a href="{{ route('admin.ventes.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                    Voir toutes les ventes →
                </a>
            </div>
        </div>
    </div>

    <!-- Commandes récentes -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Commandes récentes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Entrepôt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($commandes_recentes as $commande)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black">
                            {{ $commande->franchise->nom_complet }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $commande->entrepot->nom }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $commande->total_formate }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $commande->statut === 'livree' ? 'bg-green-100 text-green-800' : 
                                   ($commande->statut === 'validee' ? 'bg-blue-100 text-blue-800' : 
                                   ($commande->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                {{ $commande->statut_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $commande->date_commande->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-black">Aucune commande récente</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.commandes.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                Voir toutes les commandes →
            </a>
        </div>
    </div>

    <!-- Notifications de panne -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-black">Pannes signalées</p>
                <p class="text-2xl font-semibold text-black">{{ \App\Models\NotificationPanne::where('statut', 'signalee')->count() }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.notifications-pannes.index') }}" class="text-orange-600 hover:text-orange-700 text-sm">
                <i class="fas fa-arrow-right mr-1"></i>
                Voir les pannes
            </a>
        </div>
    </div>

    <!-- Demandes de camion -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-truck text-white"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-black">Demandes de camions</p>
                <p class="text-2xl font-semibold text-black">{{ \App\Models\DemandeCamion::where('statut', 'en_attente')->count() }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.demandes-camions.index') }}" class="text-orange-600 hover:text-orange-700 text-sm">
                <i class="fas fa-arrow-right mr-1"></i>
                Voir les demandes
            </a>
        </div>
    </div>
</div>
@endsection 