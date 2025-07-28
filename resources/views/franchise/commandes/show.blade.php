@extends('layouts.franchise')

@section('title', 'Détails de la Commande')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Commande #{{ $commande->id }}</h1>
                <p class="text-black">{{ $commande->entrepot->nom }} - {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($commande->statut === 'validee' || $commande->statut === 'livree')
                <a href="{{ route('franchise.commandes.download', $commande) }}" class="bg-green-600 hover:bg-green-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-download mr-2"></i>
                    Télécharger
                </a>
                @endif
                <a href="{{ route('franchise.commandes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations de la commande -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations de la commande</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-black">Statut</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $commande->statut === 'livree' ? 'bg-green-100 text-green-800' : 
                               ($commande->statut === 'validee' ? 'bg-blue-100 text-blue-800' : 
                               ($commande->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                            {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-black">Entrepôt</p>
                        </div>
                        <span class="text-sm text-black">{{ $commande->entrepot->nom }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-black">Date de commande</p>
                        </div>
                        <span class="text-sm text-black">{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}</span>
                    </div>

                    @if($commande->date_validation)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-black">Date de validation</p>
                        </div>
                        <span class="text-sm text-black">{{ \Carbon\Carbon::parse($commande->date_validation)->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif

                    @if($commande->date_livraison)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-black">Date de livraison</p>
                        </div>
                        <span class="text-sm text-black">{{ \Carbon\Carbon::parse($commande->date_livraison)->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif

                    @if($commande->notes)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-black mb-2">Notes</p>
                        <p class="text-sm text-black">{{ $commande->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Résumé financier -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Résumé financier</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-black">Total obligatoire</p>
                        </div>
                        <span class="text-sm font-medium text-black">{{ number_format($commande->total_obligatoire, 2, ',', ' ') }} €</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-black">Total libre</p>
                        </div>
                        <span class="text-sm font-medium text-black">{{ number_format($commande->total_libre, 2, ',', ' ') }} €</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border-2 border-green-200">
                        <div>
                            <p class="text-sm font-medium text-black">Total général</p>
                        </div>
                        <span class="text-lg font-bold text-black">{{ number_format($commande->total_commande, 2, ',', ' ') }} €</span>
                    </div>
                </div>

                <!-- Vérification 80/20 -->
                <div class="mt-4 p-3 {{ $commande->total_obligatoire > 0 && ($commande->total_obligatoire / $commande->total_commande) >= 0.8 ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }} rounded-lg">
                    <div class="flex items-center">
                        <i class="fas {{ $commande->total_obligatoire > 0 && ($commande->total_obligatoire / $commande->total_commande) >= 0.8 ? 'fa-check-circle text-green-600' : 'fa-exclamation-triangle text-red-600' }} mr-2"></i>
                        <div>
                            <p class="text-sm font-medium text-black">Règle 80/20</p>
                            <p class="text-xs text-black">
                                @if($commande->total_obligatoire > 0)
                                    {{ number_format(($commande->total_obligatoire / $commande->total_commande) * 100, 1) }}% obligatoire
                                @else
                                    Aucun produit obligatoire
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits commandés -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Produits commandés</h3>
                
                @if($commande->produits->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Produit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Catégorie</th>
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
                                        {{ $produit->categorie_label }}
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
                @else
                    <p class="text-black text-center py-4">Aucun produit dans cette commande</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 
