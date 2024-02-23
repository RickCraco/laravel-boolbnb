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
            $searchTerm = $request->input('search');
            $client = new Client(['verify' => false]);
            $response = $client->request('GET', 'https://api.tomtom.com/search/2/geocode/' . $searchTerm . '.json?key=2HI9GWKpWiwAq3zKIGlnZVdmoLe7u7xs');
            $body = json_decode($response->getBody(), true);

            $resultsFound = false;

            foreach($body['results'] as $result) {
                $latC = $result['position']['lat'];
                $lonC = $result['position']['lon'];

                $apartments->where('visible', '=', 1)
                ->orWhere(function ($query) use ($radius, $latC, $lonC) {
                    $query->whereRaw('( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lon ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) <= ?', [$latC, $lonC, $latC, $radius]);
                });

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

        // Filtra solo gli appartamenti visibili
        $apartments->where('visible', '=', 1);

        // Esegui la query e restituisci i risultati
        $filteredApartments = $apartments->get();
        $filteredApartments->load('user', 'images', 'sponsors');

        // Calcola la distanza per ogni appartamento e ordina
        if ($request->filled('radius')) {
            $lat = $body['results'][0]['position']['lat'];
            $lon = $body['results'][0]['position']['lon'];

            // Creiamo un array associativo con distanze come chiavi e appartamenti come valori
            $apartmentsWithDistances = [];
            foreach ($filteredApartments as $apartment) {
                $apartment->distance = $this->calculateDistance($lat, $lon, $apartment->lat, $apartment->lon);
                $apartmentsWithDistances[$apartment->distance] = $apartment;
            }

            // Ordiniamo l'array associativo in base alle chiavi (distanze)
            ksort($apartmentsWithDistances);

            // Otteniamo solo i valori (gli appartamenti ordinati)
            $filteredApartments = collect(array_values($apartmentsWithDistances));
        }

        

        return response()->json($filteredApartments);
    }

    public function calculateDistance($lat1, $lon1, $lat2, $lon2){
        $theta = $lon1 - $lon2;
        $distance = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        return round($distance, 2);
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
