<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class AddPermissionsToUser extends FormRequest
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
            'permissionsIds' => 'required|array',
        ];
    }

    public function messages()
{
    return [
        'permissionsIds.required' => 'The permissionsIds field is required.',
        'permissionsIds.array' => 'The permissionsIds must be an array.',
    ];
}

}
