<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use App\Models\User;
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
            'type' => 'required|in:admin,franchise',
        ]);

        if ($request->type === 'admin') {
            // Authentification admin
            $user = User::where('email', $request->email)->first();
            
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::guard('admin')->login($user);
                return redirect()->route('admin.dashboard');
            }
        } else {
            // Authentification franchise
            $franchise = Franchise::where('email', $request->email)->first();
            
            if ($franchise && Hash::check($request->password, $franchise->password)) {
                Auth::guard('franchise')->login($franchise);
                return redirect()->route('franchise.dashboard');
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

        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:franchises',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string',
            'date_entree' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
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

        return redirect()->route('login')->with('success', 'Inscription réussie ! Votre compte sera activé par un administrateur dans les plus brefs délais.');
    }
} 