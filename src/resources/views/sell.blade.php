@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
@endsection

@section('content')
<div class="sell">
    <form class="sell-form" action="/sell" method="post" enctype="multipart/form-data">
    @csrf
        <h1 class="title">商品の出品</h1>

        {{-- 商品画像 --}}
        <div class="sell-form__group">
            <p class="form__item-title">商品画像</p>
            <div class="sell-form__file">
                <!-- プレビュー -->
                <img class="file-preview" id="preview">
                <!-- ファイル選択 -->
                <input class="sell-form__file-input" type="file" name="item_image" id="image"  accept="image/*">
                <label class="sell-form__file-label" for="image">画像を選択する</label>
            </div>
            @error('image')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品の詳細 --}}
        <h2 class="form__title">商品の詳細</h2>
        <hr>

        {{-- カテゴリー --}}
        <div class="sell-form__group">
            <p class="form__item-title">カテゴリー</p>
            <div class="sell-form__category">
                <div class="category__list">
                    @foreach ($categories as $category)
                        <label class="category-label">
                            <input type="checkbox"
                            name="category[]"
                            value="{{ $category->id }}">
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('category')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 商品の状態 --}}
        <div class="sell-form__group">
            <p class="form__item-title">商品の状態</p>
            <div class="sell-form__status">
                <select name="status" class="status-select">
                    <option value="" selected hidden>選択してください</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
                @error('status')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 商品名と説明 --}}
        <h2 class="form__title">商品名と説明</h2>
        <hr>

        {{-- 商品名 --}}
        <div class="sell-form__group">
            <p class="form__item-title">商品名</p>
            <div>
                <input class="sell-form__name" type="text" name="name" value="{{ old('name') }}">
            </div>
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- ブランド名 --}}
        <div class="sell-form__group">
            <p class="form__item-title">ブランド名</p>
            <div>
                <input class="sell-form__brand" type="text" name="brand" value="{{ old('brand') }}">
            </div>
        </div>

        {{-- 商品の説明 --}}
        <div class="sell-form__group">
            <p class="form__item-title">商品の説明</p>
            <textarea class="sell-form__description" rows="7" name="description">{{ old('description') }}</textarea>
            @error('description')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- 販売価格 --}}
        <div class="sell-form__group">
            <p class="form__item-title">販売価格</p>
            <div class="sell-form__price">
                <span class="yen-mark">￥</span>
                <input class="price-input" type="text" name="price" value="{{ old('price') }}">
            </div>
            @error('price')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="sell-form__group">
            <div class="sell-form__button">
                <button class="store-button">出品する</button>
            </div>
        </div>
    </form>
</div>

<!-- プレビュー表示用JavaScript -->
<script>
document.getElementById('image').addEventListener('change', function (event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block'; // プレビューを表示
        };

        reader.readAsDataURL(file);
    } else {
        // ファイル選択解除したら非表示に戻す
        preview.src = '';
        preview.style.display = 'none';
    }
});
</script>

@endsection