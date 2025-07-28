@extends('layouts.franchise')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Mes Ventes</h1>
                <p class="text-black">Gérez vos ventes et consultez vos rapports</p>
            </div>
            <a href="{{ route('franchise.ventes.create') }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle vente
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-black mb-4">Filtres</h3>
        <form method="GET" action="{{ route('franchise.ventes.index') }}" id="filtres-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="periode" class="block text-sm font-medium text-black mb-2">Période</label>
                <select id="periode" name="periode" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Toutes les périodes</option>
                    <option value="aujourdhui" {{ request('periode') === 'aujourdhui' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="semaine" {{ request('periode') === 'semaine' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="mois" {{ request('periode') === 'mois' ? 'selected' : '' }}>Ce mois</option>
                    <option value="trimestre" {{ request('periode') === 'trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                    <option value="annee" {{ request('periode') === 'annee' ? 'selected' : '' }}>Cette année</option>
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
            <div>
                <label for="camion" class="block text-sm font-medium text-black mb-2">Camion</label>
                <select id="camion" name="camion" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les camions</option>
                    @foreach($camions as $camion)
                    <option value="{{ $camion->id }}" {{ request('camion') == $camion->id ? 'selected' : '' }}>
                        {{ $camion->immatriculation }} ({{ $camion->marque }} {{ $camion->modele }})
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
        
        <div class="mt-4 flex justify-between items-center">
            <button type="submit" form="filtres-form" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-filter mr-2"></i>
                Appliquer les filtres
            </button>
            <a href="{{ route('franchise.ventes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-times mr-2"></i>
                Effacer les filtres
            </a>
        </div>
    </div>

    <!-- Liste des ventes -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-black">Liste des ventes</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Camion</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Commandes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant reversé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ventes as $vente)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $vente->camion ? $vente->camion->immatriculation : 'N/A' }}
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <a href="{{ route('franchise.ventes.download', $vente) }}" class="text-orange-600 hover:text-orange-700 mr-3" title="Télécharger PDF">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="text-blue-600 hover:text-blue-700" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-black">Aucune vente trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ventes->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $ventes->links() }}
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-euro-sign text-white"></i>
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
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Total reversé</p>
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
                        <i class="fas fa-percentage text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Pourcentage moyen</p>
                    <p class="text-2xl font-semibold text-black">{{ Auth::user()->pourcentage_ventes }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des ventes -->
    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Évolution des ventes</h3>
        <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
            <p class="text-black">Graphique des ventes (à implémenter)</p>
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