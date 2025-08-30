@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <i class="fas fa-users text-orange-600 mr-2"></i>
            Gestion des Commandes Clients
        </h1>
        <p class="text-gray-600">Gestion centralisée de toutes les commandes clients</p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-list text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">En attente</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['en_attente'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Confirmées</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['confirmee'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-utensils text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">En préparation</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['en_preparation'] }}</p>
                </div>
            </div>
        </div>
        

        
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-home text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Prêtes</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['prete'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-check-circle text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Terminées</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['terminee'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times text-red-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Annulées</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['annulee'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche (toujours visibles) -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Filtres et recherche</h2>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="flex items-center space-x-4">
                <!-- Barre de recherche -->
                <form method="GET" action="{{ route('admin.commandes-clients.index') }}" class="flex items-center space-x-2">
                    <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Email ou téléphone..." class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded-md text-sm transition duration-300">
                        <i class="fas fa-search mr-1"></i>
                        Rechercher
                    </button>
                    @if(request('recherche'))
                        <a href="{{ route('admin.commandes-clients.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                            <i class="fas fa-times mr-1"></i>
                            Effacer
                        </a>
                    @endif
                </form>
                
                <!-- Sélecteur de tri -->
                <form method="GET" action="{{ route('admin.commandes-clients.index') }}" class="flex items-center space-x-2">
                    @if(request('recherche'))
                        <input type="hidden" name="recherche" value="{{ request('recherche') }}">
                    @endif
                    <label for="tri" class="text-sm font-medium text-gray-700">Trier par :</label>
                                                 <select id="tri" name="tri" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                 <option value="date_desc" {{ request('tri') == 'date_desc' ? 'selected' : '' }}>Date (récent)</option>
                                 <option value="email_asc" {{ request('tri') == 'email_asc' ? 'selected' : '' }}>Email (A-Z)</option>
                                 <option value="email_desc" {{ request('tri') == 'email_desc' ? 'selected' : '' }}>Email (Z-A)</option>
                                 <option value="statut_asc" {{ request('tri') == 'statut_asc' ? 'selected' : '' }}>Statut (A-Z)</option>
                                 <option value="statut_desc" {{ request('tri') == 'statut_desc' ? 'selected' : '' }}>Statut (Z-A)</option>
                             </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des commandes -->
    @if($commandes->count() > 0)
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    Toutes les commandes clients
                    @if(request('recherche'))
                        <span class="text-sm font-normal text-gray-500">({{ $commandes->total() }} résultat(s) pour "{{ request('recherche') }}")</span>
                    @endif
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Commande
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Franchise
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Montant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($commandes as $commande)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $commande->id }}</div>
                                <div class="text-sm text-gray-500">{{ $commande->menus->count() }} article(s)</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $commande->client->nom }} {{ $commande->client->prenom }}</div>
                                <div class="text-sm text-gray-500">{{ $commande->client->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $commande->franchise->nom }} {{ $commande->franchise->prenom }}</div>
                                <div class="text-sm text-gray-500">{{ $commande->franchise->ville }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $commande->date_commande->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $commande->date_commande->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($commande->montant_final, 2, ',', ' ') }} €</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($commande->statut)
                                        @case('en_attente')
                                            bg-yellow-100 text-yellow-800
                                            @break
                                        @case('confirmee')
                                            bg-blue-100 text-blue-800
                                            @break
                                        @case('en_preparation')
                                            bg-orange-100 text-orange-800
                                            @break
                                        @case('prete')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('terminee')
                                            bg-blue-100 text-blue-800
                                            @break
                                        @case('annulee')
                                            bg-red-100 text-red-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ ucfirst($commande->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.commandes-clients.show', $commande) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye mr-1"></i>
                                    Voir
                                </a>
                                @if(in_array($commande->statut, ['en_attente', 'confirmee']))
                                    <form method="POST" action="{{ route('admin.commandes-clients.destroy', $commande) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">
                                            <i class="fas fa-trash mr-1"></i>
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($commandes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $commandes->links() }}
            </div>
            @endif
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-6 text-center">
            @if(request('recherche'))
                <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun résultat trouvé</h3>
                <p class="text-gray-500 mb-4">Aucune commande client ne correspond à votre recherche "{{ request('recherche') }}".</p>
                <a href="{{ route('admin.commandes-clients.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-times mr-2"></i>
                    Effacer la recherche
                </a>
            @else
                <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande client</h3>
                <p class="text-gray-500">Aucune commande client n'a été trouvée.</p>
            @endif
        </div>
    @endif
</div>
@endsection
