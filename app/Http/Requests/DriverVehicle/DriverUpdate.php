<?php

namespace App\Http\Requests\DriverVehicle;

use Illuminate\Foundation\Http\FormRequest;

class DriverUpdate extends FormRequest
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
            'name' => 'string',
            'vehicle_no' => 'string',
            'vehicle_type' => 'integer|in:0,1,2,3,4',
            'color' => 'string',
            'image' => 'nullable|image',
        ];
    }


    public function messages()
{
    return [
        'name.string' => 'The name must be a string.',
        'vehicle_no.string' => 'The vehicle number must be a string.',
        'vehicle_type.integer' => 'The vehicle type must be an integer.',
        'vehicle_type.in' => 'The vehicle type must be one of the following values: 0, 1, 2, 3, 4.',
        'color.string' => 'The color must be a string.',
        'image.image' => 'The image must be an image.',
    ];
}

}
