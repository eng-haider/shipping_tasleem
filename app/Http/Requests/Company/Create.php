<?php

namespace App\Http\Requests\Company;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:companies,name,NULL,deleted_at',
            'governorates' => 'required|array|distinct',
            'governorates.*' => 'required|exists:governorates,id',
        ];
    }


    public function messages()
{
    return [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
        'name.unique' => 'The name has already been taken.',
        'governorates.required' => 'The governorates field is required.',
        'governorates.array' => 'The governorates must be an array.',
        'governorates.distinct' => 'The governorates must be distinct.',
        'governorates.*.required' => 'Each governorate field is required.',
        'governorates.*.exists' => 'One or more selected governorates do not exist.',
    ];
}

}
