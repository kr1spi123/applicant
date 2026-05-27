@extends('layouts.main')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@500&display=swap');

    :root {
        --a: #2D7A4F;
        --a-bg: rgba(45, 122, 79, .08);
        --a-ring: rgba(45, 122, 79, .18);
        --ink: #0F1014;
        --ink-2: #3B3D4A;
        --ink-3: #72747F;
        --ink-4: #A8AAB4;
        --s0: #FFFFFF;
        --s1: #F5F5F8;
        --s2: #EEEFF3;
        --bd: rgba(0, 0, 0, .07);
        --bd2: rgba(0, 0, 0, .11);
        --bg: #EAEBF0;
        --r: 16px;
        --r-s: 10px;
        --r-xs: 6px;
        --sh: 0 1px 3px rgba(0, 0, 0, .06), 0 6px 24px rgba(0, 0, 0, .07);
        --ease: cubic-bezier(.4, 0, .2, 1);
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background: var(--bg);
        font-family: 'Geist', 'Inter', system-ui, sans-serif;
        color: var(--ink);
        -webkit-font-smoothing: antialiased;
    }

    .vp {
        max-width: 920px;
        margin: 0 auto;
        padding: 36px 22px 80px;
        animation: up .4s var(--ease) both;
    }

    @keyframes up {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .vp-back {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 600;
        color: var(--ink-3);
        text-decoration: none;
        margin-bottom: 24px;
        letter-spacing: .01em;
        transition: color .15s;
    }

    .vp-back svg {
        transition: transform .15s var(--ease);
    }

    .vp-back:hover {
        color: var(--a);
    }

    .vp-back:hover svg {
        transform: translateX(-2px);
    }

    /* ═══ HERO ═══ */
    .vp-hero {
        background: var(--s0);
        border-radius: var(--r);
        border: 1px solid var(--bd);
        box-shadow: var(--sh);
        padding: 28px 32px 24px;
        margin-bottom: 12px;
        position: relative;
        overflow: hidden;
    }

    .vp-hero-stripe {
        position: absolute;
        inset: 0 0 auto;
        height: 3px;
        background: linear-gradient(90deg, #2D7A4F 0%, #FF8255 40%, #FFCAB8 100%);
    }

    .vp-hero-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .vp-hero-id {
        font-size: 11px;
        font-weight: 700;
        color: var(--a);
        letter-spacing: .1em;
        text-transform: uppercase;
        font-variant-numeric: tabular-nums;
        margin-bottom: 10px;
        background: var(--a-bg);
        display: inline-flex;
        padding: 3px 10px;
        border-radius: 5px;
        border: 1px solid var(--a-ring);
    }

    .vp-hero-name {
        font-size: 32px;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.04em;
        line-height: 1.1;
        margin-bottom: 6px;
    }

    .vp-hero-prog {
        font-size: 15px;
        font-weight: 500;
        color: var(--ink-2);
    }

    /* status */
    .status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .03em;
        border: 1.5px solid;
        flex-shrink: 0;
        white-space: nowrap;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .s-pending {
        background: #FFF8F2;
        color: #B45309;
        border-color: #FCD7A8;
    }

    .s-pending .status-dot {
        background: #F59E0B;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, .25);
    }

    .s-review {
        background: #F0F5FF;
        color: #2563EB;
        border-color: #BDD4FC;
    }

    .s-review .status-dot {
        background: #3B82F6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, .25);
    }

    .s-checked {
        background: #F0FDF5;
        color: #16803D;
        border-color: #BAEFD1;
    }

    .s-checked .status-dot {
        background: #22C55E;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, .25);
    }

    .s-approved {
        background: #EAFBF0;
        color: #166534;
        border-color: #86EFB0;
    }

    .s-approved .status-dot {
        background: #16A34A;
        box-shadow: 0 0 0 2px rgba(22, 163, 74, .25);
    }

    .s-rejected {
        background: #FFF1F2;
        color: #BE123C;
        border-color: #FBCCD4;
    }

    .s-rejected .status-dot {
        background: #F43F5E;
        box-shadow: 0 0 0 2px rgba(244, 63, 94, .25);
    }

    /* meta strip */
    .vp-meta {
        display: flex;
        flex-wrap: wrap;
        border-top: 1px solid var(--bd);
        padding-top: 18px;
    }

    .vp-meta-cell {
        display: flex;
        flex-direction: column;
        gap: 3px;
        padding-right: 24px;
        margin-right: 24px;
        border-right: 1px solid var(--bd);
    }

    .vp-meta-cell:last-child {
        border-right: none;
        padding-right: 0;
        margin-right: 0;
    }

    .vp-meta-l {
        font-size: 10px;
        font-weight: 600;
        color: var(--ink-4);
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .vp-meta-v {
        font-size: 14px;
        font-weight: 700;
        color: var(--ink);
        font-variant-numeric: tabular-nums;
    }

    /* ═══ GRID ═══ */
    .vp-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    @media(max-width:620px) {
        .vp-grid {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background: var(--s0);
        border-radius: var(--r);
        border: 1px solid var(--bd);
        box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
        padding: 22px 26px;
        animation: up .5s var(--ease) both;
    }

    .card--wide {
        grid-column: 1/-1;
    }

    .card:nth-child(2) {
        animation-delay: .06s;
    }

    .card:nth-child(3) {
        animation-delay: .11s;
    }

    .card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .card-title {
        font-size: 11px;
        font-weight: 700;
        color: var(--ink-3);
        text-transform: uppercase;
        letter-spacing: .1em;
    }

    /* score — НЕ кликабельный, без hover */
    .scores {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    @media(max-width:500px) {
        .scores {
            grid-template-columns: 1fr 1fr;
        }
    }

    .score {
        background: var(--s1);
        border: 1px solid var(--bd);
        border-radius: var(--r-s);
        padding: 16px 14px 14px;
        text-align: center;
        /* нет hover, нет cursor:pointer — это просто информация */
    }

    .score-n {
        font-size: 28px;
        font-weight: 700;
        color: var(--ink);
        line-height: 1;
        margin-bottom: 6px;
        letter-spacing: -.03em;
        font-variant-numeric: tabular-nums;
    }

    .score-n.hi {
        color: var(--a);
        font-size: 22px;
    }

    .score-l {
        font-size: 10px;
        font-weight: 600;
        color: var(--ink-4);
        text-transform: uppercase;
        letter-spacing: .07em;
    }

    /* divider */
    .vp-div {
        height: 1px;
        background: var(--s2);
        margin: 18px 0;
    }

    /* extras row — горизонтальная сетка */
    .extras-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0;
    }

    /* extra-cell — без border, без bg, просто колонки с разделителем */
    .extra-cell {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 0 20px 0 0;
        margin-right: 20px;
        border-right: 1px solid var(--bd);
    }

    .extra-cell:first-child {
        padding-left: 0;
    }

    .extra-cell:last-child {
        border-right: none;
        padding-right: 0;
        margin-right: 0;
    }

    .extra-cell-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--ink-4);
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    /* tags — просто текстовые метки, не кликабельные */
    .tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .tag {
        background: var(--s2);
        color: var(--ink-2);
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 5px;
        border: 1px solid var(--bd2);
        letter-spacing: .01em;
        /* нейтральный цвет — не похож на кнопку */
    }

    /* rows */
    .rows {
        display: flex;
        flex-direction: column;
    }

    .row {
        padding: 10px 0;
        border-bottom: 1px solid var(--s2);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .row:first-child {
        padding-top: 0;
    }

    .row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .row-l {
        font-size: 10px;
        font-weight: 600;
        color: var(--ink-4);
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .row-v {
        font-size: 13px;
        font-weight: 500;
        color: var(--ink);
        line-height: 1.4;
    }

    .row-v.mono {
        font-family: 'Geist Mono', 'Courier New', monospace;
        font-size: 12px;
        color: var(--ink-2);
    }

    /* file-btn — кликабельный, явный стиль ссылки */
    .files {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .file-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--a-bg);
        color: var(--a);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .02em;
        padding: 5px 11px;
        border-radius: var(--r-xs);
        border: 1px solid var(--a-ring);
        text-decoration: none;
        cursor: pointer;
        transition: background .15s, color .15s, border-color .15s;
    }

    .file-btn:hover {
        background: var(--a);
        color: #fff;
        border-color: var(--a);
    }

    .file-btn svg {
        flex-shrink: 0;
    }

    /* note */
    .note {
        display: flex;
        gap: 9px;
        align-items: flex-start;
        background: #FFFEF0;
        border: 1px solid #EFE0A0;
        border-radius: var(--r-s);
        padding: 12px 15px;
        font-size: 12px;
        line-height: 1.55;
        color: #7A4800;
        margin-top: 18px;
    }

    .note svg {
        flex-shrink: 0;
        margin-top: 1px;
        color: #CA9000;
    }

    @media(max-width:680px) {
        .vp-hero {
            padding: 22px 18px 18px;
        }

        .card {
            padding: 18px 16px;
        }

        .vp-meta-cell {
            padding-right: 16px;
            margin-right: 16px;
        }

        .vp-hero-name {
            font-size: 22px;
        }

        .extras-row {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .extra-cell {
            border-right: none;
            border-bottom: 1px solid var(--bd);
            padding: 0 0 14px;
            margin: 0;
        }

        .extra-cell:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="vp">

    <a href="{{ url()->previous() }}" class="vp-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Назад
    </a>

    @php
    $sk = match($application->status) {
    'Требует подтверждения' => 'pending',
    'На рассмотрении' => 'review',
    'Проверено' => 'checked',
    'Одобрено' => 'approved',
    'Отклонено' => 'rejected',
    default => 'pending',
    };
    $benefits = $application->benefits ? (is_array($application->benefits) ? $application->benefits : json_decode($application->benefits, true)) : [];
    $proofFiles = $application->benefit_proof ? (is_array($application->benefit_proof) ? $application->benefit_proof : json_decode($application->benefit_proof, true)) : [];
    $bl = ['orphan'=>'Дети-сироты','disabled'=>'Инвалидность','veteran'=>'Ветеран / дети ветеранов','low_income'=>'Малоимущая семья','other'=>'Иная льгота'];
    @endphp

    {{-- HERO --}}
    <div class="vp-hero">
        <div class="vp-hero-stripe"></div>

        <div class="vp-hero-top">
            <div>
                <div class="vp-hero-id">Заявление&nbsp;#{{ $application->id }}</div>
                <div class="vp-hero-name">{{ $application->full_name }}</div>
                <div class="vp-hero-prog">{{ $application->specialty->name }}</div>
            </div>
            <span class="status s-{{ $sk }}">
                <span class="status-dot"></span>{{ $application->status }}
            </span>
        </div>

        <div class="vp-meta">
            <div class="vp-meta-cell">
                <span class="vp-meta-l">Дата подачи</span>
                <span class="vp-meta-v">{{ $application->created_at->format('d.m.Y, H:i') }}</span>
            </div>
            <div class="vp-meta-cell">
                <span class="vp-meta-l">Форма обучения</span>
                <span class="vp-meta-v">{{ $application->study_form ?? '—' }}</span>
            </div>
            @if($application->is_verified)
            <div class="vp-meta-cell">
                <span class="vp-meta-l">Проверено</span>
                <span class="vp-meta-v">{{ $application->verified_at?->format('d.m.Y') ?? '—' }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- GRID --}}
    <div class="vp-grid">

        {{-- Scores --}}
        <div class="card card--wide">
            <div class="card-head">
                <span class="card-title">Рейтинговые показатели</span>
            </div>

            <div class="scores">
                <div class="score">
                    <div class="score-n">{{ $application->ege_score }}</div>
                    <div class="score-l">Баллы ЕГЭ</div>
                </div>
                <div class="score">
                    <div class="score-n">{{ number_format($application->certificate_score, 2) }}</div>
                    <div class="score-l">Аттестат</div>
                </div>
                <div class="score">
                    <div class="score-n hi">{{ $application->has_achievements ? '✓' : '—' }}</div>
                    <div class="score-l">Достижения</div>
                </div>
            </div>

            @if($application->certificate_file || !empty($benefits) || !empty($proofFiles))
            <div class="vp-div"></div>
            <div class="extras-row">

                @if($application->certificate_file)
                <div class="extra-cell">
                    <span class="extra-cell-label">Скан аттестата</span>
                    <div class="files">
                        <a href="{{ route('applications.certificate', $application) }}" target="_blank" class="file-btn">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            Открыть аттестат
                        </a>
                    </div>
                </div>
                @endif

                @if(!empty($benefits))
                <div class="extra-cell">
                    <span class="extra-cell-label">Льготные категории</span>
                    <div class="tags">
                        @foreach($benefits as $b)
                        <span class="tag">{{ $bl[$b] ?? $b }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($proofFiles))
                <div class="extra-cell">
                    <span class="extra-cell-label">Документы льготы</span>
                    <div class="files">
                        @foreach($proofFiles as $i => $path)
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($path) }}" target="_blank" class="file-btn">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            Документ {{ $i + 1 }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
            @endif

            @if($application->verification_notes)
            <div class="note">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div><strong>Замечания:</strong> {{ $application->verification_notes }}</div>
            </div>
            @endif
        </div>

        {{-- Personal --}}
        <div class="card" style="animation-delay:.07s">
            <div class="card-head"><span class="card-title">Личные данные</span></div>
            <div class="rows">
                <div class="row"><span class="row-l">ФИО</span><span class="row-v">{{ $application->full_name }}</span></div>
                <div class="row"><span class="row-l">Дата рождения</span><span class="row-v">{{ $application->birthdate?->format('d.m.Y') ?? '—' }}</span></div>
                <div class="row"><span class="row-l">Телефон</span><span class="row-v mono">{{ $application->phone }}</span></div>
                <div class="row"><span class="row-l">Email</span><span class="row-v mono">{{ $application->email }}</span></div>
            </div>
        </div>

        {{-- Address + Education --}}
        <div class="card" style="animation-delay:.12s">
            <div class="card-head"><span class="card-title">Адрес и образование</span></div>
            <div class="rows">
                <div class="row"><span class="row-l">Адрес</span><span class="row-v">{{ $application->street }}, д. {{ $application->house }}</span></div>
                <div class="row"><span class="row-l">Учебное заведение</span><span class="row-v">{{ $application->school }}</span></div>
                <div class="row"><span class="row-l">Год окончания</span><span class="row-v">{{ $application->graduation_year }}</span></div>
            </div>
        </div>

    </div>
</div>
@endsection