<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'rooms', 'beds', 'bathrooms', 'square_meters', 'address', 'cover_img', 'slug', 'visible', 'lat', 'lon', 'user_id'];

    public static function getSlug($title)
    {
        $slug = Str::of($title)->slug('-');
        $count = 1;

        while(Apartment::where("slug", $slug)->first()) {
            $slug = Str::of($title)->slug('-') . "-{$count}";
            $count++;
        }

        return $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
