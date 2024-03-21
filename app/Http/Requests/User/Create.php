<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
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
        return [
            'name' => 'required|string',
            'phone' => 'required|numeric|unique:users,phone|min:11',
            'role_id' => 'required|integer|exists:roles,id'
        ];
    }

    public function messages()
{
    return [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
        'phone.required' => 'The phone number is required.',
        'phone.numeric' => 'The phone number must be numeric.',
        'phone.unique' => 'The phone number has already been taken.',
        'phone.min' => 'The phone number must be at least :min digits.',
        'role_id.integer' => 'The role ID must be an integer.',
        'role_id.exists' => 'The selected role is invalid.',
    ];
}

}
