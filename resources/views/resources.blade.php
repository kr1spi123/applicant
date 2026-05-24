@extends('layouts.main')

@section('title', 'Ресурсы')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/resources.css') }}">
@endpush

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
                <h2 class="section__title">Как заселиться в общежитие СЛТ?</h2>
            </div>
            <p class="section__lead">Отделение по связям с общественностью проводит приём абитуриентов и их родителей для заселения в общежитие.</p>

            {{-- Q1 --}}
            <div class="qa-card">
                <div class="qa-card__q">
                    <span class="qa-card__num">01</span>
                    <h3>Когда можно приехать на заселение?</h3>
                </div>
                <div class="qa-card__body">
                    <div class="dorm-schedule">
                        <div class="dorm-day">
                            <div class="dorm-day__date">29 августа</div>
                            <div class="dorm-day__course">1 курс</div>
                            <div class="dorm-day__time">08:00 — 16:00</div>
                        </div>
                        <div class="dorm-day">
                            <div class="dorm-day__date">30 августа</div>
                            <div class="dorm-day__course">2 курс</div>
                            <div class="dorm-day__time">08:00 — 16:00</div>
                        </div>
                        <div class="dorm-day">
                            <div class="dorm-day__date">31 августа</div>
                            <div class="dorm-day__course">3–4 курс</div>
                            <div class="dorm-day__time">08:00 — 15:00</div>
                        </div>
                    </div>
                    <div class="dorm-address">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Менделеева, 2
                    </div>
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
                    <p>Не позднее чем за <strong>10 дней</strong> до заселения необходимо сдать справки в здрав.пункт, расположенный в общежитии №1 по адресу <strong>Юбилейная проспект, 10</strong>. Вы можете разместить их на диске или принести лично. Без справок заселение не производится.</p>
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
                        <svg class="alert__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <p>Если вы не выполните что-то из этих пунктов, в заселении в общежитие вам будет отказано.</p>
                    </div>
                    <div class="alert alert--info">
                        <svg class="alert__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
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
                            <td data-label="ФИО">Герко Ирина Николаевна</td>
                            <td data-label="Должность">Председатель приемной комиссии</td>
                            <td data-label="Период">с 17 июня по 7 июля 2024 г., с 3 августа по 31 августа 2024 г.</td>
                        </tr>
                        <tr>
                            <td data-label="ФИО">Герко Ирина Николаевна</td>
                            <td data-label="Должность">Председатель приемной комиссии</td>
                            <td data-label="Период">с 17 июня по 7 июля 2024 г., с 3 августа по 31 августа 2024 г.</td>
                        </tr>
                        <tr>
                            <td data-label="ФИО">Герко Ирина Николаевна</td>
                            <td data-label="Должность">Председатель приемной комиссии</td>
                            <td data-label="Период">с 17 июня по 7 июля 2024 г., с 3 августа по 31 августа 2024 г.</td>
                        </tr>
                        <tr>
                            <td data-label="ФИО">Герко Ирина Николаевна</td>
                            <td data-label="Должность">Председатель приемной комиссии</td>
                            <td data-label="Период">с 17 июня по 7 июля 2024 г., с 3 августа по 31 августа 2024 г.</td>
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
                    <a href="mailto:pk@slt.com" class="contact-link">
                        <span class="contact-link__icon">
                            <img src="{{ asset('assets/img/icons8-почта-50 1.png') }}" alt="Email">
                        </span>
                        <span class="contact-link__text">pk@slt.com</span>
                    </a>
                </div>

            </div>
        </section>

    </div>
</div>
@endsection