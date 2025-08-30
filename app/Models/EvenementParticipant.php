<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvenementParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'evenement_id',
        'client_id',
        'points_payes',
        'statut',
        'date_inscription'
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
    ];

    /**
     * Relation avec l'événement
     */
    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    /**
     * Relation avec le client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope pour les inscriptions confirmées
     */
    public function scopeConfirme($query)
    {
        return $query->where('statut', 'confirme');
    }
}
