<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UserChangeStatus extends FormRequest
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
            'status_id' => 'required|uuid|exists:statuses,id',
            'notes' => 'nullable|string',
        ];
    }


    public function messages()
{
    return [
        'status_id.required' => 'The status ID field is required.',
        'status_id.uuid' => 'The status ID must be a valid UUID.',
        'status_id.exists' => 'The selected status ID is invalid.',
        'notes.string' => 'The notes must be a string.',
    ];
}

}
