@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50">
    <!-- Header -->
    <div class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('client.index') }}" class="text-orange-600 hover:text-orange-700 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-orange-600">
                        <i class="fas fa-id-card text-3xl mr-2"></i>
                        Ma Carte de Fidélité
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('client.panier') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg transition duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-orange-400">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Panier
                    </a>
                    <a href="{{ route('client.commandes') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg transition duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-blue-400">
                        <i class="fas fa-list mr-2"></i>
                        Mes Commandes
                    </a>
                    <form method="POST" action="{{ route('client.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg transition duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-red-400">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Carte de fidélité principale -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-br from-yellow-400 via-orange-500 to-red-600 text-white rounded-xl shadow-2xl p-8 relative overflow-hidden border-4 border-yellow-300">
                    <!-- Effet de brillance -->
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-transparent via-yellow-200 to-transparent opacity-60"></div>
                    
                    <div class="text-center relative z-10">
                        <div class="bg-yellow-300 bg-opacity-30 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 border-2 border-yellow-200">
                            <i class="fas fa-id-card text-4xl text-yellow-100"></i>
                        </div>
                        <h2 class="text-3xl font-bold mb-4 text-yellow-100 drop-shadow-lg">Carte de Fidélité</h2>
                        <div class="text-6xl font-bold mb-3 text-yellow-100 drop-shadow-lg">{{ $infosFidelite['points'] }}</div>
                        <p class="text-yellow-200 font-bold text-xl mb-4">POINTS</p>
                        <div class="mb-6">
                            <span class="bg-yellow-300 bg-opacity-50 px-4 py-2 rounded-full text-lg font-bold text-yellow-900">
                                {{ $infosFidelite['niveau_nom'] }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Barre de progression -->
                    @if($infosFidelite['prochain_palier'])
                    <div class="mt-8 relative z-10">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-yellow-100">Progression vers le niveau {{ $infosFidelite['niveau'] + 1 }}</span>
                            <span class="text-yellow-100">{{ $infosFidelite['points'] }} / {{ $infosFidelite['prochain_palier'] }}</span>
                        </div>
                        <div class="w-full bg-yellow-300 bg-opacity-30 rounded-full h-3">
                            @php
                                $pourcentage = ($infosFidelite['points'] / $infosFidelite['prochain_palier']) * 100;
                            @endphp
                            <div class="bg-yellow-200 h-3 rounded-full transition-all duration-500" style="width: {{ min(100, $pourcentage) }}%"></div>
                        </div>
                        <p class="text-xs text-yellow-200 mt-2 text-center">
                            Plus que {{ $infosFidelite['points_pour_prochain_niveau'] }} points pour le niveau {{ $infosFidelite['niveau'] + 1 }} !
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Avantages du niveau -->
                <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        Avantages {{ $infosFidelite['niveau_nom'] }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($infosFidelite['avantages_niveau'] as $avantage)
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">{{ $avantage }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statistiques -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar text-orange-600 mr-2"></i>
                        Statistiques
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Points actuels :</span>
                            <span class="font-semibold text-lg">{{ $infosFidelite['points'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Réduction disponible :</span>
                            <span class="font-semibold text-lg text-green-600">{{ $infosFidelite['reduction_disponible'] }} €</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Réduction cumulée :</span>
                            <span class="font-semibold text-lg text-blue-600">{{ number_format($infosFidelite['reduction_cumulee'], 2) }} €</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Niveau actuel :</span>
                            <span class="font-semibold text-lg text-orange-600">{{ $infosFidelite['niveau_nom'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Comment ça marche -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Comment ça marche
                    </h3>
                    
                    <div class="space-y-3 text-sm text-gray-700">
                        <div class="flex items-start">
                            <i class="fas fa-coins text-yellow-500 mr-2 mt-1"></i>
                            <span>1€ dépensé = 1 point gagné</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-gift text-red-500 mr-2 mt-1"></i>
                            <span>100 points = 5€ de réduction</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-star text-orange-500 mr-2 mt-1"></i>
                            <span>Plus vous commandez, plus vous montez en niveau</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-percentage text-green-500 mr-2 mt-1"></i>
                            <span>Réduction limitée à 50% du montant de la commande</span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-orange-600 mr-2"></i>
                        Actions rapides
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('client.index') }}" class="block w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg transition duration-300 text-center font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-orange-400">
                            <i class="fas fa-utensils mr-2"></i>
                            Commander
                        </a>
                        <a href="{{ route('client.fidelite.historique') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg transition duration-300 text-center font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-blue-400">
                            <i class="fas fa-history mr-2"></i>
                            Historique
                        </a>
                        <a href="{{ route('client.commandes') }}" class="block w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg transition duration-300 text-center font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-green-400">
                            <i class="fas fa-list mr-2"></i>
                            Mes commandes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
