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
                            @if($franchise->camions->count() > 0)
                                @foreach($franchise->camions->take(3) as $camion)
                                    <div class="text-xs">
                                        {{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }}
                                    </div>
                                @endforeach
                                @if($franchise->camions->count() > 3)
                                    <div class="text-xs text-gray-500">+{{ $franchise->camions->count() - 3 }} autre(s)</div>
                                @endif
                            @else
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-500">Aucun camion</span>
                                    @if($franchise->statut === 'actif')
                                    <button type="button" onclick="openAssignCamionModal({{ $franchise->id }}, '{{ $franchise->nom_complet }}')" class="text-green-600 hover:text-green-700 text-xs" title="Attribuer un camion">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.franchises.edit', $franchise) }}" class="text-orange-600 hover:text-orange-700 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.franchises.show', $franchise) }}" class="text-blue-600 hover:text-blue-700 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($franchise->statut === 'inactif')
                            <button type="button" onclick="openActivationModal({{ $franchise->id }}, '{{ $franchise->nom_complet }}')" class="text-green-600 hover:text-green-700 mr-3">
                                <i class="fas fa-check"></i>
                            </button>
                            @endif
                            <button type="button" onclick="openDeleteModal({{ $franchise->id }}, '{{ $franchise->nom_complet }}')" class="text-red-600 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
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

<!-- Modal d'activation avec attribution de camions -->
<div id="activationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-black mb-4">Activer le franchisé et attribuer des camions</h3>
            
            <form id="activationForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                
                <div class="mb-4">
                    <p class="text-sm text-black">Vous êtes sur le point d'activer le franchisé : <strong id="franchiseName"></strong></p>
                </div>
                
                                 <div class="mb-6">
                     <h4 class="text-md font-medium text-black mb-3">Camions disponibles</h4>
                     <div class="mb-4">
                         <input type="text" id="searchCamionsActivation" placeholder="Rechercher par modèle ou matricule..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                     </div>
                     <div id="camionsDisponibles" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-64 overflow-y-auto">
                         <!-- Les camions seront chargés ici via JavaScript -->
                     </div>
                 </div>
                
                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        onclick="closeActivationModal()" 
                        class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-green-600 text-black rounded-md hover:bg-green-700 transition duration-300"
                    >
                        Activer et attribuer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'attribution de camion -->
<div id="assignCamionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-black mb-4">Attribuer un camion au franchisé</h3>
            
            <form id="assignCamionForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                
                <div class="mb-4">
                    <p class="text-sm text-black">Sélectionnez un camion à attribuer au franchisé : <strong id="assignFranchiseName"></strong></p>
                </div>
                
                                 <div class="mb-6">
                     <h4 class="text-md font-medium text-black mb-3">Camions disponibles</h4>
                     <div class="mb-4">
                         <input type="text" id="searchCamionsAssign" placeholder="Rechercher par modèle ou matricule..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                     </div>
                     <div id="assignCamionsDisponibles" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-64 overflow-y-auto">
                         <!-- Les camions seront chargés ici via JavaScript -->
                     </div>
                 </div>
                
                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        onclick="closeAssignCamionModal()" 
                        class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-purple-600 text-black rounded-md hover:bg-purple-700 transition duration-300"
                    >
                        Attribuer le camion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-black mb-4">Supprimer le franchisé</h3>
            
            <form id="deleteForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                
                <div class="mb-4">
                    <p class="text-sm text-black">Êtes-vous sûr de vouloir supprimer le franchisé : <strong id="deleteFranchiseName"></strong> ?</p>
                    <p class="text-sm text-red-600 mt-2">Cette action est irréversible.</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-red-600 text-black rounded-md hover:bg-red-700 transition duration-300"
                    >
                        Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openActivationModal(franchiseId, franchiseName) {
    document.getElementById('franchiseName').textContent = franchiseName;
    document.getElementById('activationForm').action = `/admin/franchises/${franchiseId}/activate`;
    
    // Charger les camions disponibles
    fetch(`/admin/franchises/${franchiseId}/camions-disponibles`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('camionsDisponibles');
            container.innerHTML = '';
            
            if (data.camions.length === 0) {
                container.innerHTML = '<p class="text-gray-500 col-span-full">Aucun camion disponible</p>';
                return;
            }
            
            // Stocker les camions pour la recherche
            window.camionsActivation = data.camions;
            
            // Afficher tous les camions initialement
            afficherCamionsActivation(data.camions);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des camions:', error);
            document.getElementById('camionsDisponibles').innerHTML = '<p class="text-red-500 col-span-full">Erreur lors du chargement des camions</p>';
        });
    
    document.getElementById('activationModal').classList.remove('hidden');
}

