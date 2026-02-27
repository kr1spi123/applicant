<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'АИС Абитуриент')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    @stack('styles')
    
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Header Styles */
        nav {
            display: flex;
            align-items: center;
            padding: 20px;
            margin: 0 auto;
            justify-content: space-between;
            max-width: 1200px;
            width: 100%;
            box-sizing: border-box;
            transition: all 0.3s ease;
            gap: 30px;
        }

        nav img {
            width: 200px;
            height: auto;
            margin-right: 0;
            transition: all 0.3s ease;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 40px;
            transition: all 0.3s ease;
            flex: 1;
            justify-content: center;
        }

        nav ul li a {
            cursor: pointer;
            text-decoration: none;
            font-size: 20px;
            color: #424551;
            font-weight: 700;
            line-height: 160%;
            font-style: normal;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        nav ul li a:hover {
            color: #FF5A30;
        }

        nav ul li a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0;
            height: 2px;
            background-color: #FF5A30;
            transition: width 0.3s ease;
        }

        nav ul li a:hover::after {
            width: 100%;
        }

        .call, .talk {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .call img, .talk img {
            width: 24px;
            height: 24px;
            margin: 0;
        }

        .call div, .talk div {
            display: flex;
            flex-direction: column;
        }

        .call p, .talk p {
            margin: 0;
            font-size: 14px;
        }
        
        /* Footer Styles */
        footer {
            width: 100%;
            background-color: #1E212C;
            color: #fff;
            padding-top: 60px;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            padding-bottom: 40px;
        }

        .footer-col h3 {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 24px;
            letter-spacing: 1px;
        }

        .footer-logo {
            margin-bottom: 24px;
            display: block;
        }

        .socials {
            display: flex;
            gap: 16px;
            margin-top: 24px;
        }

        .socials a {
            opacity: 0.6;
            transition: opacity 0.3s;
        }

        .socials a:hover {
            opacity: 1;
        }

        .contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .contact-list li {
            margin-bottom: 12px;
            font-size: 14px;
            opacity: 0.8;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .contact-list li strong {
            color: #fff;
            opacity: 1;
            min-width: 60px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s;
            font-size: 16px;
        }

        .footer-links a:hover {
            color: #FF5A30;
        }

        .subscribe-form {
            display: flex;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .subscribe-form input {
            flex: 1;
            background: transparent;
            border: none;
            padding: 12px 16px;
            color: #fff;
            outline: none;
        }
        
        .subscribe-form input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .subscribe-form button {
            background: linear-gradient(55.95deg, #FF3F3A 0%, #F75E05 100%);
            border: none;
            padding: 0 20px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .subscribe-form button:hover {
            opacity: 0.9;
        }
        
        .footer-bottom {
            background-color: #1a1c25;
            padding: 20px 0;
            margin-top: 20px;
        }
        
        .footer-bottom-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
        }
        
        .go-top-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .go-top-btn:hover {
            color: #FF5A30;
        }
        
        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .footer-bottom-content {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    @include('layouts.partials.header')

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <a href="{{ route('home') }}" class="footer-logo">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="max-height: 40px; filter: brightness(0) invert(1);" />
                </a>
                <p style="color: rgba(255,255,255,0.6); font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                    Мы помогаем абитуриентам найти свой путь и поступить в учебное заведение мечты.
                </p>
                <div class="socials">
                    <a href="#"><img src="{{ asset('assets/img/Whatsapp.png') }}" alt="WA"></a>
                    <a href="#"><img src="{{ asset('assets/img/Messanger.png') }}" alt="TG"></a>
                    <a href="#"><img src="{{ asset('assets/img/Facebook.png') }}" alt="FB"></a>
                    <a href="#"><img src="{{ asset('assets/img/YouTube.png') }}" alt="YT"></a>
                </div>
            </div>
            
            <div class="footer-col">
                <h3>Контакты</h3>
                <ul class="contact-list">
                    <li>
                        <strong>Адрес:</strong>
                        <span>г. Сыктывкар, ул. Менделеева, 2</span>
                    </li>
                    <li>
                        <strong>Телефон:</strong>
                        <span>8 (405) 555-0128</span>
                    </li>
                    <li>
                        <strong>Почта:</strong>
                        <span>applicant@gmail.com</span>
                    </li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Навигация</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('specialties.index') }}">Специальности</a></li>
                    <li><a href="{{ route('page.resources') }}">Ресурсы</a></li>
                    <li><a href="{{ route('applications.index') }}">Личный кабинет</a></li>
                    <li><a href="#">Помощь</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Рассылка</h3>
                <div class="subscribe-form">
                    <input type="email" placeholder="Ваш Email">
                    <button>→</button>
                </div>
                <div class="go-top-btn" id="goTop">
                    <span>Наверх</span>
                    <div style="background: #FF5A30; width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="19" x2="12" y2="5"></line>
                            <polyline points="5 12 12 5 19 12"></polyline>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <div>© {{ date('Y') }} АИС Абитуриент. Все права защищены.</div>
                <div>Политика конфиденциальности | Условия использования</div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('goTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.altKey && (e.key === 'a' || e.key === 'A')) {
                var link = document.querySelector('.admin-link');
                if (link) {
                    link.style.display = 'inline-block';
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
