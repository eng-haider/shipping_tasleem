<?php

namespace App\Http\Requests\Index;

use Illuminate\Foundation\Http\FormRequest;

class Pagination extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'take' => 'integer|max:500',
        ];
    }

    public function messages()
{
    return [
        'take.integer' => 'The take field must be an integer.',
        'take.max' => 'The take field may not be greater than :max.',
    ];
}

}
