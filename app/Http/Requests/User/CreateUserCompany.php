<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserCompany extends FormRequest
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
            'company_id' => 'required|uuid|exists:companies,id',
            'governorate_id' => [
                Rule::exists('company_governorates', 'governorate_id')->where('company_id', $this->company_id)
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
        'company_id.required' => 'The company ID is required.',
        'company_id.uuid' => 'The company ID must be a valid UUID.',
        'company_id.exists' => 'The selected company is invalid.',
        'governorate_id.exists' => 'The selected governorate is invalid for the company.',
    ];
}

}
