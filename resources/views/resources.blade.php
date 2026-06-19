@extends('layouts.main')

@section('title', 'Ресурсы')

<link rel="stylesheet" href="{{ asset('css/resources.css') . '?v=' . (file_exists(public_path('css/resources.css')) ? filemtime(public_path('css/resources.css')) : time()) }}">

@section('content')
<div class="page-wrapper">

    {{-- HERO --}}
    <section class="hero">
        <div class="hero__inner">
            <span class="hero__tag">Приёмная комиссия</span>
            <h1 class="hero__title">Всё, что нужно<br>для поступления</h1>
            <p class="hero__sub">Документы, заселение, контакты — в одном месте</p>
        </div>
    </section>

    <div class="container">

        {{-- DOCUMENTS --}}
        <section class="section" id="documents">
            <div class="section__header">
                <span class="section__icon">📄</span>
                <h2 class="section__title">Документы для поступления</h2>
            </div>
            <ul class="doc-list">
                <li class="doc-item">
                    <span class="doc-item__num">01</span>
                    <p>Оригинал и копию документов, удостоверяющих личность, гражданство</p>
                </li>
                <li class="doc-item">
                    <span class="doc-item__num">02</span>
                    <p>Оригинал и копию документов об образовании и (или) квалификации</p>
                </li>
                <li class="doc-item">
                    <span class="doc-item__num">03</span>
                    <p>6 фотографий (3×4, матовые)</p>
                </li>
                <li class="doc-item">
                    <span class="doc-item__num">04</span>
                    <p>Оригинал и копию СНИЛС (Постановление Правительства РФ от 29.11.2021 № 2085 п.19)</p>
                </li>
                <li class="doc-item">
                    <span class="doc-item__num">05</span>
                    <p>Копию ИНН</p>
                </li>
                <li class="doc-item">
                    <span class="doc-item__num">06</span>
                    <p>Копию приписного свидетельства для юношей</p>
                </li>
                <li class="doc-item">
                    <span class="doc-item__num">07</span>
                    <p>Медицинскую справку 086/у с заключением участкового врача о профессиональной пригодности</p>
                </li>
                <li class="doc-item">
                    <span class="doc-item__num">08</span>
                    <p>Копию медицинского сертификата</p>
                </li>
            </ul>
        </section>

        {{-- DORM --}}
        <section class="section" id="dorm">
            <div class="section__header">
                <span class="section__icon">🏠</span>
                <h2 class="section__title">Как заселиться в общежитие СЛИ?</h2>
            </div>
            <p class="section__lead">Отделение по связям с общественностью проводит приём абитуриентов и их родителей для заселения в общежитие.</p>

            {{-- Q1 --}}
            <div class="qa-card">
                <div class="qa-card__q">
                    <span class="qa-card__num">01</span>
                    <h3>Когда можно приехать на заселение?</h3>
                </div>
                <div class="qa-card__body">
                    <p><strong>Заселение начинается:</strong> 29 августа 2025 года.</p>
                    <p>Для оформления заявления и договора найма жилого помещения необходимо подойти по адресу: г. Сыктывкар, <strong>ул. Старовского, д. 26</strong>.</p>
                    <p>С заявлением и заполненным договором подойти по адресу: г. Сыктывкар, <strong>ул. Ленина, д. 39, в каб. № 107-1</strong> учебного корпуса для подписания и регистрации договора найма.</p>
                </div>
            </div>

            {{-- Q2 --}}
            <div class="qa-card">
                <div class="qa-card__q">
                    <span class="qa-card__num">02</span>
                    <h3>Может ли абитуриент приехать на заселение один?</h3>
                </div>
                <div class="qa-card__body">
                    <p>Да, если ему есть 18 лет. Если абитуриент несовершеннолетний, заселение производится только в присутствии законного представителя (родителя или опекуна). Если родители не могут присутствовать, необходимо оформить доверенность на совершеннолетнего представителя.</p>
                </div>
            </div>

            {{-- Q3 --}}
            <div class="qa-card">
                <div class="qa-card__q">
                    <span class="qa-card__num">03</span>
                    <h3>Какие справки необходимы для заселения?</h3>
                </div>
                <div class="qa-card__body">

                    <p class="checkin-section-label">Основные документы</p>
                    <div class="checkin-doc-grid">
                        <div class="checkin-doc-pill">
                            <span class="checkin-doc-icon">🪪</span>
                            <span>Паспорт</span>
                        </div>
                        <div class="checkin-doc-pill">
                            <span class="checkin-doc-icon">📷</span>
                            <span>2 фотографии 3×4</span>
                        </div>
                        <div class="checkin-doc-pill">
                            <span class="checkin-doc-icon">📋</span>
                            <span>Медицинская справка</span>
                        </div>
                    </div>

                    <p class="checkin-section-label">Медицинские документы — по курсу</p>
                    <div class="checkin-med-grid">
                        <div class="checkin-med-card">
                            <span class="checkin-med-tag checkin-med-tag--blue">1 курс</span>
                            <p>Справка формы 086-у с данными флюорографии (12 мес.)</p>
                            <p>Отметка дерматолога</p>
                            <p>Данные профилактических прививок</p>
                        </div>
                        <div class="checkin-med-card">
                            <span class="checkin-med-tag checkin-med-tag--amber">2 · 3 · 4 курс</span>
                            <p>Флюорография (12 мес.)</p>
                            <p>Анализ крови RW (реакция Вассермана)</p>
                            <p>Справка от дерматолога</p>
                            <p>Справка от гинеколога (женщинам)</p>
                        </div>
                    </div>

                    <p class="checkin-section-label">Порядок оформления</p>
                    <div class="checkin-steps">
                        <div class="checkin-step">
                            <span class="checkin-step__num">1</span>
                            <div>
                                <strong>ул. Старовского, д. 26</strong>
                                <p>Оформление заявления и договора найма жилого помещения</p>
                            </div>
                        </div>
                        <div class="checkin-step-arrow">→</div>
                        <div class="checkin-step">
                            <span class="checkin-step__num">2</span>
                            <div>
                                <strong>ул. Ленина, д. 39 — каб. № 107-1</strong>
                                <p>Подписание и регистрация договора найма в учебном корпусе</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Q4 --}}
            <div class="qa-card">
                <div class="qa-card__q">
                    <span class="qa-card__num">04</span>
                    <h3>Какие документы нужно взять с собой?</h3>
                </div>
                <div class="qa-card__body">
                    <p>Паспорт (оригинал + 2 копии), медицинский полис (оригинал + 2 копии), приписное свидетельство для юношей (оригинал + 2 копии), фотографии 3×4 — 4 шт.</p>
                    <div class="alert alert--warn">
                        <svg class="alert__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                        <p>Если вы не выполните что-то из этих пунктов, в заселении в общежитие вам будет отказано.</p>
                    </div>
                    <div class="alert alert--info">
                        <svg class="alert__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <p>Если вы не можете приехать в указанные даты, необходимо заранее предупредить администрацию по почте или телефону.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- COMMISSION TABLE --}}
        <section class="section" id="commission">
            <div class="section__header">
                <span class="section__icon">👥</span>
                <h2 class="section__title">Состав приёмной комиссии</h2>
            </div>
            <div class="table-wrap">
                <table class="commission-table">
                    <thead>
                        <tr>
                            <th>ФИО</th>
                            <th>Должность</th>
                            <th>Период работы</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="ФИО">Гурьева Любовь Александровна</td>
                            <td data-label="Должность">Председатель приемной комиссии</td>
                            <td data-label="Период">с 1 июня по 31 августа 2026 г.</td>
                        </tr>
                        <tr>
                            <td data-label="ФИО">Пестова Наталия Феликсовна</td>
                            <td data-label="Должность">Ответственный секретарь приемной комиссии</td>
                            <td data-label="Период">с 1 июня по 31 августа 2026 г.</td>
                        </tr>
                        <tr>
                            <td data-label="ФИО">Рауш Елена Анатольевна</td>
                            <td data-label="Должность">Заместитель директора по экономическим вопросам</td>
                            <td data-label="Период">с 1 июня по 31 августа 2026 г.</td>
                        </tr>
                        <tr>
                            <td data-label="ФИО">Бушманов Николай Александрович</td>
                            <td data-label="Должность">Заместитель директора по цифровой трансформации</td>
                            <td data-label="Период">с 1 июня по 31 августа 2026 г.</td>
                        </tr>
                        <tr>
                            <td data-label="ФИО">Ковалевская Марина Дмитриевна</td>
                            <td data-label="Должность">Начальник управления правового и кадрового обеспечения</td>
                            <td data-label="Период">с 1 июня по 31 августа 2026 г.</td>
                        </tr>
                        <tr>
                            <td data-label="ФИО">Самородницкий Александр Анатольевич</td>
                            <td data-label="Должность">Декан транспортно-технологического факультета</td>
                            <td data-label="Период">с 1 июня по 31 августа 2026 г.</td>
                        </tr>
                        <tr>
                            <td data-label="ФИО">Попова Татьяна Васильевна</td>
                            <td data-label="Должность">Декан факультета лесного и сельского хозяйства</td>
                            <td data-label="Период">с 1 июня по 31 августа 2026 г.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- SCHEDULE + CONTACTS --}}
        <section class="section" id="contacts">
            <div class="bottom-grid">

                <div class="schedule-card">
                    <div class="section__header section__header--left">
                        <span class="section__icon">🕐</span>
                        <h2 class="section__title">График работы</h2>
                    </div>
                    <ul class="sched-list">
                        <li class="sched-item">
                            <span class="sched-item__day">Понедельник</span>
                            <span class="sched-item__dots"></span>
                            <span class="sched-item__time">08:00 — 16:30</span>
                        </li>
                        <li class="sched-item">
                            <span class="sched-item__day">Вторник</span>
                            <span class="sched-item__dots"></span>
                            <span class="sched-item__time">08:00 — 16:30</span>
                        </li>
                        <li class="sched-item">
                            <span class="sched-item__day">Среда</span>
                            <span class="sched-item__dots"></span>
                            <span class="sched-item__time">08:00 — 16:30</span>
                        </li>
                        <li class="sched-item">
                            <span class="sched-item__day">Четверг</span>
                            <span class="sched-item__dots"></span>
                            <span class="sched-item__time">08:00 — 16:30</span>
                        </li>
                        <li class="sched-item">
                            <span class="sched-item__day">Пятница</span>
                            <span class="sched-item__dots"></span>
                            <span class="sched-item__time">08:00 — 16:30</span>
                        </li>
                        <li class="sched-item">
                            <span class="sched-item__day">Суббота</span>
                            <span class="sched-item__dots"></span>
                            <span class="sched-item__time">08:00 — 14:30</span>
                        </li>
                        <li class="sched-item sched-item--off">
                            <span class="sched-item__day">Воскресенье</span>
                            <span class="sched-item__dots"></span>
                            <span class="sched-item__time">Выходной</span>
                        </li>
                    </ul>
                </div>

                <div class="contacts-card">
                    <div class="section__header section__header--left">
                        <span class="section__icon">📬</span>
                        <h2 class="section__title">Контакты</h2>
                    </div>
                    <a href="tel:+79009209212" class="contact-link">
                        <span class="contact-link__icon">
                            <img src="{{ asset('assets/img/icons8-телефон-50 1.png') }}" alt="Phone">
                        </span>
                        <span class="contact-link__text">+7 900 920 92 12</span>
                    </a>
                    <a href="mailto:pk@sli.com" class="contact-link">
                        <span class="contact-link__icon">
                            <img src="{{ asset('assets/img/icons8-почта-50 1.png') }}" alt="Email">
                        </span>
                        <span class="contact-link__text">pk@sli.com</span>
                    </a>
                </div>

            </div>
        </section>

    </div>
</div>
@endsection