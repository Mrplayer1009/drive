<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
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
        'password',
        'langue',
        'newsletter_active',
        'points_fidelite',
        'niveau_fidelite',
        'reduction_cumulee',
        'statut',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'newsletter_active' => 'boolean',
        'points_fidelite' => 'integer',
        'niveau_fidelite' => 'integer',
        'reduction_cumulee' => 'decimal:2',
    ];

    // Relations
    public function commandes()
    {
        return $this->hasMany(CommandeClient::class);
    }

    /**
     * Relation avec les événements (participations)
     */
    public function evenements()
    {
        return $this->belongsToMany(Evenement::class, 'evenement_participants')
                    ->withPivot('points_payes', 'statut', 'date_inscription')
                    ->withTimestamps();
    }

    /**
     * Relation avec les participations aux événements
     */
    public function participationsEvenements()
    {
        return $this->hasMany(EvenementParticipant::class);
    }

    // Méthodes utilitaires
    public function getNomCompletAttribute()
    {
        if (empty($this->prenom)) {
            return $this->nom;
        }
        return $this->prenom . ' ' . $this->nom;
    }

    public function getLangueLabelAttribute()
    {
        $labels = [
            'fr' => 'Français',
            'en' => 'English',
            'es' => 'Español',
        ];
        
        return $labels[$this->langue] ?? $this->langue;
    }

    public function ajouterPoints($points)
    {
        $this->increment('points_fidelite', $points);
    }

    public function utiliserPoints($points)
    {
        if ($this->points_fidelite >= $points) {
            $this->decrement('points_fidelite', $points);
            return true;
        }
        return false;
    }
}
