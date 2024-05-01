<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Atte</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header-logo">
      <h1>
        Atte
      </h1>
    </div>
    <div class="header__inner">
      @if (Auth::check())
      <nav>
        <ul class="header-nav">
          <li class="header-nav__item">
            <a class="header-nav__link" href="/">ホーム</a>
          </li>
          <li class="header-nav__item">
            <a class="header-nav__link" href="/attendance">日付一覧</a>
          </li>
          <li class="header-nav__item">
            <form class="form" action="/logout" method="post">
              @csrf
              <button class="header-nav__button">ログアウト</button>
            </form>
          </li>
        </ul>
      </nav>
      @endif
    </div>
  </header>

  <main>
    @yield('content')
  </main>

  <footer class="footer">
    <small class="footer__copyright">Atte,inc.</small>
  </footer>

</body>

</html>