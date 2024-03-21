<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class ReassignOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'long' => ['required', 'numeric'],
            'lat' => ['required', 'numeric'],
            'fingerprint' => ['required', 'string'],
            'fingerprint_image' => ['nullable', 'image']
        ];
    }


    public function messages()
{
    return [
        'long.required' => 'The longitude is required.',
        'long.numeric' => 'The longitude must be a number.',
        'lat.required' => 'The latitude is required.',
        'lat.numeric' => 'The latitude must be a number.',
        'fingerprint.required' => 'The fingerprint is required.',
        'fingerprint.string' => 'The fingerprint must be a string.',
        'fingerprint_image.image' => 'The fingerprint image must be an image file.'
    ];
}

}
