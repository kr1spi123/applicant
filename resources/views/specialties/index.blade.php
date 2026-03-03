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
                     data-name="{{ $specialty->name }}"
                     data-duration="{{ $specialty->duration }}"
                     data-available-forms='@json($availableForms)'>
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
                        <div class="specialty-header-main">
                            <div class="specialty-title-row">
                                @if($specialty->code)
                                    <span class="specialty-code-pill">{{ $specialty->code }}</span>
                                @endif
                                <h2 class="specialty-title">{{ $specialty->name }}</h2>
                            </div>
                        </div>
                        
                        <div class="specialty-study-selector">
                            @php
                                $allForms = ['очная', 'заочная', 'очно-заочная'];
                                $firstAvailable = collect($allForms)->first(fn($f) => isset($availableForms[$f])) ?? (array_keys($availableForms)[0] ?? 'очная');
                            @endphp
                            <div class="study-form-toggle" data-specialty="{{ $specialty->id }}">
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
                            <span data-label="Срок обучения" class="meta-duration">{{ $specialty->duration }}</span>
                            <span data-label="Квалификация">{{ $specialty->qualification ?? 'Не указано' }}</span>
                            <span data-label="Бюджетных мест">{{ $specialty->budget_places }}</span>
                            <span data-label="Платных мест">{{ $paidPlaces ?: 'Уточняется' }}</span>
                            <span class="tuition-row" data-label="Стоимость (год)">
                                <span class="meta-cost">
                                    @if(isset($availableForms[$firstAvailable]) && $availableForms[$firstAvailable] > 0)
                                        {{ number_format($availableForms[$firstAvailable], 0, ',', ' ') }} ₽
                                    @else
                                        Бюджет
                                    @endif
                                </span>
                            </span>
                            <span class="tuition-row" data-label="Всего за обучение">
                                <span class="meta-total-cost">—</span>
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
        document.addEventListener('DOMContentLoaded', function () {
            const specialtyCards = document.querySelectorAll('.specialty-card');

            function updateCardStats(card, form) {
                const durationRaw = card.getAttribute('data-duration');
                const availableForms = JSON.parse(card.getAttribute('data-available-forms') || '{}');
                const cost = availableForms[form] || 0;

                const metaDuration = card.querySelector('.meta-duration');
                const metaCost = card.querySelector('.meta-cost');
                const metaTotalCost = card.querySelector('.meta-total-cost');
                const metaPaymentOptions = card.querySelector('.meta-payment-options');

                // Calculate duration adjust
                let years = 4;
                if (durationRaw.includes('3 года')) years = 3.8;
                if (durationRaw.includes('2 года')) years = 2.8;
                if (form === 'заочная' || form === 'очно-заочная') years += 1;

                const durationText = durationRaw + (form !== 'очная' ? ' (+1 год)' : '');
                if (metaDuration) metaDuration.textContent = durationText;

                if (metaCost) {
                    metaCost.textContent = cost > 0 ? cost.toLocaleString() + ' ₽' : 'Бюджет';
                }

                if (metaTotalCost) {
                    metaTotalCost.textContent = cost > 0 ? (Math.round(cost * years)).toLocaleString() + ' ₽' : '—';
                }

                if (metaPaymentOptions) {
                    metaPaymentOptions.textContent = cost > 0 ? 'Рассрочка, Посеместровая, Кредит' : '—';
                }
            }

            specialtyCards.forEach(card => {
                const toggles = card.querySelectorAll('.study-form-option');
                
                // Initial update for each card
                const activeToggle = card.querySelector('.study-form-option.active');
                if (activeToggle) {
                    updateCardStats(card, activeToggle.getAttribute('data-value'));
                }

                toggles.forEach(toggle => {
                    toggle.addEventListener('click', function() {
                        if (this.classList.contains('disabled')) return;

                        toggles.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');

                        updateCardStats(card, this.getAttribute('data-value'));
                    });
                });
            });
        });
    </script>
@endsection
