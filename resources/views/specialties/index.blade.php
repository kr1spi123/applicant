@extends('layouts.main')

@section('title', 'Специальности - Колледж')

@push('styles')
    <link rel="stylesheet"
        href="{{ asset('css/apps.css') . '?v=' . (file_exists(public_path('css/apps.css')) ? filemtime(public_path('css/apps.css')) : time()) }}">
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
                <div class="specialty-card" data-code="{{ $specialty->code }}" data-name="{{ $specialty->name }}"
                    data-duration="{{ $specialty->duration }}" data-available-forms='@json($availableForms)'>
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
                                    @if($isAvailable)
                                        <button type="button" class="study-form-option {{ $form === $firstAvailable ? 'active' : '' }}"
                                            data-value="{{ $form }}">
                                            {{ mb_convert_case($form, MB_CASE_TITLE, "UTF-8") }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="specialty-meta">
                            <span data-label="Срок обучения"
                                class="meta-duration">{{ $availableForms[$firstAvailable]['duration'] ?? $specialty->duration }}</span>
                            <span data-label="Квалификация"
                                class="meta-qualification">{{ $availableForms[$firstAvailable]['qualification'] ?? $specialty->qualification ?? 'Не указано' }}</span>
                            <span data-label="Бюджетных мест"
                                class="meta-budget-places">{{ $availableForms[$firstAvailable]['budget_places'] ?? $specialty->budget_places }}</span>
                            <span data-label="Платных мест"
                                class="meta-paid-places">{{ $availableForms[$firstAvailable]['paid_places'] ?? ($paidPlaces ?: 'Уточняется') }}</span>
                            <span class="tuition-row" data-label="Стоимость / Итого">
                                <span class="meta-cost-wrap">
                                    <span class="meta-cost-label">год:</span>
                                    <span
                                        class="meta-cost">@if(($availableForms[$firstAvailable]['cost'] ?? 0) > 0){{ number_format($availableForms[$firstAvailable]['cost'], 0, ',', ' ') }}
                                        ₽@else Бюджет @endif</span>
                                </span>
                                <span class="meta-cost-wrap">
                                    <span class="meta-cost-label">итого:</span>
                                    <span class="meta-total-cost">—</span>
                                </span>
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
                const availableForms = JSON.parse(card.getAttribute('data-available-forms') || '{}');
                const info = availableForms[form] || {};
                const cost = info.cost || 0;
                const duration = info.duration || card.getAttribute('data-duration') || '';

                const metaDuration = card.querySelector('.meta-duration');
                const metaQualification = card.querySelector('.meta-qualification');
                const metaBudget = card.querySelector('.meta-budget-places');
                const metaPaid = card.querySelector('.meta-paid-places');
                const metaCost = card.querySelector('.meta-cost');
                const metaTotalCost = card.querySelector('.meta-total-cost');

                if (metaDuration) metaDuration.textContent = duration;
                if (metaQualification) metaQualification.textContent = info.qualification || '';
                if (metaBudget) metaBudget.textContent = info.budget_places != null ? info.budget_places : '';
                if (metaPaid) metaPaid.textContent = info.paid_places != null ? info.paid_places : 'Уточняется';
                if (metaCost) metaCost.textContent = cost > 0 ? cost.toLocaleString('ru-RU') + ' ₽' : 'Бюджет';
                const metaCostLabel = card.querySelector('.meta-cost-label');
                if (metaCostLabel && cost <= 0) metaCostLabel.style.display = 'none';
                else if (metaCostLabel) metaCostLabel.style.display = '';

                if (metaTotalCost) {
                    let years = 4;
                    if (duration.includes('3 год')) years = 3.8;
                    if (duration.includes('2 год')) years = 2.8;
                    metaTotalCost.textContent = cost > 0 ? (Math.round(cost * years)).toLocaleString('ru-RU') + ' ₽' : '—';
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
                    toggle.addEventListener('click', function () {
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