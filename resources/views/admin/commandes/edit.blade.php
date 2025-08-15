@extends('layouts.admin')

@section('title', 'Modifier la Commande')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Modifier la Commande</h1>
                <p class="text-black">Commande #{{ $commande->id }} - {{ $commande->franchise->nom_complet }}</p>
            </div>
            <a href="{{ route('admin.commandes.show', $commande) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.commandes.update', $commande) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations de la commande -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations de la commande</h3>
                
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
                            <option value="{{ $franchise->id }}" {{ $commande->franchise_id == $franchise->id ? 'selected' : '' }}>
                                {{ $franchise->nom_complet }} ({{ $franchise->ville }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="entrepot_id" class="block text-sm font-medium text-black mb-2">Entrepôt</label>
                    <select id="entrepot_id" name="entrepot_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        @foreach($entrepots as $entrepot)
                            <option value="{{ $entrepot->id }}" {{ $commande->entrepot_id == $entrepot->id ? 'selected' : '' }}>
                                {{ $entrepot->nom }} ({{ $entrepot->ville }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="date_commande" class="block text-sm font-medium text-black mb-2">Date de commande</label>
                    <input type="date" id="date_commande" name="date_commande" value="{{ old('date_commande', $commande->date_commande->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="statut" class="block text-sm font-medium text-black mb-2">Statut</label>
                    <select id="statut" name="statut" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="en_attente" {{ old('statut', $commande->statut) === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="validee" {{ old('statut', $commande->statut) === 'validee' ? 'selected' : '' }}>Validée</option>
                        <option value="refusee" {{ old('statut', $commande->statut) === 'refusee' ? 'selected' : '' }}>Refusée</option>
                        <option value="livree" {{ old('statut', $commande->statut) === 'livree' ? 'selected' : '' }}>Livrée</option>
                    </select>
                </div>



                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-black mb-2">Notes (franchisé)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('notes', $commande->notes) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="notes_admin" class="block text-sm font-medium text-black mb-2">Notes administrateur</label>
                    <textarea id="notes_admin" name="notes_admin" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Notes internes pour l'équipe admin...">{{ old('notes_admin', $commande->notes_admin) }}</textarea>
                </div>
            </div>

            <!-- Produits -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Produits de la commande</h3>
                
                <div class="p-4 bg-orange-50 rounded-lg border border-orange-200 mb-4">
                    <h4 class="text-sm font-medium text-black mb-2">
                        <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                        Règle 80/20
                    </h4>
                    <div class="text-xs text-black space-y-1">
                        <p>• 80% minimum de produits obligatoires</p>
                        <p>• 20% maximum de produits libres</p>
                        <p>• La modification des produits n'est pas possible ici</p>
                    </div>
                </div>

                <!-- Liste des produits actuels -->
                <div class="space-y-3">
                    @foreach($commande->produits as $produit)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-black">{{ $produit->nom }}</p>
                            <p class="text-xs text-gray-500">{{ $produit->pivot->quantite ?? 0 }} {{ $produit->unite_mesure }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-black">{{ number_format($produit->pivot->prix_total ?? 0, 2, ',', ' ') }} €</p>
                            @if($produit->obligatoire)
                                <span class="text-xs text-red-600">Obligatoire</span>
                            @else
                                <span class="text-xs text-gray-600">Libre</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-black">Total de la commande</span>
                        <span class="text-lg font-bold text-black">{{ $commande->total_formate }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.commandes.show', $commande) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-6 py-2 rounded-lg transition duration-300">
                Annuler
            </a>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-6 py-2 rounded-lg transition duration-300">
                <i class="fas fa-save mr-2"></i>
                Sauvegarder les modifications
            </button>
        </div>
    </form>
</div>
@endsection 
