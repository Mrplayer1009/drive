@extends('layouts.franchise')

@section('title', $evenement->titre . ' - Driv\'n Cook')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $evenement->titre }}</h1>
                    <p class="mt-2 text-gray-600">Détails de l'événement et participants</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('franchise.evenements.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour
                    </a>
                    @if($evenement->statut === 'actif' && !$evenement->isPasse())
                        <a href="{{ route('franchise.evenements.edit', $evenement) }}" 
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Modifier
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations de l'événement -->
            <div class="lg:col-span-1">
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

                        <!-- Prix -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Prix</h3>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ $evenement->prix_points }} points
                                </span>
                            </div>
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
                                        Actif
                                    </span>
                                @endif
                            @elseif($evenement->statut === 'annule')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>
                                    Annulé
                                </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        @if($evenement->statut === 'actif' && !$evenement->isPasse())
                            <div class="pt-4 border-t border-gray-200">
                                <form action="{{ route('franchise.evenements.annuler', $evenement) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet événement ? Les participants seront remboursés.')">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                        <i class="fas fa-times mr-2"></i>
                                        Annuler l'événement
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Liste des participants -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Participants ({{ $participants->count() }})</h2>
                    </div>

                    @if($participants->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Client
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date d'inscription
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Points payés
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($participants as $participant)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $participant->client->nom_complet }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $participant->client->email }}
                                                    </div>
                                                    @if($participant->client->telephone)
                                                        <div class="text-sm text-gray-500">
                                                            <i class="fas fa-phone mr-1"></i>
                                                            {{ $participant->client->telephone }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $participant->date_inscription->format('d/m/Y à H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-star mr-1"></i>
                                                    {{ $participant->points_payes }} points
                                                </span>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun participant</h3>
                            <p class="text-gray-600">Aucun client ne s'est encore inscrit à cet événement.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
