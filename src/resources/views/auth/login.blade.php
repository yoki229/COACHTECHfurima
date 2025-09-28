@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css')}}">
@endsection

@section('content')
<div class="login-form">
  <h2 class="login-form__heading content__heading">ログイン</h2>
  <div class="login-form__inner">
    <form class="login-form__form" action="/login" method="post">
      @csrf

      {{-- メールアドレス --}}
      <div class="login-form__group">
        <label class="login-form__label" for="email">メールアドレス</label>
        <input class="login-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
        @error('email')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      {{-- パスワード --}}
      <div class="login-form__group">
        <label class="login-form__label" for="password">パスワード</label>
        <input class="login-form__input" type="password" name="password" id="password">
        @error('password')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      {{-- ログインエラー（全体エラー） --}}
      @error('login_error')
        <div class="login-form__group">
          <p class="error-message">{{ $message }}</p>
        </div>
      @enderror

      <input class="login-form__btn" type="submit" value="ログインする">
    </form>
    <a class="register__link" href="/register">会員登録はこちら</a>
  </div>
</div>
@endsection('content')