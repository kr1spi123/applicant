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
            background: #F0F2F8;
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
            color: #2D7A4F;
        }

        nav ul li a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0;
            height: 2px;
            background-color: #2D7A4F;
            transition: width 0.3s ease;
        }

        nav ul li a:hover::after {
            width: 100%;
        }

        .call,
        .talk {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .call img,
        .talk img {
            width: 24px;
            height: 24px;
            margin: 0;
        }

        .call div,
        .talk div {
            display: flex;
            flex-direction: column;
        }

        .call p,
        .talk p {
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
            color: #2D7A4F;
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
            background: linear-gradient(55.95deg, #1F5C3A 0%, #2D7A4F 100%);
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
            color: #2D7A4F;
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
                    <a href="https://vk.com/gpouslt" target="_blank" rel="noopener" title="ВКонтакте">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.785 16.241s.288-.032.436-.194c.136-.148.132-.427.132-.427s-.02-1.304.586-1.496c.598-.19 1.365 1.26 2.179 1.815.615.416 1.082.325 1.082.325l2.172-.03s1.135-.07.597-.963c-.044-.073-.314-.661-1.616-1.869-1.364-1.265-1.181-1.060.462-3.246.999-1.332 1.399-2.146 1.274-2.494-.12-.332-.855-.244-.855-.244l-2.443.015s-.181-.025-.315.056c-.132.079-.217.262-.217.262s-.387 1.03-.903 1.906c-1.088 1.85-1.524 1.948-1.702 1.834-.414-.267-.31-1.075-.31-1.649 0-1.793.272-2.54-.529-2.733-.266-.064-.461-.107-1.141-.114-.872-.009-1.609.003-2.025.207-.278.136-.492.44-.361.457.161.021.526.099.72.363.25.341.241 1.107.241 1.107s.144 2.11-.335 2.372c-.328.179-.778-.186-1.745-1.858-.496-.857-.871-1.805-.871-1.805s-.072-.176-.202-.271c-.157-.115-.376-.151-.376-.151l-2.322.015s-.348.01-.476.161c-.114.135-.009.414-.009.414s1.816 4.25 3.872 6.395c1.886 1.967 4.025 1.838 4.025 1.838h.97z" fill="rgba(255,255,255,0.6)" />
                        </svg>
                    </a>
                    <a href="https://t.me/SLT_FAQ_bot" target="_blank" rel="noopener" title="Telegram бот">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.944 3.239a1.5 1.5 0 0 0-1.53-.225L2.541 9.75a1.5 1.5 0 0 0 .09 2.812l4.177 1.392 1.932 5.797a.75.75 0 0 0 1.285.225l2.474-3.097 4.344 3.193a1.5 1.5 0 0 0 2.363-.938l2.25-13.5a1.5 1.5 0 0 0-.512-1.195zM10.5 17.127l-1.35-4.05 6.525-5.452-5.175 9.502zm1.275-1.402.45-3.6 2.025 1.485-2.475 2.115zm4.725 2.025-4.725-3.47 7.2-13.23-2.475 16.7z" fill="rgba(255,255,255,0.6)" />
                        </svg>
                    </a>
                    <a href="https://slt-online.ru" target="_blank" rel="noopener" title="Официальный сайт">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                    </a>
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
                    <div style="background: #2D7A4F; width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
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