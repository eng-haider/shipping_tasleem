<?php

namespace App\Http\Requests\DriverVehicle;

use Illuminate\Foundation\Http\FormRequest;

class DriverCreate extends FormRequest
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
            'name' => 'required|string',
            'vehicle_no' => 'required|string',
            'vehicle_type' => 'required|integer|in:0,1,2,3,4',
            'color' => 'required|string',
            'image' => 'nullable|image',
        ];
    }


    public function messages()
{
    return [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name field must be a string.',
        'vehicle_no.required' => 'The vehicle number field is required.',
        'vehicle_no.string' => 'The vehicle number field must be a string.',
        'vehicle_type.required' => 'The vehicle type field is required.',
        'vehicle_type.integer' => 'The vehicle type must be an integer.',
        'vehicle_type.in' => 'The vehicle type must be one of the following values: 0, 1, 2, 3, 4.',
        'color.required' => 'The color field is required.',
        'color.string' => 'The color field must be a string.',
        'image.image' => 'The image must be an image.',
    ];
}

}
