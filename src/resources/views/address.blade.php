@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css')}}">
@endsection

@section('content')

<div class="address-content">
    <h1 class="title">住所の変更</h1>
    <form class="address-form" action="/purchase/address/{{ $item->id }}" method="POST">
        @csrf
        <input type="hidden" name="item_id" value="{{ request()->item_id }}">

        <div class="postal-code">
            <label class="form-label">郵便番号</label>
            <input class="form-input" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            @error('postal_code')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="address">
            <label class="form-label">住所</label>
            <input class="form-input" type="text" name="address" value="{{ old('address', $user->address) }}">
            @error('address')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="building">
            <label class="form-label">建物名</label>
            <input class="form-input" type="text" name="building" value="{{ old('building', $user->building) }}">
        </div>

        <button class="button" type="submit">更新する</button>
    </form>
</div>
@endsection