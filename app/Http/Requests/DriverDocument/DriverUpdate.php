<?php

namespace App\Http\Requests\DriverDocument;

use Illuminate\Foundation\Http\FormRequest;

class DriverUpdate extends FormRequest
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
            'file' => 'file',
            'title' => 'string',
        ];
    }

    public function messages()
{
    return [
        'file.file' => 'The file must be a file.',
    ];
}

}
