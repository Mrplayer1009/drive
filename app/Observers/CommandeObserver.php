<?php

namespace App\Observers;

use App\Models\Commande;
use App\Services\StockService;

class CommandeObserver
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Handle the Commande "created" event.
     */
    public function created(Commande $commande): void
    {
        // Les stocks sont gérés lors de la validation de la commande
    }

    /**
     * Handle the Commande "updated" event.
     */
    public function updated(Commande $commande): void
    {
        // Si la commande passe à "livrée", traiter les stocks
        if ($commande->wasChanged('statut') && $commande->statut === 'livree') {
            try {
                $this->stockService->traiterCommande($commande);
            } catch (\Exception $e) {
                // Log l'erreur mais ne pas faire échouer la mise à jour
                \Log::error('Erreur lors du traitement des stocks pour la commande ' . $commande->id . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Commande "deleted" event.
     */
    public function deleted(Commande $commande): void
    {
        // Si une commande est supprimée, on pourrait vouloir remettre les stocks
        // Mais pour l'instant, on ne fait rien
    }

    /**
     * Handle the Commande "restored" event.
     */
    public function restored(Commande $commande): void
    {
        //
    }

    /**
     * Handle the Commande "force deleted" event.
     */
    public function forceDeleted(Commande $commande): void
    {
        //
    }
}
