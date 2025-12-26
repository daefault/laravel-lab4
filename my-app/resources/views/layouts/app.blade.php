<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Лабораторная №4')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    @yield('styles')
</head>

<body>

    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="https://avatars.mds.yandex.net/i?id=e5f9483464194c8bc568ccf408002b90_sr-8081694-images-thumbs&n=13"
                    alt="Логотип сайта">
                <span class="ms-2">Персонажи из м/с "Губка Боб Квадратные Штаны"</span>
            </a>
            <div class="d-flex align-items-center">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Друзья
                            @if(auth()->user()->receivedFriendRequests()->where('status', 'pending')->count() > 0)
                                <span class="badge bg-danger">
                                    {{ auth()->user()->receivedFriendRequests()->where('status', 'pending')->count() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('friends.index') }}">Мои друзья</a></li>
                            <li><a class="dropdown-item" href="{{ route('friends.requests') }}">Запросы</a></li>
                            <li><a class="dropdown-item" href="{{ route('friends.feed') }}">Лента</a></li>

                        </ul>
                    </li>
                    <a href="{{ route('users.index') }}" class="btn btn-custom me-2">Все пользователи</a>
                    <a href="{{ route('characters.my') }}" class="btn btn-custom me-2">Мои персонажи</a>
                    <a href="{{ route('characters.create') }}" class="btn btn-custom me-2">Добавить</a>
                    <a href="{{ route('tokens.index') }}" class="btn btn-custom me-2">API Токены</a>

                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.trash') }}" class="btn btn-danger me-2">Корзина</a>
                    @endif

                    <span class="me-2">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-custom me-2">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-custom">Регистрация</a>
                @endauth
            </div>
        </div>
    </nav>


    <main class="container my-4">
        @yield('content')
    </main>


    <footer class="footer-custom py-3 mt-auto">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0 fw-bold">Денисов Артём</p>
                <a href="/" class="btn btn-sm btn-outline-light">На главную</a>
            </div>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
</body>

</html>