@extends('layouts.main')

@section('title', $specialty->name . ' - Колледж')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') . '?v=' . (file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time()) }}">
<link rel="stylesheet" href="{{ asset('css/specialty-meta-fix.css') . '?v=' . (file_exists(public_path('css/specialty-meta-fix.css')) ? filemtime(public_path('css/specialty-meta-fix.css')) : time()) }}">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endpush

@section('content')
<div class="sp-page">

    {{-- ══════════════════════════════════════
         HERO HEADER
    ══════════════════════════════════════ --}}
    @php
    $availableForms = $specialty->available_study_forms;
    $allForms = ['очная', 'заочная', 'очно-заочная'];
    $firstAvailable = collect($allForms)->first(fn($f) => isset($availableForms[$f]))
    ?? (array_keys($availableForms)[0] ?? 'очная');
    @endphp

    <div class="sp-hero"
        data-duration="{{ $specialty->duration }}"
        data-available-forms='@json($availableForms)'>

        <div class="sp-hero__blob sp-hero__blob--1"></div>
        <div class="sp-hero__blob sp-hero__blob--2"></div>

        <div class="sp-hero__inner">



            {{-- Code + Title --}}
            <div class="sp-hero__title-row">
                @if($specialty->code)
                <span class="sp-code">{{ $specialty->code }}</span>
                @endif
                <h1 class="sp-hero__title">{{ $specialty->name }}</h1>
            </div>

            {{-- Form Toggle --}}
            <div class="sp-toggle-wrap">
                <div class="sp-toggle">
                    @foreach($allForms as $form)
                    @if(isset($availableForms[$form]))
                    <button type="button"
                        class="sp-toggle__btn {{ $form === $firstAvailable ? 'is-active' : '' }}"
                        data-value="{{ $form }}">
                        {{ mb_convert_case($form, MB_CASE_TITLE, 'UTF-8') }}
                    </button>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Meta strip --}}
            <div class="sp-meta" id="specialtyMeta">

                <div class="sp-meta__cell" id="meta-duration">
                    <span class="sp-meta__label">Срок обучения</span>
                    <span class="sp-meta__icon"><i class="fas fa-clock"></i></span>
                    <span class="sp-meta__val">{{ $availableForms[$firstAvailable]['duration'] ?? $specialty->duration }}</span>
                </div>

                <div class="sp-meta__cell" id="meta-qualification">
                    <span class="sp-meta__label">Квалификация</span>
                    <span class="sp-meta__icon"><i class="fas fa-user-graduate"></i></span>
                    <span class="sp-meta__val">{{ $availableForms[$firstAvailable]['qualification'] ?? $specialty->qualification }}</span>
                </div>

                <div class="sp-meta__cell" id="meta-budget-places">
                    <span class="sp-meta__label">Бюджетных мест</span>
                    <span class="sp-meta__icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="sp-meta__val">{{ $availableForms[$firstAvailable]['budget_places'] ?? $specialty->budget_places }}</span>
                </div>

                <div class="sp-meta__cell" id="meta-paid-places">
                    <span class="sp-meta__label">Платных мест</span>
                    <span class="sp-meta__icon"><i class="fas fa-chair"></i></span>
                    <span class="sp-meta__val">{{ $availableForms[$firstAvailable]['paid_places'] ?? max(0, ($specialty->total_places ?? 0) - $specialty->budget_places) }}</span>
                </div>

                <div class="sp-meta__cell" id="meta-cost">
                    <span class="sp-meta__label">Стоимость / год</span>
                    <span class="sp-meta__icon"><i class="fas fa-coins"></i></span>
                    <span class="sp-meta__val">
                        @if(($availableForms[$firstAvailable]['cost'] ?? 0) > 0)
                        {{ number_format($availableForms[$firstAvailable]['cost'], 0, ',', ' ') }} ₽
                        @else
                        Бюджет
                        @endif
                    </span>
                </div>

                <div class="sp-meta__cell" id="meta-total-cost">
                    <span class="sp-meta__label">Всего за обучение</span>
                    <span class="sp-meta__icon"><i class="fas fa-wallet"></i></span>
                    <span class="sp-meta__val">—</span>
                </div>

            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════
         BODY
    ══════════════════════════════════════ --}}
    <div class="sp-body container">

        {{-- ASIDE --}}
        <aside class="sp-aside" data-aos="fade-right">

            {{-- Photo --}}
            <div class="sp-photo">
                @php $photoPath = 'assets/img/specialties/' . $specialty->photo; @endphp
                @if($specialty->photo && file_exists(public_path($photoPath)))
                <img src="{{ asset($photoPath) }}" alt="{{ $specialty->name }}">
                @else
                <img src="{{ asset('assets/img/no-photo.jpg') }}" alt="Нет фото">
                @endif

            </div>

            {{-- CTA кнопки --}}
            <div class="sp-aside-cta">
                <a href="{{ route('applications.create') }}" class="sp-cta-btn sp-cta-btn--primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Подать заявку
                </a>
                <a href="/resources" class="sp-cta-btn sp-cta-btn--outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                    Список документов
                </a>
            </div>

            {{-- Quick facts --}}
            <div class="sp-facts">
                <div class="sp-facts__item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    <span>Гос. диплом</span>
                </div>
                <div class="sp-facts__item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <span>Практика с 1 курса</span>
                </div>
                <div class="sp-facts__item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <span>Трудоустройство</span>
                </div>
                <div class="sp-facts__item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                        <line x1="8" y1="21" x2="16" y2="21" />
                        <line x1="12" y1="17" x2="12" y2="21" />
                    </svg>
                    <span>Онлайн кабинет</span>
                </div>
            </div>

        </aside>

        {{-- MAIN --}}
        <main class="sp-main">

            @php
            $qualification = $specialty->qualification ?: 'специалиста';
            $duration = $specialty->duration;
            @endphp

            {{-- Lead --}}
            <div class="sp-lead" data-aos="fade-up">
                <div class="sp-lead__bar"></div>
                <p>
                    Специальность «{{ $specialty->name }}» помогает освоить практические навыки и уверенно
                    чувствовать себя в реальных рабочих задачах.
                    За {{ $duration }} вы формируете ключевые компетенции и получаете базу для дальнейшего
                    профессионального роста.
                </p>
            </div>

            {{-- Description --}}
            <section class="sp-section" data-aos="fade-up">
                <div class="sp-section__head">
                    <span class="sp-section__icon"><i class="fas fa-file-alt"></i></span>
                    <h2>Описание специальности</h2>
                </div>
                <div class="sp-section__body">
                    <p>{!! nl2br(e($specialty->description)) !!}</p>
                </div>
            </section>

            {{-- Career --}}
            <section class="sp-section" data-aos="fade-up">
                <div class="sp-section__head">
                    <span class="sp-section__icon"><i class="fas fa-briefcase"></i></span>
                    <h2>Карьерный путь</h2>
                </div>
                <div class="sp-section__body">
                    <div class="sp-career">

                        <div class="sp-career__card">
                            <h3><i class="fas fa-building"></i> Где работать</h3>
                            <ul>
                                @if(!empty($specialty->where_to_work))
                                @foreach($specialty->where_to_work as $item)
                                <li>
                                    <span class="sp-check"><i class="fas fa-check"></i></span>
                                    {{ $item }}
                                </li>
                                @endforeach
                                @else
                                <li><span class="sp-check"><i class="fas fa-check"></i></span>ИТ-компании и стартапы</li>
                                <li><span class="sp-check"><i class="fas fa-check"></i></span>Государственные структуры</li>
                                <li><span class="sp-check"><i class="fas fa-check"></i></span>Фриланс и консалтинг</li>
                                <li><span class="sp-check"><i class="fas fa-check"></i></span>Крупный бизнес</li>
                                @endif
                            </ul>
                        </div>

                        <div class="sp-career__card">
                            <h3><i class="fas fa-user-tie"></i> Кем работать</h3>
                            <ul>
                                @if(!empty($specialty->job_roles))
                                @foreach($specialty->job_roles as $item)
                                <li>
                                    <span class="sp-check sp-check--blue"><i class="fas fa-arrow-right"></i></span>
                                    {{ $item }}
                                </li>
                                @endforeach
                                @else
                                <li><span class="sp-check sp-check--blue"><i class="fas fa-arrow-right"></i></span>{{ $qualification }}</li>
                                <li><span class="sp-check sp-check--blue"><i class="fas fa-arrow-right"></i></span>Ведущий специалист</li>
                                <li><span class="sp-check sp-check--blue"><i class="fas fa-arrow-right"></i></span>Технический менеджер</li>
                                <li><span class="sp-check sp-check--blue"><i class="fas fa-arrow-right"></i></span>Аналитик данных</li>
                                @endif
                            </ul>
                        </div>

                    </div>
                </div>
            </section>

            {{-- Steps --}}
            <section class="sp-section" data-aos="fade-up">
                <div class="sp-section__head">
                    <span class="sp-section__icon"><i class="fas fa-rocket"></i></span>
                    <h2>Как поступить</h2>
                </div>
                <div class="sp-section__body">
                    <div class="sp-steps">

                        <div class="sp-step">
                            <div class="sp-step__num">1</div>
                            <div class="sp-step__line"></div>
                            <div class="sp-step__content">
                                <h3>Подача документов</h3>
                                <p>Загрузите документы через личный кабинет или принесите лично в приёмную комиссию</p>
                            </div>
                        </div>

                        <div class="sp-step">
                            <div class="sp-step__num">2</div>
                            <div class="sp-step__line"></div>
                            <div class="sp-step__content">
                                <h3>Рассмотрение</h3>
                                <p>Ваша заявка будет проверена приёмной комиссией в течение 3-х рабочих дней</p>
                            </div>
                        </div>

                        <div class="sp-step">
                            <div class="sp-step__num">3</div>
                            <div class="sp-step__line sp-step__line--hidden"></div>
                            <div class="sp-step__content">
                                <h3>Зачисление</h3>
                                <p>Следите за рейтингом и подтвердите своё намерение учиться</p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </main>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 80
    });

    document.addEventListener('DOMContentLoaded', function() {
        const hero = document.querySelector('.sp-hero');
        if (!hero) return;

        const meta = document.getElementById('specialtyMeta');
        const toggles = document.querySelectorAll('.sp-toggle__btn');
        const durRaw = hero.getAttribute('data-duration') || '';
        const avail = JSON.parse(hero.getAttribute('data-available-forms') || '{}');
        const order = ['очная', 'заочная', 'очно-заочная'];

        let curIdx = order.indexOf(
            hero.querySelector('.sp-toggle__btn.is-active')?.getAttribute('data-value') ?? 'очная'
        );
        let animating = false;

        function setVal(id, text) {
            const el = document.getElementById(id);
            if (!el) return;
            const v = el.querySelector('.sp-meta__val');
            if (v) v.textContent = text;
        }

        function applyData(form) {
            const d = avail[form] || {};
            const cost = d.cost || 0;
            const dur = d.duration || durRaw;

            setVal('meta-duration', dur);
            setVal('meta-qualification', d.qualification || '');
            setVal('meta-budget-places', d.budget_places != null ? d.budget_places : '');
            setVal('meta-paid-places', d.paid_places != null ? d.paid_places : 'Уточняется');
            setVal('meta-cost', cost > 0 ? cost.toLocaleString('ru-RU') + ' ₽' : 'Бюджет');

            let years = 4;
            if (dur.includes('3 год')) years = 3.8;
            if (dur.includes('2 год')) years = 2.8;
            setVal('meta-total-cost', cost > 0 ? Math.round(cost * years).toLocaleString('ru-RU') + ' ₽' : '—');
        }

        function slide(newForm, forward) {
            if (animating) return;
            animating = true;

            meta.classList.add(forward ? 'meta-exit-left' : 'meta-exit-right');

            setTimeout(function() {
                applyData(newForm);
                meta.classList.remove('meta-exit-left', 'meta-exit-right');
                meta.classList.add(forward ? 'meta-enter-from-right' : 'meta-enter-from-left');
                meta.getBoundingClientRect();
                meta.classList.remove('meta-enter-from-right', 'meta-enter-from-left');
                meta.classList.add('meta-enter-done');

                setTimeout(function() {
                    meta.classList.remove('meta-enter-done');
                    animating = false;
                }, 420);
            }, 300);
        }

        const active = hero.querySelector('.sp-toggle__btn.is-active');
        if (active) applyData(active.getAttribute('data-value'));

        toggles.forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (this.classList.contains('is-active')) return;
                const newForm = this.getAttribute('data-value');
                const newIdx = order.indexOf(newForm);
                const fwd = newIdx > curIdx;
                curIdx = newIdx;
                toggles.forEach(t => t.classList.remove('is-active'));
                this.classList.add('is-active');
                slide(newForm, fwd);
            });
        });
    });
</script>
@endpush
@endsection