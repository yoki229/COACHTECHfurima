<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        // 未メール認証ユーザーなら /mail にリダイレクト
        if ($user && ! $user->hasVerifiedEmail()) {
            return redirect('/email');
        }

        // 認証済みユーザーはhome
        return redirect('/');
    }
}
