<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;

//あとでミドルウエアにいれる
Route::get('/mypage_profile', [ProfileController::class, 'mypageProfile']);     //プロフィール編集画面
Route::post('/update_profile', [ProfileController::class, 'updateProfile']);        //プロフィール更新後処理用
Route::get('/mypage', [ProfileController::class, 'mypage']);        //プロフィール画面
Route::get('/item{item_id}', [ItemController::class, 'getItem']);       //商品詳細画面
Route::get('/search', [ItemController::class, 'search']);       //検索処理用
Route::get('/sell', [ItemController::class, 'sell']);       //商品出品画面


Route::middleware('auth')->group(function () {
    Route::get('/', [ItemController::class, 'index']);      //商品一覧画面（トップ画面）


});