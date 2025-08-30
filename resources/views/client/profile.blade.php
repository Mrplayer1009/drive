@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50">

    <!-- Contenu du Profil -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations personnelles -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Carte de profil -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-user-circle text-orange-600 mr-2"></i>
                        Informations personnelles
                    </h2>
                    
                    <form action="{{ route('client.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                <input type="text" id="prenom" name="prenom" value="{{ $client->prenom }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                <input type="text" id="nom" name="nom" value="{{ $client->nom }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                            <input type="email" id="email" name="email" value="{{ $client->email }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        
                        <div class="mb-4">
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone" value="{{ $client->telephone }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        
                        <div class="mb-4">
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <textarea id="adresse" name="adresse" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ $client->adresse }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                <input type="text" id="ville" name="ville" value="{{ $client->ville }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                                <input type="text" id="code_postal" name="code_postal" value="{{ $client->code_postal }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="langue" class="block text-sm font-medium text-gray-700 mb-1">Langue préférée</label>
                            <select id="langue" name="langue" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="fr" {{ $client->langue === 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ $client->langue === 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ $client->langue === 'es' ? 'selected' : '' }}>Español</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="newsletter_active" value="1" {{ $client->newsletter_active ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Recevoir la newsletter</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-green-400">
                            <i class="fas fa-save mr-2"></i>
                            Sauvegarder les modifications
                        </button>
                    </form>
                </div>

                <!-- Changement de mot de passe -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-lock text-orange-600 mr-2"></i>
                        Changer le mot de passe
                    </h2>
                    
                    <form action="{{ route('client.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="change_password" value="1">
                        
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                            <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                            <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        
                        <div class="mb-6">
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-purple-400">
                            <i class="fas fa-key mr-2"></i>
                            Changer le mot de passe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Carte de fidélité -->
                <!-- Le service JavaScript se chargera de l'affichage -->
                
                <div class="bg-gradient-to-br from-yellow-400 via-orange-500 to-red-600 text-white rounded-xl shadow-2xl p-6 relative overflow-hidden border-4 border-yellow-300">
                    <!-- Effet de brillance -->
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-transparent via-yellow-200 to-transparent opacity-60"></div>
                    
                    <div class="text-center relative z-10">
                        <div class="bg-yellow-300 bg-opacity-30 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 border-2 border-yellow-200">
                            <i class="fas fa-id-card text-3xl text-yellow-100"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-yellow-100 drop-shadow-lg">Carte de Fidélité</h3>
                        <div class="text-5xl font-bold mb-2 text-yellow-100 drop-shadow-lg">0</div>
                        <p class="text-yellow-200 font-bold text-lg">POINTS</p>
                        <div class="mt-2">
                            <span class="bg-yellow-300 bg-opacity-50 px-3 py-1 rounded-full text-sm font-bold text-yellow-900">
                                Nouveau
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-6 space-y-3 text-sm relative z-10">
                        <div class="flex justify-between items-center bg-yellow-300 bg-opacity-30 rounded-lg p-3 border border-yellow-200">
                            <span class="text-yellow-100 font-semibold">Prochain palier :</span>
                            <span class="font-bold text-yellow-100 text-lg">50 points</span>
                        </div>
                        <div class="flex justify-between items-center bg-yellow-300 bg-opacity-30 rounded-lg p-3 border border-yellow-200">
                            <span class="text-yellow-100 font-semibold">Réduction disponible :</span>
                            <span class="font-bold text-yellow-100 text-lg">0,00 €</span>
                        </div>
                        <div class="flex justify-between items-center bg-yellow-300 bg-opacity-30 rounded-lg p-3 border border-yellow-200">
                            <span class="text-yellow-100 font-semibold">Réduction cumulée :</span>
                            <span class="font-bold text-yellow-100 text-lg">0,00 €</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 bg-yellow-300 bg-opacity-30 rounded-lg p-3 relative z-10 border border-yellow-200">
                        <p class="text-sm text-yellow-100 leading-relaxed font-semibold">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Comment ça marche :</strong><br>
                            1€ dépensé = 1 point gagné<br>
                            100 points = 5€ de réduction
                        </p>
                        <p class="text-xs text-yellow-200 mt-2" id="progression-fidelite" style="display: none;">
                            <i class="fas fa-arrow-up mr-1"></i>
                            Plus que <span id="points-manquants">0</span> points pour le niveau <span id="niveau-suivant">2</span> !
                        </p>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar text-orange-600 mr-2"></i>
                        Statistiques
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Commandes totales :</span>
                            <span class="font-semibold">{{ $commandes->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Montant total :</span>
                            <span class="font-semibold">{{ number_format($commandes->sum('montant_final'), 2, ',', ' ') }} €</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Membre depuis :</span>
                            <span class="font-semibold">{{ $client->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-orange-600 mr-2"></i>
                        Actions rapides
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('client.index') }}" class="block w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg transition duration-300 text-center font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-orange-400">
                            <i class="fas fa-utensils mr-2"></i>
                            Commander
                        </a>
                        <a href="{{ route('client.commandes') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg transition duration-300 text-center font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-blue-400">
                            <i class="fas fa-list mr-2"></i>
                            Mes commandes
                        </a>
                        <a href="{{ route('client.fidelite.index') }}" class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg transition duration-300 text-center font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-yellow-400">
                            <i class="fas fa-id-card mr-2"></i>
                            Ma fidélité
                        </a>
                        <form method="POST" action="{{ route('client.logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="block w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg transition duration-300 text-center font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border-2 border-red-400">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclure le service de fidélité JavaScript -->
<script src="{{ asset('js/fidelite.js') }}"></script>
<script>
// Mettre à jour l'affichage de la fidélité avec les données JavaScript
document.addEventListener('DOMContentLoaded', function() {
    if (window.fideliteService) {
        // Forcer la synchronisation avec les données de la base
        const pointsFromServer = {{ $client->points_fidelite }};
        
        // Mettre à jour le localStorage avec les vraies données
        const fidelite = JSON.parse(localStorage.getItem('drivncook_fidelite') || '{}');
        fidelite.points = pointsFromServer;
        fidelite.lastUpdate = new Date().toISOString();
        localStorage.setItem('drivncook_fidelite', JSON.stringify(fidelite));
        
        // Récupérer les infos mises à jour
        const infosFidelite = window.fideliteService.getInfosFidelite();
        
        // Mettre à jour les éléments de la carte de fidélité
        const pointsElement = document.querySelector('.text-5xl.font-bold.mb-2.text-yellow-100');
        if (pointsElement) {
            pointsElement.textContent = infosFidelite.points;
        }
        
        const niveauElement = document.querySelector('.bg-yellow-300.bg-opacity-50.px-3.py-1.rounded-full');
        if (niveauElement) {
            niveauElement.textContent = infosFidelite.niveau_nom;
        }
        
        // Mettre à jour les autres informations
        const elements = {
            'prochain-palier': infosFidelite.prochain_palier ? infosFidelite.prochain_palier + ' points' : 'Niveau max',
            'reduction-disponible': window.fideliteService.formaterPrix(infosFidelite.reduction_disponible),
            'reduction-cumulee': window.fideliteService.formaterPrix(infosFidelite.reduction_cumulee)
        };
        
        // Mettre à jour les spans dans les divs de statistiques
        const statDivs = document.querySelectorAll('.bg-yellow-300.bg-opacity-30.rounded-lg.p-3');
        statDivs.forEach((div, index) => {
            const span = div.querySelector('.font-bold.text-yellow-100.text-lg');
            if (span) {
                switch(index) {
                    case 0: // Prochain palier
                        span.textContent = elements['prochain-palier'];
                        break;
                    case 1: // Réduction disponible
                        span.textContent = elements['reduction-disponible'];
                        break;
                    case 2: // Réduction cumulée
                        span.textContent = elements['reduction-cumulee'];
                        break;
                }
            }
        });
        
        // Mettre à jour la progression vers le niveau suivant
        const progressionElement = document.getElementById('progression-fidelite');
        if (infosFidelite.points_pour_prochain_niveau > 0) {
            document.getElementById('points-manquants').textContent = infosFidelite.points_pour_prochain_niveau;
            document.getElementById('niveau-suivant').textContent = infosFidelite.niveau + 1;
            progressionElement.style.display = 'block';
        } else {
            progressionElement.style.display = 'none';
        }
    }
});
</script>

@endsection
