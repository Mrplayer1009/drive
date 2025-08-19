<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\StockEntrepotController;
use App\Http\Controllers\Admin\StockFranchiseController;
use App\Http\Controllers\TestPdfController;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes de test PDF
Route::get('/test-pdf', [TestPdfController::class, 'test'])->name('test.pdf');
Route::get('/test-pdf-commande/{id?}', [TestPdfController::class, 'testCommande'])->name('test.pdf.commande');
Route::get('/test-pdf-commande-download/{id?}', [TestPdfController::class, 'testCommandeDownload'])->name('test.pdf.commande.download');
Route::get('/test-pdf-minimal', [TestPdfController::class, 'testMinimal'])->name('test.pdf.minimal');

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes Admin (Back-office)
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des franchisés
    Route::get('/franchises', [AdminController::class, 'franchises'])->name('franchises.index');
    Route::get('/franchises/create', [AdminController::class, 'franchisesCreate'])->name('franchises.create');
    Route::post('/franchises', [AdminController::class, 'franchisesStore'])->name('franchises.store');
    Route::get('/franchises/{franchise}', [AdminController::class, 'franchisesShow'])->name('franchises.show');
    Route::get('/franchises/{franchise}/edit', [AdminController::class, 'franchisesEdit'])->name('franchises.edit');
    Route::put('/franchises/{franchise}', [AdminController::class, 'franchisesUpdate'])->name('franchises.update');
    Route::post('/franchises/{franchise}/activate', [AdminController::class, 'franchisesActivate'])->name('franchises.activate');
    Route::get('/franchises/{franchise}/camions-disponibles', [AdminController::class, 'franchisesCamionsDisponibles'])->name('franchises.camions-disponibles');
    Route::post('/franchises/{franchise}/assign-camion', [AdminController::class, 'franchisesAssignCamion'])->name('franchises.assign-camion');
    Route::delete('/franchises/{franchise}/remove-camion/{camion}', [AdminController::class, 'franchisesRemoveCamion'])->name('franchises.remove-camion');
    Route::delete('/franchises/{franchise}', [AdminController::class, 'franchisesDestroy'])->name('franchises.destroy');
    
    // Gestion des entrepôts
    Route::get('/entrepots', [AdminController::class, 'entrepots'])->name('entrepots.index');
    Route::get('/entrepots/create', [AdminController::class, 'entrepotsCreate'])->name('entrepots.create');
    Route::post('/entrepots', [AdminController::class, 'entrepotsStore'])->name('entrepots.store');
    Route::get('/entrepots/{entrepot}', [AdminController::class, 'entrepotsShow'])->name('entrepots.show');
    Route::get('/entrepots/{entrepot}/edit', [AdminController::class, 'entrepotsEdit'])->name('entrepots.edit');
    Route::put('/entrepots/{entrepot}', [AdminController::class, 'entrepotsUpdate'])->name('entrepots.update');
    Route::delete('/entrepots/{entrepot}', [AdminController::class, 'entrepotsDestroy'])->name('entrepots.destroy');
    
    // Gestion des camions
    Route::get('/camions', [AdminController::class, 'camions'])->name('camions.index');
    Route::get('/camions/create', [AdminController::class, 'camionsCreate'])->name('camions.create');
    Route::post('/camions', [AdminController::class, 'camionsStore'])->name('camions.store');
    Route::get('/camions/{camion}', [AdminController::class, 'camionsShow'])->name('camions.show');
    Route::get('/camions/{camion}/edit', [AdminController::class, 'camionsEdit'])->name('camions.edit');
    Route::put('/camions/{camion}', [AdminController::class, 'camionsUpdate'])->name('camions.update');
    Route::delete('/camions/{camion}', [AdminController::class, 'camionsDestroy'])->name('camions.destroy');
    Route::post('/camions/{camion}/assign-franchise', [AdminController::class, 'camionsAssignFranchise'])->name('camions.assign-franchise');
    Route::delete('/camions/{camion}/remove-franchise', [AdminController::class, 'camionsRemoveFranchise'])->name('camions.remove-franchise');
    
    // Gestion des ventes
    Route::get('/ventes', [AdminController::class, 'ventes'])->name('ventes.index');
    Route::get('/ventes/create', [AdminController::class, 'ventesCreate'])->name('ventes.create');
    Route::post('/ventes', [AdminController::class, 'ventesStore'])->name('ventes.store');
    Route::get('/ventes/{vente}', [AdminController::class, 'ventesShow'])->name('ventes.show');
    Route::get('/ventes/{vente}/edit', [AdminController::class, 'ventesEdit'])->name('ventes.edit');
    Route::put('/ventes/{vente}', [AdminController::class, 'ventesUpdate'])->name('ventes.update');
    Route::delete('/ventes/{vente}', [AdminController::class, 'ventesDestroy'])->name('ventes.destroy');
    Route::get('/ventes/{vente}/download', [AdminController::class, 'ventesDownload'])->name('ventes.download');
    
    // Gestion des commandes
    Route::get('/commandes', [AdminController::class, 'commandes'])->name('commandes.index');
    Route::get('/commandes/create', [AdminController::class, 'commandesCreate'])->name('commandes.create');
    Route::post('/commandes', [AdminController::class, 'commandesStore'])->name('commandes.store');
    Route::get('/commandes/{commande}', [AdminController::class, 'commandesShow'])->name('commandes.show');
    Route::get('/commandes/{commande}/edit', [AdminController::class, 'commandesEdit'])->name('commandes.edit');
    Route::put('/commandes/{commande}', [AdminController::class, 'commandesUpdate'])->name('commandes.update');
    Route::delete('/commandes/{commande}', [AdminController::class, 'commandesDestroy'])->name('commandes.destroy');
    Route::post('/commandes/{commande}/validate', [AdminController::class, 'commandesValidate'])->name('commandes.validate');
    Route::post('/commandes/{commande}/refuse', [AdminController::class, 'commandesRefuse'])->name('commandes.refuse');
    Route::post('/commandes/{commande}/deliver', [AdminController::class, 'commandesDeliver'])->name('commandes.deliver');
    Route::get('/commandes/{commande}/download', [AdminController::class, 'commandesDownload'])->name('commandes.download');
    
    // Gestion des produits
    Route::get('/produits', [AdminController::class, 'produits'])->name('produits.index');
    Route::get('/produits/create', [AdminController::class, 'produitsCreate'])->name('produits.create');
    Route::post('/produits', [AdminController::class, 'produitsStore'])->name('produits.store');
    Route::get('/produits/{produit}', [AdminController::class, 'produitsShow'])->name('produits.show');
    Route::get('/produits/{produit}/edit', [AdminController::class, 'produitsEdit'])->name('produits.edit');
    Route::put('/produits/{produit}', [AdminController::class, 'produitsUpdate'])->name('produits.update');
    Route::delete('/produits/{produit}', [AdminController::class, 'produitsDestroy'])->name('produits.destroy');
    
    // Statistiques
    Route::get('/statistiques', [AdminController::class, 'statistiques'])->name('statistiques');
    Route::get('/statistiques/export-pdf', [AdminController::class, 'exportStatistiquesPDF'])->name('statistiques.export-pdf');
    
    // Notifications de panne
    Route::get('/notifications-pannes', [AdminController::class, 'notificationsPannes'])->name('notifications-pannes.index');
    Route::get('/notifications-pannes/{notification}', [AdminController::class, 'notificationsPannesShow'])->name('notifications-pannes.show');
    Route::get('/notifications-pannes/{notification}/edit', [AdminController::class, 'notificationsPannesEdit'])->name('notifications-pannes.edit');
    Route::put('/notifications-pannes/{notification}', [AdminController::class, 'notificationsPannesUpdate'])->name('notifications-pannes.update');
    
    // Demandes de camion
    Route::get('/demandes-camions', [AdminController::class, 'demandesCamions'])->name('demandes-camions.index');
    Route::get('/demandes-camions/create', [AdminController::class, 'demandesCamionsCreate'])->name('demandes-camions.create');
    Route::post('/demandes-camions', [AdminController::class, 'demandesCamionsStore'])->name('demandes-camions.store');
    Route::get('/demandes-camions/{demande}', [AdminController::class, 'demandesCamionsShow'])->name('demandes-camions.show');
    Route::get('/demandes-camions/{demande}/edit', [AdminController::class, 'demandesCamionsEdit'])->name('demandes-camions.edit');
    Route::put('/demandes-camions/{demande}', [AdminController::class, 'demandesCamionsUpdate'])->name('demandes-camions.update');
    
    // Gestion des stocks d'entrepôt
    Route::get('/entrepots/{entrepot}/stocks', [StockEntrepotController::class, 'index'])->name('entrepots.stocks.index');
    Route::get('/entrepots/{entrepot}/stocks/create', [StockEntrepotController::class, 'create'])->name('entrepots.stocks.create');
    Route::post('/entrepots/{entrepot}/stocks', [StockEntrepotController::class, 'store'])->name('entrepots.stocks.store');
    Route::get('/entrepots/{entrepot}/stocks/{stock}/edit', [StockEntrepotController::class, 'edit'])->name('entrepots.stocks.edit');
    Route::put('/entrepots/{entrepot}/stocks/{stock}', [StockEntrepotController::class, 'update'])->name('entrepots.stocks.update');
    Route::get('/entrepots/{entrepot}/stocks/produit/{produit}', [StockEntrepotController::class, 'show'])->name('entrepots.stocks.show');
    Route::post('/entrepots/{entrepot}/stocks/produit/{produit}/ajouter', [StockEntrepotController::class, 'ajouterStock'])->name('entrepots.stocks.ajouter');
    Route::post('/entrepots/{entrepot}/stocks/produit/{produit}/retirer', [StockEntrepotController::class, 'retirerStock'])->name('entrepots.stocks.retirer');
    
    // Gestion des stocks de franchise
    Route::get('/franchises/{franchise}/stocks', [StockFranchiseController::class, 'index'])->name('franchises.stocks.index');
    Route::get('/franchises/{franchise}/stocks/create', [StockFranchiseController::class, 'create'])->name('franchises.stocks.create');
    Route::post('/franchises/{franchise}/stocks', [StockFranchiseController::class, 'store'])->name('franchises.stocks.store');
    Route::get('/franchises/{franchise}/stocks/{stock}/edit', [StockFranchiseController::class, 'edit'])->name('franchises.stocks.edit');
    Route::put('/franchises/{franchise}/stocks/{stock}', [StockFranchiseController::class, 'update'])->name('franchises.stocks.update');
    Route::get('/franchises/{franchise}/stocks/produit/{produit}', [StockFranchiseController::class, 'show'])->name('franchises.stocks.show');
    Route::post('/franchises/{franchise}/stocks/produit/{produit}/ajouter', [StockFranchiseController::class, 'ajouterStock'])->name('franchises.stocks.ajouter');
    Route::post('/franchises/{franchise}/stocks/produit/{produit}/retirer', [StockFranchiseController::class, 'retirerStock'])->name('franchises.stocks.retirer');
    
    // Alertes de stock
    Route::get('/stocks/alertes-entrepots', [StockEntrepotController::class, 'alertes'])->name('stocks.alertes-entrepots');
    Route::get('/stocks/alertes-franchises', [StockFranchiseController::class, 'alertes'])->name('stocks.alertes-franchises');
});

