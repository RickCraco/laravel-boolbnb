<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;


class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apartments = Apartment::where('user_id', auth()->user()->id)->get();
        return view('admin.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        return view('admin.apartments.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApartmentRequest $request)
    {   
        $formdata = $request->validated();

        $slug = Str::slug($formdata['title'], '-');
        $formdata['slug'] = $slug;
        $userId = Auth::id();
        $formdata['user_id'] = $userId;
        
        $client = new Client(['verify' => false]);
        $response = $client->request('GET', 'https://api.tomtom.com/search/2/geocode/'.$formdata['address'].'.json?key=2HI9GWKpWiwAq3zKIGlnZVdmoLe7u7xs');

        $body = json_decode($response->getBody(), true);
        
        $formdata['lat'] = $body['results'][0]['position']['lat'];
        $formdata['lon'] = $body['results'][0]['position']['lon'];

        if($request->hasFile('cover_img')){
            $path = Storage::put('images', $formdata['cover_img']);
            $formdata['cover_img'] = $path;
        }

        $newApartment = Apartment::create($formdata);
        
        if($request->hasFile('images')){
            $images = $request->file('images');

            foreach($images as $image){
                $path = Storage::put('images', $image);
                $newApartment->images()->create(['url' => $path, 'title' => $newApartment->title, 'apartment_id' => $newApartment->id]);
            }
        } 

        if($request->has('services')){
            $newApartment->services()->attach($request->services);
        }

        return redirect()->route('admin.apartments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {
        return view('admin.apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {
        $services = Service::all();
        return view('admin.apartments.edit', compact('apartment', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment)
    {
        $formdata = $request->validated();
        $formdata['slug'] = $apartment->slug;

        $client = new Client(['verify' => false]);
        $response = $client->request('GET', 'https://api.tomtom.com/search/2/geocode/'.$formdata['address'].'.json?key=2HI9GWKpWiwAq3zKIGlnZVdmoLe7u7xs');

        $body = json_decode($response->getBody(), true);
        
        $formdata['lat'] = $body['results'][0]['position']['lat'];
        $formdata['lon'] = $body['results'][0]['position']['lon'];

        if($apartment->title != $formdata['title']){
            $slug = Apartment::getSlug($formdata['title']);
            $formdata['slug'] = $slug;
        }

        if($request->hasFile('cover_img')){

            if($apartment->cover_img){
                Storage::delete($apartment->cover_img);
            }

            $path = Storage::put('images', $request->cover_img);
            $formdata['cover_img'] = $path;
        }

        $apartment->update($formdata);

        if($request->hasFile('images')){
            $apartment->images()->delete();
            $images = $request->file('images');

            foreach($images as $image){
                $path = Storage::put('images', $image);
                $apartment->images()->create(['url' => $path, 'title' => $apartment->title]);
            }
        }

        if($request->has('services')){
            $apartment->services()->sync($request->services);
        }else{
            $apartment->services()->detach();
        }

        return to_route('admin.apartments.show', $apartment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment)
    {
        if($apartment->cover_image){
            Storage::delete($apartment->cover_image);
        }

        $apartment->images()->delete();

        $apartment->services()->detach();
        $apartment->delete();
        return to_route('admin.apartments.index')->with('message', "The apartment {$apartment->title} was deleted");
    }
}
