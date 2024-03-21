<?php

namespace App\Http\Requests\DriverDocument;

use Illuminate\Foundation\Http\FormRequest;

class Status extends FormRequest
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
            'status' => 'required|integer|in:0,1,2',
        ];
    }

    public function messages()
{
    return [
        'status.required' => 'The status field is required.',
        'status.integer' => 'The status must be an integer.',
        'status.in' => 'The status must be one of the following values: 0, 1, 2.',
    ];
}


}
