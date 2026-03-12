@extends('layouts.main')

@section('title', $specialty->name . ' - Колледж')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') . '?v=' . (file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time()) }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endpush

@section('content')
<div class="specialty-details-page">
    <div class="container">
        <!-- Header Section -->
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

            <div class="specialty-study-selector" style="max-width: 500px; margin: 0 auto 32px;">
                @php
                    $allForms = ['очная', 'заочная', 'очно-заочная'];
                    $firstAvailable = collect($allForms)->first(fn($f) => isset($availableForms[$f])) ?? (array_keys($availableForms)[0] ?? 'очная');
                @endphp
                <div class="study-form-toggle">
                    @foreach($allForms as $form)
                        @php
                            $isAvailable = isset($availableForms[$form]);
                        @endphp
                        <button type="button" 
                            class="study-form-option {{ $form === $firstAvailable ? 'active' : '' }} {{ !$isAvailable ? 'disabled' : '' }}"
                            data-value="{{ $form }}"
                            {{ !$isAvailable ? 'disabled' : '' }}>
                            {{ mb_convert_case($form, MB_CASE_TITLE, "UTF-8") }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="specialty-meta">
                <span data-label="Срок обучения" id="meta-duration">
                    <i class="fas fa-clock"></i>
                    {{ $availableForms[$firstAvailable]['duration'] ?? $specialty->duration }}
                </span>
                <span data-label="Квалификация" id="meta-qualification">
                    <i class="fas fa-user-graduate"></i>
                    {{ $availableForms[$firstAvailable]['qualification'] ?? $specialty->qualification }}
                </span>
                <span data-label="Бюджетных мест" id="meta-budget-places">
                    <i class="fas fa-graduation-cap"></i>
                    {{ $availableForms[$firstAvailable]['budget_places'] ?? $specialty->budget_places }}
                </span>
                <span data-label="Платных мест" id="meta-paid-places">
                    <i class="fas fa-chair"></i>
                    {{ $availableForms[$firstAvailable]['paid_places'] ?? max(0, ($specialty->total_places ?? 0) - $specialty->budget_places) }}
                </span>
                <span data-label="Стоимость" id="meta-cost">
                    <i class="fas fa-coins"></i>
                    @if(($availableForms[$firstAvailable]['cost'] ?? 0) > 0)
                        {{ number_format($availableForms[$firstAvailable]['cost'], 0, ',', ' ') }} ₽ / год
                    @else
                        Бюджет
                    @endif
                </span>
                <span data-label="Всего за обучение" id="meta-total-cost">
                    <i class="fas fa-wallet"></i>
                    —
                </span>
                <span data-label="Варианты оплаты" id="meta-payment-options">
                    <i class="fas fa-credit-card"></i>
                    —
                </span>
            </div>
        </header>

        <!-- Main Content -->
        <div class="specialty-content">
            <!-- Left Column: Media -->
            <div class="specialty-photo" data-aos="fade-right">
                @php
                    $photoPath = 'assets/img/specialties/' . $specialty->photo;
                @endphp
                @if($specialty->photo && file_exists(public_path($photoPath)))
                    <img src="{{ asset($photoPath) }}" alt="{{ $specialty->name }}">
                @else
                    <img src="{{ asset('assets/img/no-photo.jpg') }}" alt="Нет фото">
                @endif
            </div>

            <!-- Right Column: Details -->
            <div class="specialty-info">
                @php
                    $qualification = $specialty->qualification ?: 'специалиста';
                    $duration = $specialty->duration;
                @endphp

                <section class="info-section short-info" data-aos="fade-up">
                    <p>
                        Специальность «{{ $specialty->name }}» помогает освоить практические навыки и уверенно чувствовать себя в реальных рабочих задачах.
                        За {{ $duration }} вы формируете ключевые компетенции и получаете базу для дальнейшего профессионального роста.
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
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        document.addEventListener('DOMContentLoaded', function () {
            const header = document.querySelector('.specialty-header');
            if (!header) return;

            const toggles = header.querySelectorAll('.study-form-option');
            const metaDuration = document.getElementById('meta-duration');
            const metaCost = document.getElementById('meta-cost');
            const metaTotalCost = document.getElementById('meta-total-cost');
            const metaPaymentOptions = document.getElementById('meta-payment-options');

            const durationRaw = header.getAttribute('data-duration') || '';
            const availableForms = JSON.parse(header.getAttribute('data-available-forms') || '{}');

            function updateStats(form) {
                const info = availableForms[form] || {};
                const cost = info.cost || 0;
                const duration = info.duration || durationRaw;

                if (metaDuration)
                    metaDuration.innerHTML = `<i class="fas fa-clock"></i> ${duration}`;

                const metaQualification = document.getElementById('meta-qualification');
                if (metaQualification)
                    metaQualification.innerHTML = `<i class="fas fa-user-graduate"></i> ${info.qualification || ''}`;

                const metaBudget = document.getElementById('meta-budget-places');
                if (metaBudget)
                    metaBudget.innerHTML = `<i class="fas fa-graduation-cap"></i> ${info.budget_places != null ? info.budget_places : ''}`;

                const metaPaid = document.getElementById('meta-paid-places');
                if (metaPaid)
                    metaPaid.innerHTML = `<i class="fas fa-chair"></i> ${info.paid_places != null ? info.paid_places : 'Уточняется'}`;

                if (metaCost)
                    metaCost.innerHTML = `<i class="fas fa-coins"></i> ${cost > 0 ? cost.toLocaleString('ru-RU') + ' ₽ / год' : 'Бюджет'}`;

                let years = 4;
                if (duration.includes('3 год')) years = 3.8;
                if (duration.includes('2 год')) years = 2.8;

                if (metaTotalCost)
                    metaTotalCost.innerHTML = `<i class="fas fa-wallet"></i> ${cost > 0 ? (Math.round(cost * years)).toLocaleString('ru-RU') + ' ₽' : '—'}`;

                if (metaPaymentOptions)
                    metaPaymentOptions.innerHTML = `<i class="fas fa-credit-card"></i> ${cost > 0 ? 'Рассрочка, Семестровая, Кредит' : '—'}`;
            }

            // Initial update
            const activeToggle = header.querySelector('.study-form-option.active');
            if (activeToggle) {
                updateStats(activeToggle.getAttribute('data-value'));
            }

            toggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    if (this.classList.contains('disabled')) return;

                    toggles.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    updateStats(this.getAttribute('data-value'));
                });
            });
        });
    </script>
@endpush
@endsection