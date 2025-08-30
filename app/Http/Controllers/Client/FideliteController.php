<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\CommandeClient;
use App\Services\FideliteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FideliteController extends Controller
{
    protected $fideliteService;

    public function __construct(FideliteService $fideliteService)
    {
        $this->fideliteService = $fideliteService;
    }

    /**
     * Afficher les informations de fidélité du client
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        $infosFidelite = $this->fideliteService->getInfosFidelite($client);
        
        return view('client.fidelite.index', compact('infosFidelite', 'client'));
    }

    /**
     * Appliquer une réduction de fidélité à une commande
     */
    public function appliquerReduction(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commande_clients,id',
            'reduction' => 'required|numeric|min:0'
        ]);

        $client = Auth::guard('client')->user();
        $commande = CommandeClient::where('id', $request->commande_id)
            ->where('client_id', $client->id)
            ->where('statut', 'en_attente')
            ->first();

        if (!$commande) {
            return response()->json([
                'success' => false,
                'message' => 'Commande introuvable ou non éligible'
            ], 404);
        }

        $reductionAppliquee = $this->fideliteService->appliquerReduction($commande, $request->reduction);

        if ($reductionAppliquee === false) {
            return response()->json([
                'success' => false,
                'message' => 'Réduction non disponible ou montant trop élevé'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Réduction de {$reductionAppliquee}€ appliquée avec succès",
            'reduction_appliquee' => $reductionAppliquee,
            'nouveau_montant' => $commande->montant_final,
            'points_restants' => $client->fresh()->points_fidelite
        ]);
    }

    /**
     * Obtenir les informations de fidélité en AJAX
     */
    public function getInfos()
    {
        $client = Auth::guard('client')->user();
        $infosFidelite = $this->fideliteService->getInfosFidelite($client);

        return response()->json($infosFidelite);
    }

    /**
     * Historique des points de fidélité
     */
    public function historique()
    {
        $client = Auth::guard('client')->user();
        $commandes = $client->commandes()
            ->where('statut', '!=', 'annulee')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.fidelite.historique', compact('commandes'));
    }
}
