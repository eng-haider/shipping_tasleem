<?php

namespace App\Http\Requests\Driver;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
        $driverId = $this->route('driver');
        return [
            'name' => 'string|max:255',
            'phone' => 'string|max:255|unique:drivers,phone,' . $driverId . '|min:11',
            'image' => 'nullable|image|max:2048',
            'app_url' => 'nullable|string|max:255',
        ];
    }


    public function messages()
{
    return [
        'name.string' => 'The name must be a string.',
        'name.max' => 'The name may not be greater than :max characters.',
        'phone.string' => 'The phone must be a string.',
        'phone.max' => 'The phone may not be greater than :max characters.',
        'phone.unique' => 'The phone number has already been taken.',
        'phone.min' => 'The phone number must be at least :min characters.',
        'company_id.uuid' => 'The company ID must be a valid UUID.',
        'company_id.exists' => 'The selected company is invalid.',
        'image.image' => 'The image must be an image file.',
        'image.max' => 'The image may not be greater than :max kilobytes.',
        'governorate_id.required' => 'The governorate is required.',
        'governorate_id.exists' => 'The selected governorate is invalid.',
        'app_url.max' => 'The app URL may not be greater than :max characters.',
        'player_id.string' => 'The player ID must be a string.',
    ];
}

}
