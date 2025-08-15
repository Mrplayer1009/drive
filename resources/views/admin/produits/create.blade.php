@extends('layouts.admin')

@section('title', 'Nouveau Produit')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black mb-2">Nouveau Produit</h1>
                <p class="text-black">Ajouter un nouveau produit au catalogue</p>
            </div>
            <a href="{{ route('admin.produits.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.produits.store') }}" method="POST">
        @csrf
        
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
                    <label for="nom" class="block text-sm font-medium text-black mb-2">Nom du produit *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Ex: Tomates fraîches">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-black mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Description détaillée du produit...">{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="categorie" class="block text-sm font-medium text-black mb-2">Catégorie *</label>
                    <select id="categorie" name="categorie" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Sélectionner une catégorie</option>
                        <option value="ingredients" {{ old('categorie') === 'ingredients' ? 'selected' : '' }}>Ingrédients</option>
                        <option value="plats" {{ old('categorie') === 'plats' ? 'selected' : '' }}>Plats</option>
                        <option value="boissons" {{ old('categorie') === 'boissons' ? 'selected' : '' }}>Boissons</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="prix_unitaire" class="block text-sm font-medium text-black mb-2">Prix unitaire (€) *</label>
                    <input type="number" id="prix_unitaire" name="prix_unitaire" value="{{ old('prix_unitaire') }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="0.00">
                </div>

                <div class="mb-4">
                    <label for="unite_mesure" class="block text-sm font-medium text-black mb-2">Unité de mesure *</label>
                    <select id="unite_mesure" name="unite_mesure" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Sélectionner une unité</option>
                        <option value="G" {{ old('unite_mesure') === 'G' ? 'selected' : '' }}>G (Grammes)</option>
                        <option value="KG" {{ old('unite_mesure') === 'KG' ? 'selected' : '' }}>KG (Kilogrammes)</option>
                        <option value="Litre" {{ old('unite_mesure') === 'Litre' ? 'selected' : '' }}>Litre</option>
                        <option value="Pièce" {{ old('unite_mesure') === 'Pièce' ? 'selected' : '' }}>Pièce</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="obligatoire" value="1" {{ old('obligatoire') ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-black">Produit obligatoire (80% minimum)</span>
                    </label>
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
                        <p>• Choisissez soigneusement la catégorie et l'unité de mesure</p>
                        <p>• Le prix unitaire doit être cohérent avec l'unité de mesure</p>
                    </div>
                </div>

                                 <!-- Unités de mesure disponibles -->
                 <div class="mt-6">
                     <h4 class="text-sm font-medium text-black mb-3">Unités de mesure disponibles</h4>
                     <div class="space-y-2">
                         <div class="p-2 bg-gray-50 rounded">
                             <span class="text-xs font-medium text-black">G (Grammes) :</span>
                             <span class="text-xs text-black">Pour les petits ingrédients</span>
                         </div>
                         <div class="p-2 bg-gray-50 rounded">
                             <span class="text-xs font-medium text-black">KG (Kilogrammes) :</span>
                             <span class="text-xs text-black">Pour les ingrédients en vrac</span>
                         </div>
                         <div class="p-2 bg-gray-50 rounded">
                             <span class="text-xs font-medium text-black">Litre :</span>
                             <span class="text-xs text-black">Pour les liquides</span>
                         </div>
                         <div class="p-2 bg-gray-50 rounded">
                             <span class="text-xs font-medium text-black">Pièce :</span>
                             <span class="text-xs text-black">Pour les produits unitaires</span>
                         </div>
                     </div>
                 </div>

                <!-- Aperçu du produit -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-black mb-3">Aperçu du produit</h4>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-black">Nom :</span>
                                <span class="text-sm font-medium text-black" id="preview-nom">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-black">Catégorie :</span>
                                <span class="text-sm font-medium text-black" id="preview-categorie">-</span>
                            </div>
                                                         <div class="flex justify-between">
                                 <span class="text-sm text-black">Prix :</span>
                                 <span class="text-sm font-medium text-black" id="preview-prix">-</span>
                             </div>
                             <div class="flex justify-between">
                                 <span class="text-sm text-black">Unité :</span>
                                 <span class="text-sm font-medium text-black" id="preview-unite">-</span>
                             </div>
                             <div class="flex justify-between">
                                 <span class="text-sm text-black">Type :</span>
                                 <span class="text-sm font-medium text-black" id="preview-type">-</span>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.produits.index') }}" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 transition duration-300">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-orange-600 text-black rounded-md hover:bg-orange-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>
                    Créer le produit
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nomInput = document.getElementById('nom');
    const categorieSelect = document.getElementById('categorie');
    const prixInput = document.getElementById('prix_unitaire');
    const uniteSelect = document.getElementById('unite_mesure');
    const obligatoireCheckbox = document.querySelector('input[name="obligatoire"]');

    // Fonction pour mettre à jour l'aperçu
    function updatePreview() {
        const nom = nomInput.value || '-';
        const categorie = categorieSelect.options[categorieSelect.selectedIndex]?.text || '-';
        const prix = prixInput.value ? prixInput.value + ' €' : '-';
        const unite = uniteSelect.options[uniteSelect.selectedIndex]?.text || '-';
        const type = obligatoireCheckbox.checked ? 'Obligatoire' : 'Optionnel';

        document.getElementById('preview-nom').textContent = nom;
        document.getElementById('preview-categorie').textContent = categorie;
        document.getElementById('preview-prix').textContent = prix;
        document.getElementById('preview-unite').textContent = unite;
        document.getElementById('preview-type').textContent = type;
    }

    // Écouter les changements
    nomInput.addEventListener('input', updatePreview);
    categorieSelect.addEventListener('change', updatePreview);
    prixInput.addEventListener('input', updatePreview);
    uniteSelect.addEventListener('change', updatePreview);
    obligatoireCheckbox.addEventListener('change', updatePreview);

    // Initialiser l'aperçu
    updatePreview();
});
</script>
@endsection
