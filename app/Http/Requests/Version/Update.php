<?php

namespace App\Http\Requests\Version;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'version' => 'string|max:255',
            'android_url' => 'string|max:255',
            'android_public' => 'integer|in:0,1',
            'android_active' => 'integer|in:0,1',
            'android_cache' => 'integer|in:0,1',
            'ios_url' => 'string|max:255',
            'ios_public' => 'integer|in:0,1',
            'ios_active' => 'integer|in:0,1',
            'ios_cache' => 'integer|in:0,1',
        ];
    }


    public function messages()
{
    return [
        'version.string' => 'The version must be a string.',
        'version.max' => 'The version may not be greater than :max characters.',
        'android_url.string' => 'The Android URL must be a string.',
        'android_url.max' => 'The Android URL may not be greater than :max characters.',
        'android_public.integer' => 'The Android public field must be an integer.',
        'android_public.in' => 'The selected Android public is invalid.',
        'android_active.integer' => 'The Android active field must be an integer.',
        'android_active.in' => 'The selected Android active is invalid.',
        'android_cache.integer' => 'The Android cache field must be an integer.',
        'android_cache.in' => 'The selected Android cache is invalid.',
        'ios_url.string' => 'The iOS URL must be a string.',
        'ios_url.max' => 'The iOS URL may not be greater than :max characters.',
        'ios_public.integer' => 'The iOS public field must be an integer.',
        'ios_public.in' => 'The selected iOS public is invalid.',
        'ios_active.integer' => 'The iOS active field must be an integer.',
        'ios_active.in' => 'The selected iOS active is invalid.',
        'ios_cache.integer' => 'The iOS cache field must be an integer.',
        'ios_cache.in' => 'The selected iOS cache is invalid.',
    ];
}

}
