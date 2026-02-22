<nav>
    <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="Logo"></a>
    <ul>
        <li><a href="{{ route('specialties.index') }}">Специальности</a></li>
        <li><a href="{{ route('page.resources') }}">Ресурсы</a></li>
        @auth
            <li><a href="{{ route('applications.index') }}">Личный кабинет</a></li>
        @else
            <li><a href="{{ route('login') }}">Войти</a></li>
        @endauth
    </ul>
    <div class="call">
        <img src="{{ asset('assets/img/iPhone.svg') }}" alt="Phone">
        <div>
            <p style="font-size: 14px; font-weight: bold;">Call us</p>
            <p>8 (405) 555-0128</p>
        </div>
    </div>
    <div class="talk">
        <img src="{{ asset('assets/img/Chat.svg') }}" alt="Chat">
        <div>
            <p style="font-size: 14px; font-weight: bold;">Talk to us</p>
            <p>Applicant@slt.com</p>
        </div>
    </div>
</nav>
