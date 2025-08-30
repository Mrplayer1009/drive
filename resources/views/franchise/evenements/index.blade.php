@extends('layouts.franchise')

@section('title', 'Mes Événements - Driv\'n Cook')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mes Événements</h1>
                    <p class="mt-2 text-gray-600">Gérez vos dégustations et événements</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('franchise.evenements.create') }}" 
                       class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Nouvel événement
                    </a>
                </div>
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

        <!-- Liste des événements -->
        <div class="bg-white rounded-lg shadow">
            @if($evenements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Événement
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Participants
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Prix
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($evenements as $evenement)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $evenement->titre }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit($evenement->description, 50) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $evenement->date_formatee }}
                                        </div>
                                        @if($evenement->lieu)
                                            <div class="text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $evenement->lieu }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $evenement->nombre_inscrits }} / {{ $evenement->nombre_max_participants }}
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                            @php
                                                $pourcentage = ($evenement->nombre_inscrits / $evenement->nombre_max_participants) * 100;
                                            @endphp
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star mr-1"></i>
                                            {{ $evenement->prix_points }} points
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('franchise.evenements.show', $evenement) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($evenement->statut === 'actif' && !$evenement->isPasse())
                                                <a href="{{ route('franchise.evenements.edit', $evenement) }}" 
                                                   class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('franchise.evenements.annuler', $evenement) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet événement ? Les participants seront remboursés.')">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $evenements->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun événement</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore créé d'événements.</p>
                    <a href="{{ route('franchise.evenements.create') }}" 
                       class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Créer votre premier événement
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
