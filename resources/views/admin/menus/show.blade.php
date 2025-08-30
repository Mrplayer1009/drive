@extends('layouts.admin')

@section('title', 'Détails du Menu')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Détails du Menu</h1>
                <p class="text-black">{{ $menu->nom }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.menus.edit', $menu) }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.menus.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations du menu -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations du menu</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-black mb-1">Nom</label>
                    <p class="text-black">{{ $menu->nom }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-1">Description</label>
                    <p class="text-black">{{ $menu->description }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-1">Catégorie</label>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $menu->categorie === 'burger' ? 'bg-orange-100 text-orange-800' : 
                           ($menu->categorie === 'boisson' ? 'bg-blue-100 text-blue-800' : 
                           ($menu->categorie === 'dessert' ? 'bg-pink-100 text-pink-800' : 
                           ($menu->categorie === 'accompagnement' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'))) }}">
                        {{ ucfirst($menu->categorie) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-1">Prix</label>
                    <p class="text-2xl font-bold text-orange-600">{{ $menu->prix_formate }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-1">Ordre d'affichage</label>
                    <p class="text-black">{{ $menu->ordre_affichage }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-1">Statut</label>
                    <div class="flex space-x-2">
                        @if($menu->disponible)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                Disponible
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                Non disponible
                            </span>
                        @endif
                        
                        @if($menu->special)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                Spécial
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-1">Date de création</label>
                    <p class="text-black">{{ $menu->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-black mb-1">Dernière modification</label>
                    <p class="text-black">{{ $menu->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Image du menu -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Image du menu</h3>
            
            @if($menu->image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->nom }}" class="w-full h-64 object-cover rounded-lg">
                </div>
            @else
                <div class="flex items-center justify-center h-64 bg-gray-100 rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-utensils text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Aucune image</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistiques -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Statistiques des commandes</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-orange-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-shopping-cart text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-black">Commandes totales</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $menu->commandes->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-cubes text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-black">Quantité totale</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $menu->commandes->sum('pivot.quantite') }} unités</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-euro-sign text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-black">Valeur totale</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($menu->commandes->sum('pivot.prix_total'), 2, ',', ' ') }} €</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des commandes -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Historique des commandes</h3>
        
        @if($menu->commandes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Commande</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix unitaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($menu->commandes as $commande)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                #{{ $commande->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $commande->client->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $commande->pivot->quantite }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ number_format($commande->pivot->prix_unitaire, 2, ',', ' ') }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ number_format($commande->pivot->prix_total, 2, ',', ' ') }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $commande->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">Aucune commande pour ce menu</p>
            </div>
        @endif
    </div>
</div>
@endsection


