@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css')}}">
@endsection

@section('content')
<h2>住所の変更</h2>
<form action="/purchase/address/{{ $item->id }}" method="POST">
    @csrf
    <input type="hidden" name="item_id" value="{{ request()->item_id }}">

    <label>郵便番号</label>
    <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
    @error('postal_code')
        <p>{{ $message }}</p>
    @enderror

    <label>住所</label>
    <input type="text" name="address" value="{{ old('address', $user->address) }}">
    @error('address')
        <p>{{ $message }}</p>
    @enderror

    <label>建物名</label>
    <input type="text" name="building" value="{{ old('building', $user->building) }}">
    @error('building')
        <p>{{ $message }}</p>
    @enderror

    <button type="submit">更新する</button>
</form>
@endsection