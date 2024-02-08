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
        return view('admin.apartments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApartmentRequest $request)
    {
        $formdata = $request->validated();
        $slug = Str::slug($formdata['title'], '-');
        $formdata['slug'] = $slug;

        if($request->hasFile('cover_image')){
            $path = Storage::put('images', $formdata['cover_image']);
            $formdata['cover_image'] = $path;
        }

        $newApartment = Apartment::create($formdata);

        if($request->services){
            $newApartment->services()->attach($request->services);
        }

        return to_route('admin.apartments.index');
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
        return view('admin.apartments.edit', compact('apartment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment)
    {
        $formdata = $request->validated();
        $formdata['slug'] = $slug;

        if($apartment->title != $formdata['title']){
            $slug = Apartment::getSlug($formdata['title']);
            $formdata['slug'] = $slug;
        }

        if($request->hasFile('cover_image')){

            if($apartment->cover_image){
                Storage::delete($apartment->cover_image);
            }

            $path = Storage::put('images', $request->cover_image);
            $formdata['cover_image'] = $path;
        }

        $apartment->update($formdata);

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

        $apartment->services()->detach();
        $apartment->delete();
        return to_route('admin.apartments.index')->with('message', "The apartment {$apartment->title} was deleted");
    }
}
