@extends('layouts.admin')

@section('title', 'Gestion des Ventes')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Gestion des Ventes</h1>
                <p class="text-black">Supervisez toutes les ventes Driv'n Cook</p>
            </div>
            <a href="{{ route('admin.ventes.create') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle vente
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Filtres</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-black mb-2">Période</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Toutes les périodes</option>
                    <option value="aujourdhui">Aujourd'hui</option>
                    <option value="semaine">Cette semaine</option>
                    <option value="mois">Ce mois</option>
                    <option value="trimestre">Ce trimestre</option>
                    <option value="annee">Cette année</option>
                </select>
            </div>
            <div>
                <label for="franchise" class="block text-sm font-medium text-black mb-2">Franchisé</label>
                <input type="text" id="franchise" name="franchise" value="{{ request('franchise') }}" placeholder="Nom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Date de début</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Date de fin</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
        </div>
    </div>

    <!-- Liste des ventes -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-black">Liste des Ventes</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                            Franchisé
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                            Camion
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                            Commandes
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                            Montant Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                            Montant Reversé
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ventes as $vente)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $vente->date_vente->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $vente->franchise->nom_complet }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $vente->camion ? $vente->camion->immatriculation : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($vente->commande_client_id)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-shopping-cart mr-1"></i>
                                    Commande Client
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-hand-holding-usd mr-1"></i>
                                    Manuelle
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $vente->nombre_commandes }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $vente->montant_total_formate }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <span class="text-green-600">{{ $vente->montant_reverse_formate }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.ventes.show', $vente) }}" class="text-orange-600 hover:text-orange-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.ventes.edit', $vente) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.ventes.download', $vente) }}" class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form action="{{ route('admin.ventes.destroy', $vente) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')">
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
                        <td colspan="8" class="px-6 py-4 text-center text-black">
                            Aucune vente trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $ventes->links() }}
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-euro-sign text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Total des ventes</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($ventes->sum('montant_total'), 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Montant reversé</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($ventes->sum('montant_reverse'), 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Nombre de ventes</p>
                    <p class="text-2xl font-semibold text-black">{{ $ventes->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Franchisés actifs</p>
                    <p class="text-2xl font-semibold text-black">{{ $ventes->unique('franchise_id')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des ventes -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Évolution des ventes</h3>
        <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
            <p class="text-black">Graphique des ventes (à implémenter)</p>
        </div>
    </div>
</div>
@endsection 
