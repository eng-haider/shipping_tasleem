<?php

namespace App\Http\Requests\Driver;

use Illuminate\Validation\Rule;
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
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|unique:drivers,phone|min:11',
            'image' => 'nullable|image|max:2048',
            'governorate_id' => 'required|exists:company_governorates,governorate_id,company_id,'. $this->company_id,
            Rule::unique('drivers', 'phone')->where(function ($query){
                $query->where('is_active', 1);
            }),
            'company_id' => 'required|uuid|exists:companies,id',
            'app_url' => 'nullable|string|max:255',
            'player_id' => 'string',
        ];


        
    }

    public function messages()
{
    return [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
        'name.max' => 'The name may not be greater than :max characters.',
        'phone.required' => 'The phone number is required.',
        'phone.numeric' => 'The phone number must be numeric.',
        'phone.unique' => 'The phone number has already been taken.',
        'phone.min' => 'The phone number must be at least :min digits.',
        'image.image' => 'The image must be an image file.',
        'image.max' => 'The image may not be greater than :max kilobytes.',
        'governorate_id.required' => 'The governorate is required.',
        'governorate_id.exists' => 'The selected governorate is invalid.',
        'company_id.required' => 'The company is required.',
        'company_id.uuid' => 'The company ID must be a valid UUID.',
        'company_id.exists' => 'The selected company is invalid.',
        'app_url.max' => 'The app URL may not be greater than :max characters.',
        'player_id.string' => 'The player ID must be a string.',
    ];
}


}
