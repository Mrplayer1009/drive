@extends('layouts.admin')

@section('title', 'Gestion des Produits')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Gestion des Produits</h1>
                <p class="text-black">Gérez le catalogue des produits Driv'n Cook</p>
            </div>
            <a href="{{ route('admin.produits.create') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouveau produit
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Filtres</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-black mb-2">Catégorie</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Toutes les catégories</option>
                    <option value="ingredients">Ingrédients</option>
                    <option value="plats">Plats</option>
                    <option value="boissons">Boissons</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Obligatoire</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous</option>
                    <option value="1">Obligatoire</option>
                    <option value="0">Optionnel</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Prix minimum</label>
                <input type="number" step="0.01" placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Recherche</label>
                <input type="text" placeholder="Nom du produit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
        </div>
    </div>

    <!-- Liste des produits -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-black">Liste des produits</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Unité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Obligatoire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($produits as $produit)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                                        <i class="fas fa-box text-orange-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-black">{{ $produit->nom }}</div>
                                    <div class="text-sm text-black">{{ Str::limit($produit->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $produit->categorie === 'ingredients' ? 'bg-blue-100 text-blue-800' : 
                                   ($produit->categorie === 'plats' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ $produit->categorie_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $produit->prix_formate }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $produit->unite_mesure }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($produit->obligatoire)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    Obligatoire
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                    Optionnel
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.produits.edit', $produit) }}" class="text-orange-600 hover:text-orange-700 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.produits.show', $produit) }}" class="text-blue-600 hover:text-blue-700 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.produits.destroy', $produit) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-black">Aucun produit trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($produits->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $produits->links() }}
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-box text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Total produits</p>
                    <p class="text-2xl font-semibold text-black">{{ $produits->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-utensils text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Ingrédients</p>
                    <p class="text-2xl font-semibold text-black">{{ $produits->where('categorie', 'ingredients')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Obligatoires</p>
                    <p class="text-2xl font-semibold text-black">{{ $produits->where('obligatoire', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-euro-sign text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Prix moyen</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($produits->avg('prix_unitaire'), 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
