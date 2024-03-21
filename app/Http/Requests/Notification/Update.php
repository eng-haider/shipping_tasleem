<?php

namespace App\Http\Requests\Notification;

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
            'notificationIds' => 'required|array',
            'notificationIds.*' => 'required|uuid|exists:driver_notifications,notification_id'
        ];
    }

    public function messages()
{
    return [
        'notificationIds.required' => 'The notificationIds field is required.',
        'notificationIds.array' => 'The notificationIds field must be an array.',
        'notificationIds.*.required' => 'One or more notificationIds are required.',
        'notificationIds.*.uuid' => 'The notificationId must be a valid UUID.',
        'notificationIds.*.exists' => 'One or more selected notificationIds are invalid.',
    ];
}

}
