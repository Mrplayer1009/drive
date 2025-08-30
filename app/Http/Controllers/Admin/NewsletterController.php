<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Newsletter;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function index()
    {
        // Compter les clients abonnés à la newsletter
        $clientsAbonnes = Client::where('newsletter_active', true)->count();
        
        return view('admin.newsletter.index', compact('clientsAbonnes'));
    }

    public function envoyer(Request $request)
    {
        $request->validate([
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string|min:10',
        ]);

        // Récupérer tous les clients abonnés à la newsletter
        $clients = Client::where('newsletter_active', true)->get();
        
        if ($clients->isEmpty()) {
            return back()->with('error', 'Aucun client n\'est abonné à la newsletter.');
        }

        $sujet = $request->sujet;
        $contenu = $request->contenu;
        $emailsEnvoyes = 0;
        $emailsEchoues = 0;

        foreach ($clients as $client) {
            try {
                // Envoyer le mail de manière asynchrone
                dispatch(function() use ($client, $sujet, $contenu) {
                    Mail::to($client->email)->send(new Newsletter($client, $sujet, $contenu));
                })->afterResponse();
                
                $emailsEnvoyes++;
                
                // Log pour tracer
                Log::info("Newsletter envoyée à {$client->email}");
                
            } catch (\Exception $e) {
                $emailsEchoues++;
                Log::error("Erreur envoi newsletter à {$client->email}: " . $e->getMessage());
            }
        }

        $message = "Newsletter envoyée avec succès à {$emailsEnvoyes} client(s)";
        if ($emailsEchoues > 0) {
            $message .= " ({$emailsEchoues} échec(s))";
        }

        return back()->with('success', $message);
    }


}
