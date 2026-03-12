@extends('layouts.admin')

@section('title', 'Управление специальностями')

@section('content')

    {{-- УДАЛИТЬ: кастомный диалог подтверждения --}}
    <div id="deleteDialog" style="position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;">
        <div style="position:absolute;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);"
            onclick="closeDeleteDialog()"></div>
        <div
            style="position:relative;background:#fff;border-radius:16px;padding:32px 36px;max-width:420px;width:90%;box-shadow:0 24px 60px rgba(0,0,0,.18);text-align:center;">
            <div
                style="width:56px;height:56px;background:#FEF2F2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    <path d="M10 11v6" />
                    <path d="M14 11v6" />
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                </svg>
            </div>
            <h3 style="margin:0 0 8px;font-size:18px;font-weight:700;color:#1E212C;">Удалить специальность?</h3>
            <p style="margin:0 0 24px;font-size:14px;color:#888;line-height:1.5;">Это действие нельзя отменить. Все
                связанные заявки также будут затронуты.</p>
            <div style="display:flex;gap:12px;justify-content:center;">
                <button onclick="closeDeleteDialog()"
                    style="flex:1;padding:10px 20px;background:#F4F5F6;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;font-weight:600;color:#555;cursor:pointer;">
                    Отмена
                </button>
                <button id="deleteConfirmBtn" onclick="submitDelete()"
                    style="flex:1;padding:10px 20px;background:#DC2626;border:none;border-radius:8px;font-size:14px;font-weight:600;color:#fff;cursor:pointer;transition:background .2s;"
                    onmouseover="this.style.background='#B91C1C'" onmouseout="this.style.background='#DC2626'">
                    Удалить
                </button>
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h1 style="font-size:26px;font-weight:800;color:#1E212C;margin:0 0 4px;">Управление специальностями</h1>
            <p style="margin:0;font-size:14px;color:#888;">{{ $specialties->count() }}
                специальност{{ $specialties->count() === 1 ? 'ь' : ($specialties->count() < 5 ? 'и' : 'ей') }}</p>
        </div>
        <button onclick="toggleAddPanel()" id="addToggleBtn"
            style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#FF5A30;border:none;border-radius:10px;color:#fff;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;box-shadow:0 4px 12px rgba(255,90,48,.3);"
            onmouseover="this.style.background='#E04820'" onmouseout="this.style.background='#FF5A30'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Добавить специальность
        </button>
    </div>

    @if(session('success'))
        <div
            style="margin-bottom:20px;padding:14px 18px;background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;border-radius:10px;font-weight:600;font-size:14px;display:flex;align-items:center;gap:10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.5"
                stroke-linecap="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div
            style="margin-bottom:20px;padding:14px 18px;background:#FEF2F2;color:#991B1B;border:1px solid #FECACA;border-radius:10px;font-size:14px;">
            @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
        </div>
    @endif

    {{-- ===== ТАБЛИЦА СПЕЦИАЛЬНОСТЕЙ ===== --}}
    <div
        style="background:#fff;border-radius:14px;border:1px solid #E5E8ED;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.04);">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#F8F9FA;border-bottom:2px solid #E5E8ED;">
                    <th
                        style="padding:14px 20px;text-align:left;font-size:12px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.06em;">
                        Название</th>
                    <th
                        style="padding:14px 16px;text-align:left;font-size:12px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.06em;">
                        Срок</th>
                    <th
                        style="padding:14px 16px;text-align:left;font-size:12px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.06em;">
                        Квалификация</th>
                    <th
                        style="padding:14px 16px;text-align:center;font-size:12px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.06em;">
                        Бюджет</th>
                    <th
                        style="padding:14px 16px;text-align:center;font-size:12px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.06em;">
                        Всего мест</th>
                    <th
                        style="padding:14px 20px;text-align:right;font-size:12px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.06em;">
                        Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($specialties as $specialty)
                    <tr style="border-bottom:1px solid #F4F5F6;transition:background .15s;"
                        onmouseover="this.style.background='#FAFBFC'" onmouseout="this.style.background=''">
                        <td style="padding:16px 20px;">
                            <div style="font-weight:600;font-size:15px;color:#1E212C;">{{ $specialty->name }}</div>
                            @if($specialty->description)
                                <div
                                    style="font-size:12px;color:#aaa;margin-top:2px;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $specialty->description }}</div>
                            @endif
                        </td>
                        <td style="padding:16px;font-size:14px;color:#555;">{{ $specialty->duration }}</td>
                        <td style="padding:16px;font-size:14px;color:#555;">{{ $specialty->qualification ?? '—' }}</td>
                        <td style="padding:16px;text-align:center;">
                            <span style="font-weight:700;color:#15803D;font-size:16px;">{{ $specialty->budget_places }}</span>
                        </td>
                        <td style="padding:16px;text-align:center;">
                            <span
                                style="font-weight:600;color:#1E212C;font-size:15px;">{{ $specialty->total_places ?? $specialty->budget_places }}</span>
                        </td>
                        <td style="padding:16px 20px;text-align:right;">
                            <div style="display:inline-flex;gap:8px;">
                                <button onclick="toggleEditPanel({{ $specialty->id }})"
                                    style="padding:7px 14px;background:#F4F5F6;border:1px solid #E5E8ED;border-radius:7px;font-size:13px;font-weight:600;color:#424551;cursor:pointer;transition:all .2s;"
                                    onmouseover="this.style.background='#E5E8ED'" onmouseout="this.style.background='#F4F5F6'">
                                    Редактировать
                                </button>
                                <button onclick="openDeleteDialog({{ $specialty->id }})"
                                    style="padding:7px 14px;background:#FEF2F2;border:1px solid #FECACA;border-radius:7px;font-size:13px;font-weight:600;color:#DC2626;cursor:pointer;transition:all .2s;"
                                    onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='#FEF2F2'">
                                    Удалить
                                </button>
                            </div>

                            {{-- Скрытая форма удаления --}}
                            <form id="deleteForm-{{ $specialty->id }}"
                                action="{{ route('admin.specialties.destroy', $specialty) }}" method="POST"
                                style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>

                    {{-- INLINE ПАНЕЛЬ РЕДАКТИРОВАНИЯ --}}
                    <tr id="editPanel-{{ $specialty->id }}" style="display:none;">
                        <td colspan="6" style="padding:0;">
                            <div
                                style="background:#F8F9FA;border-top:1px solid #E5E8ED;border-bottom:1px solid #E5E8ED;padding:28px 32px;">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
                                    <h3 style="margin:0;font-size:16px;font-weight:700;color:#1E212C;">Редактирование:
                                        {{ $specialty->name }}</h3>
                                    <button onclick="toggleEditPanel({{ $specialty->id }})"
                                        style="background:none;border:none;cursor:pointer;color:#999;font-size:22px;line-height:1;">×</button>
                                </div>
                                @php
                                    $editForms = array_map('trim', explode(',', mb_strtolower($specialty->study_forms ?? 'очная')));
                                @endphp
                                <form action="{{ route('admin.specialties.update', $specialty) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                                        <div>
                                            <label
                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Название
                                                *</label>
                                            <input type="text" name="name" value="{{ $specialty->name }}" required
                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                        </div>
                                        <div>
                                            <label
                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Код
                                                специальности</label>
                                            <input type="text" name="code" value="{{ $specialty->code }}" placeholder="09.02.07"
                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                        </div>
                                        <div>
                                            <label
                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Фото</label>
                                            <input type="file" name="photo"
                                                style="width:100%;padding:7px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;background:#fff;">
                                        </div>
                                        <div style="grid-column:span 3;">
                                            <label
                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Описание
                                                *</label>
                                            <textarea name="description" rows="3" required
                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;resize:vertical;">{{ $specialty->description }}</textarea>
                                        </div>
                                        <div>
                                            <label
                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Где
                                                работать</label>
                                            <input type="text" name="where_to_work"
                                                value="{{ is_array($specialty->where_to_work) ? implode(', ', $specialty->where_to_work) : $specialty->where_to_work }}"
                                                placeholder="ИТ-компании, Банки"
                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                        </div>
                                        <div>
                                            <label
                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Кем
                                                работать</label>
                                            <input type="text" name="job_roles"
                                                value="{{ is_array($specialty->job_roles) ? implode(', ', $specialty->job_roles) : $specialty->job_roles }}"
                                                placeholder="Программист, Аналитик"
                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                        </div>
                                        <div>
                                            <label
                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Формы
                                                обучения</label>
                                            <input type="text" name="study_forms" value="{{ $specialty->study_forms }}"
                                                placeholder="очная, заочная"
                                                oninput="updateFormCols(this, '{{ $specialty->id }}')"
                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                        </div>
                                        {{-- Per-form blocks --}}
                                        <div style="grid-column:span 3;margin-top:4px;">
                                            <div
                                                style="font-size:11px;font-weight:700;color:#FF5A30;text-transform:uppercase;letter-spacing:.07em;margin-bottom:14px;padding-top:12px;border-top:1px solid #E5E8ED;">
                                                Данные по формам обучения
                                            </div>
                                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;"
                                                id="form-cols-{{ $specialty->id }}">
                                                @if(in_array('очная', $editForms))
                                                    <div id="form-col-full_time-{{ $specialty->id }}"
                                                        style="background:#fff;border:1px solid #E5E8ED;border-radius:10px;padding:16px;">
                                                        <div
                                                            style="font-size:12px;font-weight:700;color:#424551;margin-bottom:12px;">
                                                            🎓 Очная</div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Срок
                                                                обучения</label><input type="text" name="duration_full_time"
                                                                value="{{ $specialty->duration_full_time }}"
                                                                placeholder="{{ $specialty->duration }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Квалификация</label><input
                                                                type="text" name="qualification_full_time"
                                                                value="{{ $specialty->qualification_full_time }}"
                                                                placeholder="{{ $specialty->qualification }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Бюджетных
                                                                мест</label><input type="number" name="budget_places_full_time"
                                                                value="{{ $specialty->budget_places_full_time }}" min="0"
                                                                placeholder="{{ $specialty->budget_places }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Всего
                                                                мест</label><input type="number" name="total_places_full_time"
                                                                value="{{ $specialty->total_places_full_time }}" min="0"
                                                                placeholder="{{ $specialty->total_places }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Стоимость,
                                                                ₽/год</label><input type="number" name="cost_full_time"
                                                                value="{{ $specialty->cost_full_time }}" min="0" step="0.01"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div id="form-col-full_time-{{ $specialty->id }}"
                                                        style="display:none;background:#fff;border:1px solid #E5E8ED;border-radius:10px;padding:16px;">
                                                        <div
                                                            style="font-size:12px;font-weight:700;color:#424551;margin-bottom:12px;">
                                                            🎓 Очная</div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Срок
                                                                обучения</label><input type="text" name="duration_full_time"
                                                                value="{{ $specialty->duration_full_time }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Квалификация</label><input
                                                                type="text" name="qualification_full_time"
                                                                value="{{ $specialty->qualification_full_time }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Бюджетных
                                                                мест</label><input type="number" name="budget_places_full_time"
                                                                value="{{ $specialty->budget_places_full_time }}" min="0"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Всего
                                                                мест</label><input type="number" name="total_places_full_time"
                                                                value="{{ $specialty->total_places_full_time }}" min="0"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Стоимость,
                                                                ₽/год</label><input type="number" name="cost_full_time"
                                                                value="{{ $specialty->cost_full_time }}" min="0" step="0.01"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(in_array('заочная', $editForms))
                                                    <div id="form-col-part_time-{{ $specialty->id }}"
                                                        style="background:#fff;border:1px solid #E5E8ED;border-radius:10px;padding:16px;">
                                                        <div
                                                            style="font-size:12px;font-weight:700;color:#424551;margin-bottom:12px;">
                                                            📚 Заочная</div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Срок
                                                                обучения</label><input type="text" name="duration_part_time"
                                                                value="{{ $specialty->duration_part_time }}"
                                                                placeholder="{{ $specialty->duration }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Квалификация</label><input
                                                                type="text" name="qualification_part_time"
                                                                value="{{ $specialty->qualification_part_time }}"
                                                                placeholder="{{ $specialty->qualification }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Бюджетных
                                                                мест</label><input type="number" name="budget_places_part_time"
                                                                value="{{ $specialty->budget_places_part_time }}" min="0"
                                                                placeholder="{{ $specialty->budget_places }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Всего
                                                                мест</label><input type="number" name="total_places_part_time"
                                                                value="{{ $specialty->total_places_part_time }}" min="0"
                                                                placeholder="{{ $specialty->total_places }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Стоимость,
                                                                ₽/год</label><input type="number" name="cost_part_time"
                                                                value="{{ $specialty->cost_part_time }}" min="0" step="0.01"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div id="form-col-part_time-{{ $specialty->id }}"
                                                        style="display:none;background:#fff;border:1px solid #E5E8ED;border-radius:10px;padding:16px;">
                                                        <div
                                                            style="font-size:12px;font-weight:700;color:#424551;margin-bottom:12px;">
                                                            📚 Заочная</div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Срок
                                                                обучения</label><input type="text" name="duration_part_time"
                                                                value="{{ $specialty->duration_part_time }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Квалификация</label><input
                                                                type="text" name="qualification_part_time"
                                                                value="{{ $specialty->qualification_part_time }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Бюджетных
                                                                мест</label><input type="number" name="budget_places_part_time"
                                                                value="{{ $specialty->budget_places_part_time }}" min="0"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Всего
                                                                мест</label><input type="number" name="total_places_part_time"
                                                                value="{{ $specialty->total_places_part_time }}" min="0"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Стоимость,
                                                                ₽/год</label><input type="number" name="cost_part_time"
                                                                value="{{ $specialty->cost_part_time }}" min="0" step="0.01"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(in_array('очно-заочная', $editForms))
                                                    <div id="form-col-distance-{{ $specialty->id }}"
                                                        style="background:#fff;border:1px solid #E5E8ED;border-radius:10px;padding:16px;">
                                                        <div
                                                            style="font-size:12px;font-weight:700;color:#424551;margin-bottom:12px;">
                                                            🔄 Очно-заочная</div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Срок
                                                                обучения</label><input type="text" name="duration_distance"
                                                                value="{{ $specialty->duration_distance }}"
                                                                placeholder="{{ $specialty->duration }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Квалификация</label><input
                                                                type="text" name="qualification_distance"
                                                                value="{{ $specialty->qualification_distance }}"
                                                                placeholder="{{ $specialty->qualification }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Бюджетных
                                                                мест</label><input type="number" name="budget_places_distance"
                                                                value="{{ $specialty->budget_places_distance }}" min="0"
                                                                placeholder="{{ $specialty->budget_places }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Всего
                                                                мест</label><input type="number" name="total_places_distance"
                                                                value="{{ $specialty->total_places_distance }}" min="0"
                                                                placeholder="{{ $specialty->total_places }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Стоимость,
                                                                ₽/год</label><input type="number" name="cost_distance"
                                                                value="{{ $specialty->cost_distance }}" min="0" step="0.01"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div id="form-col-distance-{{ $specialty->id }}"
                                                        style="display:none;background:#fff;border:1px solid #E5E8ED;border-radius:10px;padding:16px;">
                                                        <div
                                                            style="font-size:12px;font-weight:700;color:#424551;margin-bottom:12px;">
                                                            🔄 Очно-заочная</div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Срок
                                                                обучения</label><input type="text" name="duration_distance"
                                                                value="{{ $specialty->duration_distance }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Квалификация</label><input
                                                                type="text" name="qualification_distance"
                                                                value="{{ $specialty->qualification_distance }}"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Бюджетных
                                                                мест</label><input type="number" name="budget_places_distance"
                                                                value="{{ $specialty->budget_places_distance }}" min="0"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div style="margin-bottom:10px;"><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Всего
                                                                мест</label><input type="number" name="total_places_distance"
                                                                value="{{ $specialty->total_places_distance }}" min="0"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                        <div><label
                                                                style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Стоимость,
                                                                ₽/год</label><input type="number" name="cost_distance"
                                                                value="{{ $specialty->cost_distance }}" min="0" step="0.01"
                                                                style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display:flex;gap:12px;margin-top:20px;">
                                        <button type="submit"
                                            style="padding:10px 24px;background:#FF5A30;border:none;border-radius:8px;color:#fff;font-size:14px;font-weight:700;cursor:pointer;">Сохранить
                                            изменения</button>
                                        <button type="button" onclick="toggleEditPanel({{ $specialty->id }})"
                                            style="padding:10px 20px;background:#F4F5F6;border:1px solid #E5E8ED;border-radius:8px;color:#555;font-size:14px;font-weight:600;cursor:pointer;">Отмена</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div id="addPanelFull"
        style="display:none;background:#fff;border:1px solid #E5E8ED;border-radius:14px;padding:28px 32px;margin-bottom:28px;box-shadow:0 4px 20px rgba(0,0,0,.06);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <h2 style="margin:0;font-size:18px;font-weight:700;color:#1E212C;">Новая специальность</h2>
            <button onclick="toggleAddPanel()"
                style="background:none;border:none;cursor:pointer;color:#999;font-size:24px;line-height:1;">×</button>
        </div>
        <form action="{{ route('admin.specialties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                <div>
                    <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Название
                        *</label>
                    <input type="text" name="name" required
                        style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Код
                        специальности</label>
                    <input type="text" name="code" placeholder="09.02.07"
                        style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Фото</label>
                    <input type="file" name="photo"
                        style="width:100%;padding:7px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;background:#fff;">
                </div>
                <div style="grid-column:span 3;">
                    <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Описание
                        *</label>
                    <textarea name="description" rows="3" required
                        style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;resize:vertical;"></textarea>
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Где
                        работать</label>
                    <input type="text" name="where_to_work" placeholder="ИТ-компании, Банки"
                        style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Кем
                        работать</label>
                    <input type="text" name="job_roles" placeholder="Программист, Аналитик"
                        style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Формы
                        обучения</label>
                    <input type="text" name="study_forms" id="add_study_forms" placeholder="очная, заочная, очно-заочная"
                        oninput="updateAddFormCols(this)"
                        style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
                {{-- Per-form blocks for add --}}
                <div style="grid-column:span 3;margin-top:4px;">
                    <div
                        style="font-size:11px;font-weight:700;color:#FF5A30;text-transform:uppercase;letter-spacing:.07em;margin-bottom:14px;padding-top:12px;border-top:1px solid #E5E8ED;">
                        Данные по формам обучения
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;" id="add-form-cols">
                        <div id="add-col-full_time"
                            style="display:none;background:#F8F9FA;border:1px solid #E5E8ED;border-radius:10px;padding:16px;">
                            <div style="font-size:12px;font-weight:700;color:#424551;margin-bottom:12px;">🎓 Очная</div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Срок
                                    обучения</label><input type="text" name="duration_full_time"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Квалификация</label><input
                                    type="text" name="qualification_full_time"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Бюджетных
                                    мест</label><input type="number" name="budget_places_full_time" min="0"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Всего
                                    мест</label><input type="number" name="total_places_full_time" min="0"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Стоимость,
                                    ₽/год</label><input type="number" name="cost_full_time" min="0" step="0.01"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                        </div>
                        <div id="add-col-part_time"
                            style="display:none;background:#F8F9FA;border:1px solid #E5E8ED;border-radius:10px;padding:16px;">
                            <div style="font-size:12px;font-weight:700;color:#424551;margin-bottom:12px;">📚 Заочная</div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Срок
                                    обучения</label><input type="text" name="duration_part_time"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Квалификация</label><input
                                    type="text" name="qualification_part_time"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Бюджетных
                                    мест</label><input type="number" name="budget_places_part_time" min="0"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Всего
                                    мест</label><input type="number" name="total_places_part_time" min="0"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Стоимость,
                                    ₽/год</label><input type="number" name="cost_part_time" min="0" step="0.01"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                        </div>
                        <div id="add-col-distance"
                            style="display:none;background:#F8F9FA;border:1px solid #E5E8ED;border-radius:10px;padding:16px;">
                            <div style="font-size:12px;font-weight:700;color:#424551;margin-bottom:12px;">🔄 Очно-заочная
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Срок
                                    обучения</label><input type="text" name="duration_distance"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Квалификация</label><input
                                    type="text" name="qualification_distance"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Бюджетных
                                    мест</label><input type="number" name="budget_places_distance" min="0"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div style="margin-bottom:10px;"><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Всего
                                    мест</label><input type="number" name="total_places_distance" min="0"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div><label
                                    style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:6px;">Стоимость,
                                    ₽/год</label><input type="number" name="cost_distance" min="0" step="0.01"
                                    style="width:100%;padding:9px 12px;border:1px solid #E5E8ED;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:12px;margin-top:24px;">
                <button type="submit"
                    style="padding:10px 24px;background:#FF5A30;border:none;border-radius:8px;color:#fff;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(255,90,48,.25);">Сохранить</button>
                <button type="button" onclick="toggleAddPanel()"
                    style="padding:10px 20px;background:#F4F5F6;border:1px solid #E5E8ED;border-radius:8px;color:#555;font-size:14px;font-weight:600;cursor:pointer;">Отмена</button>
            </div>
        </form>
    </div>

    @push('styles')
        <style>
            .admin-main {
                max-width: 100% !important;
                padding: 24px 30px;
            }

            #deleteDialog {
                display: none !important;
            }

            #deleteDialog.open {
                display: flex !important;
            }

            input:focus,
            textarea:focus,
            select:focus {
                outline: none;
                border-color: #FF5A30 !important;
                box-shadow: 0 0 0 3px rgba(255, 90, 48, .1);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            let deleteTargetId = null;

            function openDeleteDialog(id) {
                deleteTargetId = id;
                document.getElementById('deleteDialog').classList.add('open');
            }

            function closeDeleteDialog() {
                document.getElementById('deleteDialog').classList.remove('open');
                deleteTargetId = null;
            }

            function submitDelete() {
                if (deleteTargetId) {
                    document.getElementById('deleteForm-' + deleteTargetId).submit();
                }
            }

            const FORM_MAP = {
                'очная': 'full_time',
                'заочная': 'part_time',
                'очно-заочная': 'distance'
            };

            function updateFormCols(input, spId) {
                const forms = input.value.split(',').map(f => f.trim().toLowerCase());
                Object.entries(FORM_MAP).forEach(([form, suffix]) => {
                    const col = document.getElementById('form-col-' + suffix + '-' + spId);
                    if (col) col.style.display = forms.includes(form) ? 'block' : 'none';
                });
            }

            function updateAddFormCols(input) {
                const forms = input.value.split(',').map(f => f.trim().toLowerCase());
                let anyVisible = false;
                Object.entries(FORM_MAP).forEach(([form, suffix]) => {
                    const col = document.getElementById('add-col-' + suffix);
                    if (col) {
                        const show = forms.includes(form);
                        col.style.display = show ? 'block' : 'none';
                        if (show) anyVisible = true;
                    }
                });
            }

            function toggleAddPanel() {
                const panel = document.getElementById('addPanelFull');
                const btn = document.getElementById('addToggleBtn');
                const open = panel.style.display === 'none' || panel.style.display === '';
                panel.style.display = open ? 'block' : 'none';
                btn.style.background = open ? '#E04820' : '#FF5A30';
                if (open) {
                    panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Если поле форм уже заполнено — сразу показать блоки
                    const sf = document.getElementById('add_study_forms');
                    if (sf && sf.value.trim()) updateAddFormCols(sf);
                }
            }

            function toggleEditPanel(id) {
                const panel = document.getElementById('editPanel-' + id);
                const isOpen = panel.style.display !== 'none';

                // Закрыть все открытые панели
                document.querySelectorAll('[id^="editPanel-"]').forEach(p => p.style.display = 'none');

                if (!isOpen) {
                    panel.style.display = 'table-row';
                    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    // Sync form cols with current study_forms value
                    const sfInput = panel.querySelector('input[name="study_forms"]');
                    if (sfInput) updateFormCols(sfInput, id);
                }
            }

            // Закрыть диалог по Escape
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeDeleteDialog();
            });
        </script>
    @endpush
@endsection