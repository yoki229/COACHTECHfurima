@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')
<div class="container">
<form action="/purchase/{{ $item->id }}" method="POST">
    @csrf
    <div class="container-inner">
        <div class="container__left">
            {{-- 商品 --}}
            <div class="item">
                {{-- 商品画像 --}}
                <div class="item-image">
                    <img src="{{ asset($item->image) }}" alt="商品画像" class="item__img" />
                </div>

                {{-- 商品名 --}}
                <div class="item-detail__name">
                    <h1 class="item-name">{{ $item->name }}</h1>
                    @if ($item->buyer_id)
                        <span class="sold">sold</span>
                    @endif
                </div>

                {{-- 商品値段 --}}
                <div class="item-detail__price">
                    <span class="item-price">￥</span>
                    <span class="item-price-number">{{ number_format($item->price) }}</span>
                </div>
            </div>
            <hr>
            {{-- 支払い方法 --}}
            <div class="pay">
                <label for="payment_id" class="pay-title">
                    支払い方法
                </label>
                <select name="payment_id" id="payment_id" class="pay-select" required>
                    <option value="" disabled selected>選択してください</option>
                    @foreach($payments as $pay => $label)
                        <option value="{{ $pay }}">{{ $label }}</option>
                    @endforeach
                </select>
                {{-- バリデーションエラー表示 --}}
                @error('payment_id')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <hr>
            {{-- 配送先 --}}
            <div class="address">
                <h3>配送先</h3>
                <p>〒{{ $user->postal_code }}</p>
                <p>{{ $user->address }}</p>
                <p>{{ $user->building }}</p>

                <a href="/purchase/address/{{ $item->id }}">変更する</a>
            </div>
            <hr>
        </div>

        <div class="container__right">
            {{-- 決済確認 --}}
            <table class="sell-table">
                <tr>
                    <th>商品代金</th>
                    <td>{{ number_format($item->price) }}</td>
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