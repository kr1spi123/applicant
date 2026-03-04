@extends('layouts.admin')

@section('title', 'Рейтинги по специальностям')

@section('content')
<div class="admin-content">
    <div class="admin-header">
        <h1>Рейтинги по специальностям</h1>
        <p class="admin-subheader">Таблицы заявлений, отсортированные по рейтингу. Верхние — бюджет.</p>
    </div>

    {{-- ====== ФИЛЬТРЫ ====== --}}
    <div class="filters-card">
        <div style="display:grid;grid-template-columns:2fr 2fr 1fr 1.5fr 1fr 1fr 1fr auto;gap:12px;align-items:flex-end;">

            <div>
                <label class="filter-label">Поиск по ФИО</label>
                <input type="text" id="f-search" placeholder="Иванов..." oninput="applyFilters()"
                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;outline:none;transition:border-color .2s;"
                    onfocus="this.style.borderColor='#FF5A30'" onblur="this.style.borderColor='#E5E8ED'">
            </div>

            <div>
                <label class="filter-label">Специальность</label>
                <select id="f-specialty" onchange="applyFilters()" style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;background:#fff;cursor:pointer;">
                    <option value="">Все специальности</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="filter-label">Тип места</label>
                <select id="f-type" onchange="applyFilters()" style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;background:#fff;cursor:pointer;">
                    <option value="">Все</option>
                    <option value="Бюджет">Бюджет</option>
                    <option value="Платно">Платно</option>
                    <option value="Вне мест">Вне мест</option>
                </select>
            </div>

            <div>
                <label class="filter-label">Статус заявки</label>
                <select id="f-status" onchange="applyFilters()" style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;background:#fff;cursor:pointer;">
                    <option value="">Все</option>
                    <option value="Требует подтверждения">Требует подтверждения</option>
                    <option value="На рассмотрении">На рассмотрении</option>
                    <option value="Проверено">Проверено</option>
                    <option value="Одобрено">Одобрено</option>
                    <option value="Отклонено">Отклонено</option>
                </select>
            </div>

            <div>
                <label class="filter-label">ЕГЭ от</label>
                <input type="number" id="f-ege-min" min="0" max="300" placeholder="0" oninput="applyFilters()"
                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;outline:none;">
            </div>

            <div>
                <label class="filter-label">ЕГЭ до</label>
                <input type="number" id="f-ege-max" min="0" max="300" placeholder="300" oninput="applyFilters()"
                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;outline:none;">
            </div>

            <div>
                <label class="filter-label">Аттестат от</label>
                <input type="number" id="f-cert-min" min="3" max="5" step="0.1" placeholder="3.0" oninput="applyFilters()"
                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;outline:none;">
            </div>

            <div style="display:flex;align-items:flex-end;">
                <button onclick="resetFilters()"
                    style="padding:9px 16px;background:#F4F5F6;border:1px solid #E5E8ED;border-radius:8px;font-size:13px;cursor:pointer;font-weight:600;color:#555;white-space:nowrap;transition:all .2s;"
                    onmouseover="this.style.background='#E5E8ED'" onmouseout="this.style.background='#F4F5F6'">
                    ✕ Сбросить
                </button>
            </div>
        </div>

        <div style="margin-top:12px;font-size:13px;color:#888;">
            Показано заявлений: <strong id="totalVisible" style="color:#FF5A30;">0</strong>
        </div>
    </div>

    {{-- ====== ТАБЛИЦЫ ПО СПЕЦИАЛЬНОСТЯМ ====== --}}
    @foreach($specialties as $specialty)
        @php
            $budget = (int) ($specialty->budget_places ?? 0);
            $total  = (int) ($specialty->total_places ?? $budget);
            $apps   = $specialty->applications;
        @endphp

        <div class="specialty-block" data-specialty-id="{{ $specialty->id }}" style="margin-bottom:32px;">

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;padding:16px 20px;background:#fff;border-radius:10px;border:1px solid #E5E8ED;box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <h2 style="margin:0;font-size:18px;font-weight:700;">{{ $specialty->name }}</h2>
                <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:13px;color:#666;align-items:center;">
                    <span>Бюджет: <strong style="color:#15803D;">{{ $budget }}</strong></span>
                    <span>Всего мест: <strong>{{ $total }}</strong></span>
                    <span>Заявлений: <strong>{{ $apps->count() }}</strong></span>
                    <span style="color:#FF5A30;">Показано: <strong class="spec-visible-count">{{ $apps->count() }}</strong></span>
                </div>
            </div>

            @if($apps->count() > 0)
            <div style="overflow-x:auto;">
            <table class="admin-table" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width:60px;">Место</th>
                        <th>ФИО</th>
                        <th style="width:110px;">Аттестат</th>
                        <th style="width:90px;">ЕГЭ</th>
                        <th style="width:120px;">Тип</th>
                        <th style="width:180px;">Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($apps as $i => $app)
                        @php
                            $position = $i + 1;
                            $type = $position <= $budget
                                ? 'Бюджет'
                                : ($position <= $total ? 'Платно' : 'Вне мест');
                            $typeBadge = $type === 'Бюджет'  ? 'status-approved'
                                : ($type === 'Платно'        ? 'status-review' : 'status-rejected');
                            $statusBadge = match($app->status) {
                                'Требует подтверждения' => 'pending',
                                'На рассмотрении'       => 'review',
                                'Проверено'             => 'checked',
                                'Одобрено'              => 'approved',
                                'Отклонено'             => 'rejected',
                                default                 => 'pending',
                            };
                            // Полное ФИО для поиска
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

                            <td style="text-align:center;">
                                @if($position === 1)
                                    <span style="font-size:20px;">🥇</span>
                                @elseif($position === 2)
                                    <span style="font-size:20px;">🥈</span>
                                @elseif($position === 3)
                                    <span style="font-size:20px;">🥉</span>
                                @else
                                    <strong style="color:#555;">{{ $position }}</strong>
                                @endif
                            </td>
                            <td style="font-weight:500;">{{ $app->user->name }}</td>
                            <td style="font-weight:700;">{{ $app->certificate_score }}</td>
                            <td style="font-weight:700;">{{ $app->ege_score ?? '—' }}</td>
                            <td><span class="status-badge {{ $typeBadge }}">{{ $type }}</span></td>
                            <td><span class="status-badge status-{{ $statusBadge }}">{{ $app->status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @else
                <div style="color:#bbb;font-size:14px;padding:16px 20px;background:#fff;border-radius:8px;border:1px solid #E5E8ED;">Заявлений пока нет</div>
            @endif
        </div>
    @endforeach

    <div id="noResults" style="display:none;text-align:center;padding:60px;color:#999;font-size:16px;">
        Ничего не найдено по выбранным фильтрам
    </div>
</div>

@push('styles')
<style>
    .filter-label { font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:5px; }
    .filters-card { background:#fff;padding:20px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.03);margin-bottom:25px;border:1px solid #E5E8ED; }

    .status-badge { padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.03em;white-space:nowrap; }
    .status-pending  { background:#FFF7ED;color:#C2410C;border:1px solid #FED7AA; }
    .status-review   { background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE; }
    .status-checked  { background:#F0FDF4;color:#15803D;border:1px solid #BBF7D0; }
    .status-approved { background:#DCFCE7;color:#166534;border:1px solid #86EFAC; }
    .status-rejected { background:#FEF2F2;color:#B91C1C;border:1px solid #FECACA; }

    .admin-main { max-width:100% !important; padding:24px 30px; }
</style>
@endpush

@push('scripts')
<script>
function applyFilters() {
    const search     = document.getElementById('f-search').value.toLowerCase().trim();
    const specialtyF = document.getElementById('f-specialty').value;
    const typeF      = document.getElementById('f-type').value;
    const statusF    = document.getElementById('f-status').value;
    const egeMinVal  = document.getElementById('f-ege-min').value;
    const egeMaxVal  = document.getElementById('f-ege-max').value;
    const certMinVal = document.getElementById('f-cert-min').value;
    const egeMin     = egeMinVal  !== '' ? parseFloat(egeMinVal)  : 0;
    const egeMax     = egeMaxVal  !== '' ? parseFloat(egeMaxVal)  : 300;
    const certMin    = certMinVal !== '' ? parseFloat(certMinVal) : 3;

    let totalVisible = 0;

    document.querySelectorAll('.specialty-block').forEach(block => {
        const specId = block.dataset.specialtyId;
        const rows   = block.querySelectorAll('.rating-row');
        let blockVisible = 0;

        if (specialtyF && specId !== specialtyF) {
            block.style.display = 'none';
            return;
        }
        block.style.display = '';

        rows.forEach(row => {
            // Поиск: проверяем вхождение подстроки в data-name (полное ФИО в нижнем регистре)
            const nameOk = !search || row.dataset.name.includes(search);

            const match =
                nameOk &&
                (!typeF   || row.dataset.type   === typeF) &&
                (!statusF || row.dataset.status === statusF) &&
                (parseFloat(row.dataset.ege  || 0) >= egeMin) &&
                (parseFloat(row.dataset.ege  || 0) <= egeMax) &&
                (parseFloat(row.dataset.cert || 0) >= certMin);

            row.style.display = match ? '' : 'none';
            if (match) { blockVisible++; totalVisible++; }
        });

        const counter = block.querySelector('.spec-visible-count');
        if (counter) counter.textContent = blockVisible;
    });

    document.getElementById('totalVisible').textContent = totalVisible;
    document.getElementById('noResults').style.display = totalVisible === 0 ? 'block' : 'none';
}

function resetFilters() {
    ['f-search','f-specialty','f-type','f-status','f-ege-min','f-ege-max','f-cert-min']
        .forEach(id => { document.getElementById(id).value = ''; });
    applyFilters();
}

document.addEventListener('DOMContentLoaded', applyFilters);
</script>
@endpush
@endsection
