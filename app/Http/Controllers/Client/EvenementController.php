<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\EvenementParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvenementController extends Controller
{

    /**
     * Afficher la liste des événements disponibles
     */
    public function index()
    {
        $evenements = Evenement::with('franchise')
                              ->actif()
                              ->futur()
                              ->disponible()
                              ->orderBy('date_evenement', 'asc')
                              ->paginate(12);

        $client = Auth::guard('client')->user();

        return view('client.evenements.index', compact('evenements', 'client'));
    }

    /**
     * Afficher un événement
     */
    public function show(Evenement $evenement)
    {
        $client = Auth::guard('client')->user();
        $isInscrit = $evenement->isClientInscrit($client->id);

        return view('client.evenements.show', compact('evenement', 'client', 'isInscrit'));
    }

    /**
     * S'inscrire à un événement
     */
    public function inscrire(Request $request, Evenement $evenement)
    {
        $client = Auth::guard('client')->user();

        // Vérifications
        if ($evenement->isClientInscrit($client->id)) {
            return back()->with('error', 'Vous êtes déjà inscrit à cet événement.');
        }

        if ($evenement->isComplet()) {
            return back()->with('error', 'Cet événement est complet.');
        }

        if ($evenement->isPasse()) {
            return back()->with('error', 'Cet événement est déjà passé.');
        }

        if ($client->points_fidelite < $evenement->prix_points) {
            return back()->with('error', 'Vous n\'avez pas assez de points de fidélité.');
        }

        // Vérifier une dernière fois qu'il n'y a pas déjà une participation
        if ($evenement->hasClientParticipation($client->id)) {
            return back()->with('error', 'Vous avez déjà une participation à cet événement.');
        }

        // Transaction pour s'assurer de la cohérence
        DB::beginTransaction();
        try {
            // Créer l'inscription
            $participant = EvenementParticipant::create([
                'evenement_id' => $evenement->id,
                'client_id' => $client->id,
                'points_payes' => $evenement->prix_points,
                'statut' => 'confirme',
                'date_inscription' => now(),
            ]);

            // Déduire les points du client
            $client->utiliserPoints($evenement->prix_points);

            // Incrémenter le nombre d'inscrits
            $evenement->increment('nombre_inscrits');

            DB::commit();

            return redirect()->route('client.evenements.mes-evenements')
                            ->with('success', 'Inscription réussie ! Vous avez payé ' . $evenement->prix_points . ' points.');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Message d'erreur plus spécifique
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->with('error', 'Vous êtes déjà inscrit à cet événement.');
            }
            
            return back()->with('error', 'Erreur lors de l\'inscription : ' . $e->getMessage());
        }
    }

    /**
     * Se désinscrire d'un événement
     */
    public function desinscrire(Evenement $evenement)
    {
        $client = Auth::guard('client')->user();

        $participant = $evenement->participants()
                                ->where('client_id', $client->id)
                                ->where('statut', 'confirme')
                                ->first();

        if (!$participant) {
            return back()->with('error', 'Vous n\'êtes pas inscrit à cet événement.');
        }

        DB::beginTransaction();
        try {
            // Rembourser les points
            $client->ajouterPoints($participant->points_payes);

            // Supprimer complètement l'inscription
            $participant->delete();

            // Décrémenter le nombre d'inscrits
            $evenement->decrement('nombre_inscrits');

            DB::commit();

            return redirect()->route('client.evenements.mes-evenements')
                            ->with('success', 'Désinscription réussie ! Vos points ont été remboursés.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la désinscription. Veuillez réessayer.');
        }
    }

    /**
     * Afficher les événements auxquels le client est inscrit
     */
    public function mesEvenements()
    {
        $client = Auth::guard('client')->user();
        
        $evenements = $client->evenements()
                            ->wherePivot('statut', 'confirme')
                            ->orderBy('date_evenement', 'asc')
                            ->paginate(10);

        return view('client.evenements.mes-evenements', compact('evenements', 'client'));
    }

    /**
     * Afficher le calendrier des événements
     */
    public function calendrier()
    {
        $client = Auth::guard('client')->user();
        
        // Événements disponibles
        $evenementsDisponibles = Evenement::with('franchise')
                                         ->actif()
                                         ->futur()
                                         ->disponible()
                                         ->get()
                                         ->map(function ($evenement) {
                                             return [
                                                 'id' => $evenement->id,
                                                 'title' => $evenement->titre . ' - ' . $evenement->franchise->nom_complet,
                                                 'start' => $evenement->date_evenement->format('Y-m-d\TH:i:s'),
                                                 'end' => $evenement->date_evenement->addHours(2)->format('Y-m-d\TH:i:s'),
                                                 'backgroundColor' => '#059669',
                                                 'borderColor' => '#059669',
                                                 'extendedProps' => [
                                                     'type' => 'disponible',
                                                     'prix_points' => $evenement->prix_points,
                                                     'places_disponibles' => $evenement->places_disponibles,
                                                     'lieu' => $evenement->lieu,
                                                     'franchise' => $evenement->franchise->nom_complet,
                                                 ]
                                             ];
                                         });

        // Événements auxquels le client est inscrit
        $evenementsInscrits = $client->evenements()
                                    ->wherePivot('statut', 'confirme')
                                    ->get()
                                    ->map(function ($evenement) {
                                        return [
                                            'id' => 'inscrit_' . $evenement->id,
                                            'title' => '✓ ' . $evenement->titre . ' - ' . $evenement->franchise->nom_complet,
                                            'start' => $evenement->date_evenement->format('Y-m-d\TH:i:s'),
                                            'end' => $evenement->date_evenement->addHours(2)->format('Y-m-d\TH:i:s'),
                                            'backgroundColor' => '#3b82f6',
                                            'borderColor' => '#3b82f6',
                                            'extendedProps' => [
                                                'type' => 'inscrit',
                                                'prix_points' => $evenement->pivot->points_payes,
                                                'lieu' => $evenement->lieu,
                                                'franchise' => $evenement->franchise->nom_complet,
                                            ]
                                        ];
                                    });

        $evenements = $evenementsDisponibles->merge($evenementsInscrits);

        return view('client.evenements.calendrier', compact('evenements', 'client'));
    }

    /**
     * Obtenir les événements pour le calendrier (AJAX)
     */
    public function getEvenementsCalendrier()
    {
        $client = Auth::guard('client')->user();
        
        // Événements disponibles
        $evenementsDisponibles = Evenement::with('franchise')
                                         ->actif()
                                         ->futur()
                                         ->disponible()
                                         ->get()
                                         ->map(function ($evenement) {
                                             return [
                                                 'id' => $evenement->id,
                                                 'title' => $evenement->titre . ' - ' . $evenement->franchise->nom_complet,
                                                 'start' => $evenement->date_evenement->format('Y-m-d\TH:i:s'),
                                                 'end' => $evenement->date_evenement->addHours(2)->format('Y-m-d\TH:i:s'),
                                                 'backgroundColor' => '#059669',
                                                 'borderColor' => '#059669',
                                                 'extendedProps' => [
                                                     'type' => 'disponible',
                                                     'prix_points' => $evenement->prix_points,
                                                     'places_disponibles' => $evenement->places_disponibles,
                                                     'lieu' => $evenement->lieu,
                                                     'franchise' => $evenement->franchise->nom_complet,
                                                 ]
                                             ];
                                         });

        // Événements auxquels le client est inscrit
        $evenementsInscrits = $client->evenements()
                                    ->wherePivot('statut', 'confirme')
                                    ->get()
                                    ->map(function ($evenement) {
                                        return [
                                            'id' => 'inscrit_' . $evenement->id,
                                            'title' => '✓ ' . $evenement->titre . ' - ' . $evenement->franchise->nom_complet,
                                            'start' => $evenement->date_evenement->format('Y-m-d\TH:i:s'),
                                            'end' => $evenement->date_evenement->addHours(2)->format('Y-m-d\TH:i:s'),
                                            'backgroundColor' => '#3b82f6',
                                            'borderColor' => '#3b82f6',
                                            'extendedProps' => [
                                                'type' => 'inscrit',
                                                'prix_points' => $evenement->pivot->points_payes,
                                                'lieu' => $evenement->lieu,
                                                'franchise' => $evenement->franchise->nom_complet,
                                            ]
                                        ];
                                    });

        $evenements = $evenementsDisponibles->merge($evenementsInscrits);

        return response()->json($evenements);
    }
}
