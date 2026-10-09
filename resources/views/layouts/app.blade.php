<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Книжная полка')</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ route('store.index') }}">
            <span class="brand-mark">К</span>
            <span>
                <strong>Книжная полка</strong>
                <small>русская литература</small>
            </span>
        </a>
        <nav class="site-nav" aria-label="Основная навигация">
            <a href="{{ route('store.index') }}">Каталог</a>
            @if (session('user_id'))
                @if (session('user_role') === 'admin')
                    <a href="{{ route('admin.books.index') }}">Администрирование</a>
                    <a href="{{ route('admin.orders.index') }}">Заказы</a>
                @else
                    <a href="{{ route('store.account') }}">Моя библиотека</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="link-button" type="submit">Выйти</button>
                </form>
            @else
                <a class="nav-login" href="{{ route('login') }}">Войти</a>
            @endif
        </nav>
    </header>

    <main class="page-shell">
        @if (session('status'))
            <div class="notice notice-success" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="notice notice-error" role="alert">
                <strong>Проверьте данные:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <span>Кейс-задача №4</span>
    </footer>
</body>
</html>
