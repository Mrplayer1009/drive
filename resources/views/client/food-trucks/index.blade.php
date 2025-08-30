@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Food Trucks Disponibles</h1>
            <p class="text-gray-600">Trouvez le food truck le plus proche de chez vous</p>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Géolocalisation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ma position</label>
                    <button id="getLocation" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-location-arrow mr-2"></i>
                        Utiliser ma position
                    </button>
                </div>

                <!-- Rayon de recherche -->
                <div>
                    <label for="rayon" class="block text-sm font-medium text-gray-700 mb-2">Rayon de recherche</label>
                    <select id="rayon" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="5">5 km</option>
                        <option value="10" selected>10 km</option>
                        <option value="20">20 km</option>
                        <option value="50">50 km</option>
                    </select>
                </div>

                <!-- Recherche manuelle -->
                <div>
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse manuelle</label>
                    <input type="text" id="adresse" placeholder="Entrez votre adresse" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Résultats -->
        <div id="results" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($foodTrucks as $foodTruck)
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $foodTruck->nom_complet }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Disponible
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-red-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-900 font-medium">
                                    {{ $foodTruck->adresse_emplacement ?: $foodTruck->adresse }}
                                </p>
                                @if($foodTruck->ville)
                                <p class="text-sm text-gray-500">{{ $foodTruck->ville }}, {{ $foodTruck->code_postal }}</p>
                                @endif
                            </div>
                        </div>

                        @if(isset($latitude) && isset($longitude))
                        <div class="flex items-center">
                            <i class="fas fa-route text-blue-500 mr-3"></i>
                            <span class="text-sm text-gray-600">
                                {{ $foodTruck->getDistanceFormateeAttribute($latitude, $longitude) }}
                            </span>
                        </div>
                        @endif

                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-500 mr-3"></i>
                            <span class="text-sm text-gray-600">{{ $foodTruck->telephone }}</span>
                        </div>

                        <div class="flex items-center">
                            <i class="fas fa-truck text-gray-500 mr-3"></i>
                            <span class="text-sm text-gray-600">{{ $foodTruck->getCamionsActifsCount() }} camion(s) disponible(s)</span>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-3">
                        <a href="{{ route('client.food-trucks.show', $foodTruck) }}?latitude={{ $latitude ?? '' }}&longitude={{ $longitude ?? '' }}" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-lg transition duration-300">
                            <i class="fas fa-eye mr-2"></i>
                            Voir détails
                        </a>
                        <button onclick="selectFoodTruck({{ $foodTruck->id }})" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Commander
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Message si aucun food truck -->
        @if($foodTrucks->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-truck text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun food truck disponible</h3>
            <p class="text-gray-600">Essayez d'élargir votre zone de recherche ou utilisez une adresse différente.</p>
        </div>
        @endif
    </div>
</div>

<!-- Script pour la géolocalisation -->
<script>
let currentLatitude = null;
let currentLongitude = null;

document.getElementById('getLocation').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                currentLatitude = position.coords.latitude;
                currentLongitude = position.coords.longitude;
                
                // Mettre à jour l'URL avec les coordonnées
                const url = new URL(window.location);
                url.searchParams.set('latitude', currentLatitude);
                url.searchParams.set('longitude', currentLongitude);
                url.searchParams.set('rayon', document.getElementById('rayon').value);
                window.location.href = url.toString();
            },
            function(error) {
                alert('Impossible d\'obtenir votre position. Veuillez entrer votre adresse manuellement.');
            }
        );
    } else {
        alert('La géolocalisation n\'est pas supportée par votre navigateur.');
    }
});

// Mise à jour automatique lors du changement de rayon
document.getElementById('rayon').addEventListener('change', function() {
    const url = new URL(window.location);
    url.searchParams.set('rayon', this.value);
    if (currentLatitude && currentLongitude) {
        url.searchParams.set('latitude', currentLatitude);
        url.searchParams.set('longitude', currentLongitude);
    }
    window.location.href = url.toString();
});

function selectFoodTruck(foodTruckId) {
    // Stocker le food truck sélectionné
    localStorage.setItem('selectedFoodTruckId', foodTruckId);
    
    // Rediriger vers la page des menus
    window.location.href = '{{ route("client.index") }}';
}
</script>
@endsection
