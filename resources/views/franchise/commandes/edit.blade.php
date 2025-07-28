@extends('layouts.franchise')

@section('title', 'Modifier la Commande')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Modifier la Commande #{{ $commande->id }}</h1>
                <p class="text-black">{{ $commande->entrepot->nom }} - {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ route('franchise.commandes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('franchise.commandes.update', $commande) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations de la commande -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-black mb-4">Informations de la commande</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="entrepot_id" class="block text-sm font-medium text-black mb-2">Entrepôt</label>
                            <select id="entrepot_id" name="entrepot_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                                <option value="">Sélectionnez un entrepôt</option>
                                @foreach($entrepots as $entrepot)
                                <option value="{{ $entrepot->id }}" {{ $commande->entrepot_id == $entrepot->id ? 'selected' : '' }}>
                                    {{ $entrepot->nom }} - {{ $entrepot->ville }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-black mb-2">Notes (optionnel)</label>
                            <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Informations supplémentaires...">{{ old('notes', $commande->notes) }}</textarea>
                        </div>

                        <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                            <h4 class="text-sm font-medium text-black mb-2">
                                <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                                Règle 80/20
                            </h4>
                            <div class="text-xs text-black space-y-1">
                                <p>• 80% de produits obligatoires</p>
                                <p>• 20% de produits au choix</p>
                                <p>• Les produits marqués "Obligatoire" sont imposés</p>
                            </div>
                        </div>

                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <h4 class="text-sm font-medium text-black mb-2">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i>
                                Attention
                            </h4>
                            <div class="text-xs text-black space-y-1">
                                <p>• Seules les commandes en attente peuvent être modifiées</p>
                                <p>• Les produits obligatoires ne peuvent pas être supprimés</p>
                                <p>• La règle 80/20 doit être respectée</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits actuels (lecture seule) -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-black mb-4">Produits actuels</h3>
                    
                    @if($commande->produits->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Produit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Quantité</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix unitaire</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Type</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($commande->produits as $produit)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-black">{{ $produit->nom }}</div>
                                                <div class="text-sm text-gray-500">{{ $produit->description }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                            {{ $produit->pivot->quantite ?? 0 }} {{ $produit->unite_mesure }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                            {{ number_format($produit->pivot->prix_unitaire ?? 0, 2, ',', ' ') }} €
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                            {{ number_format($produit->pivot->prix_total ?? 0, 2, ',', ' ') }} €
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $produit->obligatoire ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $produit->obligatoire ? 'Obligatoire' : 'Libre' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Résumé de la commande -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-lg font-medium text-black mb-4">Résumé de la commande</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-black">Total obligatoire</p>
                                    <p class="text-lg font-semibold text-black">{{ number_format($commande->total_obligatoire, 2, ',', ' ') }} €</p>
                                </div>
                                <div>
                                    <p class="text-sm text-black">Total libre</p>
                                    <p class="text-lg font-semibold text-black">{{ number_format($commande->total_libre, 2, ',', ' ') }} €</p>
                                </div>
                                <div>
                                    <p class="text-sm text-black">Total général</p>
                                    <p class="text-lg font-semibold text-black">{{ number_format($commande->total_commande, 2, ',', ' ') }} €</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-black text-center py-4">Aucun produit dans cette commande</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('franchise.commandes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-300">
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