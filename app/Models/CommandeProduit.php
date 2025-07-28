<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeProduit extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'prix_total',
        'obligatoire',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'prix_total' => 'decimal:2',
        'obligatoire' => 'boolean',
    ];

    // Relations
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // Méthodes utilitaires
    public function getPrixTotalFormateAttribute()
    {
        return number_format($this->prix_total, 2, ',', ' ') . ' €';
    }
} 