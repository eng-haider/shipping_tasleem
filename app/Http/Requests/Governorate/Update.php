<?php

namespace App\Http\Requests\Governorate;

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
        return [
            'name' => 'string|max:255|unique:governorates,name,' . $this->id,
        ];
    }

    public function messages()
{
    return [
        'name.string' => 'The name must be a string.',
        'name.max' => 'The name may not be greater than :max characters.',
        'name.unique' => 'The name has already been taken.',
    ];
}

}
