@extends('layouts.admin')

@section('title', 'Modifier le Produit')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Modifier le Produit</h1>
                <p class="text-black">{{ $produit->nom }}</p>
            </div>
            <a href="{{ route('admin.produits.show', $produit) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.produits.update', $produit) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations du produit -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations du produit</h3>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-black mb-2">Nom du produit</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $produit->nom) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-black mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('description', $produit->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="categorie" class="block text-sm font-medium text-black mb-2">Catégorie</label>
                    <select id="categorie" name="categorie" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="ingredients" {{ old('categorie', $produit->categorie) === 'ingredients' ? 'selected' : '' }}>Ingrédients</option>
                        <option value="plats" {{ old('categorie', $produit->categorie) === 'plats' ? 'selected' : '' }}>Plats</option>
                        <option value="boissons" {{ old('categorie', $produit->categorie) === 'boissons' ? 'selected' : '' }}>Boissons</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="prix_unitaire" class="block text-sm font-medium text-black mb-2">Prix unitaire (€)</label>
                    <input type="number" id="prix_unitaire" name="prix_unitaire" value="{{ old('prix_unitaire', $produit->prix_unitaire) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="unite_mesure" class="block text-sm font-medium text-black mb-2">Unité de mesure</label>
                    <input type="text" id="unite_mesure" name="unite_mesure" value="{{ old('unite_mesure', $produit->unite_mesure) }}" maxlength="50" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="obligatoire" value="1" {{ old('obligatoire', $produit->obligatoire) ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Produit obligatoire (80% minimum)</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-black mb-2">Image du produit</label>
                    
                    @if($produit->image)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                            <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" class="h-32 w-auto rounded-lg border">
                        </div>
                    @endif
                    
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-orange-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                    <span>Télécharger une nouvelle image</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP jusqu'à 5MB</p>
                        </div>
                    </div>
                    <div id="image-preview" class="mt-2 hidden">
                        <p class="text-sm text-gray-600 mb-2">Nouvelle image :</p>
                        <img id="preview-img" src="" alt="Aperçu" class="h-32 w-auto rounded-lg border">
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations complémentaires</h3>
                
                <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <h4 class="text-sm font-medium text-black mb-2">
                        <i class="fas fa-info-circle text-orange-600 mr-1"></i>
                        Règles importantes
                    </h4>
                    <div class="text-xs text-black space-y-1">
                        <p>• Les produits obligatoires doivent représenter 80% minimum des commandes</p>
                        <p>• Les produits optionnels représentent les 20% restants</p>
                        <p>• La modification d'un produit peut affecter les commandes existantes</p>
                    </div>
                </div>

                <!-- Statistiques actuelles -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Statistiques actuelles</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-black">Commandes totales :</span>
                            <span class="text-sm font-medium text-black">{{ $produit->commandes->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-black">Quantité totale commandée</p>
                            </div>
                            <span class="text-sm font-medium text-black">{{ $produit->commandes->sum('pivot.quantite') }} {{ $produit->unite_mesure }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-black">Valeur totale commandée</p>
                            </div>
                            <span class="text-sm font-medium text-black">{{ number_format($produit->commandes->sum('pivot.prix_total'), 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>

                <!-- Catégories d'exemples -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Exemples par catégorie</h4>
                    <div class="text-xs text-black space-y-1">
                        <p><strong>Ingrédients :</strong> Viande, légumes, épices, farine...</p>
                        <p><strong>Plats :</strong> Burger, pizza, salade, dessert...</p>
                        <p><strong>Boissons :</strong> Soda, jus, café, thé...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.produits.show', $produit) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-6 py-2 rounded-lg transition duration-300">
                Annuler
            </a>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-black px-6 py-2 rounded-lg transition duration-300">
                <i class="fas fa-save mr-2"></i>
                Sauvegarder les modifications
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    // Gestion de la prévisualisation d'image
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Vérifier le type de fichier
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format de fichier non supporté. Veuillez utiliser JPEG, PNG, JPG, GIF ou WEBP.');
                imageInput.value = '';
                return;
            }
            
            // Vérifier la taille (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximum : 5MB.');
                imageInput.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }
    });

    // Glisser-déposer pour l'image
    const dropZone = document.querySelector('.border-dashed');
    
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('border-orange-400', 'bg-orange-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-orange-400', 'bg-orange-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-orange-400', 'bg-orange-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            imageInput.dispatchEvent(new Event('change'));
        }
    });
});
</script>
@endsection 
