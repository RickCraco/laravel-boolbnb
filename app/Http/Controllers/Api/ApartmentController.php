<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::leftJoin('apartment_sponsor', 'apartments.id', '=', 'apartment_sponsor.apartment_id')
        ->select('apartments.*')
        ->where('apartments.visible', '=', 1)
        ->groupBy('apartments.id')
        ->orderByRaw('CASE WHEN COUNT(apartment_sponsor.sponsor_id) > 0 THEN 0 ELSE 1 END')
        ->get();

        return response()->json($apartments->load(['user', 'images', 'sponsors']));
    }

    public function show(Apartment $apartment)
    {
        return response()->json($apartment->load(['user', 'images', 'services', 'sponsors']));
    }

    public function search(Request $request)
    {
        $apartments = Apartment::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $apartments->where(function($query) use ($searchTerm){
                $query->where('title', 'like', "%$searchTerm%")
                    ->orWhere('address', 'like', "%$searchTerm%");
            });
        }

        if ($request->filled('radius')) {
            $radius = $request->input('radius');
            $searchTerm = $request->input('search'); // Aggiungi questa riga
            $client = new Client(['verify' => false]);
            $response = $client->request('GET', 'https://api.tomtom.com/search/2/geocode/' . $searchTerm . '.json?key=2HI9GWKpWiwAq3zKIGlnZVdmoLe7u7xs');
            $body = json_decode($response->getBody(), true);

            $resultsFound = false;
            foreach($body['results'] as $result) {
                $latC = $result['position']['lat'];
                $lonC = $result['position']['lon'];

                $distLat = $radius / 110.574;
                $distLon = $radius / (111.320 * cos(deg2rad($latC)));

                $minLat = $latC - $distLat;
                $maxLat = $latC + $distLat;
                $minLon = $lonC - $distLon;
                $maxLon = $lonC + $distLon;
                
                $apartments->orWhereBetween('lat', [$minLat, $maxLat])
                            ->whereBetween('lon', [$minLon, $maxLon]);

                $resultsFound = true;
            }

            // Se non trovi risultati, aggiungi una clausola falsa per evitare di restituire tutto
            if (!$resultsFound) {
                $apartments->where('id', '=', 0);
            }
        }

        if ($request->filled('rooms')) {
            $apartments->where('rooms', '>=', $request->input('rooms'));
        }

        if ($request->filled('beds')) {
            $apartments->where('beds', '>=', $request->input('beds'));
        }

        if ($request->filled('bathrooms')) {
            $apartments->where('bathrooms', '>=', $request->input('bathrooms'));
        }

        if ($request->filled('services')) {
            $services = explode(',', $request->input('services'));
            foreach ($services as $service) {
                $apartments->whereHas('services', function ($query) use ($service) {
                    $query->where('name', $service);
                });
            }
        }

        // ordinati gli appartamenti per sponsor

        $apartments->leftJoin('apartment_sponsor', 'apartments.id', '=', 'apartment_sponsor.apartment_id')
        ->select('apartments.*')
        ->where('apartments.visible', '=', 1)
        ->where('apartment_sponsor.visible', '=', 1)
        ->where(function($query) {
            $query->where('visible', '=', 1);
        })
        ->where(function($query) use ($radius, $searchTerm) {
            if ($radius) {
                // Codice per il filtro del raggio
                foreach($body['results'] as $result) {
                    $latC = $result['position']['lat'];
                    $lonC = $result['position']['lon'];

                    $distLat = $radius / 110.574;
                    $distLon = $radius / (111.320 * cos(deg2rad($latC)));

                    $minLat = $latC - $distLat;
                    $maxLat = $latC + $distLat;
                    $minLon = $lonC - $distLon;
                    $maxLon = $lonC + $distLon;

                    $query->orWhereBetween('lat', [$minLat, $maxLat])
                            ->whereBetween('lon', [$minLon, $maxLon]);
                }
            }
        })
        ->groupBy('apartments.id')
        ->orderByRaw('CASE WHEN COUNT(apartment_sponsor.sponsor_id) > 0 THEN 0 ELSE 1 END');

        // Esegui la query e restituisci i risultati
        $filteredApartments = $apartments->get();

        return response()->json($filteredApartments->load(['user', 'images', 'sponsors']));
    }




    public function recordView(Apartment $apartment, Request $request){
        $userIP = $request->ip();

        $cacheKey = 'apartment_view_' . $apartment->id . '_' . $userIP;

        if(!Cache::has($cacheKey)){
            $apartment->visual()->create(['apartment_id' => $apartment->id, 'ip_address' => $userIP, 'date' => now()]);
            Cache::put($cacheKey, true, now()->addHours(24));
        }

        return response()->json(['message' => 'Visualizzazione registrate con successo'], 200); 
    }

    public function recordMessage(Apartment $apartment, Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'phone_number' => 'nullable',
            'email' => 'required|email',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $apartment->messages()->create(['name' => $request->name, 'surname' => $request->surname, 'phone_number' => $request->phone_number, 'email' => $request->email, 'body' => $request->body, 'apartment_id' => $apartment->id]);
        return response()->json(['message' => 'Messaggio inviato con successo'], 200);
    }
}
