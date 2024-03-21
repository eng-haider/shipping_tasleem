<?php

namespace App\Http\Requests\Otp;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpDriver extends FormRequest
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
            
        ];
    }


    public function messages()
{
    return [
        'phone.required' => 'The phone number is required.',
        'phone.numeric' => 'The phone number must be numeric.',
        'phone.exists' => 'The provided phone number does not exist or is not active.',
        'phone.min' => 'The phone number must be at least :min digits.',
    ];
}

}
