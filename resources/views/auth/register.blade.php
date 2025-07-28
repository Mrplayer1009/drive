<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Driv'n Cook</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-orange-50 to-red-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-orange-100">
                <i class="fas fa-truck text-orange-600 text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-black">
                Inscription Franchisé
            </h2>
            <p class="mt-2 text-center text-sm text-black">
                Rejoignez le réseau Driv'n Cook
            </p>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="prenom" class="block text-sm font-medium text-black mb-2">
                            Prénom
                        </label>
                        <input id="prenom" name="prenom" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="Prénom">
                    </div>
                    <div>
                        <label for="nom" class="block text-sm font-medium text-black mb-2">
                            Nom
                        </label>
                        <input id="nom" name="nom" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="Nom">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-black mb-2">
                        Adresse email
                    </label>
                    <input id="email" name="email" type="email" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="email@exemple.com">
                </div>

                <div class="mb-4">
                    <label for="telephone" class="block text-sm font-medium text-black mb-2">
                        Téléphone
                    </label>
                    <input id="telephone" name="telephone" type="tel" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="01 23 45 67 89">
                </div>

                <div class="mb-4">
                    <label for="adresse" class="block text-sm font-medium text-black mb-2">
                        Adresse
                    </label>
                    <textarea id="adresse" name="adresse" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="123 Rue de la Paix" rows="3"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="ville" class="block text-sm font-medium text-black mb-2">
                            Ville
                        </label>
                        <input id="ville" name="ville" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="Paris">
                    </div>
                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-black mb-2">
                            Code postal
                        </label>
                        <input id="code_postal" name="code_postal" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="75001">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="date_entree" class="block text-sm font-medium text-black mb-2">
                        Date d'entrée souhaitée
                    </label>
                    <input id="date_entree" name="date_entree" type="date" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-black mb-2">
                        Mot de passe
                    </label>
                    <input id="password" name="password" type="password" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="Mot de passe">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-black mb-2">
                        Confirmer le mot de passe
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-black focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="Confirmer le mot de passe">
                </div>
            </div>

            <div class="mb-4">
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-black">
                        J'accepte les <a href="#" class="text-orange-600 hover:text-orange-500">conditions d'utilisation</a> et la <a href="#" class="text-orange-600 hover:text-orange-500">politique de confidentialité</a>
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-orange-500 group-hover:text-orange-400"></i>
                    </span>
                    S'inscrire
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="font-medium text-orange-600 hover:text-orange-500">
                    <i class="fas fa-sign-in-alt mr-1"></i>
                    Déjà inscrit ? Se connecter
                </a>
            </div>
        </form>

        <!-- Informations importantes -->
        <div class="mt-8 p-4 bg-orange-50 rounded-lg border border-orange-200">
            <h3 class="text-sm font-medium text-black mb-2">
                <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                Informations importantes
            </h3>
            <div class="text-xs text-black space-y-1">
                <p><strong>Droits d'entrée :</strong> 50 000 €</p>
                <p><strong>Pourcentage de ventes :</strong> 4% reversé automatiquement</p>
                <p><strong>Formation :</strong> Incluse dans les droits d'entrée</p>
                <p><strong>Support :</strong> Assistance technique et commerciale</p>
            </div>
        </div>
    </div>
</body>
</html> 