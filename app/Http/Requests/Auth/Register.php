<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
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
            'phone' => 'nullable|numeric|unique:users,phone|min:11',
        ];
    }

    public function messages()
{
    return [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
        'phone.numeric' => 'The phone must be a number.',
        'phone.unique' => 'The phone number has already been taken.',
        'phone.min' => 'The phone number must be at least :min digits.',
    ];
}

}