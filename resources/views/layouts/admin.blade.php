<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Админ-панель')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') . '?v=' . (file_exists(public_path('css/admin.css')) ? filemtime(public_path('css/admin.css')) : time()) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <div class="admin-container">
        <!-- Horizontal Navbar -->
        <header class="admin-navbar">
            <div class="navbar-brand">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
                <h2>Админ-панель</h2>
            </div>
            <button class="navbar-toggle" aria-label="Меню" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav class="navbar-menu">
                <ul class="navbar-nav">
                    <li class="{{ request()->routeIs('admin.specialties.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.specialties.index') }}">Специальности</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.applications.index') }}">Заявки</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.statistics.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.statistics.index') }}">Статистика</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.enrollment.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.enrollment.index') }}">Рейтинги</a>
                    </li>
                </ul>
            </nav>

            <div class="navbar-user">
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                </div>
                <a href="{{ route('home') }}" class="btn-home">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    На главную
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <div class="admin-main">
            @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="alert error">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggle = document.querySelector('.navbar-toggle');
            var nav = document.querySelector('.navbar-nav');
            if (toggle && nav) {
                toggle.addEventListener('click', function() {
                    var open = nav.classList.toggle('open');
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
            }
        });
    </script>
</body>

</html>
