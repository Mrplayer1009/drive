@extends('layouts.client')

@section('title', 'Mes événements - Driv\'n Cook')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mes événements</h1>
                    <p class="mt-2 text-gray-600">Vos événements et dégustations réservés</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('client.evenements.index') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-calendar mr-2"></i>
                        Voir tous les événements
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
        @if($evenements->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($evenements as $evenement)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        <!-- En-tête de la carte -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $evenement->titre }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Inscrit
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $evenement->description }}</p>

                            <!-- Informations -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-2 text-orange-500"></i>
                                    {{ $evenement->date_formatee }}
                                </div>
                                
                                @if($evenement->lieu)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>
                                        {{ $evenement->lieu }}
                                    </div>
                                @endif

                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-store mr-2 text-orange-500"></i>
                                    {{ $evenement->franchise->nom_complet }}
                                </div>

                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-star mr-2 text-orange-500"></i>
                                    {{ $evenement->pivot->points_payes }} points payés
                                </div>
                            </div>

                            <!-- Statut de l'événement -->
                            <div class="mb-4">
                                @if($evenement->statut === 'actif')
                                    @if($evenement->isPasse())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Terminé
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            À venir
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
                            <div class="flex space-x-2">
                                <a href="{{ route('client.evenements.show', $evenement) }}" 
                                   class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg transition duration-200">
                                    <i class="fas fa-eye mr-2"></i>
                                    Voir détails
                                </a>
                                
                                @if($evenement->statut === 'actif' && !$evenement->isPasse())
                                    <form action="{{ route('client.evenements.desinscrire', $evenement) }}" 
                                          method="POST" 
                                          class="flex-1"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir vous désinscrire ? Vos points vous seront remboursés.')">
                                        @csrf
                                        <button type="submit" 
                                                style="width: 100%; background-color: #dc2626; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; border: 2px solid #b91c1c; font-weight: bold; transition: all 0.3s; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
                                                onmouseover="this.style.backgroundColor='#b91c1c'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.15)'"
                                                onmouseout="this.style.backgroundColor='#dc2626'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
                                            <i class="fas fa-times mr-2"></i>
                                            Se désinscrire
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $evenements->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun événement réservé</h3>
                <p class="text-gray-600 mb-6">Vous n'êtes inscrit à aucun événement pour le moment.</p>
                <div class="space-x-4">
                    <a href="{{ route('client.evenements.index') }}" 
                       class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg transition duration-200">
                        <i class="fas fa-calendar mr-2"></i>
                        Découvrir les événements
                    </a>
                    <a href="{{ route('client.index') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Commander pour gagner des points
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
