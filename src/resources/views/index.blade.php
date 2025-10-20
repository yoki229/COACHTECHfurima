@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="index-contents">

    @if(session('success'))
        <p class="success">{{session('success')}}</p>
    @endif

    {{-- 上部リスト --}}
    <div class="list-menu">
        <a href="/?keyword={{ $keyword }}" class="list-menu__recommend {{ $activeTab === 'recommend' ? 'active' : '' }}">
            おすすめ
        </a>
        <a href="/mylist?keyword={{ $keyword }}" class="list-menu__mylist {{ $activeTab === 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>

    {{-- 商品一覧 --}}
    <div class="index-contents__item">
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