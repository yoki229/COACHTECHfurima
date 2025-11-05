<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\StripeController;


//商品一覧画面（おすすめ画面）の表示
Route::get('/', [ItemController::class, 'index']);
//商品詳細画面の表示
Route::get('/item/{item_id}', [ItemController::class, 'getItem']);

//ログイン済み用のルーティング
Route::middleware('auth', 'verified')->group(function () {
    //商品画面一覧（マイリスト画面）の表示
    Route::get('/mylist', [ItemController::class, 'mylist']);
    //プロフィール編集画面の表示
    Route::get('/mypage_profile', [ProfileController::class, 'mypageProfile']);
    //プロフィール更新処理
    Route::post('/update_profile', [ProfileController::class, 'updateProfile']);
    //住所変更画面の表示
    Route::get('/purchase/address/{item_id}',[ProfileController::class, 'editAddress']);
    //住所変更処理
    Route::post('/purchase/address/{item_id}', [ProfileController::class, 'updateAddress']);

    //いいね機能
    Route::post('/item/{item_id}/like', [ItemController::class, 'like']);
    //コメント投稿機能
    Route::post('/item/{item_id}/comments_store', [ItemController::class, 'commentsStore']);
    //検索処理
    Route::get('/search', [ItemController::class, 'search']);
    //マイページ（出品一覧）の表示
    Route::get('/mypage', [ItemController::class, 'mypage']);
    //出品ページの表示の表示
    Route::get('/sell', [ItemController::class, 'sell']);
    //出品ページの表示の表示
    Route::post('/sell', [ItemController::class, 'sellStore']);

    //商品購入画面の表示
    Route::get('/purchase/{item_id}', [StripeController::class, 'purchase']);
    //商品購入時処理
    Route::post('/purchase/{item_id}', [StripeController::class, 'store']);
    // 成功・キャンセル時
    Route::get('/purchase/success/{item_id}', [StripeController::class, 'success']);
    Route::get('/purchase/cancel', [StripeController::class, 'cancel']);
});

// メール認証を促すページ
Route::get('/email',[MailController::class, 'email'])->name('verification.notice');
// メール認証のリンクをクリックしたときの処理
Route::get('/email/{id}/{hash}',[MailController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
// 認証はこちらからをクリックしたときの処理
Route::get('/email/check',[MailController::class, 'emailCheck'])->name('verification.handle');
// メール再送信処理
Route::post('/email/resend',[MailController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

if (app()->environment('local')){
    //メール送信テスト用ルート
    Route::get('/mail_test',[MailController::class, 'index']);
}