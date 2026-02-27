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
        <header class="specialty-header" data-aos="fade-down">
            <div class="specialty-header-line">
                <h1 class="specialty-title">{{ $specialty->name }}</h1>
                @if($specialty->code)
                    <span class="specialty-code-pill">{{ $specialty->code }}</span>
                @endif
            </div>

            @php
                $availableForms = $specialty->available_study_forms;
            @endphp

            <div class="specialty-study-row">
                @foreach($availableForms as $form => $fee)
                    @php
                        $formType = $form === 'очная' ? 'fulltime' : ($form === 'заочная' ? 'parttime' : ($form === 'очно-заочная' ? 'evening' : 'mixed'));
                        $icon = $form === 'очная' ? 'fa-sun' : ($form === 'заочная' ? 'fa-moon' : ($form === 'очно-заочная' ? 'fa-cloud-sun' : 'fa-laptop-house'));
                    @endphp
                    <span class="study-badge study-badge-{{ $formType }}">
                        <i class="fas {{ $icon }}"></i>
                        {{ $form }}
                    </span>
                @endforeach
            </div>

            <div class="specialty-meta">
                <span data-label="Срок обучения">
                    <i class="fas fa-clock"></i>
                    {{ $specialty->duration }}
                </span>
                <span data-label="Квалификация">
                    <i class="fas fa-user-graduate"></i>
                    {{ $specialty->qualification }}
                </span>
                <span data-label="Стоимость">
                    <i class="fas fa-coins"></i>
                    @if(count($availableForms) && reset($availableForms) > 0)
                        @foreach($availableForms as $formTitle => $fee)
                            {{ $formTitle }}: {{ number_format($fee, 0, ',', ' ') }} ₽@if(!$loop->last), @endif
                        @endforeach
                    @else
                        Уточняется
                    @endif
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
    </script>
@endpush
@endsection
