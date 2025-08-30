<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'titre',
        'description',
        'date_evenement',
        'prix_points',
        'nombre_max_participants',
        'nombre_inscrits',
        'statut',
        'lieu'
    ];

    protected $casts = [
        'date_evenement' => 'datetime',
    ];

    /**
     * Relation avec la franchise
     */
    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    /**
     * Relation avec les participants
     */
    public function participants()
    {
        return $this->hasMany(EvenementParticipant::class);
    }

    /**
     * Relation avec les clients participants
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'evenement_participants')
                    ->withPivot('points_payes', 'statut', 'date_inscription')
                    ->withTimestamps();
    }

    /**
     * Scope pour les événements actifs
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les événements futurs
     */
    public function scopeFutur($query)
    {
        return $query->where('date_evenement', '>', now());
    }

    /**
     * Scope pour les événements disponibles (pas pleins)
     */
    public function scopeDisponible($query)
    {
        return $query->whereRaw('nombre_inscrits < nombre_max_participants');
    }

    /**
     * Vérifier si l'événement est complet
     */
    public function isComplet()
    {
        return $this->nombre_inscrits >= $this->nombre_max_participants;
    }

    /**
     * Vérifier si l'événement est passé
     */
    public function isPasse()
    {
        return $this->date_evenement < now();
    }

    /**
     * Vérifier si un client est inscrit
     */
    public function isClientInscrit($clientId)
    {
        return $this->participants()
                    ->where('client_id', $clientId)
                    ->where('statut', 'confirme')
                    ->exists();
    }

    /**
     * Vérifier si un client a une participation (quel que soit le statut)
     */
    public function hasClientParticipation($clientId)
    {
        return $this->participants()
                    ->where('client_id', $clientId)
                    ->exists();
    }

    /**
     * Obtenir le nombre de places disponibles
     */
    public function getPlacesDisponiblesAttribute()
    {
        return $this->nombre_max_participants - $this->nombre_inscrits;
    }

    /**
     * Formater la date pour l'affichage
     */
    public function getDateFormateeAttribute()
    {
        return $this->date_evenement->format('d/m/Y à H:i');
    }

    /**
     * Formater la date pour le calendrier
     */
    public function getDateCalendrierAttribute()
    {
        return $this->date_evenement->format('Y-m-d');
    }
}
