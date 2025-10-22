@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <div class="container-inner">
        <div class="container__item">
            {{-- 商品画像 --}}
            <div class="item-image {{ $item->sold_class }}">
                <img src="{{ asset($item->image) }}" alt="商品画像" class="item__img" />
            </div>
        </div>

        <div class="container__item">
            {{-- 商品名 --}}
            <div class="item-detail__name">
                <h1 class="item-name">{{ $item->name }}</h1>
                @if ($item->buyer_id)
                    <span class="sold">sold</span>
                @endif
            </div>

            {{-- ブランド名 --}}
            <div class="item-detail__brand">
                @if($item->brand_name)
                    <p class="item-brand">ブランド：{{ $item->brand_name }}</p>
                @endif
            </div>

            {{-- 値段 --}}
            <div class="item-detail__price">
                <span class="item-price">￥</span>
                <span class="item-price-number">{{ number_format($item->price) }}</span>
                <span class="item-price">(税込)</span>
            </div>

            <div class="item-detail__count">
                {{-- いいね機能・いいね数 --}}
                <form class="like-button__item" action="/item/{{ $item->id }}/like" method="post">
                    @csrf
                    @if (Auth::check())
                        @if($item->liked)
                            <button type="submit" class="like-button--liked">
                                <i class="fa-solid fa-star"></i>
                            </button>
                        @else
                            <button type="submit" class="like-button--none">
                                <i class="fa-regular fa-star"></i>
                            </button>
                        @endif
                    @else
                        <p class="like-button--auth" title="ログインするといいねできます">
                            <i class="fa-regular fa-star"></i>
                        </p>
                    @endif
                    <p class="like-count">{{ $likeCount }}</p>
                </form>

                {{-- コメント数 --}}
                <div class="comment-count__item">
                    <i class="fa-regular fa-comment comment-count__icon"></i>
                    <p class="comment-count__number">{{ $commentCount }}</p>
                </div>
            </div>

            {{-- 購入手続きボタン --}}
            <div class="item-detail__purchase-page">
                    <div class="purchase-page">
                        @if ($item->buyer_id)
                        <div class="purchase-page--sold">
                            <p>購入できません</p>
                        </div>
                        @else
                            <a href="/purchase/{{ $item->id }}" class="purchase-page__link">購入手続きへ</a>
                        @endif
                    </div>
            </div>

            {{-- 商品説明 --}}
            <div class="item-detail__description">
                <h2 class="item__title">商品説明</h2>
                <p class="item-description__text">{{ $item->description }}</p>
            </div>

            {{-- 商品の情報 --}}
            <div class="item-detail__status">
                <h2 class="item__title">商品の情報</h2>

                {{-- カテゴリー --}}
                <div class="item-status__category">
                    <p class="category__title">カテゴリー</p>
                    <div class="category__list">
                        @foreach($categories as $category)
                            <div class="category__category">
                                {{ $category->name }}
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 商品の状態 --}}
                <div class="item-status__status">
                    <p class="status__title">商品の状態</p>
                    <p class="status__status">
                        {{ $item->status->name }}
                    </p>
                </div>
            </div>

            {{-- コメント欄 --}}
            <div class="item-detail__comments">
                <h2 class="item__title">
                    コメント ({{ $commentCount }})
                </h2>

                {{--コメント一覧--}}
                <div class="item-comment__list">
                    @foreach($item->comments as $comment)
                    <div class="comment-list">
                        <div class="comment-user">
                            <img src="{{ $comment->user->profile_image }}"
                            alt="ユーザーアイコン"
                            class="comment-icon">
                            <span class="comment-username">
                                {{ $comment->user->name }}
                            </span>
                        </div>
                        <p class="comment-content">{{ $comment->comment }}</p>
                    </div>
                    @endforeach
                </div>

                {{--コメントフォーム--}}
                <div class="item-comment__form">
                    <p class="item__form-title">
                        商品へのコメント
                    </p>

                    {{-- コメント入力時のエラーメッセージ --}}
                    @error('comment')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                        <form action="/item/{{ $item->id }}/comments_store" method="post" class="item-comment__form">
                            @csrf
                            <textarea name="comment" rows="10" class="comment-form__textarea">{{ old('comment') }}</textarea>

                            {{-- ログインしていないユーザーにエラーメッセージ --}}
                            @if(session('error'))
                                <p class="error-message__login">{{ session('error') }}</p>
                            @endif

                            <button class="comment-form__button">コメントを送信する</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection