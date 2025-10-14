@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css')}}">
@endsection

@section('content')

<form class="like-button" action="/item/{{ $item->id }}/like" method="post">
    @csrf
    @if($item->liked)
        <button type="submit" class="like-button--liked">★</button>
    @else
        <button type="submit" class="like-button--none">☆</button>
    @endif
</form>


@endsection