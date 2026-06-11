@extends('layouts.main')

@section('title', 'Списки на поступление')

@section('content')
<link rel="stylesheet" href="{{ asset('css/lkapp.css') . '?v=' . (file_exists(public_path('css/lkapp.css')) ? filemtime(public_path('css/lkapp.css')) : time()) }}">

{{-- NAV --}}
<nav class="lk-nav">
    <div class="lk-nav__inner">
        <a href="{{ route('applications.create') }}"
            class="lk-nav__link {{ request()->routeIs('applications.create') ? 'is-active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Подать заявку
        </a>
        <a href="{{ route('applications.index') }}"
            class="lk-nav__link {{ request()->routeIs('applications.index') ? 'is-active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
            </svg>
            Мои заявки
        </a>
        <a href="{{ route('applications.enrollment') }}"
            class="lk-nav__link {{ request()->routeIs('applications.enrollment') ? 'is-active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 20h9" />
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
            </svg>
            Списки на поступление
        </a>
        <a href="{{ route('profile.edit') }}"
            class="lk-nav__link {{ request()->routeIs('profile.edit') ? 'is-active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            Мой профиль
        </a>
    </div>
</nav>

<main class="lk-main">
    <div class="lk-container">

        <div class="er-header">
            <div>
                <h1 class="er-title">Рейтинги по специальностям</h1>
                <p class="er-sub">Таблицы всех поданных заявлений, отсортированные по баллу. Выделены ваши заявки.</p>
            </div>
            <div class="er-header-btns">
                <form action="{{ route('applications.recalculate-ratings') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="er-btn er-btn--primary">🔄 Обновить рейтинги</button>
                </form>
                <button class="er-btn er-btn--ghost" onclick="toggleAll(true)">↓ Развернуть все</button>
                <button class="er-btn er-btn--ghost" onclick="toggleAll(false)">↑ Свернуть все</button>
            </div>
        </div>

        {{-- ФИЛЬТРЫ --}}
        <div class="fcard">
            <div class="fgrid">
                <div class="fg fg-2">
                    <label class="flabel">Поиск по ФИО</label>
                    <input type="text" id="f-search" placeholder="Иванов..." oninput="applyFilters()" class="finput">
                </div>
                <div class="fg fg-2">
                    <label class="flabel">Специальность</label>
                    <div class="csel-wrap">
                        <div class="csel" onclick="toggleCsel(this)">
                            <span class="csel__val">Все специальности</span>
                            <svg class="csel__arr" width="12" height="8" viewBox="0 0 12 8">
                                <path d="M1 1l5 5 5-5" stroke="#999" stroke-width="1.8" fill="none" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="csel__drop">
                            <div class="csel__opt csel__opt--active" data-val="">Все специальности</div>
                            @foreach($specialties as $specialty)
                            <div class="csel__opt" data-val="{{ $specialty->id }}">{{ $specialty->name }}</div>
                            @endforeach
                        </div>
                        <input type="hidden" id="f-specialty" value="">
                    </div>
                </div>
                <div class="fg">
                    <label class="flabel">Тип места</label>
                    <div class="csel-wrap">
                        <div class="csel" onclick="toggleCsel(this)">
                            <span class="csel__val">Все</span>
                            <svg class="csel__arr" width="12" height="8" viewBox="0 0 12 8">
                                <path d="M1 1l5 5 5-5" stroke="#999" stroke-width="1.8" fill="none" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="csel__drop">
                            <div class="csel__opt csel__opt--active" data-val="">Все</div>
                            <div class="csel__opt" data-val="Бюджет" data-color="approved">Бюджет</div>
                            <div class="csel__opt" data-val="Платно" data-color="review">Платно</div>
                            <div class="csel__opt" data-val="Вне мест" data-color="rejected">Вне мест</div>
                        </div>
                        <input type="hidden" id="f-type" value="">
                    </div>
                </div>
                <div class="fg">
                    <label class="flabel">ЕГЭ от</label>
                    <input type="number" id="f-ege-min" min="0" max="300" placeholder="0" oninput="applyFilters()" class="finput">
                </div>
                <div class="fg">
                    <label class="flabel">Аттестат от</label>
                    <input type="number" id="f-cert-min" min="3" max="5" step="0.1" placeholder="3.0" oninput="applyFilters()" class="finput">
                </div>
                <div class="fg fg-end">
                    <button onclick="resetFilters()" class="btn-reset">✕ Сбросить</button>
                </div>
            </div>
            <div class="fcount">Показано заявлений: <strong id="totalVisible">0</strong></div>
        </div>

        {{-- БЛОКИ СПЕЦИАЛЬНОСТЕЙ --}}
        @foreach($specialties as $specialty)
        @php
        $budget = (int) ($specialty->budget_places ?? 0);
        $total = (int) ($specialty->total_places ?? $budget);
        $apps = $specialty->applications;
        @endphp

        <div class="spec-block" data-specialty-id="{{ $specialty->id }}">

            {{-- Заголовок — кликабельный --}}
            <div class="spec-block__head" onclick="toggleBlock(this)">
                <div class="spec-block__head-left">
                    <span class="spec-block__chevron">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="spec-block__name">{{ $specialty->name }}</span>
                </div>
                <div class="spec-block__meta">
                    <span class="spec-meta-chip spec-meta-chip--green">Бюджет: <strong>{{ $budget }}</strong></span>
                    <span class="spec-meta-chip">Мест: <strong>{{ $total }}</strong></span>
                    <span class="spec-meta-chip">Заявлений: <strong class="spec-visible-count">{{ $apps->count() }}</strong></span>
                </div>
            </div>

            {{-- Тело таблицы --}}
            <div class="spec-block__body">
                @if($apps->count() > 0)
                <div class="spec-table-wrap">
                    <table class="spec-table">
                        <thead>
                            <tr>
                                <th class="th-pos">Место</th>
                                <th>ФИО</th>
                                <th class="th-num">Рейтинг</th>
                                <th class="th-num">Аттестат</th>
                                <th class="th-num">ЕГЭ</th>
                                <th class="th-type">Тип</th>
                                <th class="th-status">Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($apps as $i => $app)
                            @php
                            $position = $i + 1;
                            $type = $position <= $budget
                                ? 'Бюджет'
                                : ($position <=$total ? 'Платно' : 'Вне мест' );
                                $typeBadge=$type==='Бюджет' ? 'approved'
                                : ($type==='Платно' ? 'review' : 'rejected' );
                                $statusBadge=match ($app->status) {
                                'Требует подтверждения' => 'pending',
                                'На рассмотрении' => 'review',
                                'Проверено' => 'checked',
                                'Одобрено' => 'approved',
                                'Отклонено' => 'rejected',
                                default => 'pending',
                                };
                                $fullNameParts = array_filter([
                                    $app->user->surname ?? null,
                                    $app->user->name ?? null,
                                    $app->user->patronymic ?? null,
                                    $app->full_name ?? null,
                                ]);
                                $fullName = strtolower(trim(implode(' ', $fullNameParts)));
                                $isMe = $app->user_id === $user->id;
                                @endphp
                                <tr class="rating-row {{ $isMe ? 'row-me' : '' }}"
                                    data-name="{{ $fullName }}"
                                    data-specialty="{{ $specialty->id }}"
                                    data-type="{{ $type }}"
                                    data-status="{{ $app->status }}"
                                    data-ege="{{ $app->ege_score ?? 0 }}"
                                    data-cert="{{ $app->certificate_score }}">
                                    <td class="td-pos">
                                        @if($position === 1) <span class="medal">🥇</span>
                                        @elseif($position === 2) <span class="medal">🥈</span>
                                        @elseif($position === 3) <span class="medal">🥉</span>
                                        @else <span class="pos-num">{{ $position }}</span>
                                        @endif
                                    </td>
                                    <td class="td-name">
                                        {{ $app->user->surname ? $app->user->surname . ' ' : '' }}
                                        {{ $app->user->name }}
                                        {{ $app->user->patronymic ?? '' }}
                                        @if($isMe) <span class="me-badge">Это вы</span> @endif
                                    </td>
                                    <td class="td-num" style="color: #2D7A4F; font-weight: 800;">{{ $app->rating }}</td>
                                    <td class="td-num">{{ $app->certificate_score }}</td>
                                    <td class="td-num">{{ $app->ege_score ?? '—' }}</td>
                                    <td><span class="sbadge sbadge--{{ $typeBadge }}">{{ $type }}</span></td>
                                    <td><span class="sbadge sbadge--{{ $statusBadge }}">{{ $app->status }}</span></td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="spec-empty">Заявлений пока нет</div>
                @endif
            </div>
        </div>
        @endforeach

        <div id="noResults" style="display:none;" class="no-results">Ничего не найдено по выбранным фильтрам</div>
    </div>
