@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css')}}">
@endsection

@section('content')

<form action="/item/{{ $item->id }}/like" method="POST">
    @csrf
    <button type="submit" class="like-button {{ $item->liked ? 'liked' : '' }}">
        ♥
    </button>
    <p>{{ $item->liked ? 'いいね済み' : '未いいね' }}</p>
</form>


@endsection