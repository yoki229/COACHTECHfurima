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
    
}
 