@extends('layouts.admin')

@section('title', 'Demandes de Camions')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Demandes de Camions</h1>
                <p class="text-black">Gérez les demandes de camions des franchisés</p>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-black mb-4">Filtres</h3>
        <form method="GET" action="{{ route('admin.demandes-camions.index') }}" id="filtres-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="statut" class="block text-sm font-medium text-black mb-2">Statut</label>
                <select id="statut" name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="approuvee" {{ request('statut') === 'approuvee' ? 'selected' : '' }}>Approuvée</option>
                    <option value="refusee" {{ request('statut') === 'refusee' ? 'selected' : '' }}>Refusée</option>
                </select>
            </div>
            <div>
                <label for="type_demande" class="block text-sm font-medium text-black mb-2">Type de demande</label>
                <select id="type_demande" name="type_demande" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les types</option>
                    <option value="nouveau" {{ request('type_demande') === 'nouveau' ? 'selected' : '' }}>Nouveau camion</option>
                    <option value="remplacement" {{ request('type_demande') === 'remplacement' ? 'selected' : '' }}>Remplacement</option>
                </select>
            </div>
            <div>
                <label for="urgent" class="block text-sm font-medium text-black mb-2">Priorité</label>
                <select id="urgent" name="urgent" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Toutes</option>
                    <option value="1" {{ request('urgent') === '1' ? 'selected' : '' }}>Urgentes</option>
                    <option value="0" {{ request('urgent') === '0' ? 'selected' : '' }}>Normales</option>
                </select>
            </div>
            <div>
                <label for="franchise" class="block text-sm font-medium text-black mb-2">Franchisé</label>
                <select id="franchise" name="franchise" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les franchisés</option>
                    @foreach($franchises as $franchise)
                    <option value="{{ $franchise->id }}" {{ request('franchise') == $franchise->id ? 'selected' : '' }}>
                        {{ $franchise->nom_complet }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
        
        <div class="mt-4 flex justify-between items-center">
            <button type="submit" form="filtres-form" class="btn-primary">
                <i class="fas fa-filter mr-2"></i>
                Appliquer les filtres
            </button>
            <a href="{{ route('admin.demandes-camions.index') }}" class="btn-secondary">
                <i class="fas fa-times mr-2"></i>
                Effacer les filtres
            </a>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-black">Liste des demandes</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Camion souhaité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date souhaitée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Urgent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($demandes as $demande)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black">
                            #{{ $demande->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $demande->franchise->nom_complet }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $demande->type_demande_label }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $demande->type_camion_souhaite_label }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ \Carbon\Carbon::parse($demande->date_debut_souhaitee)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($demande->urgent)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Urgent
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                    Normal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $demande->statut === 'approuvee' ? 'bg-green-100 text-green-800' : 
                                   ($demande->statut === 'refusee' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $demande->statut_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.demandes-camions.show', $demande) }}" class="text-orange-600 hover:text-orange-700" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($demande->statut === 'en_attente')
                                <a href="{{ route('admin.demandes-camions.edit', $demande) }}" class="text-blue-600 hover:text-blue-700" title="Traiter">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-black">Aucune demande trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($demandes->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">En attente</p>
                    <p class="text-2xl font-semibold text-black">{{ $demandes->where('statut', 'en_attente')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-check text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Approuvées</p>
                    <p class="text-2xl font-semibold text-black">{{ $demandes->where('statut', 'approuvee')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-times text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Refusées</p>
                    <p class="text-2xl font-semibold text-black">{{ $demandes->where('statut', 'refusee')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Urgentes</p>
                    <p class="text-2xl font-semibold text-black">{{ $demandes->where('urgent', true)->count() }}</p>
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
    
    // Écouter les changements sur les selects
    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
});
</script>
@endsection 