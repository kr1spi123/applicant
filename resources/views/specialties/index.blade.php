@extends('layouts.main')

@section('title', 'Специальности - Колледж')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/apps.css') . '?v=' . (file_exists(public_path('css/apps.css')) ? filemtime(public_path('css/apps.css')) : time()) }}">
@endpush

@section('content')
    <div class="container">
        <h1 class="page-title">Наши специальности</h1>
        <p class="page-description">Выберите интересующую вас специальность для получения подробной информации</p>

        <div class="specialties-grid">
            @forelse($specialties as $specialty)
                @php
                    $availableForms = $specialty->available_study_forms;
                    $paidPlaces = max(0, (int) ($specialty->total_places ?? 0) - (int) $specialty->budget_places);
                @endphp
                <div class="specialty-card"
                     data-code="{{ $specialty->code }}"
                     data-fee="{{ count($availableForms) ? reset($availableForms) : '' }}"
                     data-passing=""
                     data-forms="{{ implode(',', array_keys($availableForms)) }}"
                     data-name="{{ $specialty->name }}">
                    <div class="specialty-photo">
                        @php
                            $photo = $specialty->photo;
                            $path = 'assets/img/specialties/' . $photo;
                        @endphp
                        @if(!empty($photo) && file_exists(public_path($path)))
                            <img src="{{ asset($path) }}" alt="{{ $specialty->name }}">
                        @else
                            <img src="{{ asset('assets/img/no-photo.jpg') }}" alt="Нет фото">
                        @endif
                    </div>

                    <div class="specialty-content">
                        <div class="specialty-header-line">
                            <h2 class="specialty-title">{{ $specialty->name }}</h2>
                        </div>
                        <div class="specialty-badges-row">
                            @if($specialty->code)
                                <span class="specialty-code-pill">{{ $specialty->code }}</span>
                            @endif
                            <span class="specialty-education-pill">СПО</span>
                            @foreach($availableForms as $form => $fee)
                                @php
                                    $formType = $form === 'очная' ? 'fulltime' : ($form === 'заочная' ? 'parttime' : ($form === 'очно-заочная' ? 'evening' : 'mixed'));
                                @endphp
                                <span class="study-badge study-badge-{{ $formType }}">{{ $form }}</span>
                            @endforeach
                        </div>
                        <div class="specialty-meta">
                            <span data-label="Срок обучения">{{ $specialty->duration }}</span>
                            <span data-label="Квалификация">{{ $specialty->qualification ?? 'Не указано' }}</span>
                            <span data-label="Бюджетных мест">{{ $specialty->budget_places }}</span>
                            <span data-label="Платных мест">{{ $paidPlaces ?: 'Уточняется' }}</span>
                            <span class="tuition" data-label="Стоимость">
                                @if(count($availableForms) && reset($availableForms) > 0)
                                    @foreach($availableForms as $formTitle => $fee)
                                        <span>{{ $formTitle }} — {{ number_format($fee, 0, ',', ' ') }} ₽ / год</span>
                                    @endforeach
                                @else
                                    <span>Уточняется</span>
                                @endif
                            </span>
                        </div>
                        <div class="specialty-actions">
                            <a href="{{ route('specialties.show', $specialty) }}" class="btn-more">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-data">Нет доступных специальностей</div>
            @endforelse
        </div>
    </div>

    <script>
    </script>
@endsection
