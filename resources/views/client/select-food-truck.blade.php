@extends('layouts.client')

@section('title', 'Choisir votre Food Truck - Driv\'n Cook')

@section('content')
<div class="min-h-screen bg-gray-50">

    <!-- Hero Section -->
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 3rem 0;">
        <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem; text-align: center;">
            <h2 style="font-size: 2.25rem; font-weight: bold; margin-bottom: 1rem; color: white;">
                <i class="fas fa-map-marker-alt mr-2"></i>
                Choisissez votre Food Truck
            </h2>
            <p style="font-size: 1.25rem; color: white;">Trouvez le food truck le plus proche de chez vous</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Contrôles de géolocalisation -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-location-arrow mr-2 text-blue-600"></i>
                Localisation
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Géolocalisation automatique -->
                <div>
                    <button id="geolocate-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                        <i class="fas fa-crosshairs mr-2"></i>
                        Ma position actuelle
                    </button>
                </div>
                
                <!-- Rayon de recherche -->
                <div>
                    <label for="rayon-select" class="block text-sm font-medium text-gray-700 mb-2">Rayon de recherche</label>
                    <select id="rayon-select" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="5">5 km</option>
                        <option value="10" selected>10 km</option>
                        <option value="20">20 km</option>
                        <option value="50">50 km</option>
                    </select>
                </div>
                
                <!-- Recherche manuelle -->
                <div>
                    <label for="address-input" class="block text-sm font-medium text-gray-700 mb-2">Adresse manuelle</label>
                    <input type="text" id="address-input" placeholder="Entrez votre adresse..." 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <!-- Coordonnées actuelles -->
            <div id="coordinates-display" class="mt-4 p-3 bg-gray-50 rounded-lg hidden">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                    Position : <span id="current-lat"></span>, <span id="current-lng"></span>
                </p>
            </div>
        </div>

        <!-- Carte Google Maps -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-map mr-2 text-green-600"></i>
                Carte interactive
            </h3>
            <div id="map" style="height: 400px; width: 100%; border-radius: 0.5rem;"></div>
        </div>

        <!-- Liste des food trucks -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-truck mr-2 text-orange-600"></i>
                    Food Trucks disponibles
                </h3>
                <button id="show-all-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-list mr-2"></i>
                    Voir tous les camions
                </button>
            </div>
            
            <div id="food-trucks-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Les food trucks seront chargés ici via JavaScript -->
            </div>
            
            <div id="no-food-trucks" class="text-center py-12 hidden">
                <i class="fas fa-truck text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun food truck disponible</h3>
                <p class="text-gray-600">Aucun food truck n'est actuellement en service.</p>
            </div>
        </div>
    </div>
</div>

<!-- Script Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places"></script>
<script>
let map;
let markers = [];
let currentPosition = null;
let foodTrucks = @json($foodTrucks);
let showAllFoodTrucks = false;

// Initialiser la carte
function initMap() {
    // Position par défaut (Paris)
    const defaultPosition = { lat: 48.8566, lng: 2.3522 };
    
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: defaultPosition,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });
    
    // Ajouter les food trucks sur la carte
    addFoodTrucksToMap();
}

// Ajouter les food trucks sur la carte
function addFoodTrucksToMap() {
    // Supprimer les marqueurs existants
    markers.forEach(marker => marker.setMap(null));
    markers = [];
    
    foodTrucks.forEach(foodTruck => {
        if (foodTruck.latitude && foodTruck.longitude) {
            const marker = new google.maps.Marker({
                position: { lat: parseFloat(foodTruck.latitude), lng: parseFloat(foodTruck.longitude) },
                map: map,
                title: foodTruck.nom_complet,
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                        <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="20" cy="20" r="18" fill="#f97316" stroke="white" stroke-width="2"/>
                            <text x="20" y="25" text-anchor="middle" fill="white" font-size="12" font-weight="bold">🍔</text>
                        </svg>
                    `),
                    scaledSize: new google.maps.Size(40, 40)
                }
            });
            
            // Info window
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px; max-width: 200px;">
                        <h3 style="font-weight: bold; margin-bottom: 5px;">${foodTruck.nom_complet}</h3>
                        <p style="margin-bottom: 5px; font-size: 12px;">${foodTruck.adresse_emplacement || foodTruck.adresse}</p>
                        <p style="margin-bottom: 5px; font-size: 12px;">📞 ${foodTruck.telephone}</p>
                        <p style="margin-bottom: 5px; font-size: 12px;">🚛 ${foodTruck.camions_immatriculations || 'Aucun camion'}</p>
                        <button onclick="selectFoodTruck(${foodTruck.id})" 
                                style="background: #f97316; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">
                            Choisir ce food truck
                        </button>
                    </div>
                `
            });
            
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
            
            markers.push(marker);
        }
    });
}

