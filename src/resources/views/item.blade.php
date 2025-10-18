@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <div class="item-detail__price">
            <span class="item-price">{{ $item->price }}</span>
            <span class="item-price__tax">（税込）</span>
        </div>

        {{-- いいね機能・いいね数 --}}
        <form class="like-button" action="/item/{{ $item->id }}/like" method="post">
            @csrf
            @if($item->liked)
                <button type="submit" class="like-button--liked">★</button>
            @else
                <button type="submit" class="like-button--none">☆</button>
            @endif
            <p class="like-count">{{ $likeCount }}</p>
        </form>

        {{-- コメント数 --}}
        <p class="comment-count">
            <i class="fa-regular fa-comment comment-icon"></i>
            <p class="comment-count">{{ $commentCount }}</p>
        </p>

        {{-- 購入手続きボタン --}}

        {{-- 商品説明 --}}

        {{-- 商品の情報 --}}

        {{-- コメント欄 --}}
    </div>
</div>
@endsection