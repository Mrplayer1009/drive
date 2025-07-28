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
        'capacite_stockage',
        'cuisine',
        'statut',
    ];

    protected $casts = [
        'cuisine' => 'boolean',
        'capacite_stockage' => 'decimal:2',
    ];

    // Relations
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    // Méthodes utilitaires
    public function getAdresseCompleteAttribute()
    {
        return $this->adresse . ', ' . $this->code_postal . ' ' . $this->ville;
    }
} 