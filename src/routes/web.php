<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;

//商品一覧画面（おすすめ画面）の表示
Route::get('/', [ItemController::class, 'index']);
//商品詳細画面の表示
Route::get('/item/{item_id}', [ItemController::class, 'getItem']);
//コメント投稿機能
Route::post('/item/{item_id}/comments_store', [ItemController::class, 'commentsStore']);

//ログイン済み用のルーティング
Route::middleware('auth')->group(function () {
    //商品画面一覧（マイリスト画面）の表示
    Route::get('/mylist', [ItemController::class, 'mylist']);
    //プロフィール編集画面の表示
    Route::get('/mypage_profile', [ProfileController::class, 'mypageProfile']);
    //プロフィール更新処理
    Route::post('/update_profile', [ProfileController::class, 'updateProfile']);
    //プロフィール画面の表示
    Route::get('/mypage', [ProfileController::class, 'mypage']);
    //住所変更画面の表示
    Route::get('/purchase/address/{item_id}',[ProfileController::class, 'editAddress']);
    //住所変更処理
    Route::post('/purchase/address/{item_id}', [ProfileController::class, 'updateAddress']);

    //いいね機能
    Route::post('/item/{item_id}/like', [ItemController::class, 'like']);
    //検索処理
    Route::get('/search', [ItemController::class, 'search']);
    //商品購入画面の表示
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase']);
    //商品購入時処理
    Route::post('/purchase/{item_id}', [ItemController::class, 'store']);
    //出品ページの表示の表示
    Route::get('/sell', [ItemController::class, 'sell']);

});