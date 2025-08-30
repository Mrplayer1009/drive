<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'categorie',
        'image',
        'disponible',
        'special',
        'ordre_affichage',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'disponible' => 'boolean',
        'special' => 'boolean',
        'ordre_affichage' => 'integer',
    ];

    // Relations
    public function commandes()
    {
        return $this->belongsToMany(CommandeClient::class, 'commande_client_menus')
                    ->withPivot('quantite', 'prix_unitaire', 'prix_total', 'notes')
                    ->withTimestamps();
    }

    // Méthodes utilitaires
    public function getPrixFormateAttribute()
    {
        return number_format($this->prix, 2, ',', ' ') . ' €';
    }

    public function getCategorieLabelAttribute()
    {
        $labels = [
            'burger' => 'Burgers',
            'boisson' => 'Boissons',
            'dessert' => 'Desserts',
            'accompagnement' => 'Accompagnements',
            'entree' => 'Entrées',
        ];
        
        return $labels[$this->categorie] ?? $this->categorie;
    }

    // Scopes
    public function scopeDisponible($query)
    {
        return $query->where('disponible', true);
    }

    public function scopeSpecial($query)
    {
        return $query->where('special', true);
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }
}
