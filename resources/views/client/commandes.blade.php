@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50">

    <!-- Contenu des Commandes -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Boutons de tri -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-sort text-orange-600 mr-2"></i>
                Trier mes commandes
            </h2>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('client.commandes', ['tri' => 'date_desc']) }}" 
                   class="px-4 py-2 rounded-lg transition duration-300 {{ request('tri') == 'date_desc' || !request('tri') ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Date (plus récent)
                </a>
                
                <a href="{{ route('client.commandes', ['tri' => 'date_asc']) }}" 
                   class="px-4 py-2 rounded-lg transition duration-300 {{ request('tri') == 'date_asc' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-calendar mr-2"></i>
                    Date (plus ancien)
                </a>
                
                <a href="{{ route('client.commandes', ['tri' => 'prix_desc']) }}" 
                   class="px-4 py-2 rounded-lg transition duration-300 {{ request('tri') == 'prix_desc' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-euro-sign mr-2"></i>
                    Prix (plus élevé)
                </a>
                
                <a href="{{ route('client.commandes', ['tri' => 'prix_asc']) }}" 
                   class="px-4 py-2 rounded-lg transition duration-300 {{ request('tri') == 'prix_asc' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-euro-sign mr-2"></i>
                    Prix (plus bas)
                </a>
            </div>
        </div>
        
        @if($commandes->count() > 0)
            
            <div class="space-y-4">
                @foreach($commandes as $commande)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <!-- En-tête simple -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Commande #{{ $commande->id }}</h3>
                            <p class="text-gray-600">Passée le {{ $commande->date_commande->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @switch($commande->statut)
                                    @case('en_attente')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('en_attente_paiement')
                                        bg-purple-100 text-purple-800
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
                                        bg-gray-100 text-gray-800
                                        @break
                                    @case('annulee')
                                        bg-red-100 text-red-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                <i class="fas fa-circle text-xs mr-2"></i>
                                {{ $commande->statut_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Articles commandés (version simplifiée) -->
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2">Articles commandés :</h4>
                        <div class="space-y-1">
                            @foreach($commande->menus as $menu)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-700">{{ $menu->nom }} (x{{ $menu->pivot->quantite }})</span>
                                <span class="text-gray-600">{{ number_format($menu->pivot->prix_total, 2, ',', ' ') }} €</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Prix total et action -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div>
                            <span class="text-lg font-bold text-orange-600">{{ number_format($commande->montant_final, 2, ',', ' ') }} €</span>
                        </div>
                        <a href="{{ route('client.commandes.show', $commande->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300 text-sm">
                            <i class="fas fa-eye mr-1"></i>
                            Détails
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($commandes->hasPages())
            <div class="mt-8">
                {{ $commandes->links() }}
            </div>
            @endif
        @else
            <!-- Aucune commande -->
            <div class="text-center py-12">
                <i class="fas fa-list text-6xl text-gray-400 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-4">Aucune commande</h2>
                <p class="text-gray-500 mb-6">Vous n'avez pas encore passé de commande. Découvrez nos délicieux menus !</p>
                <a href="{{ route('client.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg transition duration-300">
                    <i class="fas fa-utensils mr-2"></i>
                    Voir les Menus
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter un effet de clic sur les boutons de tri
    const triButtons = document.querySelectorAll('a[href*="tri="]');
    triButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Ajouter un effet de loading
            this.style.opacity = '0.7';
            this.style.pointerEvents = 'none';
        });
    });
});
</script>
@endsection
