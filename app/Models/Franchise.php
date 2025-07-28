<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Franchise extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'date_entree',
        'statut',
        'droits_entree',
        'pourcentage_ventes',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_entree' => 'date',
        'droits_entree' => 'decimal:2',
        'pourcentage_ventes' => 'decimal:2',
    ];

    // Relations
    public function camions()
    {
        return $this->belongsToMany(Camion::class, 'franchise_camion')
                    ->withPivot('date_attribution', 'statut')
                    ->withTimestamps();
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
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
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getCamionsActifsAttribute()
    {
        return $this->camions()->wherePivot('statut', 'actif')->get();
    }

    public function getTotalVentesMensuel($mois = null, $annee = null)
    {
        $query = $this->ventes();
        
        if ($mois && $annee) {
            $query->whereYear('date_vente', $annee)
                  ->whereMonth('date_vente', $mois);
        }
        
        return $query->sum('montant_total');
    }

    public function getTotalReverseMensuel($mois = null, $annee = null)
    {
        $query = $this->ventes();
        
        if ($mois && $annee) {
            $query->whereYear('date_vente', $annee)
                  ->whereMonth('date_vente', $mois);
        }
        
        return $query->sum('montant_reverse');
    }
} 