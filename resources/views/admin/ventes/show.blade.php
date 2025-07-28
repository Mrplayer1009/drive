@extends('layouts.admin')

@section('title', 'Détails de la Vente')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Détails de la Vente</h1>
                <p class="text-black">Vente #{{ $vente->id }} - {{ $vente->franchise->nom_complet }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.ventes.edit', $vente) }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.ventes.download', $vente) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-download mr-2"></i>
                    Télécharger PDF
                </a>
                <a href="{{ route('admin.ventes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations de la vente -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations de la vente</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Date de vente</p>
                        <p class="text-sm text-black">{{ $vente->date_vente->format('d/m/Y') }}</p>
                    </div>
                    <i class="fas fa-calendar text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Franchisé</p>
                        <p class="text-sm text-black">{{ $vente->franchise->nom_complet }}</p>
                    </div>
                    <i class="fas fa-user text-orange-600"></i>
                </div>

                @if($vente->camion)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Camion</p>
                        <p class="text-sm text-black">{{ $vente->camion->immatriculation }}</p>
                    </div>
                    <i class="fas fa-truck text-orange-600"></i>
                </div>
                @endif

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Nombre de commandes</p>
                        <p class="text-sm text-black">{{ $vente->nombre_commandes }}</p>
                    </div>
                    <i class="fas fa-shopping-cart text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Montants -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Montants</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border border-green-200">
                    <div>
                        <p class="text-sm font-medium text-black">Montant total</p>
                        <p class="text-lg font-bold text-black">{{ $vente->montant_total_formate }}</p>
                    </div>
                    <i class="fas fa-euro-sign text-green-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg border border-orange-200">
                    <div>
                        <p class="text-sm font-medium text-black">Montant reversé ({{ $vente->franchise->pourcentage_ventes }}%)</p>
                        <p class="text-lg font-bold text-black">{{ $vente->montant_reverse_formate }}</p>
                    </div>
                    <i class="fas fa-chart-line text-orange-600 text-2xl"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div>
                        <p class="text-sm font-medium text-black">Montant net</p>
                        <p class="text-lg font-bold text-black">{{ number_format($vente->montant_total - $vente->montant_reverse, 2, ',', ' ') }} €</p>
                    </div>
                    <i class="fas fa-calculator text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if($vente->notes)
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Notes</h3>
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-black">{{ $vente->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Informations du franchisé -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Informations du franchisé</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-black mb-2">Contact</h4>
                <div class="space-y-2">
                    <p class="text-sm text-black"><strong>Email :</strong> {{ $vente->franchise->email }}</p>
                    <p class="text-sm text-black"><strong>Téléphone :</strong> {{ $vente->franchise->telephone }}</p>
                    <p class="text-sm text-black"><strong>Ville :</strong> {{ $vente->franchise->ville }}</p>
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-black mb-2">Statistiques</h4>
                <div class="space-y-2">
                    <p class="text-sm text-black"><strong>Total des ventes :</strong> {{ number_format($vente->franchise->ventes->sum('montant_total'), 2, ',', ' ') }} €</p>
                    <p class="text-sm text-black"><strong>Total reversé :</strong> {{ number_format($vente->franchise->ventes->sum('montant_reverse'), 2, ',', ' ') }} €</p>
                    <p class="text-sm text-black"><strong>Nombre de ventes :</strong> {{ $vente->franchise->ventes->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 