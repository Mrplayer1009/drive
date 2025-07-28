@extends('layouts.admin')

@section('title', 'Gestion des Franchisés')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Gestion des Franchisés</h1>
                <p class="text-black">Gérez tous les franchisés Driv'n Cook</p>
            </div>
            <a href="{{ route('admin.franchises.create') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouveau franchisé
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
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                    <option value="suspendu">Suspendu</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Ville</label>
                <input type="text" placeholder="Rechercher par ville" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Date d'entrée</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Recherche</label>
                <input type="text" placeholder="Nom, email, téléphone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
        </div>
    </div>

    <!-- Liste des franchisés -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-black">Liste des franchisés</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Localisation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Camions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($franchises as $franchise)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                                        <i class="fas fa-user text-orange-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-black">{{ $franchise->nom_complet }}</div>
                                    <div class="text-sm text-black">Entré le {{ $franchise->date_entree->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-black">{{ $franchise->email }}</div>
                            <div class="text-sm text-black">{{ $franchise->telephone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-black">{{ $franchise->ville }}</div>
                            <div class="text-sm text-black">{{ $franchise->code_postal }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $franchise->statut === 'actif' ? 'bg-green-100 text-green-800' : 
                                   ($franchise->statut === 'inactif' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($franchise->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $franchise->camions_actifs }} camion(s)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.franchises.edit', $franchise) }}" class="text-orange-600 hover:text-orange-700 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.franchises.show', $franchise) }}" class="text-blue-600 hover:text-blue-700 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($franchise->statut === 'inactif')
                            <form action="{{ route('admin.franchises.activate', $franchise) }}" method="POST" class="inline">
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
                        <td colspan="6" class="px-6 py-4 text-center text-black">Aucun franchisé trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($franchises->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $franchises->links() }}
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Total franchisés</p>
                    <p class="text-2xl font-semibold text-black">{{ $franchises->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-check-circle text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Actifs</p>
                    <p class="text-2xl font-semibold text-black">{{ $franchises->where('statut', 'actif')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-clock text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">En attente</p>
                    <p class="text-2xl font-semibold text-black">{{ $franchises->where('statut', 'inactif')->count() }}</p>
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
                    <p class="text-sm font-medium text-black">Droits d'entrée</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($franchises->sum('droits_entree'), 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
