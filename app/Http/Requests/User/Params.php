<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Params extends FormRequest
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
            'take' => 'integer|min:1|max:1000',
            'user_type' => 'required|string|in:super-admin,company-admin,all',
            'company_id' => 'uuid|exists:companies,id'
        ];
    }

    public function messages()
{
    return [
        'take.integer' => 'The take field must be an integer.',
        'take.min' => 'The take field must be at least :min.',
        'take.max' => 'The take field may not be greater than :max.',
        'user_type.required' => 'The user type field is required.',
        'user_type.string' => 'The user type field must be a string.',
        'user_type.in' => 'The selected user type is invalid.',
        'company_id.uuid' => 'The company ID must be a valid UUID.',
        'company_id.exists' => 'The selected company is invalid.',
    ];
}

}
