<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class DriverChangeStatus extends FormRequest
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
            'status_id' => 'required|uuid|exists:statuses,id',
            'long' => 'required|numeric', 
            'lat' => 'required|numeric', 
            'fingerprint' => 'string|max:255',
            'image' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
            'rate' => 'numeric|min:0|max:5',
            'fingerprint_image' => 'nullable|image|max:2048'
        ];
    }


    public function messages()
{
    return [
        'tr.required' => 'The tr field is required.',
        'tr.string' => 'The tr field must be a string.',
        'tr.max' => 'The tr field may not be greater than :max characters.',
        'image.image' => 'The image must be an image.',
        'image.max' => 'The image may not be greater than :max kilobytes.',
        'fingerprint_image.image' => 'The fingerprint image must be an image.',
        'fingerprint_image.max' => 'The fingerprint image may not be greater than :max kilobytes.',
        'long.required' => 'The long field is required.',
        'long.numeric' => 'The long field must be a number.',
        'lat.required' => 'The lat field is required.',
        'lat.numeric' => 'The lat field must be a number.',
    ];
}

}
