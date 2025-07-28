<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeCamion extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'camion_id',
        'type_demande',
        'statut',
        'type_camion_souhaite',
        'localisation_souhaitee',
        'date_debut_souhaitee',
        'duree_attribution',
        'motif',
        'urgent',
        'commentaire_admin',
        'date_reponse',
    ];

    protected $casts = [
        'urgent' => 'boolean',
        'date_debut_souhaitee' => 'date',
        'date_reponse' => 'datetime',
    ];

    // Relations
    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    // Méthodes utilitaires
    public function getStatutLabelAttribute()
    {
        $labels = [
            'en_attente' => 'En attente',
            'approuvee' => 'Approuvée',
            'refusee' => 'Refusée',
        ];
        
        return $labels[$this->statut] ?? $this->statut;
    }

    public function getTypeDemandeLabelAttribute()
    {
        $labels = [
            'nouveau' => 'Nouveau camion',
            'remplacement' => 'Remplacement',
        ];
        
        return $labels[$this->type_demande] ?? $this->type_demande;
    }

    public function getDureeAttributionLabelAttribute()
    {
        $labels = [
            'temporaire' => 'Attribution temporaire (1-7 jours)',
            'semaine' => 'Une semaine',
            'mois' => 'Un mois',
            'permanent' => 'Attribution permanente',
        ];
        
        return $labels[$this->duree_attribution] ?? $this->duree_attribution;
    }

    public function getTypeCamionSouhaiteLabelAttribute()
    {
        $labels = [
            'petit' => 'Petit camion (3-5 tonnes)',
            'moyen' => 'Camion moyen (5-10 tonnes)',
            'grand' => 'Grand camion (10+ tonnes)',
            'refrigere' => 'Camion réfrigéré',
            'plateau' => 'Camion plateau',
        ];
        
        return $labels[$this->type_camion_souhaite] ?? $this->type_camion_souhaite;
    }
}
