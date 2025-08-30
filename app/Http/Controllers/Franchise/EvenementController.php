<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\EvenementParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EvenementController extends Controller
{

    /**
     * Afficher la liste des événements du franchisé
     */
    public function index()
    {
        $franchise = Auth::guard('franchise')->user();
        $evenements = $franchise->evenements()
                               ->orderBy('date_evenement', 'desc')
                               ->paginate(10);

        return view('franchise.evenements.index', compact('evenements'));
    }

    /**
     * Afficher le formulaire de création d'événement
     */
    public function create()
    {
        return view('franchise.evenements.create');
    }

    /**
     * Créer un nouvel événement
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_evenement' => 'required|date|after:now',
            'prix_points' => 'required|integer|min:1',
            'nombre_max_participants' => 'required|integer|min:1|max:100',
            'lieu' => 'nullable|string|max:255',
        ]);

        $franchise = Auth::guard('franchise')->user();

        $evenement = $franchise->evenements()->create([
            'titre' => $request->titre,
            'description' => $request->description,
            'date_evenement' => $request->date_evenement,
            'prix_points' => $request->prix_points,
            'nombre_max_participants' => $request->nombre_max_participants,
            'lieu' => $request->lieu,
            'statut' => 'actif',
        ]);

        return redirect()->route('franchise.evenements.index')
                        ->with('success', 'Événement créé avec succès !');
    }

    /**
     * Afficher un événement
     */
    public function show(Evenement $evenement)
    {
        // Vérifier que l'événement appartient au franchisé
        if ($evenement->franchise_id !== Auth::guard('franchise')->id()) {
            abort(403);
        }

        $participants = $evenement->participants()
                                ->with('client')
                                ->where('statut', 'confirme')
                                ->orderBy('date_inscription', 'desc')
                                ->get();

        return view('franchise.evenements.show', compact('evenement', 'participants'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Evenement $evenement)
    {
        // Vérifier que l'événement appartient au franchisé
        if ($evenement->franchise_id !== Auth::guard('franchise')->id()) {
            abort(403);
        }

        return view('franchise.evenements.edit', compact('evenement'));
    }

    /**
     * Mettre à jour un événement
     */
    public function update(Request $request, Evenement $evenement)
    {
        // Vérifier que l'événement appartient au franchisé
        if ($evenement->franchise_id !== Auth::guard('franchise')->id()) {
            abort(403);
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_evenement' => 'required|date|after:now',
            'prix_points' => 'required|integer|min:1',
            'nombre_max_participants' => 'required|integer|min:1|max:100',
            'lieu' => 'nullable|string|max:255',
        ]);

        $evenement->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'date_evenement' => $request->date_evenement,
            'prix_points' => $request->prix_points,
            'nombre_max_participants' => $request->nombre_max_participants,
            'lieu' => $request->lieu,
        ]);

        return redirect()->route('franchise.evenements.index')
                        ->with('success', 'Événement mis à jour avec succès !');
    }

    /**
     * Annuler un événement
     */
    public function annuler(Evenement $evenement)
    {
        // Vérifier que l'événement appartient au franchisé
        if ($evenement->franchise_id !== Auth::guard('franchise')->id()) {
            abort(403);
        }

        $evenement->update(['statut' => 'annule']);

        // Rembourser les points aux participants
        foreach ($evenement->participants as $participant) {
            $participant->client->ajouterPoints($participant->points_payes);
            $participant->update(['statut' => 'annule']);
        }

        return redirect()->route('franchise.evenements.index')
                        ->with('success', 'Événement annulé et participants remboursés !');
    }

    /**
     * Afficher le calendrier des événements
     */
    public function calendrier()
    {
        $franchise = Auth::guard('franchise')->user();
        $evenements = $franchise->evenements()
                               ->actif()
                               ->futur()
                               ->get()
                               ->map(function ($evenement) {
                                   return [
                                       'id' => $evenement->id,
                                       'title' => $evenement->titre,
                                       'start' => $evenement->date_evenement->format('Y-m-d\TH:i:s'),
                                       'end' => $evenement->date_evenement->addHours(2)->format('Y-m-d\TH:i:s'),
                                       'backgroundColor' => $evenement->isComplet() ? '#dc2626' : '#059669',
                                       'borderColor' => $evenement->isComplet() ? '#dc2626' : '#059669',
                                       'extendedProps' => [
                                           'places_disponibles' => $evenement->places_disponibles,
                                           'prix_points' => $evenement->prix_points,
                                           'lieu' => $evenement->lieu,
                                       ]
                                   ];
                               });

        return view('franchise.evenements.calendrier', compact('evenements'));
    }

    /**
     * Obtenir les événements pour le calendrier (AJAX)
     */
    public function getEvenementsCalendrier()
    {
        $franchise = Auth::guard('franchise')->user();
        $evenements = $franchise->evenements()
                               ->actif()
                               ->get()
                               ->map(function ($evenement) {
                                   return [
                                       'id' => $evenement->id,
                                       'title' => $evenement->titre,
                                       'start' => $evenement->date_evenement->format('Y-m-d\TH:i:s'),
                                       'end' => $evenement->date_evenement->addHours(2)->format('Y-m-d\TH:i:s'),
                                       'backgroundColor' => $evenement->isComplet() ? '#dc2626' : '#059669',
                                       'borderColor' => $evenement->isComplet() ? '#dc2626' : '#059669',
                                       'extendedProps' => [
                                           'places_disponibles' => $evenement->places_disponibles,
                                           'prix_points' => $evenement->prix_points,
                                           'lieu' => $evenement->lieu,
                                       ]
                                   ];
                               });

        return response()->json($evenements);
    }
}
