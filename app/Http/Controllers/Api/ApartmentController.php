<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Support\Facades\Cache;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::where('visible','=', 1)->get();
        return response()->json($apartments->load(['user']));
    }

    public function show(Apartment $apartment)
    {
        return response()->json($apartment->load(['user']));
    }

    public function search(Request $request){
        $apartments = Apartment::query();

        if($request->filled('search')){
            $searchTerm = $request->input('search');
            $apartments->where(function($query) use ($searchTerm){
                $query->where('title', 'like', "%$searchTerm%")
                ->orWhere('address', 'like', "%$searchTerm%");
            });
        }

        $apartments->where('visible', '=', 1);
        $filteredApartments = $apartments->get();

        return response()->json($filteredApartments->load(['user']));
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
}
