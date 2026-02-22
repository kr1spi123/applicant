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
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="admin-logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
                    <h2>Админ-панель</h2>
                </a>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li class="{{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}">
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
                    <li>
                        <a href="{{ route('home') }}" style="display: block; padding: 12px 15px; text-decoration: none; color: #424551; font-weight: 500;">На сайт</a>
                    </li>
                </ul>
            </nav>
        </div>

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
</body>
</html>
