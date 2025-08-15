<?php

namespace App\Observers;

use App\Models\Vente;
use App\Services\StockService;

class VenteObserver
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Handle the Vente "created" event.
     */
    public function created(Vente $vente): void
    {
        // Traiter automatiquement les stocks lors de la création d'une vente
        try {
            $this->stockService->traiterVente($vente);
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la création
            \Log::error('Erreur lors du traitement des stocks pour la vente ' . $vente->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Handle the Vente "updated" event.
     */
    public function updated(Vente $vente): void
    {
        // Si le montant de la vente change, on pourrait vouloir ajuster les stocks
        // Mais pour l'instant, on ne fait rien
    }

    /**
     * Handle the Vente "deleted" event.
     */
    public function deleted(Vente $vente): void
    {
        // Si une vente est supprimée, on pourrait vouloir remettre les stocks
        // Mais pour l'instant, on ne fait rien
    }

    /**
     * Handle the Vente "restored" event.
     */
    public function restored(Vente $vente): void
    {
        //
    }

    /**
     * Handle the Vente "force deleted" event.
     */
    public function forceDeleted(Vente $vente): void
    {
        //
    }
}
