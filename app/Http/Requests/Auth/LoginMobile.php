<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginMobile extends FormRequest
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
            'phone' => 'required|numeric|exists:drivers,phone,is_active,1|min:11',
            'otp' => 'required|numeric|min:6',
        ];


        
    }


    public function messages()
{
    return [
        'phone.required' => 'The phone number is required.',
        'phone.numeric' => 'The phone number must be numeric.',
        'phone.exists' => 'The phone number does not exist or is not active.',
        'phone.min' => 'The phone number must be at least :min digits.',
        'otp.required' => 'The OTP is required.',
        'otp.numeric' => 'The OTP must be numeric.',
        'otp.min' => 'The OTP must be at least :min characters.',
    ];
}

}
