@extends('layouts.admin')

@section('title', 'Управление заявками')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Заявки абитуриентов</h1>
        <p class="page-sub">Показано: <strong id="visibleCount">{{ $applications->count() }}</strong> из {{ $applications->count() }}</p>
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
            <select id="f-specialty" onchange="applyFilters()" class="finput">
                <option value="">Все</option>
                @foreach($applications->pluck('specialty.name')->unique()->sort() as $sp)
                    <option value="{{ $sp }}">{{ $sp }}</option>
                @endforeach
            </select>
        </div>
        <div class="fg">
            <label class="flabel">Статус</label>
            <select id="f-status" onchange="applyFilters()" class="finput">
                <option value="">Все</option>
                <option value="Требует подтверждения">Требует подтверждения</option>
                <option value="На рассмотрении">На рассмотрении</option>
                <option value="Проверено">Проверено</option>
                <option value="Одобрено">Одобрено</option>
                <option value="Отклонено">Отклонено</option>
            </select>
        </div>
        <div class="fg">
            <label class="flabel">Льготы</label>
            <select id="f-benefits" onchange="applyFilters()" class="finput">
                <option value="">Все</option>
                <option value="yes">Есть</option>
                <option value="no">Нет</option>
            </select>
        </div>
        <div class="fg">
            <label class="flabel">Проверено</label>
            <select id="f-verified" onchange="applyFilters()" class="finput">
                <option value="">Все</option>
                <option value="1">Да</option>
                <option value="0">Нет</option>
            </select>
        </div>
        <div class="fg fg-end">
            <button onclick="resetFilters()" class="btn-reset">✕ Сбросить</button>
        </div>
    </div>
</div>

