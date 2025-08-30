<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'type' => 'required|in:admin,franchise,client',
        ]);

        if ($request->type === 'admin') {
            // Authentification admin
            $user = User::where('email', $request->email)->first();
            
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::guard('admin')->login($user);
                return redirect()->route('admin.dashboard');
            }
        } elseif ($request->type === 'franchise') {
            // Authentification franchise
            $franchise = Franchise::where('email', $request->email)->first();
            
            if ($franchise && Hash::check($request->password, $franchise->password)) {
                Auth::guard('franchise')->login($franchise);
                return redirect()->route('franchise.dashboard');
            }
        } else {
            // Authentification client
            $client = Client::where('email', $request->email)->first();
            
            if ($client && Hash::check($request->password, $client->password)) {
                Auth::guard('client')->login($client);
                return redirect()->route('client.index');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }

        if (Auth::guard('franchise')->check()) {
            Auth::guard('franchise')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if (Auth::guard('client')->check()) {
            Auth::guard('client')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'type' => 'required|in:franchise,client',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->type === 'franchise') {
            // Validation spécifique pour les franchises
            $request->validate([
                'email' => 'unique:franchises',
                'date_entree' => 'required|date',
            ]);

            $franchise = Franchise::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse,
                'ville' => $request->ville,
                'code_postal' => $request->code_postal,
                'date_entree' => $request->date_entree,
                'statut' => 'inactif', // En attente de validation par l'admin
                'droits_entree' => 50000.00,
                'pourcentage_ventes' => 4.00,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('login')->with('success', 'Inscription franchise réussie ! Votre compte sera activé par un administrateur dans les plus brefs délais.');
        } else {
            // Validation spécifique pour les clients
            $request->validate([
                'email' => 'unique:clients',
                'langue' => 'required|in:fr,en,es',
            ]);

            $client = Client::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse,
                'ville' => $request->ville,
                'code_postal' => $request->code_postal,
                'langue' => $request->langue,
                'newsletter_active' => $request->has('newsletter_active'),
                'points_fidelite' => 0,
                'statut' => 'actif',
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('login')->with('success', 'Inscription client réussie ! Vous pouvez maintenant vous connecter.');
        }
    }
} 