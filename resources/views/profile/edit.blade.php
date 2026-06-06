@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/profile.css') . '?v=' . (file_exists(public_path('css/profile.css')) ? filemtime(public_path('css/profile.css')) : time()) }}">

<div class="pr-page">

    {{-- ══ NAV ══ --}}
    <nav class="pr-nav">
        <div class="pr-nav__inner">
            <a href="{{ route('applications.create') }}"
               class="pr-nav__link {{ request()->routeIs('applications.create') ? 'is-active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Подать заявку
            </a>
            <a href="{{ route('applications.index') }}"
               class="pr-nav__link {{ request()->routeIs('applications.index') ? 'is-active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Мои заявки
            </a>
            <a href="{{ route('applications.enrollment') }}"
               class="pr-nav__link {{ request()->routeIs('applications.enrollment') ? 'is-active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                </svg>
                Списки на поступление
            </a>
            <a href="{{ route('profile.edit') }}"
               class="pr-nav__link {{ request()->routeIs('profile.edit') ? 'is-active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Мой профиль
            </a>
        </div>
    </nav>

    {{-- ══ CONTENT ══ --}}
    <div class="pr-wrap">

        @php $comp = $completion ?? 0; @endphp

        {{-- MAIN CARD --}}
        <div class="pr-card">
            <div class="pr-card__stripe"></div>

            {{-- Hero --}}
            <div class="pr-card__hero">
                <div class="pr-identity">
                    <div class="pr-avatar-wrap">
                        <div class="pr-avatar">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}{{ $user->surname ? mb_strtoupper(mb_substr($user->surname, 0, 1)) : '' }}
                        </div>
                        <div class="pr-avatar__status"></div>
                    </div>
                    <div class="pr-identity__info">
                        <h1 class="pr-name">{{ $user->surname ? $user->surname . ' ' . $user->name : $user->name }}</h1>
                        <div class="pr-name-progress">
                            <div class="pr-name-progress__track">
                                <div class="pr-name-progress__fill" style="width:{{ $comp }}%"></div>
                            </div>
                            <span class="pr-name-progress__pct">{{ $comp }}%</span>
                        </div>
                        <div class="pr-meta">
                            <span class="pr-role">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                                Абитуриент
                            </span>
                            <span class="pr-since">С {{ $user->created_at?->format('d.m.Y') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="pr-edit-btn" id="editToggle">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Редактировать
                </button>
            </div>

            {{-- Badge --}}
            <div class="pr-badges">
                @if($comp >= 80)
                    <span class="pr-badge pr-badge--green">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        Готов к подаче документов
                    </span>
                @elseif($comp >= 50)
                    <span class="pr-badge pr-badge--yellow">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Заполните ещё несколько полей
                    </span>
                @else
                    <span class="pr-badge pr-badge--gray">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Начните заполнять профиль
                    </span>
                @endif
            </div>

            {{-- Tabs --}}
            <div class="pr-tabs">
                <button type="button" class="pr-tab is-active" data-tab="personal">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Личная информация
                </button>
                <button type="button" class="pr-tab" data-tab="education">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    Образование
                </button>
            </div>

            {{-- TAB: personal --}}
            <div class="pr-tab-pane is-active" data-tab="personal">
                @php
                    $pPct = (int)round(collect([$user->name,$user->surname,$user->birthdate,$user->citizenship])->filter()->count() / 4 * 100);
                    $cPct = (int)round(collect([$user->email,$user->phone])->filter()->count() / 2 * 100);
                    $aPct = (int)round(collect([$user->city,$user->street,$user->house])->filter()->count() / 3 * 100);
                @endphp

                <div class="pr-info-card">
                    <div class="pr-info-card__head">
                        <div class="pr-info-card__icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div class="pr-info-card__text">
                            <span class="pr-info-card__title">Личные данные</span>
                            <span class="pr-info-card__sub">Имя, дата рождения и гражданство</span>
                        </div>
                        <span class="pr-info-card__pct">{{ $pPct }}%</span>
                    </div>
                    <div class="pr-info-card__bar"><div style="width:{{ $pPct }}%"></div></div>
                    <div class="pr-info-card__rows">
                        <div class="pr-row"><span>Имя</span><span>{{ $user->name ?: '—' }}</span></div>
                        <div class="pr-row"><span>Фамилия</span><span>{{ $user->surname ?: '—' }}</span></div>
                        <div class="pr-row"><span>Дата рождения</span><span>{{ $user->birthdate ? $user->birthdate->format('d.m.Y') : '—' }}</span></div>
                        <div class="pr-row"><span>Гражданство</span><span>{{ $user->citizenship ?: '—' }}</span></div>
                    </div>
                </div>

                <div class="pr-info-card">
                    <div class="pr-info-card__head">
                        <div class="pr-info-card__icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.6 3.35a2 2 0 0 1 1.29-2.27l3-.9a2 2 0 0 1 2.27 1L9.5 4a2 2 0 0 1-.45 2.11L7.8 7.37A16 16 0 0 0 13.63 13l1.27-1.27a2 2 0 0 1 2.11-.45l2.77 1.33a2 2 0 0 1 1.22 1.84z"/>
                            </svg>
                        </div>
                        <div class="pr-info-card__text">
                            <span class="pr-info-card__title">Контакты</span>
                            <span class="pr-info-card__sub">Почта и телефон для связи</span>
                        </div>
                        <span class="pr-info-card__pct">{{ $cPct }}%</span>
                    </div>
                    <div class="pr-info-card__bar"><div style="width:{{ $cPct }}%"></div></div>
                    <div class="pr-info-card__rows">
                        <div class="pr-row"><span>Email</span><span>{{ $user->email }}</span></div>
                        <div class="pr-row"><span>Телефон</span><span>{{ $user->phone ?: '—' }}</span></div>
                    </div>
                </div>

                <div class="pr-info-card">
                    <div class="pr-info-card__head">
                        <div class="pr-info-card__icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/>
                            </svg>
                        </div>
                        <div class="pr-info-card__text">
                            <span class="pr-info-card__title">Адрес</span>
                            <span class="pr-info-card__sub">Город и место проживания</span>
                        </div>
                        <span class="pr-info-card__pct">{{ $aPct }}%</span>
                    </div>
                    <div class="pr-info-card__bar"><div style="width:{{ $aPct }}%"></div></div>
                    <div class="pr-info-card__rows">
                        <div class="pr-row">
                            <span>Адрес</span>
                            <span>
                                @if($user->city || $user->street || $user->house)
                                    {{ implode(', ', array_filter([$user->city, $user->street, $user->house ? 'д. '.$user->house : null])) }}
                                @else —
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: education --}}
            <div class="pr-tab-pane" data-tab="education">
                @php
                    $ePct = (int)round(collect([$user->school,$user->graduation_year])->filter()->count() / 2 * 100);
                @endphp
                <div class="pr-edu">
                    <div class="pr-edu__marker">
                        <div class="pr-edu__dot"></div>
                        <div class="pr-edu__line"></div>
                    </div>
                    <div class="pr-info-card" style="flex:1">
                        <div class="pr-info-card__head">
                            <div class="pr-info-card__icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                </svg>
                            </div>
                            <div class="pr-info-card__text">
                                <span class="pr-info-card__title">Основное образование</span>
                                <span class="pr-info-card__sub">Школа / учебное заведение</span>
                            </div>
                            <span class="pr-edu__year">{{ $user->graduation_year ?: '—' }}</span>
                        </div>
                        <div class="pr-info-card__bar"><div style="width:{{ $ePct }}%"></div></div>
                        <div class="pr-info-card__rows">
                            <div class="pr-row"><span>Учреждение</span><span>{{ $user->school ?: '—' }}</span></div>
                            <div class="pr-row">
                                <span>Статус</span>
                                <span>{{ ($user->school || $user->graduation_year) ? 'Среднее образование завершено' : 'Не заполнено' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /pr-card --}}


        {{-- EDIT PANEL --}}
        <div class="pr-edit {{ $errors->any() ? 'is-open' : '' }}" id="editPanel">
            <div class="pr-edit__stripe"></div>
            <div class="pr-edit__head">
                <h2>Редактирование профиля</h2>
                <button type="button" class="pr-edit__close" id="editCancel">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                @if(session('success'))
                <div class="pr-alert pr-alert--success">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="pr-alert pr-alert--error">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Проверьте правильность заполнения полей
                </div>
                @endif

                <div class="pr-form-group">
                    <div class="pr-form-group__title">Личные данные</div>
                    <div class="pr-grid pr-grid--2">
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-name">Имя</label>
                            <input id="f-name" type="text" name="name"
                                class="pr-input {{ $errors->has('name') ? 'is-error' : '' }}"
                                placeholder="Введите имя" value="{{ old('name', $user->name) }}" required>
                            @error('name')<span class="pr-field__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-surname">Фамилия</label>
                            <input id="f-surname" type="text" name="surname"
                                class="pr-input" placeholder="Введите фамилию"
                                value="{{ old('surname', $user->surname) }}">
                        </div>
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-bdate">Дата рождения</label>
                            <input id="f-bdate" type="date" name="birthdate" class="pr-input"
                                value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d')) }}">
                        </div>
                        <div class="pr-field">
                            <label class="pr-field__label">Гражданство</label>
                            <div class="pr-select">
                                @php $c = old('citizenship', $user->citizenship); @endphp
                                <select name="citizenship" class="pr-input">
                                    <option value=""          {{ !$c              ? 'selected' : '' }}>Не выбрано</option>
                                    <option value="Россия"    {{ $c==='Россия'    ? 'selected' : '' }}>Россия</option>
                                    <option value="Казахстан" {{ $c==='Казахстан' ? 'selected' : '' }}>Казахстан</option>
                                    <option value="Беларусь"  {{ $c==='Беларусь'  ? 'selected' : '' }}>Беларусь</option>
                                    <option value="Другое"    {{ $c==='Другое'    ? 'selected' : '' }}>Другое</option>
                                </select>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pr-form-group">
                    <div class="pr-form-group__title">Контакты</div>
                    <div class="pr-grid pr-grid--2">
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-phone">Телефон</label>
                            <input id="f-phone" type="tel" name="phone" class="pr-input"
                                placeholder="+7 (___) ___-__-__" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-email">Email</label>
                            <input id="f-email" type="email" name="email"
                                class="pr-input {{ $errors->has('email') ? 'is-error' : '' }}"
                                placeholder="example@mail.ru" value="{{ old('email', $user->email) }}" required>
                            @error('email')<span class="pr-field__error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="pr-form-group">
                    <div class="pr-form-group__title">Адрес проживания</div>
                    <div class="pr-grid pr-grid--3">
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-city">Город</label>
                            <input id="f-city" type="text" name="city" class="pr-input"
                                placeholder="Москва" value="{{ old('city', $user->city) }}">
                        </div>
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-street">Улица</label>
                            <input id="f-street" type="text" name="street" class="pr-input"
                                placeholder="ул. Ленина" value="{{ old('street', $user->street) }}">
                        </div>
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-house">Дом</label>
                            <input id="f-house" type="text" name="house" class="pr-input"
                                placeholder="12" value="{{ old('house', $user->house) }}">
                        </div>
                    </div>
                </div>

                <div class="pr-form-group">
                    <div class="pr-form-group__title">Образование</div>
                    <div class="pr-grid pr-grid--2">
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-school">Учебное заведение</label>
                            <input id="f-school" type="text" name="school" class="pr-input"
                                placeholder="МБОУ Школа №1" value="{{ old('school', $user->school) }}">
                        </div>
                        <div class="pr-field">
                            <label class="pr-field__label" for="f-grad">Год окончания</label>
                            <input id="f-grad" type="text" name="graduation_year" class="pr-input"
                                placeholder="2025" value="{{ old('graduation_year', $user->graduation_year) }}">
                        </div>
                    </div>
                </div>

                <div class="pr-form-actions">
                    <button type="button" class="pr-btn pr-btn--ghost" id="editCancel2">Отмена</button>
                    <button type="submit" class="pr-btn pr-btn--primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>{{-- /pr-edit --}}

    </div>{{-- /pr-wrap --}}
</div>{{-- /pr-page --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    const panel   = document.getElementById('editPanel');
    const toggle  = document.getElementById('editToggle');
    const cancel  = document.getElementById('editCancel');
    const cancel2 = document.getElementById('editCancel2');
    const tabs    = document.querySelectorAll('.pr-tab');
    const panes   = document.querySelectorAll('.pr-tab-pane');

    function openPanel() {
        panel.classList.add('is-open');
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function closePanel() {
        panel.classList.remove('is-open');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    toggle?.addEventListener('click', () =>
        panel.classList.contains('is-open') ? closePanel() : openPanel()
    );
    cancel?.addEventListener('click',  e => { e.preventDefault(); closePanel(); });
    cancel2?.addEventListener('click', e => { e.preventDefault(); closePanel(); });

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            const t = this.dataset.tab;
            tabs.forEach(x => x.classList.remove('is-active'));
            panes.forEach(x => x.classList.toggle('is-active', x.dataset.tab === t));
            this.classList.add('is-active');
        });
    });

    @if($errors->any() || session('success'))
    openPanel();
    @endif
});
</script>
@endsection