<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;

//あとでミドルウエアにいれる
Route::get('/mypage_profile', [ProfileController::class, 'mypageProfile']);
Route::post('/update_profile', [ProfileController::class, 'updateProfile']);
Route::get('/mypage', [ProfileController::class, 'mypage']);
Route::get('/search', [ItemController::class, 'search']);
Route::get('/sell', [ItemController::class, 'sell']);


Route::middleware('auth')->group(function () {
    Route::get('/', [ItemController::class, 'index']);


});