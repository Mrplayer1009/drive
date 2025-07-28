@extends('layouts.admin')

@section('title', 'Modifier le Camion')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Modifier le Camion</h1>
                <p class="text-black">{{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }}</p>
            </div>
            <a href="{{ route('admin.camions.show', $camion) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.camions.update', $camion) }}" method="POST">
        @csrf
        @method('PUT')
        
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
                    <label for="immatriculation" class="block text-sm font-medium text-black mb-2">Immatriculation</label>
                    <input type="text" id="immatriculation" name="immatriculation" value="{{ old('immatriculation', $camion->immatriculation) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="marque" class="block text-sm font-medium text-black mb-2">Marque</label>
                        <input type="text" id="marque" name="marque" value="{{ old('marque', $camion->marque) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="modele" class="block text-sm font-medium text-black mb-2">Modèle</label>
                        <input type="text" id="modele" name="modele" value="{{ old('modele', $camion->modele) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="annee" class="block text-sm font-medium text-black mb-2">Année</label>
                    <input type="number" id="annee" name="annee" value="{{ old('annee', $camion->annee) }}" min="1900" max="{{ date('Y') + 1 }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="statut" class="block text-sm font-medium text-black mb-2">Statut</label>
                    <select id="statut" name="statut" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="disponible" {{ old('statut', $camion->statut) === 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="en_utilisation" {{ old('statut', $camion->statut) === 'en_utilisation' ? 'selected' : '' }}>En utilisation</option>
                        <option value="en_maintenance" {{ old('statut', $camion->statut) === 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                        <option value="hors_service" {{ old('statut', $camion->statut) === 'hors_service' ? 'selected' : '' }}>Hors service</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="ville_localisation" class="block text-sm font-medium text-black mb-2">Ville de localisation</label>
                    <input type="text" id="ville_localisation" name="ville_localisation" value="{{ old('ville_localisation', $camion->ville_localisation) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-black mb-2">Latitude</label>
                        <input type="number" id="latitude" name="latitude" value="{{ old('latitude', $camion->latitude) }}" step="0.00000001" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-black mb-2">Longitude</label>
                        <input type="number" id="longitude" name="longitude" value="{{ old('longitude', $camion->longitude) }}" step="0.00000001" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-black mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('notes', $camion->notes) }}</textarea>
                </div>
            </div>

            <!-- Maintenance -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Maintenance</h3>
                
                <div class="mb-4">
                    <label for="derniere_maintenance" class="block text-sm font-medium text-black mb-2">Dernière maintenance</label>
                    <input type="date" id="derniere_maintenance" name="derniere_maintenance" value="{{ old('derniere_maintenance', $camion->derniere_maintenance ? $camion->derniere_maintenance->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="prochaine_maintenance" class="block text-sm font-medium text-black mb-2">Prochaine maintenance</label>
                    <input type="date" id="prochaine_maintenance" name="prochaine_maintenance" value="{{ old('prochaine_maintenance', $camion->prochaine_maintenance ? $camion->prochaine_maintenance->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <h4 class="text-sm font-medium text-black mb-2">
                        <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                        Informations importantes
                    </h4>
                    <div class="text-xs text-black space-y-1">
                        <p>• Le statut "En utilisation" indique qu'un franchisé utilise le camion</p>
                        <p>• Le statut "En maintenance" empêche toute utilisation</p>
                        <p>• Les coordonnées GPS permettent le suivi en temps réel</p>
                    </div>
                </div>

                <!-- Statistiques actuelles -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Statistiques actuelles</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-black">Ventes totales :</span>
                            <span class="text-sm font-medium text-black">{{ $camion->ventes->count() }}</span>
                        </div>
                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-black">Montant total :</span>
                            <span class="text-sm font-medium text-black">{{ number_format($camion->ventes->sum('montant_total'), 2, ',', ' ') }} €</span>
                        </div>
                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-black">Franchisé actuel :</span>
                            <span class="text-sm font-medium text-black">{{ $camion->franchise_actuel ? $camion->franchise_actuel->nom_complet : 'Aucun' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.camions.show', $camion) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-300">
                Annuler
            </a>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition duration-300">
                <i class="fas fa-save mr-2"></i>
                Sauvegarder les modifications
            </button>
        </div>
    </form>
</div>
@endsection 