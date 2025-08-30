@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $foodTruck->nom_complet }}</h1>
                    <p class="text-gray-600">Food Truck Driv'n Cook</p>
                </div>
                <a href="{{ route('client.food-trucks.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations du food truck -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-red-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Adresse</p>
                            <p class="text-sm text-gray-600">
                                {{ $foodTruck->adresse_emplacement ?: $foodTruck->adresse }}
                            </p>
                            @if($foodTruck->ville)
                            <p class="text-sm text-gray-600">{{ $foodTruck->ville }}, {{ $foodTruck->code_postal }}</p>
                            @endif
                        </div>
                    </div>

                    @if($distance)
                    <div class="flex items-center">
                        <i class="fas fa-route text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Distance</p>
                            <p class="text-sm text-gray-600">{{ $distance }} km</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Téléphone</p>
                            <p class="text-sm text-gray-600">{{ $foodTruck->telephone }}</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email</p>
                            <p class="text-sm text-gray-600">{{ $foodTruck->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-truck text-gray-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Camions disponibles</p>
                            <p class="text-sm text-gray-600">{{ $foodTruck->getCamionsActifsCount() }} camion(s) actif(s)</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-clock text-gray-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Statut</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Disponible
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bouton commander -->
                <div class="mt-6">
                    <button onclick="selectFoodTruck({{ $foodTruck->id }})" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition duration-300">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Commander maintenant
                    </button>
                </div>
            </div>

            <!-- Carte (placeholder) -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Localisation</h2>
                
                @if($foodTruck->latitude && $foodTruck->longitude)
                <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-600">Carte interactive</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Coordonnées: {{ $foodTruck->latitude }}, {{ $foodTruck->longitude }}
                        </p>
                    </div>
                </div>
                @else
                <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map-marker-alt text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-600">Localisation non disponible</p>
                    </div>
                </div>
                @endif

                <!-- Instructions -->
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-900 mb-2">Comment commander ?</h3>
                    <ol class="text-sm text-blue-800 space-y-1">
                        <li>1. Cliquez sur "Commander maintenant"</li>
                        <li>2. Choisissez vos menus</li>
                        <li>3. Rendez-vous au food truck pour récupérer votre commande</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Horaires et informations supplémentaires -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations supplémentaires</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Horaires d'ouverture</h3>
                    <p class="text-gray-600">Veuillez contacter le food truck directement pour connaître les horaires d'ouverture.</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Modes de paiement</h3>
                    <div class="flex space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-credit-card mr-1"></i>
                            Carte bancaire
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-money-bill mr-1"></i>
                            Espèces
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectFoodTruck(foodTruckId) {
    // Stocker le food truck sélectionné
    localStorage.setItem('selectedFoodTruckId', foodTruckId);
    
    // Rediriger vers la page des menus
    window.location.href = '{{ route("client.index") }}';
}
</script>
@endsection