// Géolocalisation
function getCurrentLocation() {
    if (navigator.geolocation) {
        // Options de géolocalisation avec précision maximale
        const options = {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0 // Pas de cache, toujours une nouvelle position
        };
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                currentPosition = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                console.log('Position GPS brute:', currentPosition);
                console.log('Précision:', position.coords.accuracy, 'mètres');
                
                // Vérifier la précision
                if (position.coords.accuracy > 100) {
                    console.warn('Précision GPS faible:', position.coords.accuracy, 'mètres');
                }
                
                // Centrer la carte sur la position actuelle
                map.setCenter(currentPosition);
                map.setZoom(15); // Zoom plus proche pour mieux voir
                
                // Supprimer l'ancien marqueur utilisateur s'il existe
                if (window.userMarker) {
                    window.userMarker.setMap(null);
                }
                
                // Ajouter un marqueur pour la position actuelle
                window.userMarker = new google.maps.Marker({
                    position: currentPosition,
                    map: map,
                    title: 'Votre position (précision: ' + Math.round(position.coords.accuracy) + 'm)',
                    icon: {
                        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                            <svg width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="15" cy="15" r="12" fill="#3b82f6" stroke="white" stroke-width="2"/>
                                <circle cx="15" cy="15" r="6" fill="white"/>
                            </svg>
                        `),
                        scaledSize: new google.maps.Size(30, 30)
                    }
                });
                
                // Afficher les coordonnées avec plus de précision
                document.getElementById('current-lat').textContent = currentPosition.lat.toFixed(8);
                document.getElementById('current-lng').textContent = currentPosition.lng.toFixed(8);
                document.getElementById('coordinates-display').classList.remove('hidden');
                
                // Ajouter un cercle de précision si la précision est faible
                if (position.coords.accuracy > 50) {
                    if (window.accuracyCircle) {
                        window.accuracyCircle.setMap(null);
                    }
                    
                    window.accuracyCircle = new google.maps.Circle({
                        strokeColor: '#3b82f6',
                        strokeOpacity: 0.3,
                        strokeWeight: 1,
                        fillColor: '#3b82f6',
                        fillOpacity: 0.1,
                        map: map,
                        center: currentPosition,
                        radius: position.coords.accuracy
                    });
                }
                
                // Mettre à jour la liste des food trucks
                updateFoodTrucksList();
            },
            (error) => {
                console.error('Erreur de géolocalisation:', error);
                let errorMessage = 'Impossible de récupérer votre position. ';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'Veuillez autoriser la géolocalisation dans votre navigateur.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'Position temporairement indisponible.';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'Délai d\'attente dépassé.';
                        break;
                    default:
                        errorMessage += 'Veuillez entrer votre adresse manuellement.';
                }
                
                alert(errorMessage);
            },
            options
        );
    } else {
        alert('La géolocalisation n\'est pas supportée par votre navigateur.');
    }
}

// Recherche d'adresse
function searchAddress() {
    const address = document.getElementById('address-input').value;
    if (!address) return;
    
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: address }, (results, status) => {
        if (status === 'OK') {
            currentPosition = {
                lat: results[0].geometry.location.lat(),
                lng: results[0].geometry.location.lng()
            };
            
            console.log('Position depuis adresse:', currentPosition);
            
            // Centrer la carte
            map.setCenter(currentPosition);
            map.setZoom(15);
            
            // Supprimer l'ancien marqueur utilisateur s'il existe
            if (window.userMarker) {
                window.userMarker.setMap(null);
            }
            
            // Ajouter un marqueur pour la position
            window.userMarker = new google.maps.Marker({
                position: currentPosition,
                map: map,
                title: 'Position recherchée',
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                        <svg width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="15" cy="15" r="12" fill="#3b82f6" stroke="white" stroke-width="2"/>
                            <circle cx="15" cy="15" r="6" fill="white"/>
                        </svg>
                    `),
                    scaledSize: new google.maps.Size(30, 30)
                }
            });
            
            // Afficher les coordonnées
            document.getElementById('current-lat').textContent = currentPosition.lat.toFixed(8);
            document.getElementById('current-lng').textContent = currentPosition.lng.toFixed(8);
            document.getElementById('coordinates-display').classList.remove('hidden');
            
            // Mettre à jour la liste des food trucks
            updateFoodTrucksList();
        } else {
            alert('Adresse non trouvée. Veuillez vérifier votre saisie.');
        }
    });
}



