<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'entrepot_id',
        'statut',
        'total_commande',
        'total_obligatoire',
        'total_libre',
        'notes',
        'notes_admin',
        'pdf_path',
        'date_commande',
        'date_livraison',
    ];

    protected $casts = [
        'total_commande' => 'decimal:2',
        'total_obligatoire' => 'decimal:2',
        'total_libre' => 'decimal:2',
        'date_commande' => 'date',
        'date_livraison' => 'date',
    ];

    // Relations
    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function entrepot()
    {
        return $this->belongsTo(Entrepot::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_produits')
                    ->withPivot('quantite', 'prix_unitaire', 'prix_total')
                    ->withTimestamps();
    }

    // Méthodes utilitaires
    public function getStatutLabelAttribute()
    {
        $labels = [
            'en_attente' => 'En attente',
            'validee' => 'Validée',
            'refusee' => 'Refusée',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
        ];
        
        return $labels[$this->statut] ?? $this->statut;
    }

    public function getTotalFormateAttribute()
    {
        return number_format($this->total_commande, 2, ',', ' ') . ' €';
    }

    public function getPourcentageObligatoireAttribute()
    {
        if ($this->total_commande > 0) {
            return round(($this->total_obligatoire / $this->total_commande) * 100, 2);
        }
        return 0;
    }

    public function verifierRegle8020()
    {
        if ($this->total_commande > 0) {
            $pourcentage = ($this->total_obligatoire / $this->total_commande) * 100;
            return $pourcentage >= 80;
        }
        return true;
    }
} 