<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile_image' => 'file|mimes:jpg,jpeg,png',
            'name' => 'required|max:20',
            'postal_code' => 'required|regex:/^[0-9]{3}-[0-9]{4}$/',
            'address' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'profile_image.mimes'   => '画像はjpegまたはpng形式を選択してください',
            'name.required'         => '名前を入力してください',
            'name.max'              => '名前は20文字以内で入力してください',
            'postal_code.required'  => '郵便番号を入力してください',
            'postal_code.regex'     => '郵便番号はハイフンを入れて8文字で入力してください',
            'address.required'      => '住所を入力してください',
        ];
    }
}
