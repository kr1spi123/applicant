@extends('layouts.admin')

@section('title', 'Рейтинги по специальностям')

@section('content')
<div class="admin-main-wrap">

    <div class="er-header">
        <div>
            <h1 class="er-title">Рейтинги по специальностям</h1>
            <p class="er-sub">Таблицы заявлений, отсортированные по рейтингу. Верхние — бюджет.</p>
        </div>
        <div class="er-header-btns">
            <form action="{{ route('admin.recalculate-ratings') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="er-btn er-btn--primary">🔄 Пересчитать все рейтинги</button>
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
                <label class="flabel">Статус заявки</label>
                <div class="csel-wrap">
                    <div class="csel" onclick="toggleCsel(this)">
                        <span class="csel__val">Все</span>
                        <svg class="csel__arr" width="12" height="8" viewBox="0 0 12 8">
                            <path d="M1 1l5 5 5-5" stroke="#999" stroke-width="1.8" fill="none" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="csel__drop">
                        <div class="csel__opt csel__opt--active" data-val="">Все</div>
                        <div class="csel__opt" data-val="Требует подтверждения" data-color="pending">Требует подтверждения</div>
                        <div class="csel__opt" data-val="На рассмотрении" data-color="review">На рассмотрении</div>
                        <div class="csel__opt" data-val="Проверено" data-color="checked">Проверено</div>
                        <div class="csel__opt" data-val="Одобрено" data-color="approved">Одобрено</div>
                        <div class="csel__opt" data-val="Отклонено" data-color="rejected">Отклонено</div>
                    </div>
                    <input type="hidden" id="f-status" value="">
                </div>
            </div>
            <div class="fg">
                <label class="flabel">ЕГЭ от</label>
                <input type="number" id="f-ege-min" min="0" max="300" placeholder="0" oninput="applyFilters()" class="finput">
            </div>
            <div class="fg">
                <label class="flabel">ЕГЭ до</label>
                <input type="number" id="f-ege-max" min="0" max="300" placeholder="300" oninput="applyFilters()" class="finput">
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
                <span class="spec-meta-chip spec-meta-chip--green">Бюджет: {{ $budget }}</span>
                <span class="spec-meta-chip">Мест: {{ $total }}</span>
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
                            $fullName = strtolower(trim(implode(' ', array_filter([
                            $app->user->surname ?? null,
                            $app->user->name ?? null,
                            $app->full_name ?? null,
                            ]))));
                            @endphp
                            <tr class="rating-row"
                                data-name="{{ $fullName }}"
                                data-specialty="{{ $specialty->id }}"
                                data-type="{{ $type }}"
                                data-status="{{ $app->status }}"
                                data-ege="{{ $app->ege_score ?? 0 }}"
                                data-cert="{{ $app->certificate_score }}">
                                <td class="td-pos @if($position === 1) top-position-1 @elseif($position === 2) top-position-2 @elseif($position === 3) top-position-3 @endif">
                                        <span class="pos-num">{{ $position }}</span>
                                    </td>
                                <td class="td-name">{{ $app->user->name }} {{ $app->user->surname ?? '' }}</td>
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

