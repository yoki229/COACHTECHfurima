<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'index']);
});

Route::get('/mypage_profile', [AuthController::class, 'mypageProfile']);

Route::get('/test-login', function () 
    {Auth::loginUsingId(1);
    return redirect('/'); // ホームにリダイレクト
});