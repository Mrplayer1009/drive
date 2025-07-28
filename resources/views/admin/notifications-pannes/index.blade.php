@extends('layouts.admin')

@section('title', 'Notifications de Pannes')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Notifications de Pannes</h1>
                <p class="text-black">Gérez les pannes signalées par les franchisés</p>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-black mb-4">Filtres</h3>
        <form method="GET" action="{{ route('admin.notifications-pannes.index') }}" id="filtres-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="statut" class="block text-sm font-medium text-black mb-2">Statut</label>
                <select id="statut" name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les statuts</option>
                    <option value="signalee" {{ request('statut') === 'signalee' ? 'selected' : '' }}>Signalée</option>
                    <option value="en_maintenance" {{ request('statut') === 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                    <option value="resolue" {{ request('statut') === 'resolue' ? 'selected' : '' }}>Résolue</option>
                    <option value="ignoree" {{ request('statut') === 'ignoree' ? 'selected' : '' }}>Ignorée</option>
                </select>
            </div>
            <div>
                <label for="gravite" class="block text-sm font-medium text-black mb-2">Gravité</label>
                <select id="gravite" name="gravite" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Toutes les gravités</option>
                    <option value="legere" {{ request('gravite') === 'legere' ? 'selected' : '' }}>Légère</option>
                    <option value="moderee" {{ request('gravite') === 'moderee' ? 'selected' : '' }}>Modérée</option>
                    <option value="grave" {{ request('gravite') === 'grave' ? 'selected' : '' }}>Grave</option>
                    <option value="critique" {{ request('gravite') === 'critique' ? 'selected' : '' }}>Critique</option>
                </select>
            </div>
            <div>
                <label for="type_panne" class="block text-sm font-medium text-black mb-2">Type de panne</label>
                <select id="type_panne" name="type_panne" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les types</option>
                    <option value="mecanique" {{ request('type_panne') === 'mecanique' ? 'selected' : '' }}>Mécanique</option>
                    <option value="electrique" {{ request('type_panne') === 'electrique' ? 'selected' : '' }}>Électrique</option>
                    <option value="pneumatique" {{ request('type_panne') === 'pneumatique' ? 'selected' : '' }}>Pneumatique</option>
                    <option value="autre" {{ request('type_panne') === 'autre' ? 'selected' : '' }}>Autre</option>
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
            <a href="{{ route('admin.notifications-pannes.index') }}" class="btn-secondary">
                <i class="fas fa-times mr-2"></i>
                Effacer les filtres
            </a>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-black">Liste des pannes signalées</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Franchisé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Camion</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Gravité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date signalement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($notifications as $notification)
                    <tr class="{{ $notification->gravite === 'critique' ? 'bg-red-50' : ($notification->gravite === 'grave' ? 'bg-orange-50' : '') }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black">
                            #{{ $notification->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $notification->franchise->nom_complet }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $notification->camion->immatriculation }}<br>
                            <span class="text-xs text-gray-500">{{ $notification->camion->marque }} {{ $notification->camion->modele }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $notification->type_panne_label }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $notification->gravite_color }}">
                                {{ $notification->gravite_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $notification->statut === 'resolue' ? 'bg-green-100 text-green-800' : 
                                   ($notification->statut === 'ignoree' ? 'bg-gray-100 text-gray-800' : 
                                   ($notification->statut === 'en_maintenance' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                {{ $notification->statut_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.notifications-pannes.show', $notification) }}" class="text-orange-600 hover:text-orange-700" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($notification->statut === 'signalee')
                                <a href="{{ route('admin.notifications-pannes.edit', $notification) }}" class="text-blue-600 hover:text-blue-700" title="Traiter">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-black">Aucune panne signalée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Signalées</p>
                    <p class="text-2xl font-semibold text-black">{{ $notifications->where('statut', 'signalee')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-tools text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">En maintenance</p>
                    <p class="text-2xl font-semibold text-black">{{ $notifications->where('statut', 'en_maintenance')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-check text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Résolues</p>
                    <p class="text-2xl font-semibold text-black">{{ $notifications->where('statut', 'resolue')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-black">Critiques</p>
                    <p class="text-2xl font-semibold text-black">{{ $notifications->where('gravite', 'critique')->count() }}</p>
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
