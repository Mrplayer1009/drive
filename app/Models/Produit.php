<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'categorie',
        'prix_unitaire',
        'unite_mesure',
        'stock_minimum',
        'obligatoire',
        'image',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'obligatoire' => 'boolean',
    ];

    // Relations
    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produits')
                    ->withPivot('quantite', 'prix_unitaire', 'prix_total')
                    ->withTimestamps();
    }

    // Relations avec les stocks
    public function stocksEntrepots()
    {
        return $this->hasMany(EntrepotProduitStock::class);
    }

    public function stocksFranchises()
    {
        return $this->hasMany(FranchiseProduitStock::class);
    }

    // Méthodes utilitaires
    public function getPrixFormateAttribute()
    {
        return number_format($this->prix_unitaire, 2, ',', ' ') . ' €';
    }

    public function getCategorieLabelAttribute()
    {
        $labels = [
            'ingredients' => 'Ingrédients',
            'plats' => 'Plats',
            'boissons' => 'Boissons',
        ];
        
        return $labels[$this->categorie] ?? $this->categorie;
    }

    // Méthodes pour gérer les stocks
    public function getStockEntrepot($entrepotId)
    {
        return $this->stocksEntrepots()->where('entrepot_id', $entrepotId)->first();
    }

    public function getStockFranchise($franchiseId)
    {
        return $this->stocksFranchises()->where('franchise_id', $franchiseId)->first();
    }

    public function getStockTotalEntrepots()
    {
        return $this->stocksEntrepots()->sum('quantite_stock');
    }

    public function getStockTotalFranchises()
    {
        return $this->stocksFranchises()->sum('quantite_stock');
    }
} 