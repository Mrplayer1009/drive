@extends('layouts.admin')

@section('title', 'Modifier le Menu')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Modifier le Menu</h1>
                <p class="text-black">{{ $menu->nom }}</p>
            </div>
            <a href="{{ route('admin.menus.show', $menu) }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations du menu -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Informations du menu</h3>
                
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
                    <label for="nom" class="block text-sm font-medium text-black mb-2">Nom du menu *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $menu->nom) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-black mb-2">Description *</label>
                    <textarea id="description" name="description" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('description', $menu->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="categorie" class="block text-sm font-medium text-black mb-2">Catégorie *</label>
                    <select id="categorie" name="categorie" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="burger" {{ old('categorie', $menu->categorie) === 'burger' ? 'selected' : '' }}>Burger</option>
                        <option value="boisson" {{ old('categorie', $menu->categorie) === 'boisson' ? 'selected' : '' }}>Boisson</option>
                        <option value="dessert" {{ old('categorie', $menu->categorie) === 'dessert' ? 'selected' : '' }}>Dessert</option>
                        <option value="accompagnement" {{ old('categorie', $menu->categorie) === 'accompagnement' ? 'selected' : '' }}>Accompagnement</option>
                        <option value="entree" {{ old('categorie', $menu->categorie) === 'entree' ? 'selected' : '' }}>Entrée</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="prix" class="block text-sm font-medium text-black mb-2">Prix (€) *</label>
                    <input type="number" id="prix" name="prix" value="{{ old('prix', $menu->prix) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label for="ordre_affichage" class="block text-sm font-medium text-black mb-2">Ordre d'affichage</label>
                    <input type="number" id="ordre_affichage" name="ordre_affichage" value="{{ old('ordre_affichage', $menu->ordre_affichage) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="disponible" value="1" {{ old('disponible', $menu->disponible) ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Menu disponible</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="special" value="1" {{ old('special', $menu->special) ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Menu spécial (promotion)</span>
                    </label>
                </div>
            </div>

            <!-- Image du menu -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-black mb-4">Image du menu</h3>
                
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-black mb-2">Image du menu</label>
                    
                    @if($menu->image)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->nom }}" class="h-32 w-auto rounded-lg border">
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

                <!-- Statistiques du menu -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Statistiques du menu</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-black">Commandes totales :</span>
                            <span class="text-sm font-medium text-black">{{ $menu->commandes->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-black">Quantité totale commandée</p>
                            </div>
                            <span class="text-sm font-medium text-black">{{ $menu->commandes->sum('pivot.quantite') }} unités</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-black">Valeur totale commandée</p>
                            </div>
                            <span class="text-sm font-medium text-black">{{ number_format($menu->commandes->sum('pivot.prix_total'), 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.menus.show', $menu) }}" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-orange-600 text-black rounded-md hover:bg-orange-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>
                    Sauvegarder les modifications
                </button>
            </div>
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
