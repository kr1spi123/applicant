@extends('layouts.main')

@section('title', 'Специальности - Колледж')

@push('styles')
<link rel="stylesheet"
    href="{{ asset('css/apps.css') . '?v=' . (file_exists(public_path('css/apps.css')) ? filemtime(public_path('css/apps.css')) : time()) }}">
<style>
    /* ── мета-блок: сетка карточек ─────────────────────── */
    .specialty-meta {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 1px !important;
        background: #e5e7eb !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        margin-bottom: 20px !important;
        padding: 0 !important;

        will-change: transform, opacity;
        transition: transform .34s cubic-bezier(.4, 0, .2, 1),
            opacity .34s cubic-bezier(.4, 0, .2, 1);
    }

    /* каждая ячейка */
    .specialty-meta>span {
        background: #fff !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        justify-content: center !important;
        padding: 10px 14px !important;
        gap: 2px !important;
        min-height: 0 !important;
    }

    /* лейбл */
    .specialty-meta>span::before {
        content: attr(data-label) !important;
        font-size: 9px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: .05em !important;
        color: #94a3b8 !important;
        white-space: nowrap !important;
    }

    /* значение */
    .specialty-meta>span {
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
        line-height: 1.3 !important;
    }

    /* строка стоимости — на всю ширину */
    .specialty-meta .tuition-row {
        grid-column: span 2 !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        border-top: 1px solid #e5e7eb !important;
        background: #fafafa !important;
        padding: 10px 14px !important;
        gap: 8px !important;
    }

    .specialty-meta .tuition-row::before {
        display: none !important;
        /* у tuition-row свой лейбл внутри */
    }

    .meta-cost-wrap {
        display: flex !important;
        align-items: baseline !important;
        gap: 4px !important;
    }

    .meta-cost-label {
        font-size: 9px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: .05em !important;
        color: #94a3b8 !important;
    }

    .meta-cost,
    .meta-total-cost {
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #FF5A30 !important;
    }

    /* ── анимационные классы ────────────────────────────── */
    .meta-exit-left {
        transform: translateX(-36px) !important;
        opacity: 0 !important;
        pointer-events: none;
    }

    .meta-exit-right {
        transform: translateX(36px) !important;
        opacity: 0 !important;
        pointer-events: none;
    }

    .meta-enter-from-right {
        transform: translateX(36px) !important;
        opacity: 0 !important;
        transition: none !important;
    }

    .meta-enter-from-left {
        transform: translateX(-36px) !important;
        opacity: 0 !important;
        transition: none !important;
    }

    .meta-enter-done {
        transform: translateX(0) !important;
        opacity: 1 !important;
    }

    /* ── тоггл ──────────────────────────────────────────── */
    .study-form-toggle {
        display: flex !important;
        background: #f3f4f6 !important;
        border-radius: 10px !important;
        padding: 3px !important;
        gap: 3px !important;
        border: 1px solid #e5e7eb !important;
        margin-bottom: 12px !important;
    }

    .study-form-option {
        flex: 1 !important;
        padding: 7px 10px !important;
        border: none !important;
        background: transparent !important;
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #6b7280 !important;
        cursor: pointer !important;
        transition: background .15s, color .15s, box-shadow .15s !important;
        white-space: nowrap !important;
    }

    .study-form-option:hover:not(.active) {
        background: rgba(0, 0, 0, .05) !important;
        color: #374151 !important;
    }

    .study-form-option.active {
        background: #fff !important;
        color: #FF5A30 !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .10) !important;
    }

    /* убираем старый отступ у selector */
    .specialty-study-selector {
        margin-bottom: 0 !important;
    }
</style>
@endpush

