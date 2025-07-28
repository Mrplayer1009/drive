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

    // Méthodes utilitaires
    public function getPrixFormateAttribute()
    {
        return number_format($this->prix_unitaire, 2, ',', ' ') . ' €';
    }

    public function getCategorieLabelAttribute()
    {
        $labels = [
            'ingredient' => 'Ingrédient',
            'plat' => 'Plat',
            'boisson' => 'Boisson',
        ];
        
        return $labels[$this->categorie] ?? $this->categorie;
    }
} 