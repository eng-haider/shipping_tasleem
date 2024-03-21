<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class History extends FormRequest
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
            'long' => 'required|numeric', 
            'lat' => 'required|numeric', 
            'fingerprint' => 'string|max:255',
        ];
    }

    public function messages()
{
    return [
        'long.required' => 'The long field is required.',
        'long.numeric' => 'The long field must be a number.',
        'lat.required' => 'The lat field is required.',
        'lat.numeric' => 'The lat field must be a number.',
        'fingerprint.string' => 'The fingerprint must be a string.',
        'fingerprint.max' => 'The fingerprint may not be greater than :max characters.',
    ];
}

}
