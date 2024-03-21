<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderExportParams extends FormRequest
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
            'from' => 'required|date|before:to',
            'to' => 'required|date|after:from',
            'take' => 'integer|min:1',
            'columns' => 'required|array',
            'columns.*' => 'required|string|in:id,uuid,tr,bn,nd,rate,companyName,statusName,customerName,customerPhone,customerAddress,driverName,driverPhone,governorateName'
        ];
    }
}
