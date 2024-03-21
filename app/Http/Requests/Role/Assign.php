<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class Assign extends FormRequest
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
            'permissionIds' => 'required|array',
            'permissionIds.*' => 'required|integer|exists:permissions,id',
        ];
    }
}
