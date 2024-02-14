<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::where('visible','=', 1)->get();
        return response()->json($apartments->load(['user']));
    }

    public function show(Apartment $apartment)
    {
        return response()->json($apartment->load(['user', 'images', 'services', 'sponsor']));
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

    public function recordMessage(Apartment $apartment, Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'phone_number' => 'required',
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