// Mettre à jour la liste des food trucks
function updateFoodTrucksList() {
    const foodTrucksList = document.getElementById('food-trucks-list');
    const noFoodTrucks = document.getElementById('no-food-trucks');
    
    // Si on affiche tous les food trucks ou si on n'a pas de position
    let foodTrucksToShow = foodTrucks;
    
    if (!showAllFoodTrucks && currentPosition) {
        const rayon = parseInt(document.getElementById('rayon-select').value);
        
        // Calculer la distance pour tous les food trucks
        foodTrucksToShow = foodTrucks.filter(foodTruck => {
            if (!foodTruck.latitude || !foodTruck.longitude) return true; // Afficher même sans coordonnées
            
            const distance = calculateDistance(
                currentPosition.lat, currentPosition.lng,
                parseFloat(foodTruck.latitude), parseFloat(foodTruck.longitude)
            );
            
            foodTruck.distance = distance;
            return distance <= rayon;
        });
        
        // Trier par distance
        foodTrucksToShow.sort((a, b) => (a.distance || 0) - (b.distance || 0));
    } else if (showAllFoodTrucks && currentPosition) {
        // Si on affiche tous les food trucks mais qu'on a une position, calculer quand même les distances
        foodTrucksToShow = foodTrucks.map(foodTruck => {
            if (foodTruck.latitude && foodTruck.longitude) {
                const distance = calculateDistance(
                    currentPosition.lat, currentPosition.lng,
                    parseFloat(foodTruck.latitude), parseFloat(foodTruck.longitude)
                );
                foodTruck.distance = distance;
            }
            return foodTruck;
        });
        
        // Trier par distance
        foodTrucksToShow.sort((a, b) => (a.distance || 0) - (b.distance || 0));
    }
    
    if (foodTrucksToShow.length === 0) {
        foodTrucksList.classList.add('hidden');
        noFoodTrucks.classList.remove('hidden');
    } else {
        foodTrucksList.classList.remove('hidden');
        noFoodTrucks.classList.add('hidden');
        
        foodTrucksList.innerHTML = foodTrucksToShow.map(foodTruck => `
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900">${foodTruck.nom_complet}</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        ${foodTruck.camions_immatriculations || 'Aucun camion'}
                    </span>
                </div>
                
                <div class="space-y-3 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-gray-500 mr-3"></i>
                        <span class="text-sm text-gray-600">${foodTruck.adresse_emplacement || foodTruck.adresse}</span>
                    </div>
                    
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-500 mr-3"></i>
                        <span class="text-sm text-gray-600">${foodTruck.telephone}</span>
                    </div>
                    
                    ${currentPosition && foodTruck.distance ? `
                    <div class="flex items-center">
                        <i class="fas fa-route text-gray-500 mr-3"></i>
                        <span class="text-sm text-gray-600">${foodTruck.distance.toFixed(1)} km</span>
                    </div>
                    ` : ''}
                </div>
                
                <button onclick="selectFoodTruck(${foodTruck.id})" 
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-check mr-2"></i>
                    Choisir ce food truck
                </button>
            </div>
        `).join('');
    }
}

// Calculer la distance entre deux points (formule de Haversine)
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Rayon de la Terre en km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Sélectionner un food truck
function selectFoodTruck(foodTruckId) {
    // Trouver les informations du food truck
    const foodTruck = foodTrucks.find(ft => ft.id == foodTruckId);
    if (!foodTruck) {
        alert('Food truck introuvable');
        return;
    }
    
    // Stocker les informations en localStorage
    localStorage.setItem('selectedFoodTruckId', foodTruckId);
    localStorage.setItem('selectedFoodTruckName', foodTruck.nom_complet);
    localStorage.setItem('selectedFoodTruckAddress', foodTruck.adresse_emplacement || foodTruck.adresse);
    
    // Stocker le food truck sélectionné en session
    fetch('{{ route("client.select-food-truck") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            food_truck_id: foodTruckId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Rediriger vers le panier
            window.location.href = '{{ route("client.panier") }}';
        } else {
            alert(data.message || 'Erreur lors de la sélection du food truck');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la sélection du food truck');
    });
}

// Événements
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Afficher tous les food trucks au chargement
    updateFoodTrucksList();
    
    // Géolocalisation
    document.getElementById('geolocate-btn').addEventListener('click', getCurrentLocation);
    
    // Recherche d'adresse
    document.getElementById('address-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchAddress();
        }
    });
    
    // Changement de rayon
    document.getElementById('rayon-select').addEventListener('change', function() {
        updateFoodTrucksList();
    });
    
    // Bouton "Voir tous les camions"
    document.getElementById('show-all-btn').addEventListener('click', function() {
        showAllFoodTrucks = !showAllFoodTrucks;
        
        if (showAllFoodTrucks) {
            this.innerHTML = '<i class="fas fa-map-marker-alt mr-2"></i>Voir les camions proches';
            this.className = 'bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200';
        } else {
            this.innerHTML = '<i class="fas fa-list mr-2"></i>Voir tous les camions';
            this.className = 'bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200';
        }
        
        updateFoodTrucksList();
    });
    
    // Mettre à jour le compteur du panier
    const panier = JSON.parse(localStorage.getItem('panier') || '[]');
    const totalItems = panier.reduce((total, item) => total + item.quantite, 0);
    const compteurElement = document.getElementById('panier-compteur');
    if (compteurElement) {
        compteurElement.textContent = totalItems;
        compteurElement.style.display = totalItems > 0 ? 'block' : 'none';
    }
});
</script>
@endsection
