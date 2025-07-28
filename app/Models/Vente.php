<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'camion_id',
        'date_vente',
        'montant_total',
        'pourcentage_reverse',
        'montant_reverse',
        'nombre_commandes',
        'notes',
        'pdf_path',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'pourcentage_reverse' => 'decimal:2',
        'montant_reverse' => 'decimal:2',
        'date_vente' => 'date',
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
    public function getMontantTotalFormateAttribute()
    {
        return number_format($this->montant_total, 2, ',', ' ') . ' €';
    }

    public function getMontantReverseFormateAttribute()
    {
        return number_format($this->montant_reverse, 2, ',', ' ') . ' €';
    }

    public function getDateVenteFormateeAttribute()
    {
        return $this->date_vente->format('d/m/Y');
    }

    public function getPdfUrlAttribute()
    {
        if ($this->pdf_path) {
            return asset('storage/' . $this->pdf_path);
        }
        return null;
    }
} 