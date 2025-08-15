<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseProduitStock extends Model
{
    use HasFactory;

    protected $table = 'franchise_produit_stocks';

    protected $fillable = [
        'franchise_id',
        'produit_id',
        'quantite_stock',
        'stock_minimum',
    ];

    protected $casts = [
        'quantite_stock' => 'decimal:2',
        'stock_minimum' => 'decimal:2',
    ];

    // Relations
    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // Méthodes utilitaires
    public function getStockDisponibleAttribute()
    {
        return $this->quantite_stock;
    }

    public function getStockInsuffisantAttribute()
    {
        return $this->quantite_stock <= $this->stock_minimum;
    }

    public function getStockCritiqueAttribute()
    {
        return $this->quantite_stock <= 0;
    }

    // Méthodes pour gérer le stock
    public function ajouterStock($quantite)
    {
        $this->increment('quantite_stock', $quantite);
        return $this;
    }

    public function retirerStock($quantite)
    {
        if ($this->quantite_stock >= $quantite) {
            $this->decrement('quantite_stock', $quantite);
            return true;
        }
        return false;
    }
}
