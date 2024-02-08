<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = config('apartments.apartments');

        foreach($data as $apartment) {
            $newApartment = new Apartment();
            $newApartment->title = $apartment['title'];
            $newApartment->rooms = $apartment['rooms'];
            $newApartment->beds = $apartment['beds'];
            $newApartment->bathrooms = $apartment['bathrooms'];
            $newApartment->square_meters = $apartment['square_meters'];
            $newApartment->address = $apartment['address'];
            $newApartment->cover_img = ApartmentSeeder::storeimage($apartment['cover_img'], $apartment['title']);
            $newApartment->visible = $apartment['visible'];
            $newApartment->slug = Str::slug($apartment['title'], '-');
            $newApartment->user_id = $apartment['user_id'];
            $newApartment->lat = $apartment['lat'];
            $newApartment->lon = $apartment['lon'];
            $newApartment->save();
        }
    }

    public static function storeimage($img, $name)
    {
        $myurl = $img;
        $contents = file_get_contents($myurl);

        $name = Str::slug($name, '-') . '.png';
        $path = 'images/' . $name;
        Storage::put('images/' . $name, $contents);
        return $path;
    }

}
