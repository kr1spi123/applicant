@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/lkapp.css') . '?v=' . (file_exists(public_path('css/lkapp.css')) ? filemtime(public_path('css/lkapp.css')) : time()) }}">
<link rel="stylesheet" href="{{ asset('css/auth.css') . '?v=' . (file_exists(public_path('css/auth.css')) ? filemtime(public_path('css/auth.css')) : time()) }}">

<div class="nav-links">
    <a href="{{ route('applications.create') }}" class="{{ request()->routeIs('applications.create') ? 'active' : '' }}">Подать заявку на поступление</a>
    <a href="{{ route('applications.index') }}" class="{{ request()->routeIs('applications.index') ? 'active' : '' }}">Мои заявки</a>
    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Мой профиль</a>
</div>

<main>
    <div class="container" style="display: block; max-width: 1200px;">
        @if(session('success'))
            <div class="success-message active" style="background-color: #E8F5E9; color: #2E7D32; padding: 16px; border-radius: 8px; border: 1px solid #C8E6C9; margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 2.3fr 1.7fr; gap: 24px; margin-bottom: 24px;">
            <div class="application-card" style="margin: 0;">
                <div class="card-header">
                    <div>
                        <div style="font-size: 14px; color: #9A9CA5; margin-bottom: 4px;">Личный кабинет абитуриента</div>
                        <div style="font-size: 22px; font-weight: 700; color: #171717;">
                            {{ $user->surname ? $user->surname . ' ' . $user->name : $user->name }}
                        </div>
                    </div>
                    <div class="application-date">
                        <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Зарегистрирован {{ $user->created_at?->format('d.m.Y') ?? '-' }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Телефон</span>
                            <span class="info-value">{{ $user->phone ?: 'Не указан' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="justify-content: flex-end;">
                    <a href="{{ route('profile.edit') }}" class="download-link">
                        <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4v4"></path>
                            <path d="M10 14L21 3"></path>
                            <path d="M5 5v14h14"></path>
                        </svg>
                        Редактировать профиль
                    </a>
                </div>
            </div>

            <div class="application-card" style="margin: 0;">
                <div class="card-header">
                    <div class="specialty-title">Мои заявки</div>
                </div>
                <div class="card-body">
                    <div class="info-grid" style="grid-template-columns: repeat(2, 1fr);">
                        <div class="info-item">
                            <span class="info-label">Всего заявок</span>
                            <span class="info-value">{{ $stats['total'] }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">На рассмотрении</span>
                            <span class="info-value">{{ $stats['pending'] }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Одобрено</span>
                            <span class="info-value">{{ $stats['approved'] }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Отклонено</span>
                            <span class="info-value">{{ $stats['rejected'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="justify-content: space-between;">
                    <div style="font-size: 13px; color: #666;">
                        @if($latestApplication)
                            Последняя заявка от {{ $latestApplication->created_at?->format('d.m.Y') ?? '-' }}
                        @else
                            Заявки пока не поданы
                        @endif
                    </div>
                    <a href="{{ route('applications.create') }}" class="download-link">
                        <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Новая заявка
                    </a>
                </div>
            </div>
        </div>

        <div class="applications-header">
            <div>
                <h2 class="applications-title">Список заявок</h2>
                <p class="applications-subtitle">Отслеживайте статус поданных документов и параметры выбранных программ обучения</p>
            </div>
        </div>

        @if($applications->count() > 0)
            <div class="applications-list">
                @foreach($applications as $application)
                    @php
                        try {
                            $position = app(\App\Services\RankingService::class)->getPosition($application);
                        } catch (\Throwable $e) {
                            $position = '-';
                            \Log::error('Ranking error: ' . $e->getMessage());
                        }
                        
                        $specialty = $application->specialty;
                        $budget = (int) ($specialty?->budget_places ?? 0);
                        $total = (int) ($specialty?->total_places ?? $budget);
                        
                        $type = 'Не рассчитано';
                        if (is_numeric($position)) {
                            $type = $position <= $budget ? 'Бюджет' : ($position <= $total ? 'Платно' : 'Вне мест');
                        }
                        
                        $typeClass = match($type) {
                            'Бюджет' => 'type-budget',
                            'Платно' => 'type-paid',
                            default => 'type-other'
                        };
                        
                        $statusClass = match($application->status) {
                            'На рассмотрении' => 'status-pending',
                            'Одобрено' => 'status-approved',
                            'Отклонено' => 'status-rejected',
                            default => 'status-pending'
                        };

                        $code = $specialty?->code;
                        $educationLevel = 'СПО';
                        $selectedForm = $application->study_form ?: 'очная';
                        $selectedFee = null;

                        if ($specialty) {
                            $normalized = mb_strtolower($selectedForm);

                            if ($normalized === 'очная') {
                                $selectedFee = $specialty->cost_full_time;
                            } elseif ($normalized === 'заочная') {
                                $selectedFee = $specialty->cost_part_time;
                            } elseif ($normalized === 'очно-заочная') {
                                $selectedFee = $specialty->cost_distance;
                            }

                            if ($selectedFee === null) {
                                $selectedFee = $specialty->cost_full_time ?? $specialty->cost_part_time ?? $specialty->cost_distance;
                            }
                        }

                        $tuitionPeriod = 'год';
                        $paidPlaces = max(0, (int) ($specialty?->total_places ?? 0) - (int) ($specialty?->budget_places ?? 0));
                        $createdAtTimestamp = $application->created_at ? $application->created_at->getTimestamp() : null;
                    @endphp

                    <div class="application-card"
                         data-code="{{ $code }}"
                         data-form="{{ mb_strtolower($selectedForm) }}"
                         data-fee="{{ $selectedFee ?? '' }}"
                         data-date="{{ $createdAtTimestamp ?? '' }}">
                        <div class="card-header">
                            <div class="application-header-main">
                                <div class="application-title-row">
                                    @if($code)
                                        <span class="application-code-pill">{{ $code }}</span>
                                    @endif
                                    <div class="specialty-title">{{ $specialty?->name ?? 'Специальность удалена' }}</div>
                                </div>
                                <div class="application-tags-row" style="margin-top: 8px;">
                                    @if($educationLevel)
                                        <span class="application-education-pill">{{ $educationLevel }}</span>
                                    @endif
                                    @php
                                        $form = $application->study_form ?: 'Не указана';
                                        $formValue = mb_strtolower($form);
                                        $formType = match($formValue) {
                                            'очная' => 'fulltime',
                                            'заочная' => 'parttime',
                                            'очно-заочная' => 'mixed',
                                            default => 'mixed'
                                        };
                                    @endphp
                                    <span class="application-study-badge application-study-badge-{{ $formType }}">
                                        {{ $form }}
                                    </span>
                                </div>
                            </div>
                            <div class="application-header-side">
                                <div class="application-date">
                                    <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    {{ $application->created_at?->format('d.m.Y') ?? '-' }}
                                </div>
                                <div class="status-badge {{ $statusClass }}">
                                    {{ $application->status }}
                                </div>
                            </div>
                        </div>

                        <div class="application-meta-grid">
                            <div class="application-meta-item">
                                <span class="application-meta-label">Срок обучения</span>
                                <span class="application-meta-value">{{ $specialty?->duration ?? 'Не указан' }}</span>
                            </div>
                            <div class="application-meta-item">
                                <span class="application-meta-label">Стоимость обучения</span>
                                <span class="application-meta-value">
                                    @if($selectedFee)
                                        {{ number_format($selectedFee, 0, ',', ' ') }} ₽ / {{ $tuitionPeriod }}
                                    @else
                                        Уточняется
                                    @endif
                                </span>
                            </div>
                            <div class="application-meta-item">
                                <span class="application-meta-label">Количество мест</span>
                                <span class="application-meta-value">
                                    Бюджет: {{ $specialty?->budget_places ?? '—' }},
                                    Платно: {{ $paidPlaces ?: 'Уточняется' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="application-scores-grid">
                                <div class="application-score-item">
                                    <span class="application-meta-label">Баллы ЕГЭ</span>
                                    <span class="application-score-value">
                                        <svg class="icon-sm" style="color: #FF5A30;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path>
                                        </svg>
                                        {{ $application->ege_score ?? '—' }}
                                    </span>
                                </div>
                                <div class="application-score-item">
                                    <span class="application-meta-label">Аттестат</span>
                                    <span class="application-score-value">
                                        <svg class="icon-sm" style="color: #4CAF50;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        {{ $application->certificate_score ?? '—' }}
                                    </span>
                                </div>
                                <div class="application-score-item">
                                    <span class="application-meta-label">Место в рейтинге</span>
                                    <span class="application-score-value">
                                        <span class="rank-number">{{ $position }}</span>
                                        <span class="type-badge {{ $typeClass }}">{{ $type }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($application->qr_code_path)
                            <div class="application-actions" style="margin-top: 16px;">
                                <a href="{{ asset('storage/' . $application->qr_code_path) }}" target="_blank" class="application-secondary-link">
                                    PDF Заявление
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-applications">
                <div style="margin-bottom: 20px;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#e0e0e0" stroke-width="1.5" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                </div>
                <h3>У вас пока нет активных заявок</h3>
                <p style="margin-top: 10px; color: #666;">Подайте заявление на поступление, выбрав интересующую специальность.</p>
                <a href="{{ route('applications.create') }}" style="display: inline-block; margin-top: 20px; background: #FF5A30; color: white; padding: 10px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">Подать заявку</a>
            </div>
        @endif
    </div>
</main>

<script>
</script>
@endsection
