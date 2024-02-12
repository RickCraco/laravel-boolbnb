<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartment;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::all()->load(['user']);
        return response()->json($apartments);
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

        $filteredApartments = $apartments->get();

        return response()->json($filteredApartments->load(['user']));
    }
}
