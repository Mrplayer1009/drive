@extends('layouts.admin')

@section('title', 'Détails de la Commande')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Détails de la Commande</h1>
                <p class="text-black">Commande #{{ $commande->id }} - {{ $commande->franchise->nom_complet }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.commandes.edit', $commande) }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                @if($commande->statut === 'en_attente')
                <form action="{{ route('admin.commandes.validate', $commande) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-black px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-check mr-2"></i>
                        Valider
                    </button>
                </form>
                <button type="button" onclick="openRefuseModal()" class="bg-red-600 hover:bg-red-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-times mr-2"></i>
                    Refuser
                </button>
                @endif
                <a href="{{ route('admin.commandes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations de la commande -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations de la commande</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Numéro de commande</p>
                        <p class="text-sm text-black">#{{ $commande->id }}</p>
                    </div>
                    <i class="fas fa-shopping-cart text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Franchisé</p>
                        <p class="text-sm text-black">{{ $commande->franchise->nom_complet }}</p>
                    </div>
                    <i class="fas fa-user text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Entrepôt</p>
                        <p class="text-sm text-black">{{ $commande->entrepot->nom }}</p>
                    </div>
                    <i class="fas fa-warehouse text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Date de commande</p>
                        <p class="text-sm text-black">{{ $commande->date_commande->format('d/m/Y') }}</p>
                    </div>
                    <i class="fas fa-calendar text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Statut</p>
                        <p class="text-sm text-black">{{ $commande->statut_label }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $commande->statut === 'validee' ? 'bg-green-100 text-green-800' : 
                           ($commande->statut === 'en_attente' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ $commande->statut_label }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Montants et règles -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Montants et règles</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border border-green-200">
                    <div>
                        <p class="text-sm font-medium text-black">Total de la commande</p>
                        <p class="text-lg font-bold text-black">{{ $commande->total_formate }}</p>
                    </div>
                    <i class="fas fa-euro-sign text-green-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div>
                        <p class="text-sm font-medium text-black">Produits obligatoires (80%)</p>
                        <p class="text-lg font-bold text-black">{{ number_format($commande->pourcentage_obligatoire, 2, ',', ' ') }} €</p>
                    </div>
                    <i class="fas fa-exclamation-triangle text-blue-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg border border-purple-200">
                    <div>
                        <p class="text-sm font-medium text-black">Produits libres (20%)</p>
                        <p class="text-lg font-bold text-black">{{ number_format($commande->total_commande - $commande->pourcentage_obligatoire, 2, ',', ' ') }} €</p>
                    </div>
                    <i class="fas fa-check-circle text-purple-600 text-2xl"></i>
                </div>

                @if($commande->verifierRegle8020())
                <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span class="text-sm text-black font-medium">Règle 80/20 respectée</span>
                    </div>
                </div>
                @else
                <div class="p-3 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <span class="text-sm text-black font-medium">Règle 80/20 non respectée</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Produits commandés -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Produits commandés</h3>
        
        @if($commande->produits->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix unitaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Type</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($commande->produits as $produit)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-black">{{ $produit->nom }}</div>
                                    <div class="text-sm text-gray-500">{{ $produit->description }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $produit->categorie_label }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $produit->pivot->quantite ?? 0 }} {{ $produit->unite_mesure }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ number_format($produit->pivot->prix_unitaire ?? 0, 2, ',', ' ') }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ number_format($produit->pivot->prix_total ?? 0, 2, ',', ' ') }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($produit->obligatoire)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        Obligatoire
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        Libre
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-black text-center py-4">Aucun produit dans cette commande</p>
        @endif
    </div>

    <!-- Informations du franchisé -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Informations du franchisé</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-black mb-2">Contact</h4>
                <div class="space-y-2">
                    <p class="text-sm text-black"><strong>Email :</strong> {{ $commande->franchise->email }}</p>
                    <p class="text-sm text-black"><strong>Téléphone :</strong> {{ $commande->franchise->telephone }}</p>
                    <p class="text-sm text-black"><strong>Ville :</strong> {{ $commande->franchise->ville }}</p>
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-black mb-2">Statistiques</h4>
                <div class="space-y-2">
                    <p class="text-sm text-black"><strong>Total des commandes :</strong> {{ $commande->franchise->commandes->count() }}</p>
                    <p class="text-sm text-black"><strong>Total dépensé :</strong> {{ number_format($commande->franchise->commandes->sum('total_commande'), 2, ',', ' ') }} €</p>
                    <p class="text-sm text-black"><strong>Commandes validées :</strong> {{ $commande->franchise->commandes->where('statut', 'validee')->count() }}</p>
                </div>
            </div>
        </div>
    </div>



    <!-- Notes administrateur -->
    @if($commande->notes_admin)
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Notes administrateur</h3>
        
        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-sm text-blue-800">{{ $commande->notes_admin }}</p>
        </div>
    </div>
    @endif
</div>

<!-- Modal de refus -->
<div id="refuseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-black mb-4">Refuser la commande</h3>
            
            <form action="{{ route('admin.commandes.refuse', $commande) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <p class="text-sm text-black">Êtes-vous sûr de vouloir refuser cette commande ?</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        onclick="closeRefuseModal()" 
                        class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-red-600 text-black rounded-md hover:bg-red-700 transition duration-300"
                    >
                        Confirmer le refus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRefuseModal() {
    document.getElementById('refuseModal').classList.remove('hidden');
}

function closeRefuseModal() {
    document.getElementById('refuseModal').classList.add('hidden');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('refuseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRefuseModal();
    }
});
</script>
@endsection 
