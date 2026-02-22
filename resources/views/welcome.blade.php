@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<style>
    .error-message {
        color: #FF5A30;
        font-size: 0.8em;
        margin-top: 5px;
        margin-bottom: 10px;
        padding: 8px 12px;
        background-color: rgba(255, 90, 48, 0.1);
        border-radius: 4px;
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }

    .error-message.active {
        display: block;
    }

    .input-error {
        border: 1px solid #FF5A30 !important;
    }

    #login-general-error {
        margin-bottom: 15px;
    }

    .form-group {
        margin-bottom: 15px;
        position: relative;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-message {
        color: #4CAF50;
        font-size: 0.9em;
        margin: 10px 0;
        padding: 10px;
        background-color: rgba(76, 175, 80, 0.1);
        border-radius: 4px;
        display: none;
    }

    .success-message.active {
        display: block;
    }
</style>

<main>
    @guest
    <form class="register-form" method="post" action="{{ route('register') }}" id="register">
        @csrf
        <label><span style="color: #FF5A30;">App</span><span>licant</span></label>

        <!-- Сообщения об успехе/ошибке -->
        @if (session('success'))
        <div class="success-message active">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
        <div class="error-message active">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <div class="form-group">
            <input type="text" name="name" placeholder="Полное имя" required value="{{ old('name') }}">
            @error('name')
            <div class="error-message active">{{ $message }}</div>
            @enderror
            <div class="error-message" id="name-error"></div>
        </div>

        <div class="form-group">
            <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
            @error('email')
            <div class="error-message active">{{ $message }}</div>
            @enderror
            <div class="error-message" id="email-error"></div>
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Пароль" required>
            @error('password')
            <div class="error-message active">{{ $message }}</div>
            @enderror
            <div class="error-message" id="password-error"></div>
        </div>

        <div class="form-group">
            <input type="password" name="password_confirmation" placeholder="Подтвердите пароль" required>
            <div class="error-message" id="password-confirm-error"></div>
        </div>

        <button type="submit">ЗАРЕГИСТРИРОВАТЬСЯ</button>
        <p style="text-align: center; margin-top: 15px; font-size: 12px; color: #666;">
            Нажимая вы соглашаетесь с <a href="#" style="color: #FF5A30;">условиями</a> нашей политики и конфиденциальности.
        </p>
        <div class="form-switch">
            Уже есть аккаунт? <a href="#login" class="switch-to-login">Войти</a>
        </div>
    </form>

    <form class="login-form active" method="post" action="{{ route('login.post') }}" id="login">
        @csrf
        <label><span style="color: #FF5A30;">App</span><span>licant</span></label>

        @if (session('status'))
        <div class="success-message active">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
        <div class="error-message active">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
        <input type="password" name="password" placeholder="Пароль" required>
        <div class="error-message" id="login-email-error"></div>
        <div class="error-message" id="login-password-error"></div>
        <button type="submit">ВОЙТИ</button>
        <div class="form-switch">
            Нет аккаунта? <a href="#register" class="switch-to-register">Зарегистрироваться</a>
        </div>
    </form>
    @else
    <div class="welcome-section">
        <h1 class="welcome-title">Добро пожаловать, {{ Auth::user()->name }}!</h1>
        <p class="welcome-text">
            Вы успешно вошли в систему. Теперь вы можете подать заявку на поступление или просмотреть статус ваших заявок.
        </p>
        <div class="action-buttons">
            <a href="{{ route('applications.create') }}" class="action-button">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Подать заявку
            </a>
            <a href="{{ route('applications.index') }}" class="action-button">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 12H15M15 12L12 9M15 12L12 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Мои заявки
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="action-button" style="background-color: #787A80; width: auto; margin-bottom: 0; border: none;">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 17L21 12M21 12L16 7M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Выйти
                </button>
            </form>
        </div>
    </div>
    @endguest
</main>

@guest
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('.register-form');
    const loginForm = document.querySelector('.login-form');
    const switchToLoginLinks = document.querySelectorAll('.switch-to-login');
    const switchToRegisterLinks = document.querySelectorAll('.switch-to-register');

    // По умолчанию показываем форму входа
    if (!loginForm.classList.contains('active') && !registerForm.classList.contains('active')) {
        loginForm.classList.add('active');
    }

    // Функция для показа формы входа
    function showLoginForm() {
        registerForm.classList.remove('active');
        loginForm.classList.add('active');
        window.location.hash = '#login';
    }

    // Функция для показа формы регистрации
    function showRegisterForm() {
        loginForm.classList.remove('active');
        registerForm.classList.add('active');
        window.location.hash = '#register';
    }

    // Обработчики для ссылок переключения
    switchToLoginLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showLoginForm();
        });
    });

    switchToRegisterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showRegisterForm();
        });
    });

    // Проверяем хэш в URL при загрузке страницы
    function checkHashOnLoad() {
        const hash = window.location.hash;
        if (hash === '#register') {
            showRegisterForm();
        } else {
            showLoginForm();
        }
    }

    // Обработчик изменения хэша
    window.addEventListener('hashchange', checkHashOnLoad);

    // Инициализация при загрузке
    checkHashOnLoad();
});
</script>
@endguest
@endsection