// Routes Franchise (Front-office)
Route::prefix('franchise')->name('franchise.')->middleware('auth:franchise')->group(function () {
    Route::get('/', [FranchiseController::class, 'dashboard'])->name('dashboard');
    
    // Profil
    Route::get('/profile', [FranchiseController::class, 'profile'])->name('profile');
    Route::put('/profile', [FranchiseController::class, 'profileUpdate'])->name('profile.update');
    
    // Camions
    Route::get('/camions', [FranchiseController::class, 'camions'])->name('camions.index');
    Route::get('/camions/create', [FranchiseController::class, 'camionsCreate'])->name('camions.create');
    Route::post('/camions', [FranchiseController::class, 'camionsStore'])->name('camions.store');
    Route::get('/camions/{camion}', [FranchiseController::class, 'camionsShow'])->name('camions.show');
    Route::get('/camions/{demande}/edit', [FranchiseController::class, 'camionsEdit'])->name('camions.edit');
    Route::put('/camions/{demande}', [FranchiseController::class, 'camionsUpdate'])->name('camions.update');
    
    // Notifications de panne
    Route::get('/camions/{camion}/signaler-panne', [FranchiseController::class, 'signalerPanne'])->name('camions.signaler-panne');
    Route::post('/camions/{camion}/store-panne', [FranchiseController::class, 'storePanne'])->name('camions.store-panne');
    
    // Demandes de remplacement
    Route::get('/camions/{camion}/demander-remplacement', [FranchiseController::class, 'demanderRemplacement'])->name('camions.demander-remplacement');
    Route::post('/camions/{camion}/store-remplacement', [FranchiseController::class, 'storeRemplacement'])->name('camions.store-remplacement');
    
    // Commandes
    Route::get('/commandes', [FranchiseController::class, 'commandes'])->name('commandes.index');
    Route::get('/commandes/create', [FranchiseController::class, 'commandesCreate'])->name('commandes.create');
    Route::post('/commandes', [FranchiseController::class, 'commandesStore'])->name('commandes.store');
    Route::get('/commandes/{commande}', [FranchiseController::class, 'commandesShow'])->name('commandes.show');
    Route::get('/commandes/{commande}/edit', [FranchiseController::class, 'commandesEdit'])->name('commandes.edit');
    Route::put('/commandes/{commande}', [FranchiseController::class, 'commandesUpdate'])->name('commandes.update');
    Route::delete('/commandes/{commande}', [FranchiseController::class, 'commandesDestroy'])->name('commandes.destroy');
    Route::get('/commandes/{commande}/download', [FranchiseController::class, 'commandesDownload'])->name('commandes.download');
    
    // Ventes
    Route::get('/ventes', [FranchiseController::class, 'ventes'])->name('ventes.index');
    Route::get('/ventes/create', [FranchiseController::class, 'ventesCreate'])->name('ventes.create');
    Route::post('/ventes', [FranchiseController::class, 'ventesStore'])->name('ventes.store');
    Route::get('/ventes/{vente}/download', [FranchiseController::class, 'ventesDownload'])->name('ventes.download');
});
