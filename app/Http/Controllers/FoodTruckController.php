<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use Illuminate\Http\Request;

class FoodTruckController extends Controller
{
    public function index(Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $rayon = $request->get('rayon', 10); // Rayon de recherche en km par défaut

        $query = Franchise::disponible()->avecCoordonnees()->avecCamions();

        // Si on a des coordonnées, on filtre par distance
        if ($latitude && $longitude) {
            $foodTrucks = $query->get()->filter(function ($foodTruck) use ($latitude, $longitude, $rayon) {
                $distance = $foodTruck->getDistanceFrom($latitude, $longitude);
                return $distance !== null && $distance <= $rayon;
            })->sortBy(function ($foodTruck) use ($latitude, $longitude) {
                return $foodTruck->getDistanceFrom($latitude, $longitude);
            });
        } else {
            // Sinon, on affiche tous les food trucks disponibles
            $foodTrucks = $query->get();
        }

        return view('client.food-trucks.index', compact('foodTrucks', 'latitude', 'longitude', 'rayon'));
    }

    public function show(Franchise $foodTruck, Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');

        if ($latitude && $longitude) {
            $distance = $foodTruck->getDistanceFrom($latitude, $longitude);
        } else {
            $distance = null;
        }

        return view('client.food-trucks.show', compact('foodTruck', 'distance'));
    }

    public function apiNearby(Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $rayon = $request->get('rayon', 10);

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Coordonnées requises'], 400);
        }

        $foodTrucks = Franchise::disponible()
            ->avecCoordonnees()
            ->avecCamions()
            ->get()
            ->filter(function ($foodTruck) use ($latitude, $longitude, $rayon) {
                $distance = $foodTruck->getDistanceFrom($latitude, $longitude);
                return $distance !== null && $distance <= $rayon;
            })
            ->map(function ($foodTruck) use ($latitude, $longitude) {
                return [
                    'id' => $foodTruck->id,
                    'nom' => $foodTruck->nom_complet,
                    'adresse' => $foodTruck->adresse_emplacement ?: $foodTruck->adresse,
                    'latitude' => $foodTruck->latitude,
                    'longitude' => $foodTruck->longitude,
                    'distance' => $foodTruck->getDistanceFrom($latitude, $longitude),
                    'distance_formatee' => $foodTruck->getDistanceFormateeAttribute($latitude, $longitude),
                ];
            })
            ->sortBy('distance')
            ->values();

        return response()->json($foodTrucks);
    }
}
