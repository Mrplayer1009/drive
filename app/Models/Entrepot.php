<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'code_postal',
        'telephone',
        'capacite_stockage',
        'cuisine',
        'statut',
    ];

    protected $casts = [
        'cuisine' => 'boolean',
        'capacite_stockage' => 'decimal:0',
    ];

    // Relations
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    // Relations avec les stocks
    public function stocksProduits()
    {
        return $this->hasMany(EntrepotProduitStock::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'entrepot_produit_stocks')
                    ->withPivot('quantite_stock', 'stock_minimum')
                    ->withTimestamps();
    }

    // Méthodes utilitaires
    public function getAdresseCompleteAttribute()
    {
        return $this->adresse . ', ' . $this->code_postal . ' ' . $this->ville;
    }

    // Méthodes pour gérer les stocks
    public function getStockProduit($produitId)
    {
        return $this->stocksProduits()->where('produit_id', $produitId)->first();
    }

    public function getStockTotal()
    {
        return $this->stocksProduits()->sum('quantite_stock');
    }

    public function getProduitsEnRupture()
    {
        return $this->stocksProduits()->where('quantite_stock', '<=', 0)->with('produit')->get();
    }

    public function getProduitsStockInsuffisant()
    {
        return $this->stocksProduits()
                    ->whereRaw('quantite_stock <= stock_minimum')
                    ->where('quantite_stock', '>', 0)
                    ->with('produit')
                    ->get();
    }

    public function ajouterStockProduit($produitId, $quantite, $stockMinimum = 0)
    {
        $stock = $this->stocksProduits()->firstOrCreate(
            ['produit_id' => $produitId],
            ['quantite_stock' => 0, 'stock_minimum' => $stockMinimum]
        );

        $stock->ajouterStock($quantite);
        return $stock;
    }

    public function retirerStockProduit($produitId, $quantite)
    {
        $stock = $this->getStockProduit($produitId);
        
        if ($stock) {
            return $stock->retirerStock($quantite);
        }
        
        return false;
    }
} 