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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Braintree\Gateway;
use App\Models\Sponsor;

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
        if($apartment->user_id == Auth::id()){

            $visuals = $apartment->visual()->selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTHNAME(created_at)')
            ->get();        
            
            $sponsors = Sponsor::all();

            return view('admin.apartments.show', compact('apartment', 'visuals', 'sponsors'));
        }else{
            abort(403);
        }
    }

    /**
     * Show the form for payment.
     */
    public function payment(Request $request, Apartment $apartment){

        if($apartment->user_id != Auth::id()){
            abort(403);
        }

        $gateway = new Gateway([
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
        ]);

        $sponsorId = $request->query('sponsor_id');
        $sponsor = Sponsor::find($sponsorId);

        $clientToken = $gateway->ClientToken()->generate();
        return view('admin.apartments.payment', compact('apartment', 'sponsor', 'clientToken'));
    }

    public function process(Request $request, Apartment $apartment){

        if($apartment->user_id != Auth::id()){
            abort(403);
        }
        
        $sponsorId = $request->input('sponsor_id');
        $sponsor = Sponsor::findOrFail($sponsorId);

        $gateway = new Gateway([
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
        ]);

        if($request->input('nonce') != null){
            $nonceFromTheClient = $request->input('nonce');

            $gateway->transaction()->sale([
                'amount' => $sponsor->price,
                'paymentMethodNonce' => $nonceFromTheClient,
                'options' => [
                    'submitForSettlement' => true
                ]
            ]);

            $apartment->sponsors()->attach($sponsor->id, ['start_date' => now(), 'end_date' => now()->addDays($sponsor->duration)]);
            return redirect()->route('admin.apartments.show', $apartment)->with('message', 'Sponsor successfully added!');
        }else{
            $clientToken = $gateway->ClientToken()->generate();
            return view('admin.apartments.payment', compact('apartment', 'sponsor', 'clientToken'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {
        if($apartment->user_id == Auth::id()){
            $services = Service::all();
            return view('admin.apartments.edit', compact('apartment', 'services'));
        }else{
            abort(403);
        }
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
