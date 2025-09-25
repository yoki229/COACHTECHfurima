@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('link')
<ul class="header-nav">
@if (Auth::check())
  <li>
    <form action="/logout" method="post">
      @csrf
      <button class="">ログアウト</button>
    </form>
  </li>
@endif
</ul>
@endsection

@section('content')