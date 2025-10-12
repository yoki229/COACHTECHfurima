@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="index-contents">
    <!-- storeアクションでwith('message',' '商品「を登録しました');をつけるなら↓を追加 -->
    <!-- @if(session('message'))
        <p class="message">{{session('message')}}</p>
        @endif -->
    {{-- 上部リスト --}}
    <div class="list-menu">
        <a href="/" class="list-menu__recommend {{ request()->is('/') ? 'active' : '' }}">おすすめ</a>
        <a href="/mylist" class="list-menu__mylist {{ request()->is('mylist') ? 'active' : '' }}">マイリスト</a>
    </div>

    {{-- 商品一覧 --}}
    <div class="index-contents__item">
        @foreach ($items as $item)
        <div class="item-card">
            <a href="/item/{{ $item->id }}" class="item-card__link">
                <img src="{{ asset($item->image) }}" alt="商品画像" class="item-card__img" />
                <div class="item-card__content">
                    <p>{{$item->name}}</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>

</div>
@endsection