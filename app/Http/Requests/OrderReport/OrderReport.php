<?php

namespace App\Http\Requests\OrderReport;

use Illuminate\Foundation\Http\FormRequest;

class OrderReport extends FormRequest
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
            'from_date' => 'date|before_or_equal:to_date',
            'to_date' => 'date|after_or_equal:from_date',
            'company_id' => 'uuid|exists:companies,id',
        ];
    }


    public function messages()
{
    return [
        'from_date.date' => 'The :attribute must be a valid date format.',
        'from_date.before_or_equal' => 'The :attribute must be before or equal to the "to" date.',
        'to_date.date' => 'The :attribute must be a valid date format.',
        'to_date.after_or_equal' => 'The :attribute must be after or equal to the "from" date.',
        'company_id.uuid' => 'The :attribute must be a valid UUID format.',
        'company_id.exists' => 'The selected :attribute is invalid.', // Assuming you want to use a generic message for existence check failure
    ];
}

}
