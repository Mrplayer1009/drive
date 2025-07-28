@extends('layouts.admin')

@section('title', 'Détails du Franchisé')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Détails du Franchisé</h1>
                <p class="text-black">{{ $franchise->nom_complet }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.franchises.edit', $franchise) }}" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.franchises.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations personnelles -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations personnelles</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-black mb-2">Identité</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-black">Nom complet :</span>
                                <p class="font-medium text-black">{{ $franchise->nom_complet }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-black">Email :</span>
                                <p class="font-medium text-black">{{ $franchise->email }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-black">Téléphone :</span>
                                <p class="font-medium text-black">{{ $franchise->telephone }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-black mb-2">Adresse</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-black">Adresse :</span>
                                <p class="font-medium text-black">{{ $franchise->adresse }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-black">Ville :</span>
                                <p class="font-medium text-black">{{ $franchise->ville }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-black">Code postal :</span>
                                <p class="font-medium text-black">{{ $franchise->code_postal }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Camions attribués -->
            <div class="bg-white shadow rounded-lg p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-black">Camions attribués</h3>
                    <button onclick="openAssignModal()" class="bg-orange-600 hover:bg-orange-700 text-black px-3 py-1 rounded text-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Assigner un camion
                    </button>
                </div>
                
                @if($franchise->camions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($franchise->camions as $camion)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-black">{{ $camion->immatriculation }}</h4>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    Actif
                                </span>
                            </div>
                            <p class="text-sm text-black">{{ $camion->marque }} {{ $camion->modele }}</p>
                            <p class="text-sm text-black">{{ $camion->ville_localisation }}</p>
                            <p class="text-xs text-gray-500">Attribué le {{ $camion->pivot->date_attribution->format('d/m/Y') }}</p>
                            <form action="{{ route('admin.franchises.remove-camion', ['franchise' => $franchise, 'camion' => $camion]) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-xs" onclick="return confirm('Retirer ce camion ?')">
                                    <i class="fas fa-times mr-1"></i>Retirer
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-black text-center py-4">Aucun camion attribué</p>
                @endif
            </div>

            <!-- Modal d'assignation de camion -->
            <div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-black mb-4">Assigner un camion</h3>
                        <form action="{{ route('admin.franchises.assign-camion', $franchise) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="camion_id" class="block text-sm font-medium text-black mb-2">Sélectionner un camion</label>
                                <select id="camion_id" name="camion_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">Choisir un camion...</option>
                                    @foreach($camions_disponibles ?? [] as $camion)
                                        <option value="{{ $camion->id }}">
                                            {{ $camion->immatriculation }} - {{ $camion->marque }} {{ $camion->modele }} ({{ $camion->ville_localisation }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="closeAssignModal()" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded text-sm">
                                    Annuler
                                </button>
                                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-4 py-2 rounded text-sm">
                                    Assigner
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function openAssignModal() {
                    document.getElementById('assignModal').classList.remove('hidden');
                }
                
                function closeAssignModal() {
                    document.getElementById('assignModal').classList.add('hidden');
                }
            </script>
        </div>

        <!-- Informations de compte -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-black mb-4">Informations de compte</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Statut</p>
                        <p class="text-sm text-black">{{ ucfirst($franchise->statut) }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                        {{ $franchise->statut === 'actif' ? 'bg-green-100 text-green-800' : 
                           ($franchise->statut === 'inactif' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($franchise->statut) }}
                    </span>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Date d'entrée</p>
                        <p class="text-sm text-black">{{ $franchise->date_entree->format('d/m/Y') }}</p>
                    </div>
                    <i class="fas fa-calendar text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Droits d'entrée</p>
                        <p class="text-sm text-black">{{ number_format($franchise->droits_entree, 2, ',', ' ') }} €</p>
                    </div>
                    <i class="fas fa-euro-sign text-orange-600"></i>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-black">Pourcentage de ventes</p>
                        <p class="text-sm text-black">{{ $franchise->pourcentage_ventes }}%</p>
                    </div>
                    <i class="fas fa-percentage text-orange-600"></i>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="mt-6 p-4 bg-orange-50 rounded-lg border border-orange-200">
                <h4 class="text-sm font-medium text-black mb-3">Statistiques</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-black">Total des ventes</span>
                        <span class="font-medium text-black">{{ number_format($franchise->ventes->sum('montant_total'), 2, ',', ' ') }} €</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-black">Total reversé</span>
                        <span class="font-medium text-black">{{ number_format($franchise->ventes->sum('montant_reverse'), 2, ',', ' ') }} €</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-black">Nombre de ventes</span>
                        <span class="font-medium text-black">{{ $franchise->ventes->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-black">Camions attribués</span>
                        <span class="font-medium text-black">{{ $franchise->camions_actifs }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventes récentes -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-black mb-4">Ventes récentes</h3>
        
        @if($franchise->ventes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Camion</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Commandes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Montant reversé</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($franchise->ventes->take(10) as $vente)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $vente->date_vente->format('d/m/Y') }}
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-black text-center py-4">Aucune vente enregistrée</p>
        @endif
    </div>
</div>
@endsection 
