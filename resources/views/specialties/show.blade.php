@extends('layouts.main')

@section('title', $specialty->name . ' - Колледж')

@push('styles')
    <link rel="stylesheet"
        href="{{ asset('css/app.css') . '?v=' . (file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time()) }}">
    <link rel="stylesheet"
        href="{{ asset('css/specialty-meta-fix.css') . '?v=' . (file_exists(public_path('css/specialty-meta-fix.css')) ? filemtime(public_path('css/specialty-meta-fix.css')) : time()) }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endpush

@section('content')
    <div class="specialty-details-page">
        <div class="container">
            @php
                $availableForms = $specialty->available_study_forms;
            @endphp

            <header class="specialty-header" data-aos="fade-down"
                data-duration="{{ $specialty->duration }}"
                data-available-forms='@json($availableForms)'>

                <div class="specialty-header-line">
                    @if($specialty->code)
                        <span class="specialty-code-pill">{{ $specialty->code }}</span>
                    @endif
                    <h1 class="specialty-title">{{ $specialty->name }}</h1>
                </div>

                <div class="specialty-study-selector" style="max-width:500px; margin:0 auto 24px;">
                    @php
                        $allForms       = ['очная', 'заочная', 'очно-заочная'];
                        $firstAvailable = collect($allForms)->first(fn($f) => isset($availableForms[$f]))
                                          ?? (array_keys($availableForms)[0] ?? 'очная');
                    @endphp
                    <div class="study-form-toggle">
                        @foreach($allForms as $form)
                            @if(isset($availableForms[$form]))
                                <button type="button"
                                    class="study-form-option {{ $form === $firstAvailable ? 'active' : '' }}"
                                    data-value="{{ $form }}">
                                    {{ mb_convert_case($form, MB_CASE_TITLE, 'UTF-8') }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{--
                    .specialty-meta сам является слайдером (overflow:hidden + transform transition).
                    Каждая ячейка: лейбл через ::before(data-label) | иконка | .meta-val
                --}}
                <div class="specialty-meta" id="specialtyMeta">

                    <span class="meta-cell" data-label="Срок обучения" id="meta-duration">
                        <i class="fas fa-clock"></i>
                        <span class="meta-val">{{ $availableForms[$firstAvailable]['duration'] ?? $specialty->duration }}</span>
                    </span>

                    <span class="meta-cell" data-label="Квалификация" id="meta-qualification">
                        <i class="fas fa-user-graduate"></i>
                        <span class="meta-val">{{ $availableForms[$firstAvailable]['qualification'] ?? $specialty->qualification }}</span>
                    </span>

                    <span class="meta-cell" data-label="Бюджетных мест" id="meta-budget-places">
                        <i class="fas fa-graduation-cap"></i>
                        <span class="meta-val">{{ $availableForms[$firstAvailable]['budget_places'] ?? $specialty->budget_places }}</span>
                    </span>

                    <span class="meta-cell" data-label="Платных мест" id="meta-paid-places">
                        <i class="fas fa-chair"></i>
                        <span class="meta-val">{{ $availableForms[$firstAvailable]['paid_places'] ?? max(0, ($specialty->total_places ?? 0) - $specialty->budget_places) }}</span>
                    </span>

                    <span class="meta-cell" data-label="Стоимость" id="meta-cost">
                        <i class="fas fa-coins"></i>
                        <span class="meta-val">
                            @if(($availableForms[$firstAvailable]['cost'] ?? 0) > 0)
                                {{ number_format($availableForms[$firstAvailable]['cost'], 0, ',', ' ') }} ₽ / год
                            @else
                                Бюджет
                            @endif
                        </span>
                    </span>

                    <span class="meta-cell" data-label="Всего за обучение" id="meta-total-cost">
                        <i class="fas fa-wallet"></i>
                        <span class="meta-val">—</span>
                    </span>

                </div>

            </header>

            <!-- Main Content -->
            <div class="specialty-content">
                <div class="specialty-photo" data-aos="fade-right">
                    @php $photoPath = 'assets/img/specialties/' . $specialty->photo; @endphp
                    @if($specialty->photo && file_exists(public_path($photoPath)))
                        <img src="{{ asset($photoPath) }}" alt="{{ $specialty->name }}">
                    @else
                        <img src="{{ asset('assets/img/no-photo.jpg') }}" alt="Нет фото">
                    @endif
                </div>

                <div class="specialty-info">
                    @php
                        $qualification = $specialty->qualification ?: 'специалиста';
                        $duration      = $specialty->duration;
                    @endphp

                    <section class="info-section short-info" data-aos="fade-up">
                        <p>
                            Специальность «{{ $specialty->name }}» помогает освоить практические навыки и уверенно
                            чувствовать себя в реальных рабочих задачах.
                            За {{ $duration }} вы формируете ключевые компетенции и получаете базу для дальнейшего
                            профессионального роста.
                        </p>
                    </section>

                    <section class="info-section description-section" data-aos="fade-up">
                        <h2><i class="fas fa-file-alt"></i> Описание специальности</h2>
                        <p>{!! nl2br(e($specialty->description)) !!}</p>
                    </section>

                    <section class="info-section job-prospects" data-aos="fade-up">
                        <h2><i class="fas fa-briefcase"></i> Карьерный путь</h2>
                        <div class="job-grid">
                            <div class="job-card">
                                <h3>Где работать</h3>
                                <ul>
                                    @if(!empty($specialty->where_to_work))
                                        @foreach($specialty->where_to_work as $item)
                                            <li><i class="fas fa-check-circle"></i> {{ $item }}</li>
                                        @endforeach
                                    @else
                                        <li><i class="fas fa-check-circle"></i> ИТ-компании и стартапы</li>
                                        <li><i class="fas fa-check-circle"></i> Государственные структуры</li>
                                        <li><i class="fas fa-check-circle"></i> Фриланс и консалтинг</li>
                                        <li><i class="fas fa-check-circle"></i> Крупный бизнес</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="job-card">
                                <h3>Кем работать</h3>
                                <ul>
                                    @if(!empty($specialty->job_roles))
                                        @foreach($specialty->job_roles as $item)
                                            <li><i class="fas fa-user-tie"></i> {{ $item }}</li>
                                        @endforeach
                                    @else
                                        <li><i class="fas fa-user-tie"></i> {{ $qualification }}</li>
                                        <li><i class="fas fa-user-tie"></i> Ведущий специалист</li>
                                        <li><i class="fas fa-user-tie"></i> Технический менеджер</li>
                                        <li><i class="fas fa-user-tie"></i> Аналитик данных</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </section>

                    <section class="info-section admission-steps" data-aos="fade-up">
                        <h2><i class="fas fa-rocket"></i> Как поступить</h2>
                        <div class="steps-grid">
                            <div class="step">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h3>Подача документов</h3>
                                    <p>Загрузите документы через личный кабинет или принесите лично</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h3>Рассмотрение</h3>
                                    <p>Ваша заявка будет проверена приемной комиссией в течение 3-х дней</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h3>Зачисление</h3>
                                    <p>Следите за рейтингом и подтвердите свое намерение учиться</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({ duration: 800, once: true, offset: 100 });

            document.addEventListener('DOMContentLoaded', function () {
                const header  = document.querySelector('.specialty-header');
                if (!header) return;

                const block   = document.getElementById('specialtyMeta');
                const toggles = document.querySelectorAll('.study-form-option');
                const durRaw  = header.getAttribute('data-duration') || '';
                const avail   = JSON.parse(header.getAttribute('data-available-forms') || '{}');
                const order   = ['очная', 'заочная', 'очно-заочная'];

                let curIdx    = order.indexOf(
                    header.querySelector('.study-form-option.active')?.getAttribute('data-value') ?? 'очная'
                );
                let animating = false;

                /* меняем только текст внутри .meta-val, иконка остаётся */
                function setVal(id, text) {
                    const cell = document.getElementById(id);
                    if (!cell) return;
                    const v = cell.querySelector('.meta-val');
                    if (v) v.textContent = text;
                }

                function applyData(form) {
                    const d    = avail[form] || {};
                    const cost = d.cost || 0;
                    const dur  = d.duration || durRaw;

                    setVal('meta-duration',      dur);
                    setVal('meta-qualification', d.qualification || '');
                    setVal('meta-budget-places', d.budget_places != null ? d.budget_places : '');
                    setVal('meta-paid-places',   d.paid_places   != null ? d.paid_places   : 'Уточняется');
                    setVal('meta-cost',          cost > 0 ? cost.toLocaleString('ru-RU') + ' ₽ / год' : 'Бюджет');

                    let years = 4;
                    if (dur.includes('3 год')) years = 3.8;
                    if (dur.includes('2 год')) years = 2.8;
                    setVal('meta-total-cost', cost > 0 ? Math.round(cost * years).toLocaleString('ru-RU') + ' ₽' : '—');
                }

                function slide(newForm, forward) {
                    if (animating) return;
                    animating = true;

                    /* 1. уехать */
                    block.classList.add(forward ? 'meta-exit-left' : 'meta-exit-right');

                    setTimeout(function () {
                        /* 2. данные тихо обновляются */
                        applyData(newForm);

                        /* 3. стартовая позиция въезда — без transition */
                        block.classList.remove('meta-exit-left', 'meta-exit-right');
                        block.classList.add(forward ? 'meta-enter-from-right' : 'meta-enter-from-left');

                        block.getBoundingClientRect(); /* reflow */

                        /* 4. въехать */
                        block.classList.remove('meta-enter-from-right', 'meta-enter-from-left');
                        block.classList.add('meta-enter-done');

                        setTimeout(function () {
                            block.classList.remove('meta-enter-done');
                            animating = false;
                        }, 420);
                    }, 300);
                }

                /* инит */
                const active = header.querySelector('.study-form-option.active');
                if (active) applyData(active.getAttribute('data-value'));

                toggles.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        if (this.classList.contains('disabled') || this.classList.contains('active')) return;

                        const newForm = this.getAttribute('data-value');
                        const newIdx  = order.indexOf(newForm);
                        const fwd     = newIdx > curIdx;
                        curIdx        = newIdx;

                        toggles.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');

                        slide(newForm, fwd);
                    });
                });
            });
        </script>
    @endpush
@endsection