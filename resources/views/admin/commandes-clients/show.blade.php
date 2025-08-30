@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('admin.commandes-clients.index') }}" class="text-gray-600 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-eye text-orange-600 mr-2"></i>
                    Commande Client #{{ $commande->id }}
                </h1>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.commandes-clients.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-list mr-2"></i>
                    Retour à la liste
                </a>
                @if(in_array($commande->statut, ['en_attente', 'confirmee']))
                    <form method="POST" action="{{ route('admin.commandes-clients.destroy', $commande) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ? Cette action est irréversible.')">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Contenu -->
    <div class="space-y-6">
        <!-- En-tête de la commande -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold">Commande #{{ $commande->id }}</h2>
                        <p class="text-orange-100">Passée le {{ $commande->date_commande->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                            <i class="fas fa-circle text-xs mr-2"></i>
                            {{ ucfirst($commande->statut) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Détails de la commande -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Informations client -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">
                            <i class="fas fa-user text-orange-600 mr-2"></i>
                            Client
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><strong>Nom :</strong> {{ $commande->client->nom }} {{ $commande->client->prenom }}</p>
                            <p><strong>Email :</strong> {{ $commande->client->email }}</p>
                            <p><strong>Téléphone :</strong> {{ $commande->client->telephone }}</p>
                            <p><strong>Adresse :</strong> {{ $commande->client->adresse }}</p>
                            <p><strong>Ville :</strong> {{ $commande->client->ville }} {{ $commande->client->code_postal }}</p>
                        </div>
                    </div>

                    <!-- Informations franchise -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">
                            <i class="fas fa-store text-orange-600 mr-2"></i>
                            Franchise
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><strong>Nom :</strong> {{ $commande->franchise->nom }} {{ $commande->franchise->prenom }}</p>
                            <p><strong>Email :</strong> {{ $commande->franchise->email }}</p>
                            <p><strong>Téléphone :</strong> {{ $commande->franchise->telephone }}</p>
                            <p><strong>Ville :</strong> {{ $commande->franchise->ville }}</p>
                            <p><strong>Statut :</strong> 
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $commande->franchise->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($commande->franchise->statut) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Informations de livraison -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">
                            <i class="fas fa-map-marker-alt text-orange-600 mr-2"></i>
                            Livraison
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><strong>Adresse :</strong> {{ $commande->adresse_livraison }}</p>
                            <p><strong>Téléphone :</strong> {{ $commande->telephone_contact }}</p>
                            @if($commande->date_livraison_souhaitee)
                                <p><strong>Livraison souhaitée :</strong> {{ $commande->date_livraison_souhaitee->format('d/m/Y à H:i') }}</p>
                            @endif
                            @if($commande->date_livraison_effective)
                                <p><strong>Livrée le :</strong> {{ $commande->date_livraison_effective->format('d/m/Y à H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Articles commandés -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-utensils text-orange-600 mr-2"></i>
                        Articles commandés
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @foreach($commande->menus as $menu)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-200 to-red-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-utensils text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $menu->nom }}</div>
                                    <div class="text-sm text-gray-600">Quantité : {{ $menu->pivot->quantite }}</div>
                                    @if($menu->pivot->notes)
                                        <div class="text-xs text-gray-500">{{ $menu->pivot->notes }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-gray-800">{{ number_format($menu->pivot->prix_total, 2, ',', ' ') }} €</div>
                                <div class="text-sm text-gray-600">{{ number_format($menu->pivot->prix_unitaire, 2, ',', ' ') }} € l'unité</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Notes -->
                @if($commande->notes)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-sticky-note text-orange-600 mr-2"></i>
                        Notes
                    </h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-gray-700">{{ $commande->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Résumé financier -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-receipt text-orange-600 mr-2"></i>
                        Résumé
                    </h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sous-total :</span>
                            <span class="font-semibold">{{ number_format($commande->montant_total, 2, ',', ' ') }} €</span>
                        </div>
                        @if($commande->reduction_fidelite > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Réduction fidélité :</span>
                            <span class="font-semibold">-{{ number_format($commande->reduction_fidelite, 2, ',', ' ') }} €</span>
                        </div>
                        @endif
                        <div class="border-t pt-2">
                            <div class="flex justify-between">
                                <span class="text-lg font-bold text-gray-800">Total :</span>
                                <span class="text-lg font-bold text-orange-600">{{ number_format($commande->montant_final, 2, ',', ' ') }} €</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de paiement -->
                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-credit-card text-orange-600 mr-2"></i>
                        Paiement
                    </h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Mode :</strong> 
                            @if($commande->mode_paiement === 'en_ligne')
                                <span class="inline-flex items-center">
                                    <i class="fas fa-credit-card mr-1"></i>
                                    Paiement en ligne (Stripe)
                                </span>
                            @else
                                <span class="inline-flex items-center">
                                    <i class="fas fa-money-bill-wave mr-1"></i>
                                    {{ ucfirst($commande->mode_paiement) }}
                                </span>
                            @endif
                        </p>
                        @if($commande->reference_paiement)
                            <p><strong>Référence :</strong> {{ $commande->reference_paiement }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

