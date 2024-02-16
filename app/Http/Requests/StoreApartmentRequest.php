<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=> 'required|min:5|max:255|unique:apartments,title',
            'beds'=> 'required|integer',
            'rooms'=> 'required|integer',
            'bathrooms'=> 'required|integer',
            'square_meters'=> 'required|integer|min:1',
            'address'=> 'required|min:5',
            'cover_img'=> 'nullable|image',
            'services'=> 'exists:services,id|required|array|min:1',
            'lat'=> 'nullable',
            'lon'=> 'nullable',
            'visible'=> 'required',
            'user_id'=> 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve avere almeno :min caratteri',
            'title.max' => 'Il titolo deve avere al massimo :max caratteri',
            'title.unique' => 'esiste già un appartamento con questo nome',
            'rooms.required' => 'Il numero di stanze è obbligatorio',
            'rooms.integer' => 'Il numero di stanze deve essere un numero intero',
            'bathrooms.required' => 'Il numero di bagni è obbligatorio',
            'bathrooms.integer' => 'Il numero di bagni deve essere un numero intero',
            'square_meters.required' => 'Il numero di metri quadrati è obbligatorio',
            'square_meters.integer' => 'Il numero di metri quadrati deve essere un numero intero',
            'square_meters.min' => 'Il numero di metri quadrati deve essere almeno :min',
            'address.required' => 'L\'indirizzo è obbligatorio',
            'address.min' => 'L\'indirizzo deve avere almeno :min caratteri',
            'cover_img.image' => 'L\'immagine deve essere un\'immagine',
        ];
    }

}
