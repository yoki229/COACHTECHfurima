@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage_profile.css')}}">
@endsection

@section('menu')
@if (Auth::check())

    {{-- 検索ボックス --}}
    <div class="header-nav__search">
        <form action="/search" method="get">
            <input class="search-box" type="search" name="keyword" placeholder="なにをお探しですか？" value="{{request('keyword')}}">
        </form>
    </div>
    <ul class="header-nav__list">
        <li>
            <form action="/logout" method="post">
            @csrf
                <button class="list__logout">ログアウト</button>
            </form>
        </li>
        <li>
            <a href="/mypage" class="list__mypage">マイページ</a>
        </li>
        <li>
            <a href="/sell" class="list__sellpage">出品</a>
        </li>
    </ul>
@endif
@endsection

@section('content')
<div class="profile-form">
  <h2 class="profile-form__heading">プロフィール設定</h2>
  <div class="profile-form__inner">
    <form class="profile-form__form" action="/profile" method="post">
      @csrf

      {{-- ユーザー名 --}}
      <div class="profile-form__group">
        <label class="profile-form__label" for="name">ユーザー名</label>
        <input class="profile-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
        @error('name')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      {{-- 郵便番号 --}}
      <div class="profile-form__group">
        <label class="profile-form__label" for="postal_code">郵便番号</label>
        <input class="profile-form__input" type="text" name="postal_code" id="postal_code" pattern="\d{3}-?\d{4}" value="{{ old('postal_code') }}">
        @error('postal_code')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      {{-- 住所 --}}
      <div class="profile-form__group">
        <label class="profile-form__label" for="address">住所</label>
        <input class="profile-form__input" type="text" name="address" id="address">
        @error('address')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      {{-- 建物名 --}}
      <div class="profile-form__group">
        <label class="profile-form__label" for="building">建物名</label>
        <input class="profile-form__input" type="text" name="building" id="building">
        @error('building')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      <input class="profile-form__btn btn" type="submit" value="更新する">
    </form>
  </div>
</div>
@endsection