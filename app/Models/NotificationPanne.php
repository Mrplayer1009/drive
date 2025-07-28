<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPanne extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'camion_id',
        'type_panne',
        'gravite',
        'description_panne',
        'symptomes',
        'statut',
        'commentaire_admin',
        'date_resolution',
    ];

    protected $casts = [
        'date_resolution' => 'datetime',
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
            'signalee' => 'Signalée',
            'en_maintenance' => 'En maintenance',
            'resolue' => 'Résolue',
            'ignoree' => 'Ignorée',
        ];
        
        return $labels[$this->statut] ?? $this->statut;
    }

    public function getTypePanneLabelAttribute()
    {
        $labels = [
            'mecanique' => 'Mécanique',
            'electrique' => 'Électrique',
            'pneumatique' => 'Pneumatique',
            'autre' => 'Autre',
        ];
        
        return $labels[$this->type_panne] ?? $this->type_panne;
    }

    public function getGraviteLabelAttribute()
    {
        $labels = [
            'legere' => 'Légère',
            'moderee' => 'Modérée',
            'grave' => 'Grave',
            'critique' => 'Critique',
        ];
        
        return $labels[$this->gravite] ?? $this->gravite;
    }

    public function getGraviteColorAttribute()
    {
        $colors = [
            'legere' => 'bg-green-100 text-green-800',
            'moderee' => 'bg-yellow-100 text-yellow-800',
            'grave' => 'bg-orange-100 text-orange-800',
            'critique' => 'bg-red-100 text-red-800',
        ];
        
        return $colors[$this->gravite] ?? 'bg-gray-100 text-gray-800';
    }
}
