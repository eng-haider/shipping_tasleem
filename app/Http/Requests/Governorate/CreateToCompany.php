<?php

namespace App\Http\Requests\Governorate;

use Illuminate\Foundation\Http\FormRequest;

class CreateToCompany extends FormRequest
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
            'governorate_id' => 'required|exists:governorates,id',
            'company_id' => 'required|exists:companies,id',
        ];
    }

    public function messages()
{
    return [
        'governorate_id.required' => 'The governorate ID field is required.',
        'governorate_id.exists' => 'The selected governorate ID is invalid.',
        'company_id.required' => 'The company ID field is required.',
        'company_id.exists' => 'The selected company ID is invalid.',
    ];
}

}
