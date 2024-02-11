<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartment;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::all();
        return response()->json($apartments);
    }

    public function show(Apartment $apartment)
    {
        return response()->json($apartment);
    }
}
