@extends('layouts.franchise')

@section('content')
<div class="p-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Détails de la Commande #{{ $commande->id }}</h1>
                <p class="text-black">Informations complètes sur la commande client</p>
            </div>
            <div class="flex space-x-2">
                @if($commande->vente)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>
                        Vente comptabilisée
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-2"></i>
                        Vente en attente
                    </span>
                @endif
                <a href="{{ route('franchise.commandes-clients.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Informations de la commande -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Détails de la commande -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations de la Commande</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-black font-medium">Statut :</span>
                    <span class="px-2 py-1 rounded text-sm font-medium
                        @if($commande->statut === 'en_attente') bg-yellow-100 text-yellow-800
                        @elseif($commande->statut === 'confirmee') bg-blue-100 text-blue-800
                        @elseif($commande->statut === 'en_preparation') bg-orange-100 text-orange-800
                        @elseif($commande->statut === 'prete') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $commande->statut_label }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Date de commande :</span>
                    <span class="text-black">{{ $commande->date_commande->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Montant total :</span>
                    <span class="text-black font-bold">{{ $commande->montant_total_formate }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Réduction fidélité :</span>
                    <span class="text-black">{{ number_format($commande->reduction_fidelite, 2, ',', ' ') }} €</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Montant final :</span>
                    <span class="text-black font-bold text-lg">{{ $commande->montant_final_formate }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Mode de paiement :</span>
                    <span class="text-black">{{ $commande->mode_paiement ?? 'Non spécifié' }}</span>
                </div>
                @if($commande->reference_paiement)
                <div class="flex justify-between">
                    <span class="text-black font-medium">Référence paiement :</span>
                    <span class="text-black font-mono text-sm">{{ $commande->reference_paiement }}</span>
                </div>
                @endif
                @if($commande->vente)
                <div class="flex justify-between">
                    <span class="text-black font-medium">Vente associée :</span>
                    <span class="text-green-600 font-medium">Vente #{{ $commande->vente->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Montant reversé :</span>
                    <span class="text-green-600 font-bold">{{ $commande->vente->montant_reverse_formate }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Informations du client -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations du Client</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-black font-medium">Nom :</span>
                                            <span class="text-black">{{ $commande->client->nom_complet }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Email :</span>
                    <span class="text-black">{{ $commande->client->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Téléphone :</span>
                    <span class="text-black">{{ $commande->client->telephone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Adresse de réservation :</span>
                    <span class="text-black text-right">{{ $commande->adresse_livraison }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-black font-medium">Téléphone de contact :</span>
                    <span class="text-black">{{ $commande->telephone_contact }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Menus commandés -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-black mb-4">Menus Commandés</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Menu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Prix total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($commande->menus as $menu)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            <div>
                                <div class="font-medium">{{ $menu->nom }}</div>
                                <div class="text-gray-500">{{ $menu->description }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ number_format($menu->pivot->prix_unitaire, 2, ',', ' ') }} €
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                            {{ $menu->pivot->quantite }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-medium">
                            {{ number_format($menu->pivot->prix_total, 2, ',', ' ') }} €
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notes -->
    @if($commande->notes)
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-black mb-4">Notes</h3>
        <p class="text-black">{{ $commande->notes }}</p>
    </div>
    @endif

    <!-- Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Actions</h3>
        
        <div class="flex flex-wrap gap-4">
            @if($commande->statut === 'en_attente')
                <form action="{{ route('franchise.commandes-clients.validate', $commande) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" style="background-color: #16a34a !important; color: white !important; padding: 16px 32px !important; border-radius: 12px !important; font-weight: bold !important; font-size: 18px !important; box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important; border: 4px solid #22c55e !important; margin: 8px !important; display: inline-block !important; text-decoration: none !important; cursor: pointer !important; transition: all 0.3s ease !important;" onmouseover="this.style.backgroundColor='#15803d'; this.style.transform='scale(1.05)'" onmouseout="this.style.backgroundColor='#16a34a'; this.style.transform='scale(1)'">
                        <i class="fas fa-check" style="margin-right: 12px; font-size: 20px;"></i>
                        ✅ CONFIRMER LA COMMANDE
                    </button>
                </form>
            @endif

            @if($commande->statut === 'confirmee')
                <form action="{{ route('franchise.commandes-clients.prepare', $commande) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" style="background-color: #f97316 !important; color: white !important; padding: 16px 32px !important; border-radius: 12px !important; font-weight: bold !important; font-size: 18px !important; box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important; border: 4px solid #fb923c !important; margin: 8px !important; display: inline-block !important; text-decoration: none !important; cursor: pointer !important; transition: all 0.3s ease !important;" onmouseover="this.style.backgroundColor='#ea580c'; this.style.transform='scale(1.05)'" onmouseout="this.style.backgroundColor='#f97316'; this.style.transform='scale(1)'">
                        <i class="fas fa-utensils" style="margin-right: 12px; font-size: 20px;"></i>
                        🍽️ METTRE EN PRÉPARATION
                    </button>
                </form>
            @endif

            @if($commande->statut === 'en_preparation')
                <form action="{{ route('franchise.commandes-clients.prete', $commande) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" style="background-color: #22c55e !important; color: white !important; padding: 16px 32px !important; border-radius: 12px !important; font-weight: bold !important; font-size: 18px !important; box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important; border: 4px solid #4ade80 !important; margin: 8px !important; display: inline-block !important; text-decoration: none !important; cursor: pointer !important; transition: all 0.3s ease !important;" onmouseover="this.style.backgroundColor='#16a34a'; this.style.transform='scale(1.05)'" onmouseout="this.style.backgroundColor='#22c55e'; this.style.transform='scale(1)'">
                        <i class="fas fa-check-circle" style="margin-right: 12px; font-size: 20px;"></i>
                        ✅ MARQUER COMME PRÊTE
                    </button>
                </form>
            @endif

            @if(in_array($commande->statut, ['en_attente', 'confirmee', 'en_preparation']))
                <form action="{{ route('franchise.commandes-clients.cancel', $commande) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                    @csrf
                    <button type="submit" style="background-color: #ef4444 !important; color: white !important; padding: 16px 32px !important; border-radius: 12px !important; font-weight: bold !important; font-size: 18px !important; box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important; border: 4px solid #f87171 !important; margin: 8px !important; display: inline-block !important; text-decoration: none !important; cursor: pointer !important; transition: all 0.3s ease !important;" onmouseover="this.style.backgroundColor='#dc2626'; this.style.transform='scale(1.05)'" onmouseout="this.style.backgroundColor='#ef4444'; this.style.transform='scale(1)'">
                        <i class="fas fa-times" style="margin-right: 12px; font-size: 20px;"></i>
                        ❌ ANNULER LA COMMANDE
                    </button>
                </form>
            @endif
            
            @if($commande->statut === 'prete')
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-4">
                    <h4 class="text-lg font-semibold text-green-800 mb-4">
                        <i class="fas fa-qrcode mr-2"></i>
                        Récupération de commande
                    </h4>
                    <p class="text-green-700 mb-4">
                        Le client a reçu un email avec un code à 4 chiffres. 
                        Demandez-lui le code et saisissez-le ci-dessous pour finaliser la commande.
                    </p>
                    
                    <form action="{{ route('franchise.commandes-clients.terminer', $commande) }}" method="POST" class="flex items-center space-x-4">
                        @csrf
                        <div class="flex-1">
                            <label for="code_recuperation" class="block text-sm font-medium text-green-800 mb-2">
                                Code de récupération
                            </label>
                            <input 
                                type="text" 
                                id="code_recuperation" 
                                name="code_recuperation" 
                                maxlength="4"
                                pattern="[0-9]{4}"
                                class="w-full px-4 py-3 text-center text-2xl font-mono tracking-widest border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="0000"
                                required
                                autocomplete="off"
                            >
                        </div>
                        <div class="pt-6">
                            <button 
                                type="submit" 
                                style="background-color: #059669 !important; color: white !important; padding: 16px 32px !important; border-radius: 12px !important; font-weight: bold !important; font-size: 18px !important; box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important; border: 4px solid #10b981 !important; margin: 8px !important; display: inline-block !important; text-decoration: none !important; cursor: pointer !important; transition: all 0.3s ease !important;" 
                                onmouseover="this.style.backgroundColor='#047857'; this.style.transform='scale(1.05)'" 
                                onmouseout="this.style.backgroundColor='#059669'; this.style.transform='scale(1)'"
                            >
                                <i class="fas fa-check" style="margin-right: 12px; font-size: 20px;"></i>
                                ✅ TERMINER LA COMMANDE
                            </button>
                        </div>
                    </form>
                </div>
            @endif
            
            @if($commande->statut === 'terminee')
                <div class="inline-block px-8 py-4 bg-blue-100 text-blue-800 rounded-xl font-bold text-lg border-4 border-blue-300">
                    <i class="fas fa-check-double mr-3 text-xl"></i>
                    ✅ COMMANDE TERMINÉE
                </div>
            @endif
            
            @if($commande->statut === 'annulee')
                <div class="inline-block px-8 py-4 bg-red-100 text-red-800 rounded-xl font-bold text-lg border-4 border-red-300">
                    <i class="fas fa-ban mr-3 text-xl"></i>
                    ❌ COMMANDE ANNULÉE
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
