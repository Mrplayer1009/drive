@extends('layouts.franchise')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Mes Commandes</h1>
                <p class="text-black">Gérez vos commandes de stock</p>
            </div>
            <a href="{{ route('franchise.commandes.create') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle commande
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-black mb-4">Filtres</h3>
        <form method="GET" action="{{ route('franchise.commandes.index') }}" id="filtres-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="statut" class="block text-sm font-medium text-black mb-2">Statut</label>
                <select id="statut" name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="validee" {{ request('statut') === 'validee' ? 'selected' : '' }}>Validée</option>
                    <option value="livree" {{ request('statut') === 'livree' ? 'selected' : '' }}>Livrée</option>
                    <option value="annulee" {{ request('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
            <div>
                <label for="entrepot" class="block text-sm font-medium text-black mb-2">Entrepôt</label>
                <select id="entrepot" name="entrepot" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les entrepôts</option>
                    @foreach($entrepots as $entrepot)
                    <option value="{{ $entrepot->id }}" {{ request('entrepot') == $entrepot->id ? 'selected' : '' }}>
                        {{ $entrepot->nom }} - {{ $entrepot->ville }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_debut" class="block text-sm font-medium text-black mb-2">Date de début</label>
                <input type="date" id="date_debut" name="date_debut" value="{{ request('date_debut') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label for="date_fin" class="block text-sm font-medium text-black mb-2">Date de fin</label>
                <input type="date" id="date_fin" name="date_fin" value="{{ request('date_fin') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
        </form>
        
        <div class="mt-4 flex justify-between items-center">
            <button type="submit" form="filtres-form" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-filter mr-2"></i>
                Appliquer les filtres
            </button>
            <a href="{{ route('franchise.commandes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-times mr-2"></i>
                Effacer les filtres
            </a>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('franchise.commandes.show', $commande) }}" class="text-orange-600 hover:text-orange-700" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($commande->statut === 'en_attente')
                                <a href="{{ route('franchise.commandes.edit', $commande) }}" class="text-blue-600 hover:text-blue-700" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('franchise.commandes.destroy', $commande) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700" title="Annuler">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                                @if($commande->statut === 'validee' || $commande->statut === 'livree')
                                <a href="{{ route('franchise.commandes.download', $commande) }}" class="text-green-600 hover:text-green-700" title="Télécharger le bon de commande">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-black">Aucune commande trouvée</td>
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
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
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
                    <p class="text-sm font-medium text-black">Total dépensé</p>
                    <p class="text-2xl font-semibold text-black">{{ number_format($commandes->sum('total_commande'), 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit du formulaire quand on change un filtre
    const form = document.getElementById('filtres-form');
    const selects = form.querySelectorAll('select');
    const inputs = form.querySelectorAll('input[type="date"]');
    
    // Écouter les changements sur les selects
    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Écouter les changements sur les inputs de date
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Attendre un peu avant de soumettre pour éviter les soumissions multiples
            setTimeout(() => {
                form.submit();
            }, 500);
        });
    });
    
    // Empêcher la soumission si les deux dates sont remplies mais la date de début est après la date de fin
    form.addEventListener('submit', function(e) {
        const dateDebut = document.getElementById('date_debut').value;
        const dateFin = document.getElementById('date_fin').value;
        
        if (dateDebut && dateFin && dateDebut > dateFin) {
            e.preventDefault();
            alert('La date de début ne peut pas être après la date de fin.');
            return false;
        }
    });
});
</script>
@endsection 
