@extends('layouts.main')


@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700&family=Mulish:wght@300;400;500;600;700&display=swap');

    :root {
        --accent: #FF5A30;
        --accent-soft: rgba(255, 90, 48, .08);
        --accent-mid: rgba(255, 90, 48, .15);
        --dark: #1A1B1F;
        --mid: #424551;
        --muted: #9A9CA5;
        --line: #ECEDF0;
        --bg: #F6F7F9;
        --white: #fff;
        --radius: 16px;
        --shadow: 0 4px 24px rgba(0, 0, 0, .07);
    }

    body {
        background: var(--bg);
        font-family: 'Mulish', sans-serif;
    }

    /* ── Page layout ── */
    .vp-wrap {
        max-width: 860px;
        margin: 48px auto;
        padding: 0 20px 60px;
        animation: fadeUp .5s ease both;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── Back link ── */
    .vp-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 700;
        color: var(--muted);
        text-decoration: none;
        margin-bottom: 24px;
        transition: color .2s;
    }

    .vp-back:hover {
        color: var(--accent);
    }

    /* ── Hero card ── */
    .vp-hero {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--line);
        box-shadow: var(--shadow);
        padding: 36px 40px 32px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .vp-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent), #ff8a65);
    }

    .vp-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
    }

    .vp-num {
        font-family: 'Unbounded', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: var(--muted);
        letter-spacing: .06em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .vp-name {
        font-family: 'Unbounded', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: var(--dark);
        line-height: 1.2;
        margin: 0 0 6px;
    }

    .vp-specialty {
        font-size: 15px;
        color: var(--mid);
        font-weight: 500;
    }

    /* Status badge */
    .vp-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid;
        flex-shrink: 0;
    }

    .vp-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .st-pending {
        background: #FFF7ED;
        color: #C2410C;
        border-color: #FED7AA;
    }

    .st-pending .vp-status-dot {
        background: #F97316;
    }

    .st-review {
        background: #EFF6FF;
        color: #1D4ED8;
        border-color: #BFDBFE;
    }

    .st-review .vp-status-dot {
        background: #3B82F6;
    }

    .st-checked {
        background: #F0FDF4;
        color: #15803D;
        border-color: #BBF7D0;
    }

    .st-checked .vp-status-dot {
        background: #22C55E;
    }

    .st-approved {
        background: #DCFCE7;
        color: #166534;
        border-color: #86EFAC;
    }

    .st-approved .vp-status-dot {
        background: #16A34A;
    }

    .st-rejected {
        background: #FEF2F2;
        color: #B91C1C;
        border-color: #FECACA;
    }

    .st-rejected .vp-status-dot {
        background: #EF4444;
    }

    .vp-hero-meta {
        display: flex;
        gap: 24px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--line);
        flex-wrap: wrap;
    }

    .vp-meta-item {
        text-transform: capitalize;
        font-size: 16px;
        ;
        color: var(--muted);
    }

    .vp-meta-item strong {
        display: block;
        font-size: 14px;
        color: var(--mid);
        font-weight: 600;
        margin-top: 2px;
    }

    /* ── Grid cards ── */
    .vp-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    @media (max-width: 620px) {
        .vp-grid {
            grid-template-columns: 1fr;
        }
    }

    .vp-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--line);
        box-shadow: var(--shadow);
        padding: 28px 32px;
        animation: fadeUp .5s ease both;
    }

    .vp-card:nth-child(2) {
        animation-delay: .07s;
    }

    .vp-card:nth-child(3) {
        animation-delay: .14s;
    }

    .vp-card-title {
        font-family: 'Unbounded', sans-serif;
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .08em;
        margin: 0 0 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .vp-card-title svg {
        color: var(--accent);
        flex-shrink: 0;
    }

    .vp-fields {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .vp-field label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 3px;
    }

    .vp-field div {
        font-size: 15px;
        color: var(--dark);
        font-weight: 500;
    }

    /* ── Rating card full-width ── */
    .vp-card-full {
        grid-column: 1 / -1;
    }

    .vp-scores {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }

    @media (max-width: 620px) {
        .vp-scores {
            grid-template-columns: 1fr 1fr;
        }
    }

    .vp-score-box {
        background: var(--bg);
        border-radius: 12px;
        padding: 16px 18px;
        text-align: center;
        border: 1px solid var(--line);
    }

    .vp-score-val {
        font-family: 'Unbounded', sans-serif;
        font-size: 28px;
        font-weight: 700;
        color: var(--dark);
        line-height: 1;
        margin-bottom: 6px;
    }

    .vp-score-val.accent {
        color: var(--accent);
    }

    .vp-score-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    /* ── Benefits ── */
    .vp-benefits {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 4px;
    }

    .vp-benefit-tag {
        background: #EDE9FE;
        color: #5B21B6;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid #DDD6FE;
    }

    /* ── Files ── */
    .vp-files {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 4px;
    }

    .vp-file-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--accent-soft);
        color: var(--accent);
        font-size: 13px;
        font-weight: 700;
        padding: 7px 14px;
        border-radius: 8px;
        border: 1px solid var(--accent-mid);
        text-decoration: none;
        transition: all .2s;
    }

    .vp-file-link:hover {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }

    /* ── Verification notes ── */
    .vp-notes {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 10px;
        padding: 14px 18px;
        font-size: 14px;
        color: #92400E;
        margin-top: 16px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .vp-notes svg {
        flex-shrink: 0;
        margin-top: 1px;
    }
</style>
@endpush

@section('content')
<div class="vp-wrap">

    <a href="{{ url()->previous() }}" class="vp-back">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Назад
    </a>

    @php
    $statusKey = match($application->status) {
    'Требует подтверждения' => 'pending',
    'На рассмотрении' => 'review',
    'Проверено' => 'checked',
    'Одобрено' => 'approved',
    'Отклонено' => 'rejected',
    default => 'pending',
    };
    $benefits = $application->benefits
    ? (is_array($application->benefits) ? $application->benefits : json_decode($application->benefits, true))
    : [];
    $proofFiles = $application->benefit_proof
    ? (is_array($application->benefit_proof) ? $application->benefit_proof : json_decode($application->benefit_proof, true))
    : [];
    $benefitLabels = [
    'orphan' => 'Дети-сироты',
    'disabled' => 'Инвалидность',
    'veteran' => 'Ветеран / дети ветеранов',
    'low_income' => 'Малоимущая семья',
    'other' => 'Иная льгота',
    ];
    @endphp

    {{-- Hero --}}
    <div class="vp-hero">
        <div class="vp-hero-top">
            <div>
                <div class="vp-num">Заявление № {{ $application->id }}</div>
                <h1 class="vp-name">{{ $application->full_name }}</h1>
                <div class="vp-specialty">{{ $application->specialty->name }}</div>
            </div>
            <span class="vp-status st-{{ $statusKey }}">
                <span class="vp-status-dot"></span>
                {{ $application->status }}
            </span>
        </div>
        <div class="vp-hero-meta">
            <div class="vp-meta-item">
                Дата подачи
                <strong>{{ $application->created_at->format('d.m.Y, H:i') }}</strong>
            </div>
            <div class="vp-meta-item">
                Форма обучения
                <strong>{{ $application->study_form ?? '—' }}</strong>
            </div>
            @if($application->is_verified)
            <div class="vp-meta-item">
                Проверено
                <strong>{{ $application->verified_at?->format('d.m.Y') ?? '—' }}</strong>
            </div>
            @endif
            <div class="vp-meta-item">
                Рейтинг
                <strong style="color:var(--accent);">{{ $application->rating }}</strong>
            </div>
        </div>
    </div>

    {{-- Scores --}}
    <div class="vp-grid">
        <div class="vp-card vp-card-full">
            <div class="vp-card-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                </svg>
                Рейтинговые показатели
            </div>
            <div class="vp-scores">
                <div class="vp-score-box">
                    <div class="vp-score-val">{{ $application->ege_score }}</div>
                    <div class="vp-score-label">Баллы ЕГЭ</div>
                </div>
                <div class="vp-score-box">
                    <div class="vp-score-val">{{ number_format($application->certificate_score, 2) }}</div>
                    <div class="vp-score-label">Аттестат</div>
                </div>
                <div class="vp-score-box">
                    <div class="vp-score-val" style="font-size:20px; color:#ff5a30;">{{ $application->has_achievements ? '✓' : '—' }}</div>
                    <div class="vp-score-label">Достижения</div>
                </div>
            </div>

            @if($application->certificate_file)
            <div class="vp-field">
                <label>Скан аттестата</label>
                <div class="vp-files" style="margin-top:8px;">
                    <a href="{{ route('applications.certificate', $application) }}" target="_blank" class="vp-file-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        Открыть аттестат
                    </a>
                </div>
            </div>
            @endif

            @if(!empty($benefits))
            <div class="vp-field" style="margin-top:16px;">
                <label>Льготные категории</label>
                <div class="vp-benefits">
                    @foreach($benefits as $b)
                    <span class="vp-benefit-tag">{{ $benefitLabels[$b] ?? $b }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($proofFiles))
            <div class="vp-field" style="margin-top:16px;">
                <label>Документы льготы</label>
                <div class="vp-files">
                    @foreach($proofFiles as $i => $path)
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($path) }}" target="_blank" class="vp-file-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        Документ {{ $i + 1 }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($application->verification_notes)
            <div class="vp-notes">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div><strong>Замечания проверяющего:</strong> {{ $application->verification_notes }}</div>
            </div>
            @endif
        </div>

        {{-- Personal --}}
        <div class="vp-card" style="animation-delay:.1s;">
            <div class="vp-card-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                Личные данные
            </div>
            <div class="vp-fields">
                <div class="vp-field">
                    <label>ФИО</label>
                    <div>{{ $application->full_name }}</div>
                </div>
                <div class="vp-field">
                    <label>Дата рождения</label>
                    <div>{{ $application->birthdate?->format('d.m.Y') ?? '—' }}</div>
                </div>
                <div class="vp-field">
                    <label>Телефон</label>
                    <div>{{ $application->phone }}</div>
                </div>
                <div class="vp-field">
                    <label>Email</label>
                    <div>{{ $application->email }}</div>
                </div>
            </div>
        </div>

        {{-- Address + Education --}}
        <div class="vp-card" style="animation-delay:.17s;">
            <div class="vp-card-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Адрес и образование
            </div>
            <div class="vp-fields">
                <div class="vp-field">
                    <label>Адрес</label>
                    <div>{{ $application->street }}, д. {{ $application->house }}</div>
                </div>
                <div class="vp-field">
                    <label>Учебное заведение</label>
                    <div>{{ $application->school }}</div>
                </div>
                <div class="vp-field">
                    <label>Год окончания</label>
                    <div>{{ $application->graduation_year }}</div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
