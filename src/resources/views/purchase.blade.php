@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')
<div class="container">
    <form action="/purchase/{{ $item->id }}" method="post">
    @csrf
        <div class="container-inner">
            {{-- 左側 --}}
            <div class="container__left">
                {{-- 商品 --}}
                <div class="item">
                    {{-- 商品画像 --}}
                    <div class="item-image">
                        <img src="{{ asset($item->image) }}" alt="商品画像" class="item__img" />
                    </div>

                    {{-- 商品名と値段 --}}
                    <div class="item-detail">
                        <h1 class="item-name">{{ $item->name }}</h1>

                        <div class="item-detail__price">
                            <span class="item-price">￥</span><span class="item-price-number">{{ number_format($item->price) }}</span>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- 支払い方法 --}}
                <div class="pay">
                    <h3 class="pay-title">支払い方法</h3>
                    <select name="payment_method" id="payment_id" class="pay-select">
                        <option value="" selected hidden>選択してください</option>
                        @foreach($payments as $pay => $label)
                            <option value="{{ $pay }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    {{-- バリデーションエラー表示 --}}
                    @error('payment_method')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <hr>

                {{-- 配送先 --}}
                <div class="address">
                    <div class="address__head">
                        <h3 class="address-title">配送先</h3>
                        <a href="/purchase/address/{{ $item->id }}" class="address-put">変更する</a>
                    </div>
                    <div class="address-list">
                        <p class="address-postal">〒{{ $user->postal_code }}</p>
                        <p class="address-text">{{ $user->address }}{{ $user->building }}</p>

                        <input type="hidden" name="postal_code" value="{{ $user->postal_code }}">
                        <input type="hidden" name="address" value="{{ $user->address }}">
                        <input type="hidden" name="building" value="{{ $user->building }}">
                    </div>
                </div>
                <hr>
            </div>

            {{-- 右側 --}}
            <div class="container__right">
                {{-- 決済確認 --}}
                <table class="sell-table">
                    <tr>
                        <th>商品代金</th>
                        <td>
                            <span class="item-price">￥</span><span class="item-price-number">{{ number_format($item->price) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>支払方法</th>
                        <td id="payment-preview">未選択</td>
                    </tr>
                </table>

                {{-- 購入ボタン --}}
                <div class="sell-button">
                    <button type="submit" class="sell-button__button">購入する</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const select = document.getElementById('payment_id');
    const preview = document.getElementById('payment-preview');

    select.addEventListener('change', function() {
        // 選択したオプションのテキストを取得して右側に反映
        const selectedLabel = select.options[select.selectedIndex].text;
        preview.textContent = selectedLabel;
    });
</script>

@endsection