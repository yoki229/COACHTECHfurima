<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\RegisterRequest as FortifyRegisterRequest;

class RegisterRequest extends FortifyRegisterRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:20',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required'         => 'お名前を入力してください',
            'name.max'              => 'お名前は20文字以内で入力してください',
            'email.required'        => 'メールアドレスを入力してください',
            'email.email'           => 'メールアドレスは「@ドメイン」形式で入力してください',
            'password.required'     => 'パスワードを入力してください',
            'password.min'          => 'パスワードは8文字以上で入力してください',
            'password.confirmed'    => 'パスワードが一致しません',
        ];
    }
    
}
