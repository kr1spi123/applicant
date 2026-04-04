@extends('layouts.admin')

@section('title', 'Управление заявками')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Заявки абитуриентов</h1>
            <p class="page-sub">Показано: <strong id="visibleCount">{{ $applications->count() }}</strong> из
                {{ $applications->count() }}</p>
        </div>
    </div>

    {{-- ФИЛЬТРЫ --}}
    <div class="fcard">
        <div class="fgrid">
            <div class="fg fg-wide">
                <label class="flabel">Поиск по ФИО</label>
                <input type="text" id="f-search" placeholder="Иванов Иван..." oninput="applyFilters()" class="finput">
            </div>
            <div class="fg fg-wide">
                <label class="flabel">Специальность</label>
                <div class="csel-wrap">
                    <div class="csel" id="csel-specialty" onclick="toggleCsel(this)">
                        <span class="csel__val">Все</span>
                        <svg class="csel__arr" width="12" height="8" viewBox="0 0 12 8">
                            <path d="M1 1l5 5 5-5" stroke="#999" stroke-width="1.8" fill="none" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="csel__drop" id="csel-specialty-drop">
                        <div class="csel__opt csel__opt--active" data-val="">Все</div>
                        @foreach($applications->pluck('specialty.name')->unique()->sort() as $sp)
                            <div class="csel__opt" data-val="{{ $sp }}">{{ $sp }}</div>
                        @endforeach
                    </div>
                    <input type="hidden" id="f-specialty" value="">
                </div>
            </div>
            <div class="fg">
                <label class="flabel">Статус</label>
                <div class="csel-wrap">
                    <div class="csel" id="csel-status" onclick="toggleCsel(this)">
                        <span class="csel__val">Все</span>
                        <svg class="csel__arr" width="12" height="8" viewBox="0 0 12 8">
                            <path d="M1 1l5 5 5-5" stroke="#999" stroke-width="1.8" fill="none" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="csel__drop" id="csel-status-drop">
                        <div class="csel__opt csel__opt--active" data-val="">Все</div>
                        <div class="csel__opt" data-val="Требует подтверждения" data-color="pending">Требует подтверждения
                        </div>
                        <div class="csel__opt" data-val="На рассмотрении" data-color="review">На рассмотрении</div>
                        <div class="csel__opt" data-val="Проверено" data-color="checked">Проверено</div>
                        <div class="csel__opt" data-val="Одобрено" data-color="approved">Одобрено</div>
                        <div class="csel__opt" data-val="Отклонено" data-color="rejected">Отклонено</div>
                    </div>
                    <input type="hidden" id="f-status" value="">
                </div>
            </div>
            <div class="fg">
                <label class="flabel">Льготы</label>
                <div class="csel-wrap">
                    <div class="csel" id="csel-benefits" onclick="toggleCsel(this)">
                        <span class="csel__val">Все</span>
                        <svg class="csel__arr" width="12" height="8" viewBox="0 0 12 8">
                            <path d="M1 1l5 5 5-5" stroke="#999" stroke-width="1.8" fill="none" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="csel__drop" id="csel-benefits-drop">
                        <div class="csel__opt csel__opt--active" data-val="">Все</div>
                        <div class="csel__opt" data-val="yes">Есть</div>
                        <div class="csel__opt" data-val="no">Нет</div>
                    </div>
                    <input type="hidden" id="f-benefits" value="">
                </div>
            </div>
            <div class="fg">
                <label class="flabel">Проверено</label>
                <div class="csel-wrap">
                    <div class="csel" id="csel-verified" onclick="toggleCsel(this)">
                        <span class="csel__val">Все</span>
                        <svg class="csel__arr" width="12" height="8" viewBox="0 0 12 8">
                            <path d="M1 1l5 5 5-5" stroke="#999" stroke-width="1.8" fill="none" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="csel__drop" id="csel-verified-drop">
                        <div class="csel__opt csel__opt--active" data-val="">Все</div>
                        <div class="csel__opt" data-val="yes">Да</div>
                        <div class="csel__opt" data-val="no">Нет</div>
                    </div>
                    <input type="hidden" id="f-verified" value="">
                </div>
            </div>
            <div class="fg fg-end">
                <button onclick="resetFilters()" class="btn-reset">✕ Сбросить</button>
            </div>
        </div>
    </div>

    {{-- КАРТОЧКИ ЗАЯВОК --}}
    <div class="apps-list" id="appsList">
        @foreach($applications as $application)
            @php
                $statusKey = match ($application->status) {
                    'Требует подтверждения' => 'pending',
                    'На рассмотрении' => 'review',
                    'Проверено' => 'checked',
                    'Одобрено' => 'approved',
                    'Отклонено' => 'rejected',
                    default => 'pending'
                };
                $statusLabel = $application->status;
                $benefits = $application->benefits
                    ? (is_array($application->benefits) ? $application->benefits : json_decode($application->benefits, true))
                    : [];
                $hasBenefits = !empty($benefits);
                $proofFiles = $application->benefit_proof
                    ? (is_array($application->benefit_proof) ? $application->benefit_proof : json_decode($application->benefit_proof, true))
                    : [];
                $fullName = strtolower(trim(implode(' ', array_filter([
                    $application->user->surname ?? null,
                    $application->user->name ?? null,
                    $application->full_name ?? null,
                ]))));
            @endphp

            <div class="app-card app-card--{{ $statusKey }}"
                data-name="{{ mb_strtolower(trim(($application->user->surname ?? '') . ' ' . ($application->user->name ?? '') . ' ' . ($application->full_name ?? ''))) }}"
                data-specialty="{{ $application->specialty->name }}" data-status="{{ $application->status }}"
                data-benefits="{{ $hasBenefits ? 'yes' : 'no' }}"
                data-verified="{{ $application->status === 'Проверено' ? 'yes' : 'no' }}" data-id="{{ $application->id }}">

                {{-- Верхняя строка --}}
                <div class="app-card__top">
                    <div class="app-card__id">#{{ $application->id }}</div>

                    <div class="app-card__person">
                        <span class="app-card__name">{{ $application->user->name }}
                            {{ $application->user->surname ?? '' }}</span>
                    </div>

                    <div class="app-card__spec">{{ $application->specialty->name }}</div>

                    <div class="app-card__scores">
                        <div class="score-chip">
                            <span class="score-chip__label">Аттестат</span>
                            <span class="score-chip__val">{{ $application->certificate_score ?: '—' }}</span>
                        </div>
                        <div class="score-chip">
                            <span class="score-chip__label">ЕГЭ</span>
                            <span
                                class="score-chip__val {{ $application->ege_score ? '' : 'score-chip__val--empty' }}">{{ $application->ege_score ?: '—' }}</span>
                        </div>

                    </div>

                    <div class="app-card__right">
                        <span class="status-badge status-badge--{{ $statusKey }}">{{ $statusLabel }}</span>
                        @if($application->is_verified)
                            <span class="verified-badge">✓ Проверено</span>
                        @endif
                        <div class="app-card__date">
                            {{ $application->created_at->format('d.m.Y') }}<span>{{ $application->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                </div>

               
                {{-- Панель действий --}}
                <div class="app-card__actions">
                    <form action="{{ route('admin.applications.update-status', $application) }}" method="POST"
                        class="action-status-form">
                        @csrf @method('PATCH')
                        <div class="action-group">
                            <label class="action-label">Статус</label>
                            <select name="status" onchange="this.form.submit()" class="status-sel status-sel--{{ $statusKey }}">
                                <option value="Требует подтверждения" {{ $application->status == 'Требует подтверждения' ? 'selected' : '' }}>Требует подтверждения</option>
                                <option value="На рассмотрении" {{ $application->status == 'На рассмотрении' ? 'selected' : '' }}>
                                    На рассмотрении</option>
                                <option value="Проверено" {{ $application->status == 'Проверено' ? 'selected' : '' }}>Проверено
                                </option>
                                <option value="Одобрено" {{ $application->status == 'Одобрено' ? 'selected' : '' }}>Одобрено
                                </option>
                                <option value="Отклонено" {{ $application->status == 'Отклонено' ? 'selected' : '' }}>Отклонено
                                </option>
                            </select>
                        </div>
                    </form>

                    <div class="action-divider"></div>

                    <form action="{{ route('admin.applications.update-scores', $application) }}" method="POST"
                        class="action-scores-form">
                        @csrf @method('PATCH')
                        <div class="action-group">
                            <label class="action-label">ЕГЭ</label>
                            <input type="number" name="ege_score" value="{{ $application->ege_score }}" min="0" max="300"
                                placeholder="0–300" class="score-input">
                        </div>
                        <div class="action-group">
                            <label class="action-label">Аттестат</label>
                            <input type="number" step="0.1" name="certificate_score"
                                value="{{ $application->certificate_score }}" min="3" max="5" placeholder="3–5"
                                class="score-input">
                        </div>
                        <div class="action-group action-group--wide">
                            <label class="action-label">Замечания</label>
                            <input type="text" name="verification_notes" value="{{ $application->verification_notes }}"
                                placeholder="Заметки проверяющего..." class="notes-input">
                        </div>

                        <div class="action-group action-group--btns">
                            <label class="action-label">&nbsp;</label>
                            <div style="display:flex;gap:6px;">
                                <button type="submit" class="abtn abtn--primary">Сохранить</button>
                                <a href="{{ route('applications.verify', $application->id) }}"
                                    class="abtn abtn--ghost">Просмотр</a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        @endforeach
    </div>

    <div id="noResults" class="no-results" style="display:none;">
        Ничего не найдено по выбранным фильтрам
    </div>

    @push('styles')
        <style>
            .admin-main {
                max-width: 100% !important;
                padding: 24px 30px;
            }

            /* Header */
            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 20px;
            }

            .page-title {
                font-size: 24px;
                font-weight: 800;
                color: #1E212C;
                margin: 0 0 4px;
            }

            .page-sub {
                font-size: 13px;
                color: #aaa;
                margin: 0;
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
                grid-template-columns: 1.8fr 1.8fr 1.4fr 1fr 1fr auto;
                gap: 12px;
                align-items: flex-end;
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
                appearance: none;
                -webkit-appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23999' stroke-width='1.8' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                padding-right: 32px;
            }

            input.finput {
                background-image: none;
                padding-right: 14px;
            }

            .finput:focus {
                outline: none;
                border-color: #FF5A30;
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

            /* Apps list */
            .apps-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            /* Card */
            .app-card {
                background: #fff;
                border-radius: 12px;
                border: 1px solid #E5E8ED;
                border-left: 4px solid #E5E8ED;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
                overflow: hidden;
                transition: box-shadow .15s;
            }

            .app-card:hover {
                box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
            }

            .app-card--pending {
                border-left-color: #FDBA74;
            }

            .app-card--review {
                border-left-color: #93C5FD;
            }

            .app-card--checked {
                border-left-color: #6EE7B7;
            }

            .app-card--approved {
                border-left-color: #34D399;
            }

            .app-card--rejected {
                border-left-color: #F87171;
            }

            /* Top row */
            .app-card__top {
                display: grid;
                grid-template-columns: 44px 1fr 2fr auto auto;
                align-items: center;
                gap: 16px;
                padding: 16px 20px;
            }

            .app-card__id {
                font-size: 12px;
                font-weight: 700;
                color: #C0C4CC;
            }

            .app-card__name {
                font-size: 15px;
                font-weight: 700;
                color: #1E212C;
            }

            .app-card__spec {
                font-size: 15px;
                font-weight: 700;
                color: #1E212C;
                line-height: 1.4;
                max-width: 460px;
            }

            .app-card__scores {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .score-chip {
                display: flex;
                flex-direction: column;
                align-items: center;
                background: #F8F9FA;
                border: 1px solid #EDEEF0;
                border-radius: 10px;
                padding: 10px 18px;
                min-width: 72px;
            }

            .score-chip__label {
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                color: #9A9CA5;
                letter-spacing: .05em;
            }

            .score-chip__val {
                font-size: 20px;
                font-weight: 800;
                color: #1E212C;
                line-height: 1.2;
                margin-top: 3px;
            }

            .score-chip__val--empty {
                color: #D0D3DA;
            }

            .score-chip--rating .score-chip__val {
                color: #FF5A30;
            }

            .score-chip--rating {
                border-color: #FFD5C8;
                background: #FFF5F2;
            }

            .app-card__right {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 6px;
            }

            /* Status badge */
            .status-badge {
                display: inline-block;
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 700;
                white-space: nowrap;
            }

            .status-badge--pending {
                background: #FFF7ED;
                color: #C2410C;
                border: 1px solid #FED7AA;
            }

            .status-badge--review {
                background: #EFF6FF;
                color: #1D4ED8;
                border: 1px solid #BFDBFE;
            }

            .status-badge--checked {
                background: #F0FDF4;
                color: #15803D;
                border: 1px solid #BBF7D0;
            }

            .status-badge--approved {
                background: #DCFCE7;
                color: #166534;
                border: 1px solid #86EFAC;
            }

            .status-badge--rejected {
                background: #FEF2F2;
                color: #B91C1C;
                border: 1px solid #FECACA;
            }

            .verified-badge {
                font-size: 11px;
                font-weight: 700;
                color: #15803D;
            }

            .app-card__date {
                font-size: 12px;
                color: #999;
                font-weight: 600;
                text-align: right;
            }

            .app-card__date span {
                display: block;
                font-size: 11px;
                color: #C0C4CC;
                font-weight: 400;
            }

            /* Benefits */
            .app-card__benefits {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 6px;
                padding: 8px 20px 10px;
                border-top: 1px solid #F4F5F6;
                background: #FAFBFC;
            }

            .benefits-label {
                font-size: 11px;
                font-weight: 700;
                color: #9A9CA5;
                text-transform: uppercase;
                letter-spacing: .05em;
                margin-right: 4px;
            }

            .benefit-tag {
                background: #EDE9FE;
                color: #5B21B6;
                font-size: 11px;
                font-weight: 700;
                padding: 3px 8px;
                border-radius: 4px;
                border: 1px solid #DDD6FE;
            }

            .proof-link {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                background: #F0F9FF;
                color: #0369A1;
                font-size: 11px;
                font-weight: 600;
                padding: 3px 8px;
                border-radius: 4px;
                border: 1px solid #BAE6FD;
                text-decoration: none;
            }

            .proof-link:hover {
                background: #E0F2FE;
            }

            /* Actions panel */
            .app-card__actions {
                display: flex;
                align-items: flex-end;
                flex-wrap: wrap;
                gap: 16px;
                padding: 12px 20px 14px;
                border-top: 1px solid #F0F1F3;
                background: #F8F9FA;
            }

            .action-divider {
                width: 1px;
                background: #E5E8ED;
                align-self: stretch;
            }

            .action-group {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            .action-group--wide {
                flex: 1;
                min-width: 180px;
            }

            .action-group--check {
                justify-content: flex-end;
            }

            .action-group--btns {
                justify-content: flex-end;
            }

            .action-label {
                font-size: 11px;
                font-weight: 700;
                color: #888;
                text-transform: uppercase;
                letter-spacing: .05em;
                white-space: nowrap;
            }

            .action-status-form {
                display: flex;
            }

            .action-scores-form {
                display: flex;
                align-items: flex-end;
                gap: 12px;
                flex-wrap: wrap;
                flex: 1;
            }

            .status-sel {
                padding: 7px 12px;
                border-radius: 7px;
                font-size: 13px;
                font-weight: 700;
                border: 1px solid;
                cursor: pointer;
                min-width: 200px;
            }

            .status-sel--pending {
                background: #FFF7ED;
                color: #C2410C;
                border-color: #FED7AA;
            }

            .status-sel--review {
                background: #EFF6FF;
                color: #1D4ED8;
                border-color: #BFDBFE;
            }

            .status-sel--checked {
                background: #F0FDF4;
                color: #15803D;
                border-color: #BBF7D0;
            }

            .status-sel--approved {
                background: #DCFCE7;
                color: #166534;
                border-color: #86EFAC;
            }

            .status-sel--rejected {
                background: #FEF2F2;
                color: #B91C1C;
                border-color: #FECACA;
            }

            .score-input {
                width: 80px;
                padding: 7px 10px;
                border: 1px solid #E5E8ED;
                border-radius: 7px;
                font-size: 13px;
                box-sizing: border-box;
            }

            .score-input:focus {
                outline: none;
                border-color: #FF5A30;
                box-shadow: 0 0 0 3px rgba(255, 90, 48, .1);
            }

            .notes-input {
                width: 100%;
                padding: 7px 10px;
                border: 1px solid #E5E8ED;
                border-radius: 7px;
                font-size: 13px;
                box-sizing: border-box;
            }

            .notes-input:focus {
                outline: none;
                border-color: #FF5A30;
                box-shadow: 0 0 0 3px rgba(255, 90, 48, .1);
            }

            .verified-check {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                color: #555;
                cursor: pointer;
                padding: 7px 0;
                white-space: nowrap;
            }

            .abtn {
                padding: 7px 16px;
                border-radius: 7px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                border: none;
                white-space: nowrap;
            }

            .abtn--primary {
                background: #FF5A30;
                color: #fff;
            }

            .abtn--primary:hover {
                background: #E04820;
            }

            .abtn--ghost {
                background: #fff;
                color: #424551;
                border: 1px solid #E5E8ED;
            }

            .abtn--ghost:hover {
                background: #F4F5F6;
            }

            .no-results {
                text-align: center;
                padding: 60px;
                color: #bbb;
                font-size: 15px;
                background: #fff;
                border-radius: 12px;
                border: 1px solid #E5E8ED;
                margin-top: 16px;
            }

            /* ── Custom Select ─────────────────────────── */
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
                border-color: #FF5A30;
                box-shadow: 0 0 0 3px rgba(255, 90, 48, .1);
            }

            .csel__val {
                color: #333;
                flex: 1;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .csel__val--placeholder {
                color: #aaa;
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
                z-index: 100;
                padding: 6px;
                max-height: 220px;
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
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .csel__opt:hover {
                background: #F4F5F6;
            }

            .csel__opt--active {
                background: #FFF0EC;
                color: #FF5A30;
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


            /* Option styling — работает в Firefox, частично в Chrome */
            .finput option {
                border-radius: 8px;
                padding: 8px 12px;
                margin: 3px 6px;
                font-size: 13px;
            }

            /* Status select options */
            .status-sel option {
                padding: 8px 12px;
                border-radius: 8px;
                margin: 2px;
            }

            @media(max-width:1100px) {
                .app-card__top {
                    grid-template-columns: 44px 1fr 1fr auto auto;
                }
            }

            @media(max-width:800px) {
                .app-card__top {
                    grid-template-columns: 1fr auto;
                }

                .app-card__id,
                .app-card__spec {
                    display: none;
                }

                .fgrid {
                    grid-template-columns: 1fr 1fr;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Custom select logic
            function toggleCsel(el) {
                const wrap = el.closest('.csel-wrap');
                const drop = wrap.querySelector('.csel__drop');
                const isOpen = drop.classList.contains('open');

                // Close all
                document.querySelectorAll('.csel__drop.open').forEach(d => {
                    d.classList.remove('open');
                    d.previousElementSibling.classList.remove('open');
                });

                if (!isOpen) {
                    drop.classList.add('open');
                    el.classList.add('open');
                }
            }

            function selectCselOpt(optEl) {
                const drop = optEl.closest('.csel__drop');
                const wrap = optEl.closest('.csel-wrap');
                const trigger = wrap.querySelector('.csel');
                const valSpan = trigger.querySelector('.csel__val');
                const hiddenInput = wrap.querySelector('input[type="hidden"]');
                const val = optEl.dataset.val;
                const label = optEl.textContent.trim();

                // Update active
                drop.querySelectorAll('.csel__opt').forEach(o => o.classList.remove('csel__opt--active'));
                optEl.classList.add('csel__opt--active');

                // Update display
                valSpan.textContent = label;
                if (val === '') valSpan.classList.add('csel__val--placeholder');
                else valSpan.classList.remove('csel__val--placeholder');

                // Update hidden input
                hiddenInput.value = val;

                // Close
                drop.classList.remove('open');
                trigger.classList.remove('open');

                applyFilters();
            }

            // Close on outside click
            document.addEventListener('click', e => {
                if (!e.target.closest('.csel-wrap')) {
                    document.querySelectorAll('.csel__drop.open').forEach(d => {
                        d.classList.remove('open');
                        d.closest('.csel-wrap').querySelector('.csel').classList.remove('open');
                    });
                }
            });

            // Attach option click handlers on load
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.csel__opt').forEach(opt => {
                    opt.addEventListener('click', e => { e.stopPropagation(); selectCselOpt(opt); });
                });
            });

            function applyFilters() {
                const search = document.getElementById('f-search').value.toLowerCase().trim();
                const specialty = document.getElementById('f-specialty').value;
                const status = document.getElementById('f-status').value;
                const benefits = document.getElementById('f-benefits').value;
                const verified = document.getElementById('f-verified').value;

                const cards = document.querySelectorAll('.app-card');
                let visible = 0;

                cards.forEach(card => {
                    const cardName = (card.dataset.name || '').toLowerCase();
                    const match =
                        (!search || cardName.includes(search)) &&
                        (!specialty || card.dataset.specialty === specialty) &&
                        (!status || card.dataset.status === status) &&
                        (!benefits || card.dataset.benefits === benefits) &&
                        (!verified || card.dataset.verified === verified);

                    card.style.display = match ? '' : 'none';
                    if (match) visible++;
                });

                document.getElementById('visibleCount').textContent = visible;
                document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
            }

            function resetFilters() {
                document.getElementById('f-search').value = '';
                // Reset custom selects
                document.querySelectorAll('.csel-wrap').forEach(wrap => {
                    const firstOpt = wrap.querySelector('.csel__opt');
                    if (firstOpt) selectCselOpt(firstOpt);
                });
                applyFilters();
            }
        </script>
    @endpush
@endsection