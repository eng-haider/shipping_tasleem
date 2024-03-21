<?php

namespace App\Http\Requests\Status;

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
            'name' => 'required|string|max:255|unique:statuses,name',
            'color' => 'required|string|max:6|min:6',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
{
    return [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
        'name.max' => 'The name may not be greater than :max characters.',
        'name.unique' => 'The name has already been taken.',
        'color.required' => 'The color field is required.',
        'color.string' => 'The color must be a string.',
        'color.max' => 'The color must be exactly :max characters long.',
        'color.min' => 'The color must be exactly :min characters long.',
        'image.image' => 'The image must be an image.',
        'image.max' => 'The image may not be greater than :max kilobytes.',
    ];
}

}
