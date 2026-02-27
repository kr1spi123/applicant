@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/lkform.css') . '?v=' . (file_exists(public_path('css/lkform.css')) ? filemtime(public_path('css/lkform.css')) : time()) }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') . '?v=' . (file_exists(public_path('css/auth.css')) ? filemtime(public_path('css/auth.css')) : time()) }}">

    <div class="nav-links">
        <a href="{{ route('applications.create') }}"
            class="{{ request()->routeIs('applications.create') ? 'active' : '' }}">Подать заявку на поступление</a>
        <a href="{{ route('applications.index') }}"
            class="{{ request()->routeIs('applications.index') ? 'active' : '' }}">Мои заявки</a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Мой
            профиль</a>
    </div>

    <div class="container">
        <div class="form-layout">
            <div class="form-main">
                <form class="application-form" method="POST" action="{{ route('applications.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger"
                            style="background-color: rgba(255, 90, 48, 0.1); color: #FF5A30; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-status-message"></div>
                    <div class="form-error-message alert alert-danger" style="display: none;">
                    </div>

                    <div class="form-wrapper">
                        <div class="form-progress">
                            <div class="form-steps">
                                <button type="button" class="form-step active" data-step-target="personal">1. Личные
                                    данные</button>
                                <button type="button" class="form-step" data-step-target="address">2. Адрес</button>
                                <button type="button" class="form-step" data-step-target="education">3. Образование</button>
                                <button type="button" class="form-step" data-step-target="benefits">4. Льготы</button>
                                <button type="button" class="form-step" data-step-target="documents">5. Документы</button>
                                <button type="button" class="form-step" data-step-target="specialties">6.
                                    Специальности</button>
                            </div>
                            <div class="form-progress-bar">
                                <div class="form-progress-fill"></div>
                            </div>
                        </div>

                        <!-- Первая строка -->
                        <div class="form-row">
                            <div class="form-block personal-data" data-step="personal">
                                <h3>Личные данные</h3>
                                <div class="input-grid personal-grid">
                                    <div class="input-wrapper">
                                        <input type="text" name="name" placeholder="Введите имя" class="text-input" required
                                            pattern="[А-Яа-яЁё\s-]{2,50}" value="{{ old('name', auth()->user()->name) }}">
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Введите корректное имя (только русские буквы)</div>
                                    </div>
                                    <div class="input-wrapper">
                                        <input type="text" name="surname" placeholder="Введите фамилию" class="text-input"
                                            required pattern="[А-Яа-яЁё\s-]{2,50}"
                                            value="{{ old('surname', auth()->user()->surname) }}">
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Введите корректную фамилию (только русские буквы)</div>
                                    </div>
                                    <div class="input-wrapper">
                                        <input type="date" name="birthdate" placeholder="Дата рождения" class="text-input"
                                            required min="1900-01-01"
                                            value="{{ old('birthdate', optional(auth()->user()->birthdate)->format('Y-m-d')) }}">
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Выберите корректную дату рождения</div>
                                    </div>
                                    <div class="input-wrapper">
                                        <input type="tel" name="phone" placeholder="Номер телефона" class="text-input"
                                            required pattern="\+7\s?\(?\d{3}\)?\s?\d{3}[-\s]?\d{2}[-\s]?\d{2}"
                                            value="{{ old('phone', auth()->user()->phone) }}">
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Введите телефон в формате +7(XXX)XXX-XX-XX</div>
                                    </div>
                                    <div class="input-wrapper">
                                        <input type="email" name="email" placeholder="Email" class="text-input" required
                                            value="{{ old('email', auth()->user()->email) }}">
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Введите корректный email адрес</div>
                                    </div>
                                    <div class="input-wrapper">
                                        <input type="text" name="citizenship" placeholder="Гражданство" class="text-input"
                                            required pattern="[A-Za-zА-Яа-яЁё\s-]{2,50}" list="citizenship-list-application"
                                            value="{{ old('citizenship', auth()->user()->citizenship) }}">
                                        <datalist id="citizenship-list-application">
                                            <option value="Россия"></option>
                                            <option value="Казахстан"></option>
                                            <option value="Беларусь"></option>
                                            <option value="Армения"></option>
                                            <option value="Киргизия"></option>
                                            <option value="Другое"></option>
                                        </datalist>
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Выберите или введите гражданство</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-block address" data-step="address">
                                <h3>Адрес проживания</h3>
                                <div class="input-grid">
                                    <div class="input-wrapper">
                                        <input type="text" name="city" placeholder="Город" class="text-input"
                                            list="city-list-application" value="{{ old('city', auth()->user()->city) }}">
                                        <datalist id="city-list-application">
                                            <option value="Москва"></option>
                                            <option value="Санкт-Петербург"></option>
                                            <option value="Новосибирск"></option>
                                            <option value="Екатеринбург"></option>
                                            <option value="Казань"></option>
                                        </datalist>
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Введите корректное название города</div>
                                    </div>
                                    <div class="input-wrapper">
                                        <input type="text" name="street" placeholder="Улица" class="text-input" required
                                            pattern="[А-Яа-яЁё\s-\.]{2,100}"
                                            value="{{ old('street', auth()->user()->street) }}">
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Введите корректное название улицы</div>
                                    </div>
                                    <div class="input-wrapper">
                                        <input type="text" name="house" placeholder="Дом" class="text-input" required
                                            pattern="[0-9А-Яа-яЁё\s-\./]{1,10}"
                                            value="{{ old('house', auth()->user()->house) }}">
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Введите корректный номер дома</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Вторая строка -->
                        <div class="form-row">
                            <div class="form-block education" data-step="education">
                                <h3>Образование</h3>
                                <input type="hidden" name="school" value="{{ old('school', auth()->user()->school) }}">
                                <input type="hidden" name="graduation_year"
                                    value="{{ old('graduation_year', auth()->user()->graduation_year) }}">
                                <div class="education-list">
                                    <div class="education-item" data-index="0">
                                        <div class="education-item-header">
                                            <div class="education-type">
                                                @php
                                                    $types = ['🏫 Школа', '🏛️ Колледж', '🎓 Университет'];
                                                    $selectedType = old('education.0.type', 'Школа');
                                                @endphp
                                                <select name="education[0][type]" class="text-input education-type-select">
                                                    @foreach($types as $type)
                                                        <option value="{{ $type }}" {{ $selectedType === $type ? 'selected' : '' }}>{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="education-title">
                                                <div class="education-name-dropdown">
                                                    <input type="text" name="education[0][name]"
                                                        placeholder="Учебное заведение"
                                                        class="text-input education-name-input" autocomplete="off"
                                                        pattern="[А-Яа-яЁё0-9\s\.-]{2,100}"
                                                        value="{{ old('education.0.name', old('school', auth()->user()->school)) }}">
                                                    <button type="button" class="education-name-toggle"
                                                        aria-label="Показать список"></button>
                                                    <div class="education-name-panel"></div>
                                                </div>
                                            </div>
                                            <div class="education-year">
                                                <input type="text" name="education[0][year]" placeholder="Год окончания"
                                                    class="text-input education-year-input" pattern="[0-9]{4}"
                                                    value="{{ old('education.0.year', old('graduation_year', auth()->user()->graduation_year)) }}">
                                            </div>
                                        </div>
                                        <div class="education-item-body">
                                            <div class="education-doc">
                                                <input type="text" name="education[0][doc_series]"
                                                    placeholder="Серия документа" class="text-input education-series-input"
                                                    pattern="[0-9А-ЯA-Z\-]{0,10}"
                                                    value="{{ old('education.0.doc_series') }}" style="margin-bottom:7px;">
                                            </div>
                                            <div class="education-doc">
                                                <input type="text" name="education[0][doc_number]"
                                                    placeholder="Номер документа" class="text-input education-number-input"
                                                    pattern="[0-9\-]{0,20}" value="{{ old('education.0.doc_number') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="add-education-button">Добавить учебное заведение</button>
                                <div class="input-grid" style="margin-top: 20px;">
                                    <div class="input-wrapper">
                                        <input type="number" name="ege_score" placeholder="Баллы ЕГЭ (0-300)"
                                            class="text-input" required min="0" max="300" value="{{ old('ege_score') }}">
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Введите баллы ЕГЭ (0-300)</div>
                                    </div>
                                    <div class="input-wrapper">
                                        <input type="number" step="0.1" name="certificate_score"
                                            placeholder="Средний балл (3.0-5.0)" class="text-input" required min="3.0"
                                            max="5.0" value="{{ old('certificate_score') }}">
                                        <svg class="validation-icon success" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg class="validation-icon error" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z"
                                                stroke="currentColor" stroke-width="2" />
                                            <path d="M10 6.66667V10" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="error-message">Введите средний балл (3.0-5.0)</div>
                                    </div>
                                </div>
                                <div class="ege-calculator">
                                    <div class="ege-calculator-title">Соответствие проходным баллам</div>
                                    <div class="ege-calculator-status" data-state="empty">
                                        Выберите специальность и введите баллы ЕГЭ, чтобы увидеть результат
                                    </div>
                                </div>
                                <div class="input-grid" style="margin-top: 10px;">
                                    <div class="input-wrapper checkbox-wrapper" style="margin-top: 10px;">
                                        <label class="benefit-item benefit-item-orphan">
                                            <input type="checkbox" name="has_achievements" value="1"
                                                class="benefit-checkbox" {{ old('has_achievements') ? 'checked' : '' }}>
                                            <span class="benefit-check"></span>
                                            <span class="benefit-text">
                                                <span class="benefit-title">Есть индивидуальные достижения (олимпиады, ГТО и
                                                    др.)</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-block benefits" data-step="benefits">
                                <h3>Льготные категории</h3>
                                <div class="benefits-grid">
                                    <label class="benefit-item benefit-item-orphan">
                                        <input type="checkbox" name="benefits[]" value="orphan" class="benefit-checkbox" {{ is_array(old('benefits')) && in_array('orphan', old('benefits')) ? 'checked' : '' }}>
                                        <span class="benefit-check"></span>
                                        <span class="benefit-text">
                                            <span class="benefit-title">Дети-сироты и дети, оставшиеся без попечения
                                                родителей</span>
                                            <span class="benefit-caption">Прикрепите решение органа опеки или иной
                                                подтверждающий документ.</span>
                                        </span>
                                    </label>
                                    <label class="benefit-item benefit-item-disabled">
                                        <input type="checkbox" name="benefits[]" value="disabled" class="benefit-checkbox"
                                            {{ is_array(old('benefits')) && in_array('disabled', old('benefits')) ? 'checked' : '' }}>
                                        <span class="benefit-check"></span>
                                        <span class="benefit-text">
                                            <span class="benefit-title">Лица с инвалидностью</span>
                                            <span class="benefit-caption">Необходимо приложить справку об установлении
                                                инвалидности.</span>
                                        </span>
                                    </label>
                                    <label class="benefit-item benefit-item-veteran">
                                        <input type="checkbox" name="benefits[]" value="veteran" class="benefit-checkbox" {{ is_array(old('benefits')) && in_array('veteran', old('benefits')) ? 'checked' : '' }}>
                                        <span class="benefit-check"></span>
                                        <span class="benefit-text">
                                            <span class="benefit-title">Ветераны боевых действий и их дети</span>
                                            <span class="benefit-caption">Приложите удостоверение ветерана или иные
                                                подтверждающие документы.</span>
                                        </span>
                                    </label>
                                    <label class="benefit-item benefit-item-low_income">
                                        <input type="checkbox" name="benefits[]" value="low_income" class="benefit-checkbox"
                                            {{ is_array(old('benefits')) && in_array('low_income', old('benefits')) ? 'checked' : '' }}>
                                        <span class="benefit-check"></span>
                                        <span class="benefit-text">
                                            <span class="benefit-title">Лица из малоимущих семей</span>
                                            <span class="benefit-caption">Добавьте подтверждение статуса малоимущей
                                                семьи.</span>
                                        </span>
                                    </label>
                                    <label class="benefit-item benefit-item-other">
                                        <input type="checkbox" name="benefits[]" value="other" class="benefit-checkbox" {{ is_array(old('benefits')) && in_array('other', old('benefits')) ? 'checked' : '' }}>
                                        <span class="benefit-check"></span>
                                        <span class="benefit-text">
                                            <span class="benefit-title">Иные льготные категории</span>
                                            <span class="benefit-caption">Укажите тип льготы и прикрепите подтверждение при
                                                наличии.</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-block documents" data-step="documents">
                            <h3>Документы</h3>
                            <div class="file-upload">
                                <input type="file" name="certificate_file" id="certificate" class="file-input"
                                    accept=".pdf,.jpg,.jpeg,.png" required>
                                <label for="certificate" class="file-label">
                                    <span class="upload-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 16L12 8" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M9 11L12 8L15 11" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M8 16H16" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                    </span>
                                    <span class="upload-text">ЗАГРУЗИТЬ АТТЕСТАТ</span>
                                </label>
                                <div class="file-info">
                                    <div class="file-preview">
                                        <svg class="file-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z"
                                                stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M14 2V8H20" stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M16 13H8" stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M16 17H8" stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M10 9H8" stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span class="file-name"></span>
                                    </div>
                                    <button type="button" class="remove-file" style="display: none;">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15 5L5 15" stroke="#9A9CA5" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M5 5L15 15" stroke="#9A9CA5" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="file-hint">Допустимые форматы: PDF, JPG, PNG. Максимальный размер: 5MB</p>
                            <div class="benefit-proof" style="display: none;">
                                <div class="benefit-proof-title">Документ, подтверждающий льготу</div>
                                <div class="file-upload">
                                    <input type="file" name="benefit_proof" id="benefit_proof" class="file-input"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <label for="benefit_proof" class="file-label secondary">
                                        <span class="upload-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 16L12 8" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" />
                                                <path d="M9 11L12 8L15 11" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8 16H16" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </span>
                                        <span class="upload-text">ЗАГРУЗИТЬ ДОКУМЕНТ ЛЬГОТЫ</span>
                                    </label>
                                    <div class="file-info benefit-file-info">
                                        <div class="file-preview benefit-file-preview">
                                            <svg class="file-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z"
                                                    stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M14 2V8H20" stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M16 13H8" stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M16 17H8" stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M10 9H8" stroke="#FF5A30" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span class="file-name benefit-file-name"></span>
                                        </div>
                                        <button type="button" class="remove-file remove-benefit-file" style="display: none;">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15 5L5 15" stroke="#9A9CA5" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M5 5L15 15" stroke="#9A9CA5" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="file-hint">Загрузите справку или скан документа, подтверждающего льготу.
                                    Допустимые форматы: PDF, JPG, PNG. Максимальный размер: 5MB</p>
                            </div>
                        </div>

                        <!-- Третья строка -->
                        <div class="form-block specialties" data-step="specialties">
                            <div class="specialties-header">
                                <h3>Специальности</h3>
                                @if(isset($existingCount) && $existingCount > 0)
                                    <div class="limit-info {{ $existingCount >= 3 ? 'limit-reached' : '' }}">
                                        <i class="fas fa-info-circle"></i>
                                        @if($existingCount >= 3)
                                            Вы уже подали максимально допустимое количество заявок (3). Подача новых заявок временно недоступна.
                                        @else
                                            У вас уже подано <strong>{{ $existingCount }}</strong> заявки(ок). 
                                            Вы можете выбрать еще не более <strong>{{ 3 - $existingCount }}</strong>.
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="specialties-filters">
                                <div class="specialties-filter">
                                    <span>Уровень образования:</span>
                                    <select id="level-filter" class="text-input valid">
                                        <option value="all">🌟 Все уровни</option>
                                        <option value="бакалавриат">📘 Бакалавриат</option>
                                        <option value="специалитет">📚 Специалитет</option>
                                        <option value="магистратура">🎯 Магистратура</option>
                                    </select>
                                </div>
                            </div>
                            <div class="specialties-content">
                                <div class="specialties-list">
                                    @foreach($specialties as $specialty)
                                        @php
                                            $levelMap = [
                                                '09.02.07' => 'бакалавриат',
                                                '09.02.06' => 'бакалавриат',
                                                '54.02.01' => 'специалитет',
                                                '38.02.01' => 'бакалавриат',
                                            ];
                                            $level = $levelMap[$specialty->code] ?? 'бакалавриат';
                                            $totalPlaces = $specialty->total_places ?? $specialty->budget_places;
                                            $paidPlaces = max(($totalPlaces ?? 0) - $specialty->budget_places, 0);
                                            $passingScores = [
                                                '09.02.07' => 230,
                                                '09.02.06' => 220,
                                                '54.02.01' => 210,
                                                '38.02.01' => 215,
                                            ];
                                            $passingScore = $passingScores[$specialty->code] ?? null;
                                        @endphp
                                        <label class="specialty-item" data-description="{{ $specialty->description }}"
                                            data-level="{{ $level }}" @if($passingScore)
                                            data-passing-score="{{ $passingScore }}" @endif>
                                            <div class="specialty-text">
                                                <div class="specialty-header-line">
                                                    <span class="specialty-name">{{ $specialty->name }}</span>
                                                    @if($specialty->code)
                                                        <span class="specialty-code-pill">{{ $specialty->code }}</span>
                                                    @endif
                                                </div>
                                                <div class="specialty-study-row">
                                                    @php
                                                        $availableForms = array_keys($specialty->available_study_forms);
                                                        $allForms = ['очная', 'заочная', 'очно-заочная'];
                                                        $firstAvailable = $availableForms[0] ?? 'очная';
                                                    @endphp
                                                    <div class="study-form-toggle" data-specialty="{{ $specialty->id }}">
                                                        @foreach($allForms as $form)
                                                            @php
                                                                $isAvailable = in_array($form, $availableForms);
                                                            @endphp
                                                            <button type="button" 
                                                                class="study-form-option {{ $form === $firstAvailable ? 'active' : '' }} {{ !$isAvailable ? 'disabled' : '' }}"
                                                                data-value="{{ $form }}"
                                                                {{ !$isAvailable ? 'disabled' : '' }}>
                                                                {{ mb_convert_case($form, MB_CASE_TITLE, "UTF-8") }}
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                    <div class="specialty-places">
                                                        <span class="places-badge">Бюджет:
                                                            {{ $specialty->budget_places }}</span>
                                                        <span class="places-badge">Платные: {{ $paidPlaces }}</span>
                                                    </div>
                                                </div>
                                                @if($passingScore)
                                                    <div class="specialty-passing-score">
                                                        Проходной балл прошлых лет: <span>{{ $passingScore }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="hidden" name="study_form[{{ $specialty->id }}]" value="{{ $firstAvailable }}">
                                            <input type="checkbox" name="specialty[]" value="{{ $specialty->id }}"
                                                class="specialty-checkbox" {{ is_array(old('specialty')) && in_array($specialty->id, old('specialty')) ? 'checked' : '' }}>
                                            <span class="custom-checkbox"></span>
                                        </label>
                                    @endforeach
                                    @if($specialties->isEmpty())
                                        <p class="no-specialties">Нет доступных специальностей</p>
                                    @endif
                                </div>
                                <div class="specialty-description">
                                    <p>Выберите специальность, чтобы увидеть её описание. <br> Можно выбрать до 3-х
                                        специальностей.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions-row">
                        @if(isset($existingCount) && $existingCount < 3)
                            <button type="submit" class="submit-button">ПОДАТЬ ЗАЯВКУ</button>
                        @else
                            <div class="limit-reached-badge">
                                <i class="fas fa-lock"></i> Лимит заявок исчерпан
                            </div>
                        @endif
                    </div>
                </form>
            </div>

        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const EXISTING_COUNT = {{ $existingCount ?? 0 }};
            const MAX_TOTAL = 3;
            const MAX_SELECTIONS = MAX_TOTAL - EXISTING_COUNT;
            
            const specialtiesContainer = document.querySelector('.specialties-list');
            const specialtyItems = document.querySelectorAll('.specialty-item');
            const descriptionBox = document.querySelector('.specialty-description p');
            let selectedCount = 0;
            let draggedItem = null;

            // Initialize limit state
            if (MAX_SELECTIONS <= 0) {
                specialtyItems.forEach(item => {
                    item.classList.add('disabled');
                    const cb = item.querySelector('.specialty-checkbox');
                    if (cb) cb.disabled = true;
                });
            }

            document.querySelectorAll('.specialty-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function (e) {
                    const currentSelected = document.querySelectorAll('.specialty-checkbox:checked').length;
                    const specialtyItem = this.closest('.specialty-item');

                    if (currentSelected > MAX_SELECTIONS) {
                        e.preventDefault();
                        this.checked = false;
                        alert('Вы не можете выбрать более ' + MAX_SELECTIONS + ' специальности(ей), так как у вас уже есть поданные заявки.');
                        return;
                    }

                    // Добавляем/удаляем класс selected при изменении состояния чекбокса
                    if (this.checked) {
                        specialtyItem.classList.add('selected');
                    } else {
                        specialtyItem.classList.remove('selected');
                    }

                    selectedCount = currentSelected;

                    // Если достигнут максимум выборов, отключаем остальные чекбоксы
                    if (selectedCount >= MAX_SELECTIONS) {
                        specialtyItems.forEach(item => {
                            const cb = item.querySelector('.specialty-checkbox');
                            if (!cb.checked && !item.classList.contains('already-applied')) {
                                item.classList.add('disabled');
                                cb.disabled = true;
                            }
                        });
                    } else {
                        // Если меньше максимума, включаем все чекбоксы кроме тех, на которые уже подана заявка
                        specialtyItems.forEach(item => {
                            const cb = item.querySelector('.specialty-checkbox');
                            if (!item.classList.contains('already-applied')) {
                                item.classList.remove('disabled');
                                cb.disabled = false;
                            }
                        });
                    }

                    updateEgeCalculator();
                });
            });

            document.querySelectorAll('.custom-checkbox').forEach(box => {
                box.addEventListener('click', function (e) {
                    e.preventDefault();
                    const item = this.closest('.specialty-item');
                    if (!item || item.classList.contains('disabled') || item.classList.contains('already-applied')) {
                        return;
                    }
                    const cb = item.querySelector('.specialty-checkbox');
                    if (!cb) return;
                    cb.checked = !cb.checked;
                    cb.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            if (specialtiesContainer) {
                specialtyItems.forEach(item => {
                    item.setAttribute('draggable', 'true');

                    item.addEventListener('dragstart', function (e) {
                        draggedItem = this;
                        this.classList.add('dragging');
                        e.dataTransfer.effectAllowed = 'move';
                        e.dataTransfer.setData('text/plain', '');
                    });

                    item.addEventListener('dragover', function (e) {
                        e.preventDefault();
                        if (!draggedItem || this === draggedItem) return;
                        const rect = this.getBoundingClientRect();
                        const offset = e.clientY - rect.top;
                        const shouldPlaceBefore = offset < rect.height / 2;

                        this.classList.add('drop-target');

                        if (shouldPlaceBefore) {
                            specialtiesContainer.insertBefore(draggedItem, this);
                        } else {
                            specialtiesContainer.insertBefore(draggedItem, this.nextSibling);
                        }
                    });

                    item.addEventListener('dragleave', function () {
                        this.classList.remove('drop-target');
                    });

                    item.addEventListener('drop', function (e) {
                        e.preventDefault();
                        this.classList.remove('drop-target');
                    });

                    item.addEventListener('dragend', function () {
                        this.classList.remove('dragging');
                        specialtiesContainer.querySelectorAll('.drop-target').forEach(target => {
                            target.classList.remove('drop-target');
                        });
                        draggedItem = null;
                    });
                });
            }

            document.querySelectorAll('.specialty-name').forEach(name => {
                name.addEventListener('click', function (e) {
                    e.preventDefault();
                    const description = this.closest('.specialty-item').getAttribute('data-description');
                    if (descriptionBox) {
                        descriptionBox.textContent = description;

                        // Добавляем анимацию для привлечения внимания
                        descriptionBox.style.opacity = '0';
                        setTimeout(() => {
                            descriptionBox.style.opacity = '1';
                        }, 100);
                    }
                });
            });

            const fileInput = document.getElementById('certificate');
            const fileInfo = document.querySelector('.file-info');
            const fileName = document.querySelector('.file-name');
            const removeButton = document.querySelector('.remove-file');
            const maxSize = 5 * 1024 * 1024;

            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    const file = this.files[0];
                    if (file) {
                        if (file.size > maxSize) {
                            alert('Файл слишком большой. Максимальный размер: 5MB');
                            this.value = '';
                            fileInfo.classList.remove('active');
                            return;
                        }
                        fileName.textContent = file.name;
                        fileInfo.classList.add('active');
                        removeButton.style.display = 'flex';
                    } else {
                        fileInfo.classList.remove('active');
                        removeButton.style.display = 'none';
                    }
                });
            }

            if (removeButton) {
                removeButton.addEventListener('click', function () {
                    fileInput.value = '';
                    fileInfo.classList.remove('active');
                    this.style.display = 'none';
                });
            }

            const inputs = document.querySelectorAll('.text-input');

            inputs.forEach(input => {
                // Валидация при вводе
                input.addEventListener('input', function () {
                    validateInput(this);
                });

                // Валидация при потере фокуса
                input.addEventListener('blur', function () {
                    validateInput(this);
                });

                // Валидация при получении фокуса
                input.addEventListener('focus', function () {
                    // Убираем классы valid/invalid при фокусе
                    this.classList.remove('valid', 'invalid');
                    // Скрываем сообщение об ошибке
                    const errorMessage = this.parentElement.querySelector('.error-message');
                    if (errorMessage) errorMessage.classList.remove('visible');
                });
            });

            function validateInput(input) {
                const wrapper = input.parentElement;
                const errorMessage = wrapper.querySelector('.error-message');
                let isValid = true;

                // Очищаем предыдущие состояния
                input.classList.remove('valid', 'invalid');
                if (errorMessage) errorMessage.classList.remove('visible');

                // Проверяем заполненность
                if (input.required && !input.value) {
                    isValid = false;
                }

                // Проверяем паттерны для разных типов полей
                if (input.value) {
                    switch (input.type) {
                        case 'text':
                            if (input.pattern && !new RegExp(input.pattern).test(input.value)) {
                                isValid = false;
                            }
                            break;
                        case 'email':
                            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                            if (!emailPattern.test(input.value)) {
                                isValid = false;
                            }
                            break;
                        case 'tel':
                            if (input.pattern && !new RegExp(input.pattern).test(input.value)) {
                                isValid = false;
                            }
                            break;
                        case 'date':
                            const date = new Date(input.value);
                            const minDate = new Date(input.min || '1900-01-01');
                            const today = new Date();
                            if (isNaN(date.getTime()) || date < minDate || date > today) {
                                isValid = false;
                            }
                            break;
                        case 'number':
                            if (input.min && parseFloat(input.value) < parseFloat(input.min)) isValid = false;
                            if (input.max && parseFloat(input.value) > parseFloat(input.max)) isValid = false;
                            break;
                    }
                }

                // Применяем соответствующие классы и показываем/скрываем сообщение об ошибке
                if (input.value) {
                    input.classList.add(isValid ? 'valid' : 'invalid');
                    if (!isValid && errorMessage) {
                        errorMessage.classList.add('visible');
                    }
                }
            }

            const phoneInput = document.querySelector('input[name="phone"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0 && value[0] !== '7') {
                        value = '7' + value;
                    }
                    let formattedValue = '';
                    if (value.length > 0) {
                        formattedValue = '+' + value[0];
                        if (value.length > 1) {
                            formattedValue += '(' + value.substring(1, 4);
                        }
                        if (value.length > 4) {
                            formattedValue += ')' + value.substring(4, 7);
                        }
                        if (value.length > 7) {
                            formattedValue += '-' + value.substring(7, 9);
                        }
                        if (value.length > 9) {
                            formattedValue += '-' + value.substring(9, 11);
                        }
                    }
                    e.target.value = formattedValue;
                });
            }

            const educationNameMain = document.querySelector('.education-item[data-index="0"] .education-name-input');
            const educationYearMain = document.querySelector('.education-item[data-index="0"] .education-year-input');
            const hiddenSchool = document.querySelector('input[name="school"]');
            const hiddenGraduationYear = document.querySelector('input[name="graduation_year"]');

            function syncMainEducationFields() {
                if (educationNameMain && hiddenSchool) {
                    hiddenSchool.value = educationNameMain.value;
                }
                if (educationYearMain && hiddenGraduationYear) {
                    hiddenGraduationYear.value = educationYearMain.value;
                }
            }

            if (educationNameMain) {
                educationNameMain.addEventListener('input', syncMainEducationFields);
            }
            if (educationYearMain) {
                educationYearMain.addEventListener('input', syncMainEducationFields);
            }
            syncMainEducationFields();

            const educationSuggestions = [
                'Средняя школа №1',
                'Средняя школа №2',
                'Средняя школа №3',
                'Гимназия №1',
                'Лицей №1',
                'Лицей информационных технологий',
                'Колледж информационных технологий',
                'Педагогический колледж',
                'Экономический университет',
                'Государственный университет'
            ];

            function initEducationDropdown(container) {
                const input = container.querySelector('.education-name-input');
                const panel = container.querySelector('.education-name-panel');
                const toggle = container.querySelector('.education-name-toggle');
                if (!input || !panel || !toggle) return;
                let filtered = [];
                let activeIndex = -1;

                function renderOptions() {
                    const term = input.value.trim().toLowerCase();
                    filtered = educationSuggestions.filter(function (option) {
                        return option.toLowerCase().indexOf(term) !== -1;
                    });
                    panel.innerHTML = '';
                    if (!filtered.length) {
                        const empty = document.createElement('div');
                        empty.className = 'education-name-empty';
                        empty.textContent = 'Ничего не найдено';
                        panel.appendChild(empty);
                        return;
                    }
                    filtered.forEach(function (value, index) {
                        const option = document.createElement('button');
                        option.type = 'button';
                        option.className = 'education-name-option';
                        if (index === activeIndex) {
                            option.classList.add('active');
                        }
                        option.textContent = value;
                        option.addEventListener('mousedown', function (e) {
                            e.preventDefault();
                            input.value = value;
                            syncMainEducationFields();
                            closePanel();
                            input.focus();
                        });
                        panel.appendChild(option);
                    });
                }

                function openPanel() {
                    renderOptions();
                    container.classList.add('open');
                }

                function closePanel() {
                    container.classList.remove('open');
                    activeIndex = -1;
                }

                input.addEventListener('focus', function () {
                    openPanel();
                });

                input.addEventListener('input', function () {
                    openPanel();
                });

                toggle.addEventListener('click', function () {
                    if (container.classList.contains('open')) {
                        closePanel();
                    } else {
                        openPanel();
                        input.focus();
                    }
                });

                input.addEventListener('keydown', function (e) {
                    if (!container.classList.contains('open')) return;
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (filtered.length === 0) return;
                        activeIndex = activeIndex + 1 >= filtered.length ? 0 : activeIndex + 1;
                        renderOptions();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (filtered.length === 0) return;
                        activeIndex = activeIndex - 1 < 0 ? filtered.length - 1 : activeIndex - 1;
                        renderOptions();
                    } else if (e.key === 'Enter') {
                        if (activeIndex >= 0 && activeIndex < filtered.length) {
                            e.preventDefault();
                            input.value = filtered[activeIndex];
                            syncMainEducationFields();
                            closePanel();
                        }
                    } else if (e.key === 'Escape') {
                        if (container.classList.contains('open')) {
                            e.preventDefault();
                            closePanel();
                        }
                    }
                });
            }

            function initAllEducationDropdowns() {
                document.querySelectorAll('.education-name-dropdown').forEach(function (dropdown) {
                    if (dropdown.dataset.dropdownReady === '1') return;
                    initEducationDropdown(dropdown);
                    dropdown.dataset.dropdownReady = '1';
                });
            }

            function showEducationHint(item) {
                const existing = item.querySelector('.education-hint');
                if (existing) {
                    existing.remove();
                }
                const hint = document.createElement('div');
                hint.className = 'education-hint';
                hint.textContent = 'Новое учебное заведение добавлено ниже. Заполните данные.';
                item.appendChild(hint);
                requestAnimationFrame(function () {
                    hint.classList.add('visible');
                });
                setTimeout(function () {
                    hint.classList.remove('visible');
                    setTimeout(function () {
                        if (hint.parentNode) {
                            hint.parentNode.removeChild(hint);
                        }
                    }, 200);
                }, 2600);
            }

            document.addEventListener('click', function (e) {
                document.querySelectorAll('.education-name-dropdown.open').forEach(function (dropdown) {
                    if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('open');
                    }
                });
            });

            initAllEducationDropdowns();

            const addEducationButton = document.querySelector('.add-education-button');
            const educationList = document.querySelector('.education-list');
            let educationIndex = 1;

            if (addEducationButton && educationList) {
                addEducationButton.addEventListener('click', function () {
                    if (addEducationButton.classList.contains('loading')) return;
                    addEducationButton.classList.add('loading');
                    const firstItem = educationList.querySelector('.education-item');
                    if (!firstItem) {
                        addEducationButton.classList.remove('loading');
                        return;
                    }
                    const newItem = firstItem.cloneNode(true);
                    newItem.setAttribute('data-index', educationIndex.toString());
                    newItem.querySelectorAll('input, select').forEach(function (el) {
                        if (el.name.indexOf('education[0]') !== -1) {
                            el.name = el.name.replace('education[0]', 'education[' + educationIndex + ']');
                        } else {
                            el.name = el.name.replace(/education\[\d+\]/, 'education[' + educationIndex + ']');
                        }
                        if (el.tagName.toLowerCase() === 'select') {
                            el.selectedIndex = 0;
                        } else {
                            el.value = '';
                        }
                        el.classList.remove('valid', 'invalid');
                        const wrapper = el.parentElement;
                        if (wrapper) {
                            const errorMessage = wrapper.querySelector('.error-message');
                            if (errorMessage) errorMessage.classList.remove('visible');
                        }
                    });
                    educationList.appendChild(newItem);
                    educationIndex += 1;
                    initAllEducationDropdowns();
                    setTimeout(function () {
                        addEducationButton.classList.remove('loading');
                        newItem.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        showEducationHint(newItem);
                    }, 250);
                });
            }

            if (educationList) {
                educationList.addEventListener('input', function (e) {
                    const target = e.target;
                    if (target.classList.contains('education-series-input')) {
                        let value = target.value.toUpperCase().replace(/[^0-9А-ЯA-Z\-]/g, '');
                        if (value.length > 10) value = value.slice(0, 10);
                        target.value = value;
                    }
                    if (target.classList.contains('education-number-input')) {
                        let value = target.value.replace(/\D/g, '');
                        if (value.length > 4) {
                            value = value.slice(0, 4) + '-' + value.slice(4, 10);
                        }
                        target.value = value;
                    }
                });
            }

            const benefitCheckboxes = document.querySelectorAll('.benefits .benefit-checkbox');
            const benefitProofBlock = document.querySelector('.benefit-proof');
            const benefitFileInput = document.getElementById('benefit_proof');
            const benefitFileInfo = document.querySelector('.benefit-file-info');
            const benefitFileName = document.querySelector('.benefit-file-name');
            const benefitRemoveButton = document.querySelector('.remove-benefit-file');

            function updateBenefitVisibility() {
                if (!benefitProofBlock) return;
                let anyChecked = false;
                benefitCheckboxes.forEach(function (cb) {
                    if (cb.checked) anyChecked = true;
                });
                benefitProofBlock.style.display = anyChecked ? 'block' : 'none';
                if (!anyChecked && benefitFileInput) {
                    benefitFileInput.value = '';
                    if (benefitFileName) benefitFileName.textContent = '';
                    if (benefitRemoveButton) benefitRemoveButton.style.display = 'none';
                    if (benefitFileInfo) {
                        benefitFileInfo.classList.remove('active');
                    }
                }
            }

            benefitCheckboxes.forEach(function (cb) {
                cb.addEventListener('change', updateBenefitVisibility);
            });
            updateBenefitVisibility();

            if (benefitFileInput && benefitFileInfo && benefitFileName && benefitRemoveButton) {
                benefitFileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) {
                        benefitFileName.textContent = '';
                        benefitRemoveButton.style.display = 'none';
                        benefitFileInfo.classList.remove('active');
                        return;
                    }
                    if (file.size > maxSize) {
                        alert('Файл слишком большой. Максимальный размер: 5MB');
                        benefitFileInput.value = '';
                        benefitFileName.textContent = '';
                        benefitRemoveButton.style.display = 'none';
                        benefitFileInfo.classList.remove('active');
                        return;
                    }
                    benefitFileName.textContent = file.name;
                    benefitFileInfo.classList.add('active');
                    benefitRemoveButton.style.display = 'inline-flex';
                });

                benefitRemoveButton.addEventListener('click', function () {
                    benefitFileInput.value = '';
                    benefitFileName.textContent = '';
                    benefitRemoveButton.style.display = 'none';
                    benefitFileInfo.classList.remove('active');
                });
            }

            const egeInput = document.querySelector('input[name="ege_score"]');
            const egeStatus = document.querySelector('.ege-calculator-status');

            function updateEgeCalculator() {
                if (!egeInput || !egeStatus) return;
                const rawValue = egeInput.value;
                const score = parseInt(rawValue, 10);
                const checked = document.querySelector('.specialty-checkbox:checked');
                egeStatus.classList.remove('match', 'mismatch');
                if (!checked || !rawValue) {
                    egeStatus.textContent = 'Выберите специальность и введите баллы ЕГЭ, чтобы увидеть результат';
                    egeStatus.setAttribute('data-state', 'empty');
                    return;
                }
                const item = checked.closest('.specialty-item');
                if (!item) {
                    egeStatus.textContent = 'Выберите специальность и введите баллы ЕГЭ, чтобы увидеть результат';
                    egeStatus.setAttribute('data-state', 'empty');
                    return;
                }
                const passingAttr = item.getAttribute('data-passing-score');
                const name = item.querySelector('.specialty-name') ? item.querySelector('.specialty-name').textContent.trim() : '';
                if (!passingAttr) {
                    egeStatus.textContent = 'Для выбранной специальности нет данных о проходном балле.';
                    egeStatus.setAttribute('data-state', 'no-data');
                    return;
                }
                const passing = parseInt(passingAttr, 10);
                if (!score || isNaN(score)) {
                    egeStatus.textContent = 'Введите корректные баллы ЕГЭ.';
                    egeStatus.setAttribute('data-state', 'invalid');
                    return;
                }
                if (score >= passing) {
                    egeStatus.textContent = 'Ваш результат ' + score + ' баллов подходит для специальности «' + name + '» (проходной балл ' + passing + ').';
                    egeStatus.classList.add('match');
                    egeStatus.setAttribute('data-state', 'match');
                } else {
                    egeStatus.textContent = 'Ваш результат ' + score + ' баллов ниже проходного ' + passing + ' для специальности «' + name + '».';
                    egeStatus.classList.add('mismatch');
                    egeStatus.setAttribute('data-state', 'mismatch');
                }
            }

            if (egeInput) {
                egeInput.addEventListener('input', updateEgeCalculator);
            }

            const studyFormToggles = document.querySelectorAll('.study-form-toggle');
            studyFormToggles.forEach(function (toggle) {
                const specialtyId = toggle.getAttribute('data-specialty');
                const hiddenInput = document.querySelector('input[name="study_form[' + specialtyId + ']"]');
                const buttons = toggle.querySelectorAll('.study-form-option');
                buttons.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        if (btn.classList.contains('disabled')) return;
                        
                        buttons.forEach(function (b) {
                            b.classList.remove('active');
                        });
                        btn.classList.add('active');
                        if (hiddenInput) {
                            hiddenInput.value = btn.getAttribute('data-value');
                        }
                    });
                });
            });

            const levelFilter = document.getElementById('level-filter');
            if (levelFilter) {
                levelFilter.addEventListener('change', function () {
                    const value = this.value;
                    specialtyItems.forEach(function (item) {
                        const level = item.getAttribute('data-level');
                        if (value === 'all' || level === value) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            const form = document.querySelector('.application-form');

            if (form) {
                const statusBox = document.querySelector('.form-status-message');
                const errorBox = document.querySelector('.form-error-message');
                const submitButton = form.querySelector('.submit-button');
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (!submitButton || submitButton.disabled) {
                        return;
                    }
                    
                    let allValid = true;
                    
                    // Валидация текстовых полей
                    const textInputs = form.querySelectorAll('.text-input');
                    textInputs.forEach(function (input) {
                        validateInput(input);
                        if (input.required && (!input.value || input.classList.contains('invalid'))) {
                            allValid = false;
                        }
                    });

                    // Проверка выбора хотя бы одной специальности
                    const selectedSpecialties = form.querySelectorAll('.specialty-checkbox:checked');
                    if (selectedSpecialties.length === 0) {
                        allValid = false;
                        if (errorBox) {
                            errorBox.textContent = 'Пожалуйста, выберите хотя бы одну специальность.';
                            errorBox.style.display = 'block';
                        }
                        setActiveStep('specialties', { scroll: true });
                        return;
                    }

                    // Проверка загрузки аттестата
                    const certificateFile = document.getElementById('certificate');
                    if (certificateFile && certificateFile.required && !certificateFile.files.length) {
                        allValid = false;
                        if (errorBox) {
                            errorBox.textContent = 'Пожалуйста, загрузите скан аттестата.';
                            errorBox.style.display = 'block';
                        }
                        setActiveStep('documents', { scroll: true });
                        return;
                    }

                    if (!allValid) {
                        const firstInvalid = form.querySelector('.text-input.invalid');
                        if (firstInvalid) {
                            const block = firstInvalid.closest('.form-block[data-step]');
                            if (block) {
                                const key = block.getAttribute('data-step');
                                if (key) {
                                    setActiveStep(key, { scroll: true });
                                }
                            }
                            firstInvalid.focus();
                        }
                        return;
                    }

                    if (statusBox) {
                        statusBox.textContent = '';
                        statusBox.classList.remove('visible');
                    }
                    if (errorBox) {
                        errorBox.innerHTML = '';
                        errorBox.style.display = 'none';
                    }
                    
                    submitButton.disabled = true;
                    submitButton.classList.add('loading');
                    
                    const formData = new FormData(form);
                    const csrfInput = form.querySelector('input[name="_token"]');
                    let csrfToken = '';
                    if (csrfInput && csrfInput.value) {
                        csrfToken = csrfInput.value;
                    } else {
                        const meta = document.querySelector('meta[name="csrf-token"]');
                        if (meta && meta.getAttribute('content')) {
                            csrfToken = meta.getAttribute('content');
                        }
                    }
                    const headers = {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    };
                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken;
                    }
                    fetch(form.action, {
                        method: 'POST',
                        headers: headers,
                        credentials: 'same-origin',
                        body: formData
                    }).then(function (response) {
                        const contentType = response.headers.get('Content-Type') || '';
                        if (!response.ok) {
                            return response.json().then(function (data) {
                                throw { status: response.status, data: data, nonJson: false };
                            }).catch(function () {
                                throw { status: response.status, data: null, nonJson: contentType.indexOf('application/json') === -1 };
                            });
                        }
                        if (contentType.indexOf('application/json') === -1) {
                            throw { status: response.status, data: null, nonJson: true };
                        }
                        return response.json();
                    }).then(function (data) {
                        form.reset();
                        const allInputs = form.querySelectorAll('.text-input');
                        allInputs.forEach(function (input) {
                            input.classList.remove('valid', 'invalid');
                        });
                        if (benefitProofBlock) {
                            benefitProofBlock.style.display = 'none';
                        }
                        const selectedItems = document.querySelectorAll('.specialty-item.selected');
                        selectedItems.forEach(function (item) {
                            item.classList.remove('selected');
                        });
                        const specialtyCheckboxes = document.querySelectorAll('.specialty-checkbox');
                        specialtyCheckboxes.forEach(function (cb) {
                            cb.checked = false;
                        });
                        updateEgeCalculator();
                        if (statusBox) {
                            let text = 'Заявка успешно отправлена.';
                            if (data && data.application_ids && data.application_ids.length) {
                                text += ' Номер(а) заявки: ' + data.application_ids.join(', ');
                            }
                            statusBox.textContent = text;
                            statusBox.classList.add('visible');
                        } else {
                            alert('Заявка успешно отправлена.');
                        }
                    }).catch(function (error) {
                        if (error && error.data && error.data.errors && errorBox) {
                            const errors = error.data.errors;
                            const list = [];
                            Object.keys(errors).forEach(function (key) {
                                const messages = errors[key];
                                messages.forEach(function (msg) {
                                    list.push(msg);
                                });
                            });
                            if (list.length) {
                                errorBox.innerHTML = '<strong>Пожалуйста, исправьте следующие ошибки:</strong><ul style="margin-top:10px; padding-left:20px;"><li>' + list.join('</li><li>') + '</li></ul>';
                                errorBox.style.display = 'block';
                                errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        } else {
                            if (error && error.nonJson && form) {
                                form.submit();
                                return;
                            }
                            var message = 'Произошла ошибка при отправке. Попробуйте ещё раз позже.';
                            if (error && error.data) {
                                if (typeof error.data.message === 'string' && error.data.message) {
                                    message = error.data.message;
                                } else if (typeof error.data.error === 'string' && error.data.error) {
                                    message = error.data.error;
                                }
                            } else if (error && typeof error.message === 'string' && error.message) {
                                message = error.message;
                            }
                            if (error && typeof error.status === 'number' && error.status >= 400) {
                                message += ' (код ' + error.status + ')';
                            }
                            if (errorBox) {
                                errorBox.textContent = message;
                                errorBox.style.display = 'block';
                                errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            } else {
                                alert(message);
                            }
                        }
                    }).finally(function () {
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.classList.remove('loading');
                        }
                    });
                });
            }

            const steps = document.querySelectorAll('.form-step');
            const formBlocks = document.querySelectorAll('.form-block[data-step]');
            const progressFill = document.querySelector('.form-progress-fill');

            function setActiveStep(stepKey, options) {
                options = options || {};
                var withScroll = options.scroll !== false;
                let activeIndex = 0;
                steps.forEach(function (step, index) {
                    const target = step.getAttribute('data-step-target');
                    if (target === stepKey) {
                        step.classList.add('active');
                        activeIndex = index;
                    } else {
                        step.classList.remove('active');
                    }
                });
                if (progressFill && steps.length > 0) {
                    const percent = ((activeIndex + 1) / steps.length) * 100;
                    progressFill.style.width = percent + '%';
                }
                if (withScroll) {
                    formBlocks.forEach(function (block) {
                        const key = block.getAttribute('data-step');
                        if (key === stepKey) {
                            block.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                }
            }

            steps.forEach(function (step) {
                step.addEventListener('click', function () {
                    const target = step.getAttribute('data-step-target');
                    if (target) {
                        setActiveStep(target, { scroll: true });
                    }
                });
            });

            formBlocks.forEach(function (block) {
                block.addEventListener('focusin', function () {
                    const key = block.getAttribute('data-step');
                    if (key) {
                        setActiveStep(key, { scroll: false });
                    }
                });
            });

            const previewButton = document.querySelector('.preview-button');
            const previewOverlay = document.querySelector('.form-preview-overlay');
            const previewCloseButtons = document.querySelectorAll('.form-preview-close');

            function buildPreviewSection() {
                if (!previewOverlay || !form) return;
                const personalSection = previewOverlay.querySelector('[data-preview="personal"]');
                const addressSection = previewOverlay.querySelector('[data-preview="address"]');
                const educationSection = previewOverlay.querySelector('[data-preview="education"]');
                const benefitsSection = previewOverlay.querySelector('[data-preview="benefits"]');
                const documentsSection = previewOverlay.querySelector('[data-preview="documents"]');
                const specialtiesSection = previewOverlay.querySelector('[data-preview="specialties"]');

                if (personalSection) {
                    const name = form.querySelector('input[name="name"]') ? form.querySelector('input[name="name"]').value : '';
                    const surname = form.querySelector('input[name="surname"]') ? form.querySelector('input[name="surname"]').value : '';
                    const birthdate = form.querySelector('input[name="birthdate"]') ? form.querySelector('input[name="birthdate"]').value : '';
                    const phone = form.querySelector('input[name="phone"]') ? form.querySelector('input[name="phone"]').value : '';
                    const email = form.querySelector('input[name="email"]') ? form.querySelector('input[name="email"]').value : '';
                    const citizenship = form.querySelector('input[name="citizenship"]') ? form.querySelector('input[name="citizenship"]').value : '';
                    personalSection.innerHTML =
                        '<h4>Личные данные</h4>' +
                        '<p><strong>ФИО:</strong> ' + surname + ' ' + name + '</p>' +
                        '<p><strong>Дата рождения:</strong> ' + birthdate + '</p>' +
                        '<p><strong>Телефон:</strong> ' + phone + '</p>' +
                        '<p><strong>Email:</strong> ' + email + '</p>' +
                        '<p><strong>Гражданство:</strong> ' + citizenship + '</p>';
                }

                if (addressSection) {
                    const city = form.querySelector('input[name="city"]') ? form.querySelector('input[name="city"]').value : '';
                    const street = form.querySelector('input[name="street"]') ? form.querySelector('input[name="street"]').value : '';
                    const house = form.querySelector('input[name="house"]') ? form.querySelector('input[name="house"]').value : '';
                    addressSection.innerHTML =
                        '<h4>Адрес проживания</h4>' +
                        '<p><strong>Город:</strong> ' + city + '</p>' +
                        '<p><strong>Улица:</strong> ' + street + '</p>' +
                        '<p><strong>Дом:</strong> ' + house + '</p>';
                }

                if (educationSection) {
                    const eduName = educationNameMain ? educationNameMain.value : '';
                    const eduYear = educationYearMain ? educationYearMain.value : '';
                    const ege = egeInput ? egeInput.value : '';
                    const certScore = form.querySelector('input[name="certificate_score"]') ? form.querySelector('input[name="certificate_score"]').value : '';
                    educationSection.innerHTML =
                        '<h4>Образование</h4>' +
                        '<p><strong>Учебное заведение:</strong> ' + eduName + '</p>' +
                        '<p><strong>Год окончания:</strong> ' + eduYear + '</p>' +
                        '<p><strong>Баллы ЕГЭ:</strong> ' + ege + '</p>' +
                        '<p><strong>Средний балл аттестата:</strong> ' + certScore + '</p>';
                }

                if (benefitsSection) {
                    const chosen = [];
                    benefitCheckboxes.forEach(function (cb) {
                        if (cb.checked) {
                            const label = cb.parentElement.querySelector('.benefit-title');
                            if (label) {
                                chosen.push(label.textContent.trim());
                            }
                        }
                    });
                    benefitsSection.innerHTML =
                        '<h4>Льготы</h4>' +
                        (chosen.length ? '<ul><li>' + chosen.join('</li><li>') + '</li></ul>' : '<p>Льготы не выбраны</p>');
                }

                if (documentsSection) {
                    const certFile = fileInput && fileInput.files[0] ? fileInput.files[0].name : 'Файл не выбран';
                    const benefitFile = benefitFileInput && benefitFileInput.files && benefitFileInput.files[0] ? benefitFileInput.files[0].name : 'Файл не выбран';
                    documentsSection.innerHTML =
                        '<h4>Документы</h4>' +
                        '<p><strong>Аттестат:</strong> ' + certFile + '</p>' +
                        '<p><strong>Документ, подтверждающий льготу:</strong> ' + benefitFile + '</p>';
                }

                if (specialtiesSection) {
                    const chosenSpecialties = [];
                    document.querySelectorAll('.specialty-checkbox:checked').forEach(function (cb) {
                        const item = cb.closest('.specialty-item');
                        if (!item) return;
                        const name = item.querySelector('.specialty-name') ? item.querySelector('.specialty-name').textContent.trim() : '';
                        const code = item.querySelector('.specialty-code-pill') ? item.querySelector('.specialty-code-pill').textContent.trim() : '';
                        const toggle = item.querySelector('.study-form-toggle');
                        let formText = '';
                        if (toggle) {
                            const active = toggle.querySelector('.study-form-option.active');
                            if (active) formText = active.textContent.trim();
                        }
                        chosenSpecialties.push((code ? code + ' ' : '') + name + (formText ? ' (' + formText + ')' : ''));
                    });
                    specialtiesSection.innerHTML =
                        '<h4>Выбранные специальности</h4>' +
                        (chosenSpecialties.length ? '<ul><li>' + chosenSpecialties.join('</li><li>') + '</li></ul>' : '<p>Вы не выбрали ни одной специальности</p>');
                }
            }

            if (previewButton && previewOverlay) {
                previewButton.addEventListener('click', function () {
                    buildPreviewSection();
                    previewOverlay.style.display = 'flex';
                });
            }

            previewCloseButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    if (previewOverlay) {
                        previewOverlay.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
