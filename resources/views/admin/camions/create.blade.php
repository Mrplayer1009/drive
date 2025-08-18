@extends('layouts.admin')

@section('title', 'Créer un Camion')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Créer un Camion</h1>
                <p class="text-black">Ajouter un nouveau camion à la flotte</p>
            </div>
            <a href="{{ route('admin.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.camions.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations du camion -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations du camion</h3>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="immatriculation" class="block text-sm font-medium text-black mb-2">Immatriculation *</label>
                    <input type="text" id="immatriculation" name="immatriculation" value="{{ old('immatriculation') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Ex: AB-123-CD">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="marque" class="block text-sm font-medium text-black mb-2">Marque *</label>
                        <input type="text" id="marque" name="marque" value="{{ old('marque') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Ex: Renault">
                    </div>
                    <div>
                        <label for="modele" class="block text-sm font-medium text-black mb-2">Modèle *</label>
                        <input type="text" id="modele" name="modele" value="{{ old('modele') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Ex: Master">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="annee" class="block text-sm font-medium text-black mb-2">Année *</label>
                    <input type="number" id="annee" name="annee" value="{{ old('annee') }}" min="1900" max="{{ date('Y') + 1 }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Ex: 2023">
                </div>

                <div class="mb-4">
                    <label for="statut" class="block text-sm font-medium text-black mb-2">Statut *</label>
                    <select id="statut" name="statut" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Sélectionner un statut...</option>
                        <option value="disponible" {{ old('statut') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="en_utilisation" {{ old('statut') === 'en_utilisation' ? 'selected' : '' }}>En utilisation</option>
                        <option value="en_maintenance" {{ old('statut') === 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                        <option value="hors_service" {{ old('statut') === 'hors_service' ? 'selected' : '' }}>Hors service</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="ville_localisation" class="block text-sm font-medium text-black mb-2">Ville de localisation</label>
                    <div class="relative">
                        <input type="text" id="ville_localisation" name="ville_localisation" value="{{ old('ville_localisation') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Ex: Paris">
                        <div id="loading-coords" class="absolute right-3 top-2 hidden">
                            <i class="fas fa-spinner fa-spin text-orange-600"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Les coordonnées GPS seront automatiquement remplies</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-black mb-2">Latitude</label>
                        <input type="number" id="latitude" name="latitude" value="{{ old('latitude') }}" step="0.00000001" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Ex: 48.8566" readonly>
                    </div>
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-black mb-2">Longitude</label>
                        <input type="number" id="longitude" name="longitude" value="{{ old('longitude') }}" step="0.00000001" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Ex: 2.3522" readonly>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-black mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Informations complémentaires sur le camion...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Maintenance et informations -->
            <div class="space-y-6">
                <!-- Maintenance -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-black mb-4">Maintenance</h3>
                    
                    <div class="mb-4">
                        <label for="derniere_maintenance" class="block text-sm font-medium text-black mb-2">Dernière maintenance</label>
                        <input type="date" id="derniere_maintenance" name="derniere_maintenance" value="{{ old('derniere_maintenance') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div class="mb-4">
                        <label for="prochaine_maintenance" class="block text-sm font-medium text-black mb-2">Prochaine maintenance</label>
                        <input type="date" id="prochaine_maintenance" name="prochaine_maintenance" value="{{ old('prochaine_maintenance') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                        <h4 class="text-sm font-medium text-black mb-2">
                            <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                            Informations importantes
                        </h4>
                        <div class="text-xs text-black space-y-1">
                            <p>• Le statut "Disponible" permet l'attribution à un franchisé</p>
                            <p>• Le statut "En maintenance" empêche toute utilisation</p>
                            <p>• Les coordonnées GPS permettent le suivi en temps réel</p>
                            <p>• L'immatriculation doit être unique dans la flotte</p>
                        </div>
                    </div>
                </div>

                <!-- Aide et conseils -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-black mb-4">Aide et conseils</h3>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="text-sm font-medium text-black mb-2">
                                <i class="fas fa-lightbulb text-blue-600 mr-1"></i>
                                Conseils pour l'immatriculation
                            </h4>
                            <div class="text-xs text-black space-y-1">
                                <p>• Format recommandé : XX-123-XX</p>
                                <p>• Évitez les caractères spéciaux</p>
                                <p>• Vérifiez l'unicité avant création</p>
                            </div>
                        </div>

                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <h4 class="text-sm font-medium text-black mb-2">
                                <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                Bonnes pratiques
                            </h4>
                            <div class="text-xs text-black space-y-1">
                                <p>• Renseignez la ville de localisation</p>
                                <p>• Programmez la prochaine maintenance</p>
                                <p>• Ajoutez des notes si nécessaire</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-6 py-2 rounded-lg transition duration-300">
                Annuler
            </a>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-6 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Créer le camion
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation de l'année
    const anneeInput = document.getElementById('annee');
    anneeInput.addEventListener('input', function() {
        const annee = parseInt(this.value);
        const currentYear = new Date().getFullYear();
        
        if (annee < 1900 || annee > currentYear + 1) {
            this.setCustomValidity('L\'année doit être entre 1900 et ' + (currentYear + 1));
        } else {
            this.setCustomValidity('');
        }
    });

    // Validation de l'immatriculation
    const immatriculationInput = document.getElementById('immatriculation');
    immatriculationInput.addEventListener('input', function() {
        const value = this.value.toUpperCase();
        this.value = value;
        
        // Format basique : XX-123-XX
        const format = /^[A-Z]{2}-\d{3}-[A-Z]{2}$/;
        if (value && !format.test(value)) {
            this.setCustomValidity('Format attendu : XX-123-XX');
        } else {
            this.setCustomValidity('');
        }
    });

    // Géocodage automatique de la ville
    const villeInput = document.getElementById('ville_localisation');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const loadingCoords = document.getElementById('loading-coords');
    let timeoutId;

    villeInput.addEventListener('input', function() {
        const ville = this.value.trim();
        
        // Effacer les coordonnées si la ville est vide
        if (!ville) {
            latitudeInput.value = '';
            longitudeInput.value = '';
            return;
        }

        // Annuler la requête précédente
        clearTimeout(timeoutId);
        
        // Attendre 1 seconde après la fin de la saisie
        timeoutId = setTimeout(() => {
            if (ville.length >= 3) {
                getCoordinatesFromCity(ville);
            }
        }, 1000);
    });

    function getCoordinatesFromCity(city) {
        // Afficher l'indicateur de chargement
        loadingCoords.classList.remove('hidden');
        
        // Utiliser l'API Nominatim (OpenStreetMap) - gratuite et sans clé API
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(city + ', France')}&limit=1`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                loadingCoords.classList.add('hidden');
                
                if (data && data.length > 0) {
                    const location = data[0];
                    latitudeInput.value = parseFloat(location.lat).toFixed(8);
                    longitudeInput.value = parseFloat(location.lon).toFixed(8);
                    
                    // Afficher un message de succès
                    showMessage('Coordonnées trouvées pour ' + city, 'success');
                } else {
                    // Essayer sans "France" si pas de résultat
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(city)}&limit=1`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                const location = data[0];
                                latitudeInput.value = parseFloat(location.lat).toFixed(8);
                                longitudeInput.value = parseFloat(location.lon).toFixed(8);
                                showMessage('Coordonnées trouvées pour ' + city, 'success');
                            } else {
                                showMessage('Aucune coordonnée trouvée pour ' + city, 'warning');
                            }
                        })
                        .catch(error => {
                            console.error('Erreur lors de la recherche:', error);
                            showMessage('Erreur lors de la recherche des coordonnées', 'error');
                        });
                }
            })
            .catch(error => {
                loadingCoords.classList.add('hidden');
                console.error('Erreur lors de la recherche:', error);
                showMessage('Erreur lors de la recherche des coordonnées', 'error');
            });
    }

    function showMessage(message, type) {
        // Supprimer les messages précédents
        const existingMessage = document.querySelector('.coords-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Créer le nouveau message
        const messageDiv = document.createElement('div');
        messageDiv.className = `coords-message p-2 rounded text-xs mt-1 ${
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
            type === 'warning' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
            'bg-red-100 text-red-800 border border-red-200'
        }`;
        messageDiv.textContent = message;
        
        // Insérer après le champ ville
        villeInput.parentNode.parentNode.appendChild(messageDiv);
        
        // Supprimer le message après 5 secondes
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }
});
</script>
@endsection
