@extends('layouts.app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('menu')
<ul class="header-nav">
@if (Auth::check())
  <li>
    <form action="/logout" method="post">
      @csrf
      <button class="nav__logout">ログアウト</button>
    </form>
  </li>
@endif
</ul>
@endsection

@section('content')

@endsection