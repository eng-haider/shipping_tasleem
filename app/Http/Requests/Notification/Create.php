<?php

namespace App\Http\Requests\Notification;

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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'driverIds' => 'required|array',
            'driverIds.*' => 'required|uuid|exists:drivers,id',
        ];
    }


    public function messages()
{
    return [
        'take.integer' => 'The take field must be an integer.',
        'take.max' => 'The take field may not be greater than :max.',
        'from.date' => 'The from field must be a valid date.',
        'from.before' => 'The from date must be before the to date.',
        'to.date' => 'The to field must be a valid date.',
        'to.after' => 'The to date must be after the from date.',
    ];
}

}
