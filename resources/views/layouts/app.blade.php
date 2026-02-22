<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>АИС Абитуриент</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('applications.index') }}" class="text-xl font-bold">АИС Абитуриент</a>
            <div>
                @auth
                    <span class="mr-4">Привет, {{ Auth::user()->name }}</span>
                    <a href="{{ route('applications.create') }}" class="bg-green-500 px-4 py-2 rounded hover:bg-green-600">Подать заявление</a>
                @else
                    <a href="/login-dev" class="bg-white text-blue-600 px-4 py-2 rounded">Войти (Dev)</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8 p-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
