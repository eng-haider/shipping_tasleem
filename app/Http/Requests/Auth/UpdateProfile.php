<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
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
        $id = auth('user')->user()->id;
        return [
            'name' => 'string',
            'phone' => 'nullable|string|unique:users,phone,' . $id. '|min:11',
        ];
    }

    public function messages()
{
    return [
        'name.string' => 'The name must be a string.',
        'phone.string' => 'The phone must be a string.',
        'phone.unique' => 'The phone number has already been taken.',
        'phone.min' => 'The phone number must be at least :min characters.',
    ];
}


}
