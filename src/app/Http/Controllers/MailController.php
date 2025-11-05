<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class MailController extends Controller
{
    // メール送信テスト用ルート
    public function index()
    {
        // Bladeの内容をHTMLに変換
        $html = view('emails.test')->render();

        // HTMLメールとして送信
        Mail::html($html, function ($message) {
            $message->to('abc@example.com', 'test')
                    ->subject('This is a test mail');
        });

        return 'メールを送信しました';
    }

    // メール認証を促すページ
    public function email(){
        return view('auth.mail');
    }

    // メール認証のリンクをクリックしたときの処理
    public function verify(EmailVerificationRequest $request){
        $request->fulfill(); // 認証完了
        Auth::login($request->user());
        return redirect('mypage_profile?from=register');
    }

    // 認証はこちらからをクリックしたときの処理
    public function emailCheck(){
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect('mypage_profile');
        }
        else {
            return redirect('/email')
            ->with('message', 'メール認証を完了してください。');
        }
    }

    // メール再送信処理
    public function resend(Request $request){
        $request->user()->sendEmailVerificationNotification();
        return back();
    }
}
