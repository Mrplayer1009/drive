<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'franchise_id',
        'food_truck_id',
        'statut',
        'montant_total',
        'reduction_fidelite',
        'montant_final',
        'notes',
        'adresse_livraison',
        'telephone_contact',
        'mode_paiement',
        'reference_paiement',
        'date_commande',
        'date_livraison_souhaitee',
        'date_livraison_effective',
        'code_recuperation',
        'date_generation_code',
        'date_recuperation',
        'token_paiement',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'reduction_fidelite' => 'decimal:2',
        'montant_final' => 'decimal:2',
        'date_commande' => 'datetime',
        'date_livraison_souhaitee' => 'datetime',
        'date_livraison_effective' => 'datetime',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function foodTruck()
    {
        return $this->belongsTo(Franchise::class, 'food_truck_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'commande_client_menus')
                    ->withPivot('quantite', 'prix_unitaire', 'prix_total', 'notes')
                    ->withTimestamps();
    }

    // Relation avec Vente
    public function vente()
    {
        return $this->hasOne(Vente::class, 'commande_client_id');
    }

    // Méthodes utilitaires
    public function getStatutLabelAttribute()
    {
        $labels = [
            'en_attente' => 'En attente',
            'en_attente_paiement' => 'En attente de paiement',
            'confirmee' => 'Confirmée',
            'en_preparation' => 'En préparation',
            'prete' => 'Prête',
            'terminee' => 'Terminée',
            'annulee' => 'Annulée',
        ];
        
        return $labels[$this->statut] ?? $this->statut;
    }

    public function getMontantTotalFormateAttribute()
    {
        return number_format($this->montant_total, 2, ',', ' ') . ' €';
    }

    public function getMontantFinalFormateAttribute()
    {
        return number_format($this->montant_final, 2, ',', ' ') . ' €';
    }

    public function calculerMontantFinal()
    {
        $this->montant_final = $this->montant_total - $this->reduction_fidelite;
        return $this->montant_final;
    }

    // Méthode pour créer une vente automatiquement (seulement quand prête)
    public function creerVente()
    {
        // Vérifier que la commande est prête
        if ($this->statut !== 'prete') {
            throw new \Exception('Une vente ne peut être créée que pour une commande prête');
        }

        if ($this->vente) {
            return $this->vente; // Une vente existe déjà
        }

        $franchise = $this->franchise;
        $montant_reverse = $this->montant_final * ($franchise->pourcentage_ventes / 100);

        return Vente::create([
            'franchise_id' => $this->franchise_id,
            'commande_client_id' => $this->id,
            'date_vente' => $this->date_commande,
            'montant_total' => $this->montant_final,
            'pourcentage_reverse' => $franchise->pourcentage_ventes,
            'montant_reverse' => $montant_reverse,
            'nombre_commandes' => 1, // Une commande client = 1 vente
            'notes' => 'Vente générée automatiquement depuis la commande client #' . $this->id . ' (Stripe) - Prête le ' . now()->format('d/m/Y'),
        ]);
    }

    // Méthode pour supprimer la vente si la commande est annulée
    public function supprimerVente()
    {
        if ($this->vente) {
            $this->vente->delete();
        }
    }

    // Méthodes pour le code de récupération
    public function genererCodeRecuperation()
    {
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Utiliser withoutEvents pour éviter la boucle infinie
        static::withoutEvents(function() use ($code) {
            $this->update([
                'code_recuperation' => $code,
                'date_generation_code' => now(),
            ]);
        });
        
        return $code;
    }

    public function verifierCodeRecuperation($code)
    {
        return $this->code_recuperation === $code;
    }

    public function recupererCommande()
    {
        $this->update([
            'date_recuperation' => now(),
            'statut' => 'terminee',
        ]);
    }

    // Observer pour gérer automatiquement les ventes selon le statut
    protected static function booted()
    {
        static::updated(function ($commandeClient) {
            // Si le statut a changé
            if ($commandeClient->wasChanged('statut')) {
                $ancienStatut = $commandeClient->getOriginal('statut');
                $nouveauStatut = $commandeClient->statut;

                // Si la commande passe à "prête", créer la vente
                if ($nouveauStatut === 'prete' && $ancienStatut !== 'prete') {
                    try {
                        // Vérifier que la commande n'a pas déjà une vente
                        if (!$commandeClient->vente) {
                            // Créer la vente
                            $commandeClient->creerVente();
                            
                            // Le code de récupération est déjà généré par le contrôleur
                            // L'email est déjà envoyé par le contrôleur
                            \Log::info('Vente créée automatiquement pour la commande #' . $commandeClient->id);
                        }
                        
                    } catch (\Exception $e) {
                        // Log l'erreur mais ne pas faire échouer la mise à jour
                        \Log::error('Erreur lors de la création de la vente pour la commande #' . $commandeClient->id . ': ' . $e->getMessage());
                    }
                }

                // Si la commande passe à "annulée", supprimer la vente
                if ($nouveauStatut === 'annulee' && $ancienStatut !== 'annulee') {
                    $commandeClient->supprimerVente();
                }
            }
        });
    }
}
