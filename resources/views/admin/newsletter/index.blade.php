@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">📧 Newsletter</h1>
            <div class="text-right">
                <div class="text-sm text-gray-600">Clients abonnés</div>
                <div class="text-2xl font-bold text-blue-600">{{ $clientsAbonnes }}</div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.newsletter.envoyer') }}" method="POST" id="newsletterForm">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Formulaire de composition -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold mb-4">Composer la newsletter</h2>
                        
                        <div class="mb-4">
                            <label for="sujet" class="block text-sm font-medium text-gray-700 mb-2">
                                Sujet de l'email *
                            </label>
                            <input type="text" 
                                   id="sujet" 
                                   name="sujet" 
                                   value="{{ old('sujet') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ex: Nouveaux burgers disponibles !"
                                   required>
                            @error('sujet')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="contenu" class="block text-sm font-medium text-gray-700 mb-2">
                                Contenu de la newsletter *
                            </label>
                            <textarea id="contenu" 
                                      name="contenu" 
                                      rows="12"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Rédigez votre newsletter ici..."
                                      required>{{ old('contenu') }}</textarea>
                            @error('contenu')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir envoyer cette newsletter à {{ $clientsAbonnes }} client(s) ?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md transition duration-200">
                                📤 Envoyer la newsletter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Aperçu et statistiques -->
                <div class="lg:col-span-1">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">📊 Informations</h3>
                        
                        <div class="space-y-4">
                            <div class="bg-white rounded p-4">
                                <div class="text-sm text-gray-600">Destinataires</div>
                                <div class="text-xl font-bold text-blue-600">{{ $clientsAbonnes }}</div>
                                <div class="text-xs text-gray-500">clients abonnés</div>
                            </div>

                            <div class="bg-white rounded p-4">
                                <div class="text-sm text-gray-600">Dernière newsletter</div>
                                <div class="text-sm font-medium text-gray-800">-</div>
                                <div class="text-xs text-gray-500">Pas encore envoyée</div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                                <div class="flex items-center">
                                    <div class="text-yellow-600 mr-2">⚠️</div>
                                    <div class="text-sm text-yellow-800">
                                        <strong>Attention :</strong> Cette action enverra un email à tous les clients abonnés à la newsletter.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection
