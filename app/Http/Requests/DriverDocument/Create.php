<?php

namespace App\Http\Requests\DriverDocument;

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
            'driver_id' => 'required|uuid|exists:drivers,id',
            'title' => 'required|string',
            'file' => 'required|file',
            'document_id' => 'required|uuid|exists:documents,id',
        ];
    }

    public function messages()
{
    return [
        'driver_id.required' => 'The driver ID is required.',
        'driver_id.uuid' => 'The driver ID must be a valid UUID.',
        'driver_id.exists' => 'The selected driver ID is invalid.',
        'file.required' => 'The file is required.',
        'file.file' => 'The file must be a file.',
        'document_id.required' => 'The document ID is required.',
        'document_id.uuid' => 'The document ID must be a valid UUID.',
        'document_id.exists' => 'The selected document ID is invalid.',
    ];
}


}
