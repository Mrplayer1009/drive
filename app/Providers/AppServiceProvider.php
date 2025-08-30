<?php

namespace App\Providers;

use App\Models\Commande;
use App\Models\Vente;
use App\Observers\CommandeObserver;
use App\Observers\VenteObserver;
use App\Services\StockService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Enregistrer le service de stock
        $this->app->singleton(StockService::class, function ($app) {
            return new StockService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configuration pour MySQL - résoudre le problème de longueur de clé
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
        
        // Enregistrer les observateurs
        Commande::observe(CommandeObserver::class);
        Vente::observe(VenteObserver::class);
    }
}
