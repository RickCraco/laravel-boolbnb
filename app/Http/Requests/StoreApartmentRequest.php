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
            'title'=> 'required|min:5|max:255',
            'beds'=> 'required|integer',
            'rooms'=> 'required|integer',
            'bathrooms'=> 'required|integer',
            'square_meters'=> 'required|integer',
            'address'=> 'required|min:5',
            'cover_img'=> 'nullable|image',
            'services'=> 'exists:services,id',
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
            'rooms.required' => 'Il numero di stanze è obbligatorio',
            'rooms.integer' => 'Il numero di stanze deve essere un numero intero',
            'bathrooms.required' => 'Il numero di bagni è obbligatorio',
            'bathrooms.integer' => 'Il numero di bagni deve essere un numero intero',
            'square_meters.required' => 'Il numero di metri quadrati è obbligatorio',
            'square_meters.integer' => 'Il numero di metri quadrati deve essere un numero intero',
            'address.required' => 'L\'indirizzo è obbligatorio',
            'address.min' => 'L\'indirizzo deve avere almeno :min caratteri',
            'cover_img.image' => 'L\'immagine deve essere un\'immagine',
        ];
    }

}
