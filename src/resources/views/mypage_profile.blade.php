@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage_profile.css')}}">
@endsection

@section('content')
<div class="profile-form">
    <h2 class="profile-form__heading content__heading">プロフィール設定</h2>
    <div class="profile-form__inner">
        <form class="profile-form__form" action="/update_profile" method="post" enctype="multipart/form-data">
        @csrf

            {{-- プロフィール画像 --}}
            <div class="profile-form__group">
                <div class="profile-form__file">
                    <!-- プレビュー -->
                    <img class="profile-form__file-preview" src="{{ $user->profile_image }}" id="preview">
                    <!-- ファイル選択 -->
                    <input class="profile-form__file-input" type="file" name="profile_image" id="image"  accept="image/*">
                    <label class="profile-form__file-label" for="image">画像を選択する</label>
                </div>
                @error('profile_image')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- ユーザー名 --}}
            <div class="profile-form__group">
                <label class="profile-form__label" for="name">ユーザー名</label>
                <input class="profile-form__input" type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
            </div>

            {{-- 郵便番号 --}}
            <div class="profile-form__group">
                <label class="profile-form__label" for="postal_code">郵便番号</label>
                <input class="profile-form__input" type="text" name="postal_code" id="postal_code" pattern="\d{3}-?\d{4}" value="{{ old('postal_code', $user->postal_code) }}">
                    @error('postal_code')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
        </div>

            {{-- 住所 --}}
            <div class="profile-form__group">
                <label class="profile-form__label" for="address">住所</label>
                <input class="profile-form__input" type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
                    @error('address')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
            </div>

            {{-- 建物名 --}}
            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                <input class="profile-form__input" type="text" name="building" id="building" value="{{ old('building', $user->building) }}">
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