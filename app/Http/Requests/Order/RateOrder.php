<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class RateOrder extends FormRequest
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
            'rate' => ['required', 'numeric', 'min:1', 'max:5'],
        ];
    }


    public function messages()
{
    return [
        'rate.required' => 'The rate field is required.',
        'rate.numeric' => 'The rate field must be a number.',
        'rate.min' => 'The rate field must be at least :min.',
        'rate.max' => 'The rate field may not be greater than :max.',
    ];
}

}
