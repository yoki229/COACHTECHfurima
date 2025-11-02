@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/mail.css')}}">
@endsection

@section('content')
<div class="mail">
    <div class="message">
        <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
        <p>メール認証を完了してください。</p>
    </div>

    <form method="GET" action="/email/check">
        <button class="send" type="submit">認証はこちらから</button>
    </form>


    <form method="POST" action="/email/resend">
        @csrf
        <button class="resend" type="submit">確認メールを再送する</button>
    </form>

</div>
@endsection('content')