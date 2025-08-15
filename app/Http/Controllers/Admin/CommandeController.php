<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $query = Commande::with(['franchise', 'entrepot', 'produits']);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('franchise')) {
            $query->whereHas('franchise', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->franchise . '%')
                  ->orWhere('prenom', 'like', '%' . $request->franchise . '%');
            });
        }

        if ($request->filled('date_debut')) {
            $query->where('date_commande', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_commande', '<=', $request->date_fin);
        }

        $commandes = $query->orderBy('date_commande', 'desc')->paginate(15)->withQueryString();

        return view('admin.commandes.index', compact('commandes'));
    }

    public function show(Commande $commande)
    {
        $commande->load(['franchise', 'entrepot', 'produits']);
        return view('admin.commandes.show', compact('commande'));
    }

    public function edit(Commande $commande)
    {
        $commande->load(['franchise', 'entrepot', 'produits']);
        return view('admin.commandes.edit', compact('commande'));
    }

    public function update(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,validee,refusee,livree',
            'notes_admin' => 'nullable|string|max:1000',
        ]);

        $commande->update([
            'statut' => $request->statut,
            'notes_admin' => $request->notes_admin,
        ]);

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Commande mise à jour avec succès');
    }

    public function validate(Commande $commande)
    {
        $commande->update(['statut' => 'validee']);
        
        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Commande validée avec succès');
    }

    public function refuse(Request $request, Commande $commande)
    {
        $commande->update([
            'statut' => 'refusee',
        ]);

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Commande refusée avec succès');
    }

    public function deliver(Commande $commande)
    {
        $commande->update(['statut' => 'livree']);
        
        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Commande marquée comme livrée');
    }

    public function download(Commande $commande)
    {
        try {
            // Charger les relations nécessaires
            $commande->load(['franchise', 'entrepot', 'produits']);
            
            // Vérifier que les données nécessaires existent
            if (!$commande->franchise || !$commande->entrepot) {
                throw new \Exception('Données manquantes pour générer le PDF');
            }
            
            // Générer le PDF directement pour téléchargement
            $pdf = Pdf::loadView('pdf.commande-minimal', compact('commande'));
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->download('commande_' . $commande->id . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Erreur génération PDF commande ' . $commande->id . ': ' . $e->getMessage());
            return redirect()->route('admin.commandes.show', $commande)
                ->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }
}
