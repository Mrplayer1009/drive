<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Commande;

class TestPdfController extends Controller
{
    public function test()
    {
        try {
            $data = [
                'title' => 'Test PDF',
                'content' => 'Ceci est un test de génération PDF avec DomPDF',
                'date' => now()->format('d/m/Y H:i:s')
            ];
            
            $pdf = Pdf::loadView('pdf.test', $data);
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->download('test.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function testCommande($id = null)
    {
        try {
            // Si pas d'ID, créer des données de test
            if (!$id) {
                $commande = (object) [
                    'id' => 999,
                    'franchise' => (object) [
                        'nom' => 'Test',
                        'prenom' => 'Franchise'
                    ],
                    'entrepot' => (object) [
                        'nom' => 'Entrepôt Test',
                        'ville' => 'Paris'
                    ],
                    'date_commande' => now(),
                    'statut' => 'validee',
                    'notes' => 'Commande de test',
                    'total_obligatoire' => 100.00,
                    'total_libre' => 25.00,
                    'total_commande' => 125.00,
                    'produits' => collect([
                        (object) [
                            'nom' => 'Produit Test',
                            'categorie' => 'ingredient',
                            'unite_mesure' => 'kg',
                            'obligatoire' => true,
                            'pivot' => (object) [
                                'quantite' => 5,
                                'prix_unitaire' => 20.00,
                                'prix_total' => 100.00
                            ]
                        ]
                    ])
                ];
            } else {
                // Charger une vraie commande
                $commande = Commande::with(['franchise', 'entrepot', 'produits'])->findOrFail($id);
            }
            
            $pdf = Pdf::loadView('pdf.commande-minimal', compact('commande'));
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->download('commande_test.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function testCommandeDownload($id = null)
    {
        try {
            // Si pas d'ID, créer des données de test
            if (!$id) {
                $commande = (object) [
                    'id' => 999,
                    'franchise' => (object) [
                        'nom' => 'Test',
                        'prenom' => 'Franchise'
                    ],
                    'entrepot' => (object) [
                        'nom' => 'Entrepôt Test',
                        'ville' => 'Paris'
                    ],
                    'date_commande' => now(),
                    'statut' => 'validee',
                    'notes' => 'Commande de test',
                    'total_obligatoire' => 100.00,
                    'total_libre' => 25.00,
                    'total_commande' => 125.00,
                    'produits' => collect([
                        (object) [
                            'nom' => 'Produit Test',
                            'categorie' => 'ingredient',
                            'unite_mesure' => 'kg',
                            'obligatoire' => true,
                            'pivot' => (object) [
                                'quantite' => 5,
                                'prix_unitaire' => 20.00,
                                'prix_total' => 100.00
                            ]
                        ]
                    ])
                ];
            } else {
                // Charger une vraie commande
                $commande = Commande::with(['franchise', 'entrepot', 'produits'])->findOrFail($id);
            }
            
            // Simuler exactement la méthode commandesDownload
            $pdf = Pdf::loadView('pdf.commande-minimal', compact('commande'));
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->download('commande_' . $commande->id . '.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function testMinimal()
    {
        try {
            $data = [
                'message' => 'Test PDF minimal',
                'date' => now()->format('d/m/Y H:i:s')
            ];
            
            $pdf = Pdf::loadView('pdf.minimal', $data);
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->download('test_minimal.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
