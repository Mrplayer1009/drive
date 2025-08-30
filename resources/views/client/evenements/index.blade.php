@extends('layouts.client')

@section('title', 'Événements - Driv\'n Cook')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Événements & Dégustations</h1>
                    <p class="mt-2 text-gray-600">Découvrez nos événements spéciaux et réservez votre place</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('client.evenements.mes-evenements') }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-ticket-alt mr-2"></i>
                        Mes événements
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

        <!-- Informations sur les points -->
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Vos points de fidélité</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Vous avez actuellement <strong id="points-actuels">{{ $client->points_fidelite }} points</strong> disponibles.</p>
                        <p class="mt-1">100 points = 5€ de réduction sur vos commandes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des événements -->
        @if($evenements->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($evenements as $evenement)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        <!-- En-tête de la carte -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $evenement->titre }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ $evenement->prix_points }} points
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
                                    <i class="fas fa-users mr-2 text-orange-500"></i>
                                    {{ $evenement->places_disponibles }} places disponibles
                                </div>

                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-store mr-2 text-orange-500"></i>
                                    {{ $evenement->franchise->nom_complet }}
                                </div>
                            </div>

                            <!-- Barre de progression -->
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Places occupées</span>
                                    <span>{{ $evenement->nombre_inscrits }}/{{ $evenement->nombre_max_participants }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    @php
                                        $pourcentage = ($evenement->nombre_inscrits / $evenement->nombre_max_participants) * 100;
                                    @endphp
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <a href="{{ route('client.evenements.show', $evenement) }}" 
                                   class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg transition duration-200">
                                    <i class="fas fa-eye mr-2"></i>
                                    Voir détails
                                </a>
                                
                                @if($client->points_fidelite >= $evenement->prix_points)
                                    <form action="{{ route('client.evenements.inscrire', $evenement) }}" 
                                          method="POST" 
                                          class="flex-1"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir vous inscrire à cet événement ? {{ $evenement->prix_points }} points seront déduits de votre compte.')">
                                        @csrf
                                        <button type="submit" 
                                                style="width: 100%; background-color: #f97316; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; border: 2px solid #ea580c; font-weight: bold; transition: all 0.3s; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
                                                onmouseover="this.style.backgroundColor='#ea580c'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.15)'"
                                                onmouseout="this.style.backgroundColor='#f97316'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
                                            <i class="fas fa-ticket-alt mr-2"></i>
                                            S'inscrire
                                        </button>
                                    </form>
                                @else
                                    <button disabled 
                                            style="width: 100%; background-color: #9ca3af; color: #6b7280; padding: 0.5rem 1rem; border-radius: 0.5rem; border: 2px solid #d1d5db; font-weight: bold; cursor: not-allowed; opacity: 0.7;">
                                        <i class="fas fa-lock mr-2"></i>
                                        Points insuffisants
                                    </button>
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun événement disponible</h3>
                <p class="text-gray-600 mb-6">Il n'y a actuellement aucun événement disponible.</p>
                <a href="{{ route('client.evenements.calendrier') }}" 
                   class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg transition duration-200">
                    <i class="fas fa-calendar mr-2"></i>
                    Voir le calendrier
                </a>
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

<script>
// Synchroniser les points de fidélité avec le service JavaScript
document.addEventListener('DOMContentLoaded', function() {
    if (window.fideliteService) {
        // Forcer la synchronisation avec les données de la base
        const pointsFromServer = {{ $client->points_fidelite }};
        
        // Mettre à jour le localStorage avec les vraies données
        const fidelite = JSON.parse(localStorage.getItem('drivncook_fidelite') || '{}');
        fidelite.points = pointsFromServer;
        fidelite.lastUpdate = new Date().toISOString();
        localStorage.setItem('drivncook_fidelite', JSON.stringify(fidelite));
        
        // Récupérer les infos mises à jour
        const infosFidelite = window.fideliteService.getInfosFidelite();
        const pointsElement = document.getElementById('points-actuels');
        
        if (pointsElement) {
            pointsElement.textContent = infosFidelite.points + ' points';
        }
        
        console.log('Points synchronisés depuis la base :', infosFidelite.points);
    }
});
</script>
@endsection
