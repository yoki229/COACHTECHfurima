<!DOCTYPE html>
<html class="html" lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ</title>
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
  <link rel="stylesheet" href="{{ asset('css/common.css')}}">
  @yield('css')
</head>

<body class="body">
  <div class="app">

    <header class="header">
      <div class="header-nav__logo">
        <a class="header-nav__logo-link" href="/">
          <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="header__img">
        </a>
      </div>
      @if (Auth::check())
      <div class="header-nav__search">
          <form action="/search" method="get">
              <input class="search-box" type="search" name="keyword" placeholder="なにをお探しですか？" value="{{request('keyword')}}">
          </form>
      </div>
      <ul class="header-nav__list">
          <li>
              <form action="/logout" method="post">
              @csrf
                  <button class="list__logout">ログアウト</button>
              </form>
          </li>
          <li>
              <a href="/mypage" class="list__mypage">マイページ</a>
          </li>
          <li>
              <a href="/sell" class="list__sellpage">出品</a>
          </li>
      </ul>
      @endif
    </header>

    <div class="content">
      @yield('content')
    </div>

  </div>
</body>

</html>
