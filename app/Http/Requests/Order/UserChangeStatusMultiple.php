<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UserChangeStatusMultiple extends FormRequest
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
            'orders' => 'required|array|distinct',
            'orders.*' => 'required|exists:orders,uuid,company_id,'. auth('user')->user()->company->company_id,
        ];
    }


    public function messages()
{
    return [
        'status_id.required' => 'The status ID field is required.',
        'status_id.uuid' => 'The status ID must be a valid UUID.',
        'status_id.exists' => 'The selected status ID is invalid.',

        'notes.string' => 'The notes field must be a string.',

        'orders.required' => 'The orders field is required.',
        'orders.array' => 'The orders must be an array.',
        'orders.distinct' => 'The orders field must not contain duplicate values.',

        'orders.*.required' => 'Each order ID field is required.',
        'orders.*.exists' => 'One or more selected order IDs are invalid.',
    ];
}

}
