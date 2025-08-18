@extends('layouts.admin')

@section('title', 'Gestion des Camions')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Gestion des Camions</h1>
                <p class="text-black">Gérez la flotte de camions Driv'n Cook</p>
            </div>
            <a href="{{ route('admin.camions.create') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouveau camion
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Filtres</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="statut" class="block text-sm font-medium text-black mb-2">Statut</label>
                <select id="statut" name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les statuts</option>
                    <option value="disponible" {{ request('statut') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="en_utilisation" {{ request('statut') === 'en_utilisation' ? 'selected' : '' }}>En utilisation</option>
                    <option value="en_maintenance" {{ request('statut') === 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                    <option value="hors_service" {{ request('statut') === 'hors_service' ? 'selected' : '' }}>Hors service</option>
                </select>
            </div>
            <div>
                <label for="franchise" class="block text-sm font-medium text-black mb-2">Franchisé</label>
                <input type="text" id="franchise" name="franchise" value="{{ request('franchise') }}" placeholder="Nom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label for="localisation" class="block text-sm font-medium text-black mb-2">Localisation</label>
                <input type="text" id="localisation" name="localisation" value="{{ request('localisation') }}" placeholder="Ville..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label for="annee" class="block text-sm font-medium text-black mb-2">Année</label>
                <input type="number" id="annee" name="annee" value="{{ request('annee') }}" placeholder="Année..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="md:col-span-4 flex space-x-2">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-search mr-2"></i>
                    Filtrer
                </button>
                <a href="{{ route('admin.camions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-times mr-2"></i>
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des camions -->
    <div class="bg-white shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Camion</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Localisation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé assigné</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Maintenance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($camions as $camion)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-black">{{ $camion->immatriculation }}</div>
                                <div class="text-sm text-black">{{ $camion->marque }} {{ $camion->modele }}</div>
                                <div class="text-sm text-gray-500">{{ $camion->annee }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $camion->statut === 'disponible' ? 'bg-green-100 text-green-800' : 
                                   ($camion->statut === 'en_utilisation' ? 'bg-blue-100 text-blue-800' : 
                                   ($camion->statut === 'en_maintenance' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) }}">
                                {{ ucfirst(str_replace('_', ' ', $camion->statut)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $camion->ville_localisation ?? 'Non localisé' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            @if($camion->franchise_actuel)
                                <div class="flex items-center">
                                    <span class="text-black">{{ $camion->franchise_actuel->nom_complet }}</span>
                                    <div class="ml-2 flex space-x-1">
                                        <button onclick="openAssignModal({{ $camion->id }})" class="text-blue-600 hover:text-blue-700 text-xs" title="Modifier l'assignation">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.camions.remove-franchise', $camion) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 text-xs" onclick="return confirm('Enlever ce franchisé du camion ?')" title="Enlever le franchisé">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <span class="text-gray-500">Aucun franchisé</span>
                                    <button onclick="openAssignModal({{ $camion->id }})" class="ml-2 text-green-600 hover:text-green-700 text-xs" title="Assigner un franchisé">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            @if($camion->prochaine_maintenance)
                                {{ $camion->prochaine_maintenance_formatee }}
                            @else
                                <span class="text-gray-500">Non programmée</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.camions.show', $camion) }}" class="text-blue-600 hover:text-blue-700 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.camions.edit', $camion) }}" class="text-orange-600 hover:text-orange-700 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.camions.destroy', $camion) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700" onclick="return confirm('Supprimer ce camion ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $camions->links() }}
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-truck text-green-600 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-black truncate">Total camions</dt>
                        <dd class="text-lg font-medium text-black">{{ $stats['total_camions'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-blue-600 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-black truncate">En service</dt>
                        <dd class="text-lg font-medium text-black">{{ $stats['en_utilisation'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-tools text-orange-600 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-black truncate">En maintenance</dt>
                        <dd class="text-lg font-medium text-black">{{ $stats['en_maintenance'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-user text-purple-600 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-black truncate">Assignés</dt>
                        <dd class="text-lg font-medium text-black">{{ $stats['assignes'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'assignation de franchisé -->
    <div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-black mb-4">Assigner un franchisé</h3>
                <form id="assignForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="franchise_id" class="block text-sm font-medium text-black mb-2">Sélectionner un franchisé</label>
                        <select id="franchise_id" name="franchise_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Choisir un franchisé...</option>
                            @foreach($franchises ?? [] as $franchise)
                                <option value="{{ $franchise->id }}">
                                    {{ $franchise->nom_complet }} ({{ $franchise->ville }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeAssignModal()" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded text-sm">
                            Annuler
                        </button>
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded text-sm">
                            Assigner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAssignModal(camionId) {
            document.getElementById('assignForm').action = `/admin/camions/${camionId}/assign-franchise`;
            document.getElementById('assignModal').classList.remove('hidden');
        }
        
        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }
    </script>
</div>
@endsection 
