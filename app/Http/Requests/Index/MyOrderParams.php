<?php

namespace App\Http\Requests\Index;

use Illuminate\Foundation\Http\FormRequest;

class MyOrderParams extends FormRequest
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
            'from' => 'date|before_or_equal:to',
            'to' => 'date|after_or_equal:from',
            'statuses' => 'array|exists:statuses,id',
        ];
    }


    public function messages()
{

    return [
        'take.integer' => 'The take field must be an integer.',
        'take.max' => 'The take field must not be greater than :max.',
        'from.date' => 'The from field must be a valid date.',
        'to.date' => 'The to field must be a valid date.',
        'from.before_or_equal' => 'The from field must be before or equal to the to field.',
        'to.after_or_equal' => 'The to field must be after or equal to the from field.',
        'statuses.array' => 'The statuses field must be an array.',
        'statuses.exists' => 'One or more selected statuses are invalid.',
    ];
}

}
