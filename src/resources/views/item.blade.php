@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="container__item">
        {{-- 商品画像 --}}
        <div class="item-image {{ $item->sold_class }}">
            <img src="{{ asset($item->image) }}" alt="商品画像" class="item__img" />
        </div>
    </div>

    <div class="container__item">
        {{-- 商品名 --}}
        <div class="item-detail__name">
            <p class="item-name">{{ $item->name }}</p>
            @if ($item->buyer_id)
                <span class="sold">sold</span>
            @endif
        </div>

        {{-- ブランド名 --}}
        <div class="item-detail__brand">
            @if($item->brand_name)
                <p class="item-brand">{{ $item->brand_name }}</p>
            @endif
        </div>

        {{-- 値段 --}}

        {{-- いいね機能・いいね数 --}}
        <form class="like-button" action="/item/{{ $item->id }}/like" method="post">
            @csrf
            @if($item->liked)
                <button type="submit" class="like-button--liked">★</button>
            @else
                <button type="submit" class="like-button--none">☆</button>
            @endif
        </form>

        {{-- コメント数 --}}

        {{-- 購入手続きボタン --}}

        {{-- 商品説明 --}}

        {{-- 商品の情報 --}}

        {{-- コメント欄 --}}
    </div>
</div>
@endsection