@push('styles')
<style>
    .admin-main {
        max-width: 100% !important;
        padding: 24px 30px;
    }

    .admin-main-wrap {}

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
        color: #1E212C;
        margin: 0 0 4px;
    }

    .er-sub {
        font-size: 14px;
        color: #aaa;
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
        background: #F4F5F6;
        color: #555;
        border: 1px solid #E5E8ED;
    }

    .er-btn--ghost:hover {
        background: #E5E8ED;
    }

    .er-btn--primary {
        background: #2D7A4F;
        color: #fff;
        border: 1px solid #2D7A4F;
    }

    .er-btn--primary:hover {
        background: #1e683e;
    }

    /* Filters */
    .fcard {
        background: #fff;
        padding: 18px 20px;
        border-radius: 12px;
        border: 1px solid #E5E8ED;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        margin-bottom: 24px;
    }

    .fgrid {
        display: grid;
        grid-template-columns: 1.5fr 1.5fr 1fr 1fr 0.7fr 0.7fr 0.7fr auto;
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
        color: #666;
        text-transform: uppercase;
        letter-spacing: .05em;
        display: block;
        margin-bottom: 5px;
    }

    .finput {
        width: 100%;
        padding: 8px 14px;
        border: 1px solid #E5E8ED;
        border-radius: 20px;
        font-size: 13px;
        background: #fff;
        transition: border-color .15s;
        box-sizing: border-box;
    }

    .finput:focus {
        outline: none;
        border-color: #2D7A4F;
        box-shadow: 0 0 0 3px rgba(255, 90, 48, .1);
    }

    .btn-reset {
        padding: 8px 16px;
        background: #F4F5F6;
        border: 1px solid #E5E8ED;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: #555;
        cursor: pointer;
        white-space: nowrap;
        width: 100%;
    }

    .btn-reset:hover {
        background: #E5E8ED;
    }

    .fcount {
        margin-top: 10px;
        font-size: 13px;
        color: #888;
    }

    .fcount strong {
        color: #2D7A4F;
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
        border: 1px solid #E5E8ED;
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
        border-color: #2D7A4F;
        box-shadow: 0 0 0 3px rgba(255, 90, 48, .1);
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
        border: 1px solid #E5E8ED;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
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
        background: #F4F5F6;
    }

    .csel__opt--active {
        background: #FFF0EC;
        color: #2D7A4F;
        font-weight: 700;
    }

    .csel__opt[data-color="pending"]:hover,
    .csel__opt[data-color="pending"].csel__opt--active {
        background: #FFF7ED;
        color: #C2410C;
    }

    .csel__opt[data-color="review"]:hover,
    .csel__opt[data-color="review"].csel__opt--active {
        background: #EFF6FF;
        color: #1D4ED8;
    }

    .csel__opt[data-color="checked"]:hover,
    .csel__opt[data-color="checked"].csel__opt--active {
        background: #F0FDF4;
        color: #15803D;
    }

    .csel__opt[data-color="approved"]:hover,
    .csel__opt[data-color="approved"].csel__opt--active {
        background: #DCFCE7;
        color: #166534;
    }

    .csel__opt[data-color="rejected"]:hover,
    .csel__opt[data-color="rejected"].csel__opt--active {
        background: #FEF2F2;
        color: #B91C1C;
    }

    /* Specialty block */
    .spec-block {
        background: #fff;
        border: 1px solid #E5E8ED;
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .04);
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
        border-bottom: 1px solid #F0F1F3;
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
        color: #1E212C;
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
        color: #666;
        background: #F4F5F6;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid #EDEEF0;
        white-space: nowrap;
    }

    .spec-meta-chip--green strong {
        color: #15803D;
    }

    .spec-meta-chip strong {
        color: #2D7A4F;
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
        background: #F8F9FA;
        border-bottom: 1px solid #EDEEF0;
    }

    /* Центрируем все заголовки */
    .spec-table th {
        padding: 10px 16px;
        font-size: 11px;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: .05em;
        text-align: center;
        /* Было left */
        white-space: nowrap;
    }

    /* Центрируем все данные в ячейках */
    .spec-table td {
        padding: 11px 16px;
        border-bottom: 1px solid #F4F5F6;
        vertical-align: middle;
        text-align: center;
        /* Добавлено для центровки */
    }

    .spec-table tbody tr:last-child td {
        border-bottom: none;
    }

    .spec-table tbody tr:hover {
        background: #FAFBFC;
    }

    /* Уточняем ширину, если нужно, но выравнивание уже наследуется */
    .th-pos,
    .td-pos {
        width: 64px;
    }

    .th-num,
    .td-num {
        width: 100px;
        font-size: 15px;
        font-weight: 700;
        color: #1E212C;
    }

    .th-type {
        width: 100px;
    }

    .th-status {
        width: 190px;
    }

    .td-name {
        font-size: 14px;
        font-weight: 600;
        color: #1E212C;
        text-align: left;
    }

    .spec-table tbody tr:last-child td {
        border-bottom: none;
    }

    .spec-table tbody tr:hover {
        background: #FAFBFC;
    }

    .th-pos,
    .td-pos {
        width: 64px;
        text-align: center;
    }

    .th-num,
    .td-num {
        width: 100px;
        font-size: 15px;
        font-weight: 700;
        color: #1E212C;
    }

    .th-type {
        width: 100px;
    }

    .th-status {
        width: 190px;
    }

    .td-name {
        font-size: 14px;
        font-weight: 600;
        color: #1E212C;
    }

    .medal {
        font-size: 20px;
    }

    .pos-num {
        font-size: 14px;
        font-weight: 700;
        color: #888;
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

    .sbadge--pending {
        background: #FFF7ED;
        color: #C2410C;
        border: 1px solid #FED7AA;
    }

    .sbadge--review {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
    }

    .sbadge--checked {
        background: #F0FDF4;
        color: #15803D;
        border: 1px solid #BBF7D0;
    }

    .sbadge--approved {
        background: #DCFCE7;
        color: #166534;
        border: 1px solid #86EFAC;
    }

    .sbadge--rejected {
        background: #FEF2F2;
        color: #B91C1C;
        border: 1px solid #FECACA;
    }

    .spec-empty {
        padding: 16px 20px;
        color: #bbb;
        font-size: 14px;
    }

    .no-results {
        text-align: center;
        padding: 60px;
        color: #999;
        font-size: 16px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #E5E8ED;
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
        const statusF = document.getElementById('f-status').value;
        const egeMinVal = document.getElementById('f-ege-min').value;
        const egeMaxVal = document.getElementById('f-ege-max').value;
        const certMinVal = document.getElementById('f-cert-min').value;
        const egeMin = egeMinVal !== '' ? parseFloat(egeMinVal) : 0;
        const egeMax = egeMaxVal !== '' ? parseFloat(egeMaxVal) : 300;
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
                    (!statusF || row.dataset.status === statusF) &&
                    (parseFloat(row.dataset.ege || 0) >= egeMin) &&
                    (parseFloat(row.dataset.ege || 0) <= egeMax) &&
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
            if (blockVisible > 0 && (search || typeF || statusF || egeMinVal || egeMaxVal || certMinVal)) {
                block.classList.remove('collapsed');
            }
        });

        document.getElementById('totalVisible').textContent = totalVisible;
        document.getElementById('noResults').style.display = totalVisible === 0 ? 'block' : 'none';
    }

    function resetFilters() {
        document.getElementById('f-search').value = '';
        ['f-ege-min', 'f-ege-max', 'f-cert-min'].forEach(id => document.getElementById(id).value = '');
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