function afficherCamionsActivation(camions) {
    const container = document.getElementById('camionsDisponibles');
    container.innerHTML = '';
    
    if (camions.length === 0) {
        container.innerHTML = '<p class="text-gray-500 col-span-full">Aucun camion trouvé</p>';
        return;
    }
    
    camions.forEach(camion => {
        const camionDiv = document.createElement('div');
        camionDiv.className = 'border border-gray-200 rounded-lg p-3';
        camionDiv.innerHTML = `
            <div class="flex items-center space-x-3">
                <input type="checkbox" name="camions[]" value="${camion.id}" id="camion_${camion.id}" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                <label for="camion_${camion.id}" class="flex-1 cursor-pointer">
                    <div class="font-medium text-black">${camion.immatriculation}</div>
                    <div class="text-sm text-gray-600">${camion.marque} ${camion.modele} (${camion.annee})</div>
                    <div class="text-xs text-gray-500">${camion.ville_localisation}</div>
                </label>
            </div>
        `;
        container.appendChild(camionDiv);
    });
}

function closeActivationModal() {
    document.getElementById('activationModal').classList.add('hidden');
}

function openDeleteModal(franchiseId, franchiseName) {
    document.getElementById('deleteFranchiseName').textContent = franchiseName;
    document.getElementById('deleteForm').action = `/admin/franchises/${franchiseId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function openAssignCamionModal(franchiseId, franchiseName) {
    document.getElementById('assignFranchiseName').textContent = franchiseName;
    document.getElementById('assignCamionForm').action = `/admin/franchises/${franchiseId}/assign-camion`;
    
    // Charger les camions disponibles
    fetch(`/admin/franchises/${franchiseId}/camions-disponibles`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('assignCamionsDisponibles');
            container.innerHTML = '';
            
            if (data.camions.length === 0) {
                container.innerHTML = '<p class="text-gray-500 col-span-full">Aucun camion disponible</p>';
                return;
            }
            
            // Stocker les camions pour la recherche
            window.camionsAssign = data.camions;
            
            // Afficher tous les camions initialement
            afficherCamionsAssign(data.camions);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des camions:', error);
            document.getElementById('assignCamionsDisponibles').innerHTML = '<p class="text-red-500 col-span-full">Erreur lors du chargement des camions</p>';
        });
    
    document.getElementById('assignCamionModal').classList.remove('hidden');
}

function afficherCamionsAssign(camions) {
    const container = document.getElementById('assignCamionsDisponibles');
    container.innerHTML = '';
    
    if (camions.length === 0) {
        container.innerHTML = '<p class="text-gray-500 col-span-full">Aucun camion trouvé</p>';
        return;
    }
    
    camions.forEach(camion => {
        const camionDiv = document.createElement('div');
        camionDiv.className = 'border border-gray-200 rounded-lg p-3';
        camionDiv.innerHTML = `
            <div class="flex items-center space-x-3">
                <input type="radio" name="camion_id" value="${camion.id}" id="assign_camion_${camion.id}" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" required>
                <label for="assign_camion_${camion.id}" class="flex-1 cursor-pointer">
                    <div class="font-medium text-black">${camion.immatriculation}</div>
                    <div class="text-sm text-gray-600">${camion.marque} ${camion.modele} (${camion.annee})</div>
                    <div class="text-xs text-gray-500">${camion.ville_localisation}</div>
                </label>
            </div>
        `;
        container.appendChild(camionDiv);
    });
}

function closeAssignCamionModal() {
    document.getElementById('assignCamionModal').classList.add('hidden');
}

// Fermer les modals en cliquant à l'extérieur
document.getElementById('activationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeActivationModal();
    }
});

document.getElementById('assignCamionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAssignCamionModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Fonctions de recherche pour les camions
function rechercherCamionsActivation(searchTerm) {
    if (!window.camionsActivation) return;
    
    const camionsFiltres = window.camionsActivation.filter(camion => {
        const searchLower = searchTerm.toLowerCase();
        const immatriculation = camion.immatriculation.toLowerCase();
        const modele = camion.modele.toLowerCase();
        const marque = camion.marque.toLowerCase();
        
        return immatriculation.includes(searchLower) || 
               modele.includes(searchLower) || 
               marque.includes(searchLower);
    });
    
    afficherCamionsActivation(camionsFiltres);
}

function rechercherCamionsAssign(searchTerm) {
    if (!window.camionsAssign) return;
    
    const camionsFiltres = window.camionsAssign.filter(camion => {
        const searchLower = searchTerm.toLowerCase();
        const immatriculation = camion.immatriculation.toLowerCase();
        const modele = camion.modele.toLowerCase();
        const marque = camion.marque.toLowerCase();
        
        return immatriculation.includes(searchLower) || 
               modele.includes(searchLower) || 
               marque.includes(searchLower);
    });
    
    afficherCamionsAssign(camionsFiltres);
}

// Event listeners pour la recherche
document.addEventListener('DOMContentLoaded', function() {
    // Recherche pour modal d'activation
    const searchActivation = document.getElementById('searchCamionsActivation');
    if (searchActivation) {
        searchActivation.addEventListener('input', function() {
            rechercherCamionsActivation(this.value);
        });
    }
    
    // Recherche pour modal d'attribution
    const searchAssign = document.getElementById('searchCamionsAssign');
    if (searchAssign) {
        searchAssign.addEventListener('input', function() {
            rechercherCamionsAssign(this.value);
        });
    }
});
</script>
@endsection 