</main>

@push('styles')
<style>
    /* Header */
    .er-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .er-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--lk-text);
        margin: 0 0 4px;
    }

    .er-sub {
        font-size: 14px;
        color: var(--lk-muted);
        margin: 0;
    }

    .er-header-btns {
        display: flex;
        gap: 8px;
    }

    .er-btn {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all .15s;
    }

    .er-btn--ghost {
        background: var(--lk-white);
        color: var(--lk-muted);
        border: 1px solid var(--lk-border);
    }

    .er-btn--ghost:hover {
        background: var(--lk-accent-soft);
        color: var(--lk-accent);
        border-color: var(--lk-accent);
    }

    .er-btn--primary {
        background: var(--lk-accent);
        color: #fff;
        border: 1px solid var(--lk-accent);
    }

    .er-btn--primary:hover {
        background: #1e683e;
    }

    /* Filters */
    .fcard {
        background: #fff;
        padding: 18px 20px;
        border-radius: 12px;
        border: 1px solid var(--lk-border);
        box-shadow: var(--lk-shadow-sm);
        margin-bottom: 24px;
    }

    .fgrid {
        display: grid;
        grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1fr auto;
        gap: 12px;
        align-items: flex-end;
    }

    .fg-2 {
        grid-column: span 1;
    }

    .fg-end {
        display: flex;
        align-items: flex-end;
    }

    .flabel {
        font-size: 11px;
        font-weight: 700;
        color: var(--lk-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
        display: block;
        margin-bottom: 5px;
    }

    .finput {
        width: 100%;
        padding: 8px 14px;
        border: 1px solid var(--lk-border);
        border-radius: 20px;
        font-size: 13px;
        background: #fff;
        transition: border-color .15s;
        box-sizing: border-box;
    }

    .finput:focus {
        outline: none;
        border-color: var(--lk-accent);
        box-shadow: 0 0 0 3px rgba(45, 122, 79, .1);
    }

    .btn-reset {
        padding: 8px 16px;
        background: var(--lk-white);
        border: 1px solid var(--lk-border);
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: var(--lk-muted);
        cursor: pointer;
        white-space: nowrap;
        width: 100%;
    }

    .btn-reset:hover {
        background: var(--lk-accent-soft);
        color: var(--lk-accent);
    }

    .fcount {
        margin-top: 10px;
        font-size: 13px;
        color: var(--lk-muted);
    }

    .fcount strong {
        color: var(--lk-accent);
    }

    /* Custom select */
    .csel-wrap {
        position: relative;
    }

    .csel {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 14px;
        border: 1px solid var(--lk-border);
        border-radius: 20px;
        font-size: 13px;
        background: #fff;
        cursor: pointer;
        user-select: none;
        transition: border-color .15s, box-shadow .15s;
        min-height: 36px;
    }

    .csel:hover {
        border-color: #ccc;
    }

    .csel.open {
        border-color: var(--lk-accent);
        box-shadow: 0 0 0 3px rgba(45, 122, 79, .1);
    }

    .csel__val {
        color: #333;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .csel__arr {
        flex-shrink: 0;
        margin-left: 8px;
        transition: transform .2s;
    }

    .csel.open .csel__arr {
        transform: rotate(180deg);
    }

    .csel__drop {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid var(--lk-border);
        border-radius: 14px;
        box-shadow: var(--lk-shadow-md);
        z-index: 200;
        padding: 6px;
        max-height: 240px;
        overflow-y: auto;
    }

    .csel__drop.open {
        display: block;
    }

    .csel__opt {
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 13px;
        cursor: pointer;
        color: #333;
        transition: background .12s;
    }

    .csel__opt:hover {
        background: var(--lk-accent-soft);
    }

    .csel__opt--active {
        background: var(--lk-accent-soft);
        color: var(--lk-accent);
        font-weight: 700;
    }

    /* Specialty block */
    .spec-block {
        background: #fff;
        border: 1px solid var(--lk-border);
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
        box-shadow: var(--lk-shadow-sm);
        transition: box-shadow .15s;
    }

    .spec-block:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, .07);
    }

    .spec-block__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        cursor: pointer;
        user-select: none;
        border-bottom: 1px solid var(--lk-border);
        transition: background .12s;
        flex-wrap: wrap;
        gap: 10px;
    }

    .spec-block__head:hover {
        background: #FAFBFC;
    }

    .spec-block.collapsed .spec-block__head {
        border-bottom: none;
    }

    .spec-block__head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .spec-block__chevron {
        color: #aaa;
        transition: transform .25s;
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .spec-block.collapsed .spec-block__chevron {
        transform: rotate(-90deg);
    }

    .spec-block__name {
        font-size: 16px;
        font-weight: 700;
        color: var(--lk-text);
    }

    .spec-block__meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .spec-meta-chip {
        font-size: 12px;
        font-weight: 600;
        color: var(--lk-muted);
        background: var(--lk-bg);
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid var(--lk-border);
        white-space: nowrap;
    }

    .spec-meta-chip strong {
        color: var(--lk-accent);
    }

    /* Body */
    .spec-block__body {
        overflow: hidden;
        transition: max-height .3s ease, opacity .3s ease;
        max-height: 2000px;
        opacity: 1;
    }

    .spec-block.collapsed .spec-block__body {
        max-height: 0;
        opacity: 0;
        pointer-events: none;
    }

    /* Table */
    .spec-table-wrap {
        overflow-x: auto;
    }

    .spec-table {
        width: 100%;
        border-collapse: collapse;
    }

    .spec-table thead tr {
        background: var(--lk-bg);
        border-bottom: 1px solid var(--lk-border);
    }

    .spec-table th {
        padding: 10px 16px;
        font-size: 11px;
        font-weight: 700;
        color: var(--lk-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
        text-align: center;
        white-space: nowrap;
    }

    .spec-table td {
        padding: 11px 16px;
        border-bottom: 1px solid var(--lk-bg);
        vertical-align: middle;
        text-align: center;
    }

    .spec-table tbody tr:hover {
        background: #FAFBFC;
    }

    .td-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--lk-text);
        text-align: left;
    }

    .th-num, .td-num {
        width: 100px;
        font-size: 15px;
        font-weight: 700;
        color: var(--lk-text);
    }

    .medal {
        font-size: 20px;
    }

    .pos-num {
        font-size: 14px;
        font-weight: 700;
        color: #888;
    }

    /* Row highlight for me */
    .row-me {
        background: var(--lk-accent-soft) !important;
    }
    .row-me td {
        border-bottom-color: rgba(45, 122, 79, 0.1) !important;
    }
    .me-badge {
        display: inline-block;
        padding: 2px 8px;
        background: var(--lk-accent);
        color: #fff;
        font-size: 10px;
        border-radius: 4px;
        margin-left: 6px;
        text-transform: uppercase;
        vertical-align: middle;
    }

    /* Badges */
    .sbadge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .sbadge--pending { background: #FFF7ED; color: #C2410C; border: 1px solid #FED7AA; }
    .sbadge--review { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .sbadge--checked { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .sbadge--approved { background: #DCFCE7; color: #166534; border: 1px solid #86EFAC; }
    .sbadge--rejected { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }

    .spec-empty {
        padding: 16px 20px;
        color: var(--lk-muted);
        font-size: 14px;
    }

    .no-results {
        text-align: center;
        padding: 60px;
        color: var(--lk-muted);
        font-size: 16px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--lk-border);
        margin-top: 16px;
    }
</style>
@endpush

@push('scripts')
<script>
    // ── Custom selects ────────────────────────────
    function toggleCsel(el) {
        const wrap = el.closest('.csel-wrap');
        const drop = wrap.querySelector('.csel__drop');
        const isOpen = drop.classList.contains('open');
        document.querySelectorAll('.csel__drop.open').forEach(d => {
            d.classList.remove('open');
            d.closest('.csel-wrap').querySelector('.csel').classList.remove('open');
        });
        if (!isOpen) {
            drop.classList.add('open');
            el.classList.add('open');
        }
    }

    function selectCselOpt(opt) {
        const drop = opt.closest('.csel__drop');
        const wrap = opt.closest('.csel-wrap');
        drop.querySelectorAll('.csel__opt').forEach(o => o.classList.remove('csel__opt--active'));
        opt.classList.add('csel__opt--active');
        wrap.querySelector('.csel__val').textContent = opt.textContent.trim();
        wrap.querySelector('input[type="hidden"]').value = opt.dataset.val;
        drop.classList.remove('open');
        wrap.querySelector('.csel').classList.remove('open');
        applyFilters();
    }
    document.addEventListener('click', e => {
        if (!e.target.closest('.csel-wrap')) {
            document.querySelectorAll('.csel__drop.open').forEach(d => {
                d.classList.remove('open');
                d.closest('.csel-wrap').querySelector('.csel').classList.remove('open');
            });
        }
    });
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.csel__opt').forEach(opt => {
            opt.addEventListener('click', e => {
                e.stopPropagation();
                selectCselOpt(opt);
            });
        });
    });

    // ── Collapse / Expand ─────────────────────────
    function toggleBlock(headEl) {
        const block = headEl.closest('.spec-block');
        block.classList.toggle('collapsed');
    }

    function toggleAll(expand) {
        document.querySelectorAll('.spec-block').forEach(b => {
            if (expand) b.classList.remove('collapsed');
            else b.classList.add('collapsed');
        });
    }

    // ── Filters ───────────────────────────────────
    function applyFilters() {
        const search = document.getElementById('f-search').value.toLowerCase().trim();
        const specialtyF = document.getElementById('f-specialty').value;
        const typeF = document.getElementById('f-type').value;
        const egeMinVal = document.getElementById('f-ege-min').value;
        const certMinVal = document.getElementById('f-cert-min').value;
        const egeMin = egeMinVal !== '' ? parseFloat(egeMinVal) : 0;
        const certMin = certMinVal !== '' ? parseFloat(certMinVal) : 3;

        let totalVisible = 0;

        document.querySelectorAll('.spec-block').forEach(block => {
            const specId = block.dataset.specialtyId;
            const rows = block.querySelectorAll('.rating-row');
            let blockVisible = 0;

            if (specialtyF && specId !== specialtyF) {
                block.style.display = 'none';
                return;
            }
            block.style.display = '';

            rows.forEach(row => {
                const match =
                    (!search || row.dataset.name.includes(search)) &&
                    (!typeF || row.dataset.type === typeF) &&
                    (parseFloat(row.dataset.ege || 0) >= egeMin) &&
                    (parseFloat(row.dataset.cert || 0) >= certMin);

                row.style.display = match ? '' : 'none';
                if (match) {
                    blockVisible++;
                    totalVisible++;
                }
            });

            const counter = block.querySelector('.spec-visible-count');
            if (counter) counter.textContent = blockVisible;

            // Auto-expand block if filter shows results in it
            if (blockVisible > 0 && (search || typeF || egeMinVal || certMinVal)) {
                block.classList.remove('collapsed');
            }
        });

        document.getElementById('totalVisible').textContent = totalVisible;
        document.getElementById('noResults').style.display = totalVisible === 0 ? 'block' : 'none';
    }

    function resetFilters() {
        document.getElementById('f-search').value = '';
        ['f-ege-min', 'f-cert-min'].forEach(id => document.getElementById(id).value = '');
        document.querySelectorAll('.csel-wrap').forEach(wrap => {
            const first = wrap.querySelector('.csel__opt');
            if (first) selectCselOpt(first);
        });
        applyFilters();
    }

    document.addEventListener('DOMContentLoaded', applyFilters);
</script>
@endpush
@endsection
