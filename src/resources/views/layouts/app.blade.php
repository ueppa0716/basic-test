<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <h1 class="header__heading">Atte</h1>
            <nav class="header__link-nav">
                <ul class="header__link-ul">
                    @if (Auth::check())
                        <li class="header-link-li"><a class="header-link-a" href="/">ホーム</a></li>
                        <li class="header-link-li"><a class="header-link-a" href="/user">ユーザー一覧</a></li>
                        <li class="header-link-li"><a class="header-link-a" href="/attendance">日付一覧</a></li>
                        <li class="header-link-li">
                            <form action="/logout" method="post">
                                @csrf
                                <button type="submit">ログアウト</button>
                                <!-- <a class="header-link-a">ログアウト</a> -->
                                <!-- <input class="header-link-a" value="ログアウト" name="logout"></input> -->
                            </form>
                        </li>
                    @endif
                </ul>
            </nav>
        </header>

        <div class="page_top">
            @yield('page_top')
        </div>

        <div class="content">
            @yield('content')
        </div>

        <div class="page_bottom">
            @yield('page_bottom')
        </div>

        <footer class="footer">
            <p>Atte,inc.</p>
        </footer>
    </div>
</body>

</html>
