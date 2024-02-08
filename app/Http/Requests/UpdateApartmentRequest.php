<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApartmentRequest extends FormRequest
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
            'rooms'=> 'required|integer',
            'bathrooms'=> 'required|integer',
            'square_meters'=> 'required|integer',
            'address'=> 'required|min:5',
            'cover_image'=> 'nullable|image',
            'services'=> 'exists:services,id',
        ];
    }
}
