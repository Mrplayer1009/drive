@extends('layouts.admin')

@section('title', 'Traiter la Panne')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Traiter la Panne #{{ $notification->id }}</h1>
                <p class="text-black">Camion : {{ $notification->camion->immatriculation }} ({{ $notification->camion->marque }} {{ $notification->camion->modele }})</p>
            </div>
            <a href="{{ route('admin.notifications-pannes.show', $notification) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Formulaire de traitement -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Traitement de la panne</h3>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.notifications-pannes.update', $notification) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="statut" class="block text-sm font-medium text-black mb-2">Nouveau statut</label>
                    <select id="statut" name="statut" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="signalee" {{ $notification->statut === 'signalee' ? 'selected' : '' }}>Signalée</option>
                        <option value="en_maintenance" {{ $notification->statut === 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                        <option value="resolue" {{ $notification->statut === 'resolue' ? 'selected' : '' }}>Résolue</option>
                        <option value="ignoree" {{ $notification->statut === 'ignoree' ? 'selected' : '' }}>Ignorée</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="commentaire_admin" class="block text-sm font-medium text-black mb-2">Commentaire admin</label>
                    <textarea id="commentaire_admin" name="commentaire_admin" rows="4" placeholder="Ajoutez un commentaire sur le traitement de cette panne..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('commentaire_admin', $notification->commentaire_admin) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="mettre_camion_maintenance" class="flex items-center">
                        <input type="checkbox" id="mettre_camion_maintenance" name="mettre_camion_maintenance" value="1" {{ $notification->camion->statut === 'en_maintenance' ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Mettre le camion en maintenance</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label for="attribuer_remplacement" class="flex items-center">
                        <input type="checkbox" id="attribuer_remplacement" name="attribuer_remplacement" value="1" class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Attribuer un camion de remplacement au franchisé</span>
                    </label>
                </div>

                <!-- SChoisir le camion -->
                <div id="selection_camion_remplacement" class="mb-4 hidden">
                    <label for="camion_remplacement" class="block text-sm font-medium text-black mb-2">Camion de remplacement <span class="text-red-500">*</span></label>
                    <select id="camion_remplacement" name="camion_remplacement" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Choisir un camion...</option>
                        @php
                            $camionsDisponibles = \App\Models\Camion::where('statut', 'disponible')->get();
                            $camionsProches = $camionsDisponibles->filter(function($camion) use ($notification) {
                                return $camion->ville_localisation === $notification->franchise->ville;
                            });
                        @endphp
                        
                        @if($camionsProches->count() > 0)
                            <optgroup label="Camions disponibles dans la même ville ({{ $notification->franchise->ville }})">
                                @foreach($camionsProches as $camion)
                                <option value="{{ $camion->id }}">
                                    {{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }} ({{ $camion->ville_localisation }})
                                </option>
                                @endforeach
                            </optgroup>
                        @endif
                        
                        @if($camionsDisponibles->count() > 0)
                            <optgroup label="Tous les camions disponibles">
                                @foreach($camionsDisponibles as $camion)
                                <option value="{{ $camion->id }}">
                                    {{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }} ({{ $camion->ville_localisation }})
                                </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    
                    @if($camionsDisponibles->count() === 0)
                        <p class="text-sm text-red-600 mt-2">Aucun camion disponible actuellement</p>
                    @endif
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.notifications-pannes.show', $notification) }}" class="btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer le traitement
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations de la panne -->
        <div class="space-y-6">
            <!-- Détails de la panne -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Détails de la panne</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Type :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->type_panne_label }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Gravité :</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $notification->gravite_color }}">
                            {{ $notification->gravite_label }}
                        </span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Signalée le :</span>
                        <span class="text-sm font-medium text-black">{{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="p-2 bg-gray-50 rounded">
                        <span class="text-sm font-medium text-black block mb-1">Description :</span>
                        <p class="text-sm text-black">{{ $notification->description_panne }}</p>
                    </div>
                    <div class="p-2 bg-gray-50 rounded">
                        <span class="text-sm font-medium text-black block mb-1">Symptômes :</span>
                        <p class="text-sm text-black">{{ $notification->symptomes }}</p>
                    </div>
                </div>
            </div>

            <!-- Informations du franchisé -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Franchisé</h3>
                
                <div class="space-y-2">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Nom :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->franchise->nom_complet }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Email :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->franchise->email }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Téléphone :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->franchise->telephone }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Ville :</span>
                        <span class="text-sm font-medium text-black">{{ $notification->franchise->ville }}</span>
                    </div>
                </div>
            </div>

            <!-- Camions disponibles pour remplacement -->
            @if($notification->gravite === 'critique' || $notification->gravite === 'grave')
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Camions disponibles pour remplacement</h3>
                
                @php
                    $camionsDisponibles = \App\Models\Camion::where('statut', 'disponible')->get();
                    $camionsProches = $camionsDisponibles->filter(function($camion) use ($notification) {
                        return $camion->ville_localisation === $notification->franchise->ville;
                    });
                @endphp
                
                @if($camionsProches->count() > 0)
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-black mb-2">Camions dans la même ville ({{ $notification->franchise->ville }})</h4>
                    <div class="space-y-2">
                        @foreach($camionsProches as $camionDisponible)
                        <div class="flex justify-between items-center p-2 bg-green-50 rounded border border-green-200">
                            <div>
                                <span class="text-sm font-medium text-black">{{ $camionDisponible->immatriculation }}</span>
                                <br>
                                <span class="text-xs text-gray-600">{{ $camionDisponible->marque }} {{ $camionDisponible->modele }}</span>
                            </div>
                            <span class="text-xs text-green-600 font-medium">Proche</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($camionsDisponibles->count() > 0)
                <div>
                    <h4 class="text-sm font-medium text-black mb-2">Tous les camions disponibles</h4>
                    <div class="space-y-2">
                        @foreach($camionsDisponibles as $camionDisponible)
                        <div class="flex justify-between items-center p-2 bg-blue-50 rounded border border-blue-200">
                            <div>
                                <span class="text-sm font-medium text-black">{{ $camionDisponible->immatriculation }}</span>
                                <br>
                                <span class="text-xs text-gray-600">{{ $camionDisponible->marque }} {{ $camionDisponible->modele }} ({{ $camionDisponible->ville_localisation }})</span>
                            </div>
                            <span class="text-xs text-blue-600 font-medium">Disponible</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="p-3 bg-yellow-50 rounded border border-yellow-200">
                    <p class="text-sm text-yellow-800">Aucun camion disponible actuellement</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statutSelect = document.getElementById('statut');
    const mettreMaintenanceCheckbox = document.getElementById('mettre_camion_maintenance');
    const attribuerRemplacementCheckbox = document.getElementById('attribuer_remplacement');
    const selectionCamionDiv = document.getElementById('selection_camion_remplacement');
    const camionRemplacementSelect = document.getElementById('camion_remplacement');
    
    // Si on met en maintenance, cocher automatiquement la case
    statutSelect.addEventListener('change', function() {
        if (this.value === 'en_maintenance') {
            mettreMaintenanceCheckbox.checked = true;
        }
    });
    
    // Afficher/masquer la sélection de camion de remplacement
    attribuerRemplacementCheckbox.addEventListener('change', function() {
        if (this.checked) {
            selectionCamionDiv.classList.remove('hidden');
            camionRemplacementSelect.required = true;
        } else {
            selectionCamionDiv.classList.add('hidden');
            camionRemplacementSelect.required = false;
            camionRemplacementSelect.value = '';
        }
    });
});
</script>
@endsection 
