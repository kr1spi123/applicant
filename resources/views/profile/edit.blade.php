@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/lkform.css') . '?v=' . (file_exists(public_path('css/lkform.css')) ? filemtime(public_path('css/lkform.css')) : time()) }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') . '?v=' . (file_exists(public_path('css/auth.css')) ? filemtime(public_path('css/auth.css')) : time()) }}">
    <link rel="stylesheet" href="{{ asset('css/lkapp.css') . '?v=' . (file_exists(public_path('css/lkapp.css')) ? filemtime(public_path('css/lkapp.css')) : time()) }}">

    <div class="nav-links">
        <a href="{{ route('applications.create') }}" class="{{ request()->routeIs('applications.create') ? 'active' : '' }}">Подать заявку на поступление</a>
        <a href="{{ route('applications.index') }}" class="{{ request()->routeIs('applications.index') ? 'active' : '' }}">Мои заявки</a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Мой профиль</a>
    </div>

    <div class="profile-page">
        <div class="container">
            <div class="application-card profile-main-card">
                <div class="profile-main-top">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div class="profile-avatar">
                            {{ mb_substr($user->name, 0, 1) }}@if($user->surname){{ mb_substr($user->surname, 0, 1) }}@endif
                        </div>
                        <div class="profile-main-info">
                            <div class="profile-name">
                                {{ $user->surname ? $user->surname . ' ' . $user->name : $user->name }}
                            </div>
                            <div class="profile-tag">Абитуриент</div>
                            <div class="profile-meta">
                                В кабинете с {{ $user->created_at?->format('d.m.Y') ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <button type="button" class="profile-edit-toggle">
                        Редактировать профиль
                    </button>
                </div>

                <div class="profile-progress-wrapper">
                    <div class="profile-progress-header">
                        <span class="profile-progress-title">Заполненность профиля</span>
                        <span class="profile-progress-value">{{ $completion ?? 0 }}%</span>
                    </div>
                    <div class="profile-progress-bar" aria-hidden="true">
                        <div class="profile-progress-fill" style="width: {{ $completion ?? 0 }}%;"></div>
                    </div>
                </div>

                <div class="profile-badges">
                    @php
                        $completionValue = $completion ?? 0;
                    @endphp
                    @if($completionValue >= 80)
                        <span class="profile-badge badge-success">Почти готов к подаче документов</span>
                    @elseif($completionValue >= 50)
                        <span class="profile-badge badge-warning">Заполните ещё несколько полей</span>
                    @else
                        <span class="profile-badge badge-neutral">Начните заполнять профиль</span>
                    @endif
                </div>

                <div class="profile-tabs" role="tablist">
                    <button type="button" class="profile-tab-button active" data-tab="personal" role="tab">
                        Личная информация
                    </button>
                    <button type="button" class="profile-tab-button" data-tab="education" role="tab">
                        Образование
                    </button>
                </div>

                <div class="profile-main-grid">
                    <div class="profile-tab-content active" data-tab="personal">
                        @php
                            $personalValues = [
                                $user->name,
                                $user->surname,
                                $user->birthdate,
                                $user->citizenship,
                            ];
                            $personalFilled = collect($personalValues)->filter(function ($value) {
                                return !empty($value);
                            })->count();
                            $personalTotal = count($personalValues) ?: 1;
                            $personalProgress = (int) round($personalFilled / $personalTotal * 100);

                            $contactValues = [
                                $user->email,
                                $user->phone,
                            ];
                            $contactFilled = collect($contactValues)->filter(function ($value) {
                                return !empty($value);
                            })->count();
                            $contactTotal = count($contactValues) ?: 1;
                            $contactProgress = (int) round($contactFilled / $contactTotal * 100);

                            $addressValues = [
                                $user->city,
                                $user->street,
                                $user->house,
                            ];
                            $addressFilled = collect($addressValues)->filter(function ($value) {
                                return !empty($value);
                            })->count();
                            $addressTotal = count($addressValues) ?: 1;
                            $addressProgress = (int) round($addressFilled / $addressTotal * 100);
                        @endphp

                        <div class="profile-info-section profile-scroll-card">
                            <div class="scroll-card-header">
                                <div class="scroll-card-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <circle cx="12" cy="7" r="4"></circle>
                                        <path d="M4 20c0-4 3-7 8-7s8 3 8 7"></path>
                                    </svg>
                                </div>
                                <div class="scroll-card-header-text">
                                    <div class="section-title">Личные данные</div>
                                    <div class="scroll-card-subtitle">Имя, дата рождения и гражданство</div>
                                </div>
                                <div class="scroll-card-progress-value">{{ $personalProgress }}%</div>
                            </div>
                            <div class="scroll-card-progress">
                                <div class="scroll-card-progress-fill" style="width: {{ $personalProgress }}%;"></div>
                            </div>
                            <div class="scroll-card-body">
                                <div class="info-row">
                                    <span class="info-label">Имя</span>
                                    <span class="info-value">{{ $user->name ?: 'Не указано' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Фамилия</span>
                                    <span class="info-value">{{ $user->surname ?: 'Не указано' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Дата рождения</span>
                                    <span class="info-value">
                                        {{ $user->birthdate ? $user->birthdate->format('d.m.Y') : 'Не указано' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Гражданство</span>
                                    <span class="info-value">{{ $user->citizenship ?: 'Не указано' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="profile-info-section profile-scroll-card">
                            <div class="scroll-card-header">
                                <div class="scroll-card-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M4 4h16v16H4z"></path>
                                        <path d="M4 9h16"></path>
                                        <circle cx="8" cy="7" r="1"></circle>
                                        <circle cx="12" cy="7" r="1"></circle>
                                    </svg>
                                </div>
                                <div class="scroll-card-header-text">
                                    <div class="section-title">Контакты</div>
                                    <div class="scroll-card-subtitle">Почта и телефон для связи</div>
                                </div>
                                <div class="scroll-card-progress-value">{{ $contactProgress }}%</div>
                            </div>
                            <div class="scroll-card-progress">
                                <div class="scroll-card-progress-fill" style="width: {{ $contactProgress }}%;"></div>
                            </div>
                            <div class="scroll-card-body">
                                <div class="info-row">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ $user->email }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Телефон</span>
                                    <span class="info-value">{{ $user->phone ?: 'Не указан' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="profile-info-section profile-scroll-card">
                            <div class="scroll-card-header">
                                <div class="scroll-card-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M3 11l9-8 9 8"></path>
                                        <path d="M5 10v10h14V10"></path>
                                    </svg>
                                </div>
                                <div class="scroll-card-header-text">
                                    <div class="section-title">Адрес</div>
                                    <div class="scroll-card-subtitle">Город и место проживания</div>
                                </div>
                                <div class="scroll-card-progress-value">{{ $addressProgress }}%</div>
                            </div>
                            <div class="scroll-card-progress">
                                <div class="scroll-card-progress-fill" style="width: {{ $addressProgress }}%;"></div>
                            </div>
                            <div class="scroll-card-body">
                                <div class="info-row">
                                    <span class="info-label">Адрес проживания</span>
                                    <span class="info-value">
                                        @if($user->city || $user->street || $user->house)
                                            @if($user->city){{ $user->city }}@endif
                                            @if($user->street)
                                                @if($user->city), @endif{{ $user->street }}@if($user->house), д. {{ $user->house }}@endif
                                            @endif
                                        @else
                                            Не указан
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-tab-content" data-tab="education">
                        @php
                            $educationValues = [
                                $user->school,
                                $user->graduation_year,
                            ];
                            $educationFilled = collect($educationValues)->filter(function ($value) {
                                return !empty($value);
                            })->count();
                            $educationTotal = count($educationValues) ?: 1;
                            $educationProgress = (int) round($educationFilled / $educationTotal * 100);
                        @endphp
                        <div class="profile-education-timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-line"></div>
                                </div>
                                <div class="timeline-card">
                                    <div class="timeline-card-header">
                                        <div class="timeline-card-title-row">
                                            <div class="timeline-card-title">Основное образование</div>
                                            <span class="timeline-tag">Учёба</span>
                                        </div>
                                        <div class="timeline-date-badge">
                                            <span class="timeline-date-label">Дата</span>
                                            <span class="timeline-date-value">{{ $user->graduation_year ?: 'Не указан' }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-card-body">
                                        <div class="timeline-row">
                                            <div class="timeline-label">Место</div>
                                            <div class="timeline-value">
                                                {{ $user->school ?: 'Учебное заведение не указано' }}
                                            </div>
                                        </div>
                                        <div class="timeline-row">
                                            <div class="timeline-label">Результат</div>
                                            <div class="timeline-value">
                                                @if($user->school || $user->graduation_year)
                                                    Завершено среднее образование
                                                @else
                                                    Информация об образовании пока не заполнена
                                                @endif
                                            </div>
                                        </div>
                                        <div class="timeline-progress">
                                            <div class="timeline-progress-fill" style="width: {{ $educationProgress }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-edit-panel {{ $errors->any() ? 'active' : '' }}">
                <form class="profile-edit-form" method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    @if (session('success'))
                        <div class="success-message active">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="profile-form-section">
                        <div class="profile-form-title">Личные данные</div>
                        <div class="profile-form-grid">
                            <div class="profile-field">
                                <label for="name" class="profile-field-label">Имя</label>
                                <input id="name" type="text" name="name" class="text-input" required value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="profile-field">
                                <label for="surname" class="profile-field-label">Фамилия</label>
                                <input id="surname" type="text" name="surname" class="text-input" value="{{ old('surname', $user->surname) }}">
                                @error('surname')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="profile-field">
                                <label for="birthdate" class="profile-field-label">Дата рождения</label>
                                <input id="birthdate" type="date" name="birthdate" class="text-input" value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d')) }}">
                                @error('birthdate')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="profile-form-section">
                        <div class="profile-form-title">Контактная информация</div>
                        <div class="profile-form-grid">
                            <div class="profile-field">
                                <label for="phone" class="profile-field-label">Телефон</label>
                                <input id="phone" type="tel" name="phone" class="text-input" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="profile-field">
                                <label for="email" class="profile-field-label">Email</label>
                                <input id="email" type="email" name="email" class="text-input" required value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="profile-form-section">
                        <div class="profile-form-title">Адрес проживания</div>
                        <div class="profile-form-grid">
                            <div class="profile-field">
                                <label for="street" class="profile-field-label">Улица</label>
                                <input id="street" type="text" name="street" class="text-input" value="{{ old('street', $user->street) }}">
                                @error('street')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="profile-field">
                                <label for="house" class="profile-field-label">Дом</label>
                                <input id="house" type="text" name="house" class="text-input" value="{{ old('house', $user->house) }}">
                                @error('house')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="profile-field">
                                <label for="city" class="profile-field-label">Город</label>
                                <input id="city" type="text" name="city" class="text-input" list="city-list" autocomplete="address-level2" value="{{ old('city', $user->city) }}">
                                <datalist id="city-list">
                                    <option value="Москва"></option>
                                    <option value="Санкт-Петербург"></option>
                                    <option value="Новосибирск"></option>
                                    <option value="Екатеринбург"></option>
                                    <option value="Казань"></option>
                                </datalist>
                                @error('city')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="profile-form-section">
                        <div class="profile-form-title">Гражданство</div>
                        <div class="profile-form-grid">
                            <div class="profile-field">
                                <label for="citizenship" class="profile-field-label">Гражданство</label>
                                <select id="citizenship" name="citizenship" class="text-input" aria-label="Гражданство">
                                    @php
                                        $citizenship = old('citizenship', $user->citizenship);
                                    @endphp
                                    <option value="" {{ $citizenship ? '' : 'selected' }}>Не выбрано</option>
                                    <option value="Россия" {{ $citizenship === 'Россия' ? 'selected' : '' }}>Россия</option>
                                    <option value="Казахстан" {{ $citizenship === 'Казахстан' ? 'selected' : '' }}>Казахстан</option>
                                    <option value="Беларусь" {{ $citizenship === 'Беларусь' ? 'selected' : '' }}>Беларусь</option>
                                    <option value="Другое" {{ $citizenship === 'Другое' ? 'selected' : '' }}>Другое</option>
                                </select>
                                @error('citizenship')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="profile-form-section">
                        <div class="profile-form-title">Образование</div>
                        <div class="profile-form-grid">
                            <div class="profile-field">
                                <label for="school" class="profile-field-label">Учебное заведение</label>
                                <input id="school" type="text" name="school" class="text-input" value="{{ old('school', $user->school) }}">
                                @error('school')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="profile-field">
                                <label for="graduation_year" class="profile-field-label">Год окончания</label>
                                <input id="graduation_year" type="text" name="graduation_year" class="text-input" value="{{ old('graduation_year', $user->graduation_year) }}">
                                @error('graduation_year')
                                    <div class="profile-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="profile-form-actions">
                        <button type="button" class="secondary-button profile-edit-cancel">
                            Отмена
                        </button>
                        <button type="submit" class="profile-edit-toggle">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var panel = document.querySelector('.profile-edit-panel');
            var toggle = document.querySelector('.profile-edit-toggle');
            var cancel = document.querySelector('.profile-edit-cancel');
            var inputs = document.querySelectorAll('.profile-edit-form .text-input');
            var tabs = document.querySelectorAll('.profile-tab-button');
            var tabContents = document.querySelectorAll('.profile-tab-content');

            if (!panel) {
                return;
            }

            function openPanel() {
                panel.classList.add('active');
            }

            function closePanel() {
                panel.classList.remove('active');
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    if (panel.classList.contains('active')) {
                        closePanel();
                    } else {
                        openPanel();
                    }
                });
            }

            if (cancel) {
                cancel.addEventListener('click', function (event) {
                    event.preventDefault();
                    closePanel();
                });
            }

            inputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    var value = input.value.trim();
                    if (!value) {
                        input.removeAttribute('aria-invalid');
                        return;
                    }

                    var valid = true;

                    if (input.type === 'email') {
                        valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                    }

                    if (input.name === 'name' || input.name === 'surname') {
                        valid = value.length >= 2 && value.length <= 50;
                    }

                    if (input.name === 'city') {
                        valid = value.length >= 2;
                    }

                    if (input.name === 'phone') {
                        valid = true;
                    }

                    if (input.name === 'graduation_year') {
                        var year = parseInt(value, 10);
                        valid = !isNaN(year) && year >= 1900 && year <= new Date().getFullYear();
                    }

                    input.setAttribute('aria-invalid', valid ? 'false' : 'true');
                });
            });

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    var target = tab.getAttribute('data-tab');

                    tabs.forEach(function (t) {
                        t.classList.remove('active');
                    });

                    tab.classList.add('active');

                    tabContents.forEach(function (content) {
                        if (content.getAttribute('data-tab') === target) {
                            content.classList.add('active');
                        } else {
                            content.classList.remove('active');
                        }
                    });
                });
            });
        });
    </script>
@endsection
