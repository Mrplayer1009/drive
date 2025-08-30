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
        'latitude',
        'longitude',
        'adresse_emplacement',
        'disponible',
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

    // Relations avec les stocks
    public function stocksProduits()
    {
        return $this->hasMany(FranchiseProduitStock::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'franchise_produit_stocks')
                    ->withPivot('quantite_stock', 'stock_minimum')
                    ->withTimestamps();
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

    // Méthodes pour gérer les stocks
    public function getStockProduit($produitId)
    {
        return $this->stocksProduits()->where('produit_id', $produitId)->first();
    }

    public function getStockTotal()
    {
        return $this->stocksProduits()->sum('quantite_stock');
    }

    public function getProduitsEnRupture()
    {
        return $this->stocksProduits()->where('quantite_stock', '<=', 0)->with('produit')->get();
    }

    public function getProduitsStockInsuffisant()
    {
        return $this->stocksProduits()
                    ->whereRaw('quantite_stock <= stock_minimum')
                    ->where('quantite_stock', '>', 0)
                    ->with('produit')
                    ->get();
    }

    public function ajouterStockProduit($produitId, $quantite, $stockMinimum = 0)
    {
        $stock = $this->stocksProduits()->firstOrCreate(
            ['produit_id' => $produitId],
            ['quantite_stock' => 0, 'stock_minimum' => $stockMinimum]
        );

        $stock->ajouterStock($quantite);
        return $stock;
    }

    public function retirerStockProduit($produitId, $quantite)
    {
        $stock = $this->getStockProduit($produitId);
        
        if ($stock) {
            return $stock->retirerStock($quantite);
        }
        
        return false;
    }

    // Méthodes pour les food trucks
    public function commandesClients()
    {
        return $this->hasMany(\App\Models\CommandeClient::class, 'food_truck_id');
    }

    /**
     * Relation avec les événements
     */
    public function evenements()
    {
        return $this->hasMany(Evenement::class);
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        // Formule de Haversine pour calculer la distance
        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($latitude);
        $lon2 = deg2rad($longitude);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = 6371 * $c; // Rayon de la Terre en km

        return round($distance, 2);
    }

    public function getDistanceFormateeAttribute($latitude = null, $longitude = null)
    {
        if ($latitude && $longitude) {
            $distance = $this->getDistanceFrom($latitude, $longitude);
            return $distance ? $distance . ' km' : 'Distance inconnue';
        }
        return 'Distance non calculée';
    }

    public function scopeDisponible($query)
    {
        return $query->where('disponible', true);
    }

    public function scopeAvecCoordonnees($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    public function scopeAvecCamions($query)
    {
        return $query->whereHas('camions', function ($q) {
            $q->where('franchise_camion.statut', 'actif');
        });
    }

    public function hasCamionsActifs()
    {
        return $this->camions()->where('franchise_camion.statut', 'actif')->exists();
    }

    public function getCamionsActifsCount()
    {
        return $this->camions()->where('franchise_camion.statut', 'actif')->count();
    }

    public function assignerCamion($camionId)
    {
        // Vérifier si le franchisé a déjà un camion
        $camionExistant = $this->camions()->where('franchise_camion.statut', 'actif')->first();
        
        if ($camionExistant) {
            // Désactiver l'ancien camion
            $this->camions()->updateExistingPivot($camionExistant->id, ['statut' => 'inactif']);
        }

        // Assigner le nouveau camion
        $this->camions()->attach($camionId, ['statut' => 'actif']);
        
        return true;
    }

    public function retirerCamion()
    {
        $camionActuel = $this->camions()->where('franchise_camion.statut', 'actif')->first();
        
        if ($camionActuel) {
            $this->camions()->updateExistingPivot($camionActuel->id, ['statut' => 'inactif']);
            return true;
        }
        
        return false;
    }

    public function getCamionActuel()
    {
        return $this->camions()->where('franchise_camion.statut', 'actif')->first();
    }
} 