@section('content')
<div class="container">
    <h1 class="page-title">Наши специальности</h1>
    <p class="page-description">Выберите интересующую вас специальность для получения подробной информации</p>

    <div class="specialties-grid">
        @forelse($specialties as $specialty)
        @php
        $availableForms = $specialty->available_study_forms;
        $paidPlaces = max(0, (int)($specialty->total_places ?? 0) - (int)$specialty->budget_places);
        $allForms = ['очная', 'заочная', 'очно-заочная'];
        $firstAvailable = collect($allForms)->first(fn($f) => isset($availableForms[$f]))
        ?? (array_keys($availableForms)[0] ?? 'очная');
        @endphp
        <div class="specialty-card"
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

                {{-- мета-блок — он сам является слайдером --}}
                <div class="specialty-meta">
                    <span data-label="Срок обучения" class="meta-duration">
                        {{ $availableForms[$firstAvailable]['duration'] ?? $specialty->duration }}
                    </span>
                    <span data-label="Квалификация" class="meta-qualification">
                        {{ $availableForms[$firstAvailable]['qualification'] ?? $specialty->qualification ?? 'Не указано' }}
                    </span>
                    <span data-label="Бюджетных мест" class="meta-budget-places">
                        {{ $availableForms[$firstAvailable]['budget_places'] ?? $specialty->budget_places }}
                    </span>
                    <span data-label="Платных мест" class="meta-paid-places">
                        {{ $availableForms[$firstAvailable]['paid_places'] ?? ($paidPlaces ?: 'Уточняется') }}
                    </span>
                    <span class="tuition-row">
                        <span class="meta-cost-wrap">
                            <span class="meta-cost-label">год:</span>
                            <span class="meta-cost">
                                @if(($availableForms[$firstAvailable]['cost'] ?? 0) > 0)
                                {{ number_format($availableForms[$firstAvailable]['cost'], 0, ',', ' ') }} ₽
                                @else
                                Бюджет
                                @endif
                            </span>
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
    document.addEventListener('DOMContentLoaded', function() {
        const order = ['очная', 'заочная', 'очно-заочная'];

        document.querySelectorAll('.specialty-card').forEach(function(card) {
            const avail = JSON.parse(card.getAttribute('data-available-forms') || '{}');
            const durRaw = card.getAttribute('data-duration') || '';
            const meta = card.querySelector('.specialty-meta');
            const toggles = card.querySelectorAll('.study-form-option');

            let curIdx = order.indexOf(
                card.querySelector('.study-form-option.active')?.getAttribute('data-value') ?? order[0]
            );
            let animating = false;

            /* ── обновить значения ячеек ── */
            function applyData(form) {
                const d = avail[form] || {};
                const cost = d.cost || 0;
                const dur = d.duration || durRaw;

                const set = (cls, val) => {
                    const el = meta.querySelector('.' + cls);
                    if (el) el.textContent = val;
                };

                set('meta-duration', dur);
                set('meta-qualification', d.qualification || '');
                set('meta-budget-places', d.budget_places != null ? d.budget_places : '');
                set('meta-paid-places', d.paid_places != null ? d.paid_places : 'Уточняется');
                set('meta-cost', cost > 0 ? cost.toLocaleString('ru-RU') + ' ₽' : 'Бюджет');

                /* лейбл "год:" прячем если бюджет */
                const costLabel = meta.querySelector('.meta-cost-label');
                if (costLabel) costLabel.style.display = cost > 0 ? '' : 'none';

                let years = 4;
                if (dur.includes('3 год')) years = 3.8;
                if (dur.includes('2 год')) years = 2.8;
                set('meta-total-cost', cost > 0 ? Math.round(cost * years).toLocaleString('ru-RU') + ' ₽' : '—');
            }

            /* ── слайд-анимация ── */
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
                    }, 380);
                }, 270);
            }

            
            const active = card.querySelector('.study-form-option.active');
            if (active) applyData(active.getAttribute('data-value'));

            /* клики */
            toggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    if (this.classList.contains('disabled') || this.classList.contains('active')) return;

                    const newForm = this.getAttribute('data-value');
                    const newIdx = order.indexOf(newForm);
                    const fwd = newIdx > curIdx;
                    curIdx = newIdx;

                    toggles.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    slide(newForm, fwd);
                });
            });
        });
    });
</script>
@endsection