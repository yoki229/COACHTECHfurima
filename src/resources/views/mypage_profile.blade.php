@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage_profile.css')}}">
@endsection

{{-- ヘッダー --}}

@section('menu')
@if (Auth::check())

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

{{-- コンテンツ --}}

@section('content')
<div class="profile-form">
    <h2 class="profile-form__heading content__heading">プロフィール設定</h2>
    <div class="profile-form__inner">
        <form class="profile-form__form" action="/update_profile" method="post" enctype="multipart/form-data">
        @csrf

            {{-- プロフィール画像 --}}
            <div class="profile-form__group">
                <!-- プレビュー画像 -->
                <div class="profile-form__file">
                    <img src="{{ asset('storage/test_images/user_default.png') }}" id="preview">
                    <!-- ファイル選択 -->
                    <input class="profile-form__file-input" type="file" name="image" id="image"  accept="image/*">
                    <label class="profile-form__file-label" for="image">画像を選択する</label>
                </div>
                @error('image')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

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
                <input class="profile-form__input" type="text" name="address" id="address" value="{{ old('address') }}">
                    @error('address')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
            </div>

            {{-- 建物名 --}}
            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                <input class="profile-form__input" type="text" name="building" id="building" value="{{ old('building') }}">
                    @error('building')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
            </div>

            <input type="hidden" name="redirect_to" value="{{ request('from') == 'register' ? '/' : '/mypage' }}">
            <input class="profile-form__btn btn" type="submit" value="更新する">
        </form>
    </div>
</div>

<!-- プレビュー表示用JavaScript -->
<script>
document.getElementById('image').addEventListener('change', function(event){
    const file = event.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>

@endsection