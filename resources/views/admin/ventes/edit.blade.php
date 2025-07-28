@extends('layouts.admin')

@section('title', 'Modifier la Vente')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Modifier la Vente</h1>
                <p class="text-black">Vente #{{ $vente->id }} - {{ $vente->franchise->nom_complet }}</p>
            </div>
            <a href="{{ route('admin.ventes.show', $vente) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.ventes.update', $vente) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations de la vente -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations de la vente</h3>
                
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
                    <label for="franchise_id" class="block text-sm font-medium text-black mb-2">Franchisé</label>
                    <select id="franchise_id" name="franchise_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        @foreach($franchises as $franchise)
                            <option value="{{ $franchise->id }}" {{ $vente->franchise_id == $franchise->id ? 'selected' : '' }}>
                                {{ $franchise->nom_complet }} ({{ $franchise->ville }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="camion_id" class="block text-sm font-medium text-black mb-2">Camion (optionnel)</label>
                    <select id="camion_id" name="camion_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Aucun camion</option>
                        @foreach($camions as $camion)
                            <option value="{{ $camion->id }}" {{ $vente->camion_id == $camion->id ? 'selected' : '' }}>
                                {{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="date_vente" class="block text-sm font-medium text-black mb-2">Date de vente</label>
                    <input type="date" id="date_vente" name="date_vente" value="{{ old('date_vente', $vente->date_vente->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="nombre_commandes" class="block text-sm font-medium text-black mb-2">Nombre de commandes</label>
                    <input type="number" id="nombre_commandes" name="nombre_commandes" value="{{ old('nombre_commandes', $vente->nombre_commandes) }}" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-black mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('notes', $vente->notes) }}</textarea>
                </div>
            </div>

            <!-- Montants -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Montants</h3>
                
                <div class="mb-4">
                    <label for="montant_total" class="block text-sm font-medium text-black mb-2">Montant total (€)</label>
                    <input type="number" id="montant_total" name="montant_total" value="{{ old('montant_total', $vente->montant_total) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <h4 class="text-sm font-medium text-black mb-2">
                        <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                        Calcul automatique
                    </h4>
                    <div class="text-xs text-black space-y-1">
                        <p>• Le montant reversé sera calculé automatiquement</p>
                        <p>• Basé sur le pourcentage du franchisé sélectionné</p>
                        <p>• Le montant net = montant total - montant reversé</p>
                    </div>
                </div>

                <!-- Affichage des montants actuels -->
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Montant reversé actuel :</span>
                        <span class="text-sm font-medium text-black">{{ $vente->montant_reverse_formate }}</span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-black">Montant net actuel :</span>
                        <span class="text-sm font-medium text-black">{{ number_format($vente->montant_total - $vente->montant_reverse, 2, ',', ' ') }} €</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.ventes.show', $vente) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-300">
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