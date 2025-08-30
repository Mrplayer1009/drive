@extends('layouts.client')

@section('title', $evenement->titre . ' - Driv\'n Cook')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $evenement->titre }}</h1>
                    <p class="mt-2 text-gray-600">Détails de l'événement</p>
                </div>
                <a href="{{ route('client.evenements.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>

        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations de l'événement -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations</h2>
                    
                    <div class="space-y-4">
                        <!-- Description -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Description</h3>
                            <p class="text-gray-900">{{ $evenement->description }}</p>
                        </div>

                        <!-- Date et heure -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Date et heure</h3>
                            <div class="flex items-center text-gray-900">
                                <i class="fas fa-calendar mr-2 text-orange-500"></i>
                                {{ $evenement->date_formatee }}
                            </div>
                        </div>

                        <!-- Lieu -->
                        @if($evenement->lieu)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Lieu</h3>
                                <div class="flex items-center text-gray-900">
                                    <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>
                                    {{ $evenement->lieu }}
                                </div>
                            </div>
                        @endif

                        <!-- Franchisé -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Organisateur</h3>
                            <div class="flex items-center text-gray-900">
                                <i class="fas fa-store mr-2 text-orange-500"></i>
                                {{ $evenement->franchise->nom_complet }}
                            </div>
                        </div>

                        <!-- Prix -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Prix</h3>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ $evenement->prix_points }} points
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Équivalent à {{ number_format($evenement->prix_points * 0.05, 2) }}€ de réduction
                            </p>
                        </div>

                        <!-- Participants -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Participants</h3>
                            <div class="text-gray-900">
                                {{ $evenement->nombre_inscrits }} / {{ $evenement->nombre_max_participants }}
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                @php
                                    $pourcentage = ($evenement->nombre_inscrits / $evenement->nombre_max_participants) * 100;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $evenement->places_disponibles }} places disponibles
                            </p>
                        </div>

                        <!-- Statut -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Statut</h3>
                            @if($evenement->statut === 'actif')
                                @if($evenement->isPasse())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Terminé
                                    </span>
                                @elseif($evenement->isComplet())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-users mr-1"></i>
                                        Complet
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Inscriptions ouvertes
                                    </span>
                                @endif
                            @elseif($evenement->statut === 'annule')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>
                                    Annulé
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions et informations client -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Vos points</h2>
                    
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            <span class="text-sm font-medium text-yellow-800">
                                {{ $client->points_fidelite }} points disponibles
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($evenement->statut === 'actif' && !$evenement->isPasse())
                        @if($isInscrit)
                            <div class="space-y-3">
                                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                        <span class="text-sm font-medium text-green-800">
                                            Vous êtes inscrit à cet événement
                                        </span>
                                    </div>
                                </div>

                                @if($evenement->date_evenement->diffInHours(now()) >= 24)
                                    <form action="{{ route('client.evenements.desinscrire', $evenement) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir vous désinscrire ? Vos points vous seront remboursés.')">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                            <i class="fas fa-times mr-2"></i>
                                            Se désinscrire
                                        </button>
                                    </form>
                                @else
                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-xs text-gray-600 text-center">
                                            Désinscription impossible moins de 24h avant l'événement
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @else
                            @if($evenement->isComplet())
                                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-users text-red-400 mr-2"></i>
                                        <span class="text-sm font-medium text-red-800">
                                            Cet événement est complet
                                        </span>
                                    </div>
                                </div>
                            @elseif($client->points_fidelite >= $evenement->prix_points)
                                <form action="{{ route('client.evenements.inscrire', $evenement) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir vous inscrire à cet événement ? {{ $evenement->prix_points }} points seront déduits de votre compte.')">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                        <i class="fas fa-ticket-alt mr-2"></i>
                                        S'inscrire ({{ $evenement->prix_points }} points)
                                    </button>
                                </form>
                            @else
                                <div class="space-y-3">
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle text-red-400 mr-2"></i>
                                            <span class="text-sm font-medium text-red-800">
                                                Points insuffisants
                                            </span>
                                        </div>
                                        <p class="mt-2 text-xs text-red-600">
                                            Il vous faut {{ $evenement->prix_points - $client->points_fidelite }} points supplémentaires
                                        </p>
                                    </div>
                                    
                                    <a href="{{ route('client.index') }}" 
                                       class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg transition duration-200">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        Commander pour gagner des points
                                    </a>
                                </div>
                            @endif
                        @endif
                    @elseif($evenement->statut === 'annule')
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-times text-red-400 mr-2"></i>
                                <span class="text-sm font-medium text-red-800">
                                    Cet événement a été annulé
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                <span class="text-sm font-medium text-gray-800">
                                    Cet événement est terminé
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