<div class="table-wrap">
<table class="apps-table">
    <thead>
        <tr>
            <th class="col-id">ID</th>
            <th class="col-name">ФИО</th>
            <th class="col-spec">Специальность</th>
            <th class="col-score">Аттестат</th>
            <th class="col-score">ЕГЭ</th>
            <th class="col-benefits">Льготы</th>
            <th class="col-date">Дата</th>
        </tr>
    </thead>
    <tbody>
        @foreach($applications as $application)
            @php
                $statusKey = match($application->status) {
                    'Требует подтверждения' => 'pending',
                    'На рассмотрении'       => 'review',
                    'Проверено'             => 'checked',
                    'Одобрено'              => 'approved',
                    'Отклонено'             => 'rejected',
                    default                 => 'pending'
                };
                $benefits = $application->benefits ? json_decode($application->benefits, true) : [];
                $hasBenefits = !empty($benefits);
                $fullName = strtolower(trim(implode(' ', array_filter([
                    $application->user->surname ?? null,
                    $application->user->name ?? null,
                    $application->full_name ?? null,
                ]))));
            @endphp

            {{-- Основная строка --}}
            <tr class="app-row stripe-{{ $statusKey }}"
                data-name="{{ $fullName }}"
                data-specialty="{{ $application->specialty->name }}"
                data-status="{{ $application->status }}"
                data-benefits="{{ $hasBenefits ? 'yes' : 'no' }}"
                data-verified="{{ $application->is_verified ? '1' : '0' }}"
                data-id="{{ $application->id }}">

                <td class="col-id"><span class="app-id">#{{ $application->id }}</span></td>

                <td class="col-name">
                    <div class="name-primary">{{ $application->user->name }}</div>
                    @isset($application->user->surname)
                        <div class="name-secondary">{{ $application->user->surname }}</div>
                    @endisset
                </td>

                <td class="col-spec">{{ $application->specialty->name }}</td>

                <td class="col-score">
                    <span class="score-val">{{ $application->certificate_score }}</span>
                </td>

                <td class="col-score">
                    @if($application->ege_score)
                        <span class="score-val">{{ $application->ege_score }}</span>
                    @else
                        <span class="score-empty">—</span>
                    @endif
                </td>

                <td class="col-benefits">
                    @if($hasBenefits)
                        <div class="benefit-tags">
                            @foreach($benefits as $benefit)
                                <span class="benefit-tag">{{ $benefit }}</span>
                            @endforeach
                        </div>
                        @php
                            $proofFiles = $application->benefit_proof
                                ? (is_array($application->benefit_proof)
                                    ? $application->benefit_proof
                                    : json_decode($application->benefit_proof, true))
                                : [];
                        @endphp
                        @if(!empty($proofFiles))
                            <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:6px;">
                                @foreach($proofFiles as $i => $path)
                                    <a href="{{ Storage::url($path) }}" target="_blank"
                                        style="display:inline-flex;align-items:center;gap:4px;background:#F0F9FF;color:#0369A1;font-size:11px;font-weight:600;padding:3px 8px;border-radius:4px;border:1px solid #BAE6FD;text-decoration:none;"
                                        title="{{ basename($path) }}">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Файл {{ $i + 1 }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <span class="score-empty">—</span>
                    @endif
                </td>

                <td class="col-date">
                    <div class="date-main">{{ $application->created_at->format('d.m.Y') }}</div>
                    <div class="date-time">{{ $application->created_at->format('H:i') }}</div>
                </td>
            </tr>

            {{-- Строка действий — занимает всю ширину таблицы --}}
            <tr class="actions-row stripe-actions-{{ $statusKey }}"
                data-for="{{ $application->id }}">
                <td colspan="7" class="actions-cell">
                    <div class="actions-inner">

                        {{-- Статус --}}
                        <form action="{{ route('admin.applications.update-status', $application) }}" method="POST" class="status-form">
                            @csrf
                            @method('PATCH')
                            <label class="action-label">Статус</label>
                            <select name="status" onchange="this.form.submit()" class="status-sel status-sel-{{ $statusKey }}">
                                <option value="Требует подтверждения" {{ $application->status == 'Требует подтверждения' ? 'selected' : '' }}>Требует подтверждения</option>
                                <option value="На рассмотрении"       {{ $application->status == 'На рассмотрении'       ? 'selected' : '' }}>На рассмотрении</option>
                                <option value="Проверено"             {{ $application->status == 'Проверено'             ? 'selected' : '' }}>Проверено</option>
                                <option value="Одобрено"              {{ $application->status == 'Одобрено'              ? 'selected' : '' }}>Одобрено</option>
                                <option value="Отклонено"             {{ $application->status == 'Отклонено'             ? 'selected' : '' }}>Отклонено</option>
                            </select>
                        </form>

                        <div class="actions-divider"></div>

                        {{-- Баллы --}}
                        <form action="{{ route('admin.applications.update-scores', $application) }}" method="POST" class="scores-form">
                            @csrf
                            @method('PATCH')
                            <div class="scores-inner">
                                <div class="score-field">
                                    <label class="action-label">ЕГЭ</label>
                                    <input type="number" name="ege_score" value="{{ $application->ege_score }}" min="0" max="300" placeholder="0–300" class="score-input">
                                </div>
                                <div class="score-field">
                                    <label class="action-label">Аттестат</label>
                                    <input type="number" step="0.1" name="certificate_score" value="{{ $application->certificate_score }}" min="3" max="5" placeholder="3.0–5.0" class="score-input">
                                </div>
                                <div class="score-field score-field-wide">
                                    <label class="action-label">Замечания</label>
                                    <input type="text" name="verification_notes" value="{{ $application->verification_notes }}" placeholder="Заметки проверяющего..." class="notes-input">
                                </div>
                                <div class="score-field score-field-check">
                                    <label class="action-label">&nbsp;</label>
                                </div>
                                <div class="score-field score-field-btns">
                                    <label class="action-label">&nbsp;</label>
                                    <div style="display:flex;gap:6px;">
                                        <button type="submit" class="abtn abtn-primary">Сохранить</button>
                                        <a href="{{ route('applications.verify', $application->id) }}" class="abtn abtn-ghost">Просмотр</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </td>
            </tr>

        @endforeach
    </tbody>
</table>
</div>

<div id="noResults" class="no-results" style="display:none;">
    Ничего не найдено по выбранным фильтрам
</div>

@push('styles')
<style>
/* ── Контейнер ─────────────────────────────── */
.admin-main { max-width:100% !important; padding:24px 30px; }

/* ── Шапка ──────────────────────────────────── */
.page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px; }
.page-title  { font-size:24px; font-weight:800; color:#1E212C; margin:0 0 4px; }
.page-sub    { font-size:13px; color:#aaa; margin:0; }

/* ── Фильтры ────────────────────────────────── */
.fcard  { background:#fff; padding:18px 20px; border-radius:12px; border:1px solid #E5E8ED; box-shadow:0 2px 8px rgba(0,0,0,.04); margin-bottom:20px; }
.fgrid  { display:grid; grid-template-columns:1.8fr 1.8fr 1.4fr 1fr 1fr auto; gap:12px; align-items:flex-end; }
.fg-end { display:flex; align-items:flex-end; }
.flabel { font-size:11px; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:.05em; display:block; margin-bottom:5px; }
.finput { width:100%; padding:8px 11px; border:1px solid #E5E8ED; border-radius:7px; font-size:13px; background:#fff; transition:border-color .15s; box-sizing:border-box; }
.finput:focus { outline:none; border-color:#FF5A30; box-shadow:0 0 0 3px rgba(255,90,48,.1); }
.btn-reset { padding:8px 16px; background:#F4F5F6; border:1px solid #E5E8ED; border-radius:7px; font-size:13px; font-weight:600; color:#555; cursor:pointer; white-space:nowrap; width:100%; }
.btn-reset:hover { background:#E5E8ED; }

/* ── Таблица ────────────────────────────────── */
.table-wrap  { overflow-x:auto; width:100%; }
.apps-table  { width:100%; border-collapse:collapse; background:#fff; border-radius:12px; border:1px solid #E5E8ED; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.04); }
.apps-table thead tr { background:#F8F9FA; border-bottom:2px solid #E5E8ED; }
.apps-table th { padding:12px 14px; font-size:11px; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:.05em; text-align:left; white-space:nowrap; }
.apps-table td { padding:12px 14px; border-bottom:none; vertical-align:middle; }

/* Основные строки */
.app-row td { border-top:1px solid #F0F1F3; }
.app-row:first-of-type td { border-top: 2px solid #E5E8ED; }
.app-row:hover td { background:#FAFBFC; }
.app-row:hover + .actions-row .actions-cell { background:#F5F6F8; }

/* Строки действий */
.actions-row td { padding:0; border-top:none; }
.actions-cell {
    background: #F8F9FA;
    border-bottom: 2px solid #E5E8ED;
    padding: 0 !important;
}
.actions-inner {
    display: flex;
    align-items: flex-end;
    gap: 0;
    padding: 12px 14px;
    flex-wrap: wrap;
    gap: 16px;
}

/* Цветные полосы — левая граница основной строки */
.stripe-pending  td:first-child  { border-left: 4px solid #FDBA74; }
.stripe-review   td:first-child  { border-left: 4px solid #93C5FD; }
.stripe-checked  td:first-child  { border-left: 4px solid #6EE7B7; }
.stripe-approved td:first-child  { border-left: 4px solid #34D399; }
.stripe-rejected td:first-child  { border-left: 4px solid #F87171; }

.stripe-actions-pending  .actions-cell { border-left: 4px solid #FDBA74; }
.stripe-actions-review   .actions-cell { border-left: 4px solid #93C5FD; }
.stripe-actions-checked  .actions-cell { border-left: 4px solid #6EE7B7; }
.stripe-actions-approved .actions-cell { border-left: 4px solid #34D399; }
.stripe-actions-rejected .actions-cell { border-left: 4px solid #F87171; }

/* Колонки */
.col-id       { width:50px; }
.col-name     { width:170px; }
.col-spec     { width:220px; font-size:13px; color:#424551; }
.col-score    { width:90px; }
.col-benefits { width:220px; }
.col-date     { width:95px; }

/* Ячейки */
.app-id         { font-size:12px; color:#bbb; font-weight:600; }
.name-primary   { font-weight:700; font-size:14px; color:#1E212C; }
.name-secondary { font-size:12px; color:#999; margin-top:2px; }
.score-val      { font-size:16px; font-weight:800; color:#1E212C; }
.score-empty    { color:#ddd; font-size:14px; }
.date-main      { font-size:13px; color:#555; font-weight:600; }
.date-time      { font-size:11px; color:#bbb; margin-top:2px; }

.benefit-tags { display:flex; flex-wrap:wrap; gap:4px; }
.benefit-tag  { background:#EDE9FE; color:#5B21B6; font-size:11px; font-weight:700; padding:3px 8px; border-radius:4px; border:1px solid #DDD6FE; white-space:nowrap; }

/* ── Панель действий ────────────────────────── */
.action-label { font-size:11px; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:.05em; display:block; margin-bottom:5px; white-space:nowrap; }

.status-form { display:flex; flex-direction:column; }
.status-sel  { padding:7px 12px; border-radius:7px; font-size:13px; font-weight:700; border:1px solid; cursor:pointer; min-width:210px; }
.status-sel-pending  { background:#FFF7ED; color:#C2410C; border-color:#FED7AA; }
.status-sel-review   { background:#EFF6FF; color:#1D4ED8; border-color:#BFDBFE; }
.status-sel-checked  { background:#F0FDF4; color:#15803D; border-color:#BBF7D0; }
.status-sel-approved { background:#DCFCE7; color:#166534; border-color:#86EFAC; }
.status-sel-rejected { background:#FEF2F2; color:#B91C1C; border-color:#FECACA; }

.actions-divider { width:1px; background:#E5E8ED; align-self:stretch; margin:0 4px; }

.scores-form   { flex:1; }
.scores-inner  { display:flex; align-items:flex-end; gap:12px; flex-wrap:wrap; }
.score-field   { display:flex; flex-direction:column; }
.score-field-wide  { flex:1; min-width:200px; }
.score-field-check { justify-content:flex-end; }
.score-field-btns  { justify-content:flex-end; }

.score-input { width:90px; padding:7px 10px; border:1px solid #E5E8ED; border-radius:7px; font-size:13px; box-sizing:border-box; }
.score-input:focus { outline:none; border-color:#FF5A30; box-shadow:0 0 0 3px rgba(255,90,48,.1); }
.notes-input { width:100%; padding:7px 10px; border:1px solid #E5E8ED; border-radius:7px; font-size:13px; box-sizing:border-box; }
.notes-input:focus { outline:none; border-color:#FF5A30; box-shadow:0 0 0 3px rgba(255,90,48,.1); }
.verified-check { display:inline-flex; align-items:center; gap:6px; font-size:13px; color:#555; cursor:pointer; padding:7px 0; white-space:nowrap; }

.abtn         { padding:7px 16px; border-radius:7px; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-block; border:none; white-space:nowrap; }
.abtn-primary { background:#FF5A30; color:#fff; }
.abtn-primary:hover { background:#E04820; }
.abtn-ghost   { background:#fff; color:#424551; border:1px solid #E5E8ED; }
.abtn-ghost:hover { background:#F4F5F6; }

.no-results { text-align:center; padding:60px; color:#bbb; font-size:15px; background:#fff; border-radius:12px; border:1px solid #E5E8ED; margin-top:16px; }
</style>
@endpush

@push('scripts')
<script>
function applyFilters() {
    const search    = document.getElementById('f-search').value.toLowerCase().trim();
    const specialty = document.getElementById('f-specialty').value;
    const status    = document.getElementById('f-status').value;
    const benefits  = document.getElementById('f-benefits').value;
    const verified  = document.getElementById('f-verified').value;

    const rows = document.querySelectorAll('.app-row');
    let visible = 0;

    rows.forEach(row => {
        const id = row.dataset.id;
        const actionsRow = document.querySelector(`.actions-row[data-for="${id}"]`);

        const match =
            (!search    || row.dataset.name.includes(search)) &&
            (!specialty || row.dataset.specialty === specialty) &&
            (!status    || row.dataset.status === status) &&
            (!benefits  || row.dataset.benefits === benefits) &&
            (!verified  || row.dataset.verified === verified);

        row.style.display = match ? '' : 'none';
        if (actionsRow) actionsRow.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('visibleCount').textContent = visible;
    document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
}

function resetFilters() {
    ['f-search','f-specialty','f-status','f-benefits','f-verified'].forEach(id => {
        document.getElementById(id).value = '';
    });
    applyFilters();
}
</script>
@endpush
@endsection
