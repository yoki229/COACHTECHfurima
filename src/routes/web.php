<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;

Route::get('/', [ItemController::class, 'index']);      //商品一覧画面（おすすめ画面）
Route::get('/item/{item_id}', [ItemController::class, 'getItem']);               //商品詳細画面
Route::post('/item/{item_id}/comments_store', [ItemController::class, 'commentsStore']);               //コメント投稿機能

//あとでミドルウエアにいれる
Route::get('/mypage_profile', [ProfileController::class, 'mypageProfile']);     //プロフィール編集画面
Route::post('/update_profile', [ProfileController::class, 'updateProfile']);    //プロフィール更新後処理用
Route::get('/mypage', [ProfileController::class, 'mypage']);                    //プロフィール画面
Route::post('/item/{item_id}/like', [ItemController::class, 'like']);       //いいね機能
Route::get('/search', [ItemController::class, 'search']);                       //検索処理用
Route::get('/sell', [ItemController::class, 'sell']);                           //商品出品画面


Route::middleware('auth')->group(function () {
    Route::get('/mylist', [ItemController::class, 'mylist']);          //商品画面一覧（マイリスト画面）

});