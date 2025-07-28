<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camion extends Model
{
    use HasFactory;

    protected $fillable = [
        'immatriculation',
        'marque',
        'modele',
        'annee',
        'statut',
        'ville_localisation',
        'latitude',
        'longitude',
        'derniere_maintenance',
        'prochaine_maintenance',
        'notes',
    ];

    protected $casts = [
        'derniere_maintenance' => 'date',
        'prochaine_maintenance' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relations
    public function franchises()
    {
        return $this->belongsToMany(Franchise::class, 'franchise_camion')
                    ->withPivot('date_attribution', 'statut')
                    ->withTimestamps();
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function demandesCamions()
    {
        return $this->hasMany(DemandeCamion::class);
    }

    public function notificationsPannes()
    {
        return $this->hasMany(NotificationPanne::class);
    }

    // Méthodes utilitaires
    public function getFranchiseActuelAttribute()
    {
        return $this->franchises()->wherePivot('statut', 'actif')->first();
    }

    public function getLocalisationCompleteAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return $this->latitude . ', ' . $this->longitude;
        }
        return $this->ville_localisation ?? 'Non localisé';
    }

    public function getProchaineMaintenanceAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::parse($value);
        }
        return null;
    }

    public function getProchaineMaintenanceFormateeAttribute()
    {
        if ($this->prochaine_maintenance) {
            return $this->prochaine_maintenance->format('d/m/Y');
        }
        return 'Non programmée';
    }
} 