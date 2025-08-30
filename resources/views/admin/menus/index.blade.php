@extends('layouts.admin')

@section('title', 'Gestion des Menus')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Gestion des Menus</h1>
                <p class="text-black">Gérez le catalogue des menus Driv'n Cook</p>
            </div>
            <a href="{{ route('admin.menus.create') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouveau menu
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
                    <option value="burger">Burgers</option>
                    <option value="boisson">Boissons</option>
                    <option value="dessert">Desserts</option>
                    <option value="accompagnement">Accompagnements</option>
                    <option value="entree">Entrées</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Disponibilité</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous</option>
                    <option value="1">Disponible</option>
                    <option value="0">Non disponible</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Prix minimum</label>
                <input type="number" step="0.01" placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Recherche</label>
                <input type="text" placeholder="Nom du menu" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-black">Liste des menus</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Menu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Ordre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($menus as $menu)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->nom }}" class="h-12 w-12 rounded-lg object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <i class="fas fa-utensils text-orange-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-black">{{ $menu->nom }}</div>
                                    <div class="text-sm text-black">{{ Str::limit($menu->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $menu->categorie === 'burger' ? 'bg-orange-100 text-orange-800' : 
                                   ($menu->categorie === 'boisson' ? 'bg-blue-100 text-blue-800' : 
                                   ($menu->categorie === 'dessert' ? 'bg-pink-100 text-pink-800' : 
                                   ($menu->categorie === 'accompagnement' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'))) }}">
                                {{ ucfirst($menu->categorie) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $menu->prix_formate }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                                <span class="ml-1 px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    Spécial
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $menu->ordre_affichage }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.menus.show', $menu) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.menus.edit', $menu) }}" class="text-orange-600 hover:text-orange-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce menu ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-black">
                            Aucun menu trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $menus->links() }}
        </div>
    </div>
</div>
@endsection


