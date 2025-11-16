<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {

            // ログイン済み & 未認証 → /login へ
            if (! Auth::user()->hasVerifiedEmail()) {
                return $next($request);
            }

            // ログイン済み & 認証済み → /index へ
            return redirect(RouteServiceProvider::HOME);
        }

    // 未ログインは普通に /login にアクセス可能
    return $next($request);
    }
}
