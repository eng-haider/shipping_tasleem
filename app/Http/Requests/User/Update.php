<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
        $id = $this->route('user');
        return [
            'name' => 'string',
            'phone' => 'numeric|unique:users,phone,'.$id . '|min:11',
        ];
    }


    public function messages()
{
    return [
        'name.string' => 'The name must be a string.',
        'phone.numeric' => 'The phone must be a numeric value.',
        'phone.unique' => 'The phone number has already been taken.',
        'phone.min' => 'The phone number must be at least :min digits.',
    ];
}

}
