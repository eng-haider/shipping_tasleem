<?php

namespace App\Http\Requests\Company;

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
        return [
            'name' => 'string|unique:companies,name,' . $this->id,
            'company_cdc_id' => 'string',
        ];
    }


    public function messages()
{
    return [
        'name.string' => 'The name must be a string.',
        'name.unique' => 'The name has already been taken.',
    ];
}


}
