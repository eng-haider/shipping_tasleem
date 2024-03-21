<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeFromCompany extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|unique:users,phone|min:11',
            'governorate_id' => [
                Rule::exists('company_governorates', 'governorate_id')->where('company_id', auth('user')->user()->company->company_id)
            ]
        ];

    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than :max characters.',
            'phone.required' => 'The phone number is required.',
            'phone.numeric' => 'The phone number must be numeric.',
            'phone.unique' => 'The phone number has already been taken.',
            'phone.min' => 'The phone number must be at least :min digits.',
            'governorate_id.exists' => 'The selected governorate is invalid.',
        ];

    }
}
