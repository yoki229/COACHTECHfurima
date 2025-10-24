@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css')}}">
@endsection

@section('content')
<div class="mypage">
    {{-- ユーザー --}}
    <div class="mypage__user">
        <div class="user__profile">
            <img class="user__img" src="{{ $user->profile_image }}">
            <p class="user__name">{{ $user->name }}</p>
        </div>
        <a href="/mypage_profile" class="user__profile-link">プロフィールを編集</a>
    </div>

    {{-- リスト切り替え --}}
    <div class="mypage__menu">
        <a href="/mypage?page=sell" class="list-menu__sell {{ $activeTab === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>
        <a href="/mypage?page=buy" class="list-menu__buy {{ $activeTab === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>
    </div>

    <hr>

    {{-- 商品一覧 --}}
    <div class="mypage__item">
        @foreach ($items as $item)
        <div class="item-card">
            <a href="/item/{{ $item->id }}" class="item-card__link">
                <div class="item-card__wrapper {{ $item->sold_class }}">
                    <img src="{{ asset($item->image) }}" alt="商品画像" class="item-card__img" />
                </div>
                <div class="item-card__content">
                    <p class="item-name">{{$item->name}}</p>
                    @if ($item->buyer_id)
                        <span class="sold">sold</span>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

@endsection