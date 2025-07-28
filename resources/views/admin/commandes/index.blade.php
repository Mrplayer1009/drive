@extends('layouts.admin')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Gestion des Commandes</h1>
                <p class="text-black">Supervisez toutes les commandes de stock</p>
            </div>
            <a href="{{ route('admin.commandes.create') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle commande
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Filtres</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-black mb-2">Statut</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente">En attente</option>
                    <option value="validee">Validée</option>
                    <option value="livree">Livrée</option>
                    <option value="annulee">Annulée</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Franchisé</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les franchisés</option>
                    @foreach($franchises as $franchise)
                    <option value="{{ $franchise->id }}">{{ $franchise->nom_complet }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Entrepôt</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les entrepôts</option>
                    @foreach($entrepots as $entrepot)
                    <option value="{{ $entrepot->id }}">{{ $entrepot->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Date</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-black">Liste des commandes</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">N° Commande</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Entrepôt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($commandes as $commande)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black">
                            #{{ $commande->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $commande->franchise->nom_complet }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $commande->entrepot->nom }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $commande->date_commande->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $commande->total_formate }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $commande->statut === 'livree' ? 'bg-green-100 text-green-800' : 
                                   ($commande->statut === 'validee' ? 'bg-blue-100 text-blue-800' : 
                                   ($commande->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                {{ $commande->statut_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.commandes.show', $commande) }}" class="text-blue-600 hover:text-blue-700 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.commandes.edit', $commande) }}" class="text-orange-600 hover:text-orange-700 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($commande->statut === 'en_attente')
                            <form action="{{ route('admin.commandes.validate', $commande) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-700 mr-3">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-black">Aucune commande trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($commandes->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $commandes->links() }}
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-clock text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">En attente</p>
                    <p class="text-2xl font-semibold text-black">{{ $commandes->where('statut', 'en_attente')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-check text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Validées</p>
                    <p class="text-2xl font-semibold text-black">{{ $commandes->where('statut', 'validee')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-truck text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Livrées</p>
                    <p class="text-2xl font-semibold text-black">{{ $commandes->where('statut', 'livree')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-euro-sign text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Total commandes</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($commandes->sum('total_commande'), 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
