<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
       return [
            'payment_id' => 'required',
            'address_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'payment_id.required' => '支払い方法を選択してください',
            'address_id.required' => '配送先を選択してください',
        ];
    }
}
