<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class DriverUpdateProfile extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $id = auth('driver')->user()->id;
        return [
            'name' => 'string',
            'phone' => 'nullable|numeric|unique:drivers,phone,' . $id. '|min:11',
            'image' => 'image',
            'personal_code' => 'string|unique:drivers,personal_code,'. $id,
            'player_id' => 'string',

        ];
    }

    public function messages()
{
    return [
        'name.string' => 'The name must be a string.',
        'phone.numeric' => 'The phone must be a number.',
        'phone.unique' => 'The phone number has already been taken.',
        'phone.min' => 'The phone number must be at least :min digits.',
        'image.image' => 'The image must be an image file.',
        'personal_code.string' => 'The personal code must be a string.',
        'personal_code.unique' => 'The personal code has already been taken.',
        'player_id.string' => 'The player id must be a string.',
    ];
}

}
