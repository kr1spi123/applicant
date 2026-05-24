@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/lkapp.css') . '?v=' . (file_exists(public_path('css/lkapp.css')) ? filemtime(public_path('css/lkapp.css')) : time()) }}">
<link rel="stylesheet" href="{{ asset('css/auth.css') . '?v=' . (file_exists(public_path('css/auth.css')) ? filemtime(public_path('css/auth.css')) : time()) }}">

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

        {{-- SUCCESS --}}
        @if(session('success'))
        <div class="lk-alert lk-alert--success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- TOP GRID --}}
        <div class="lk-top-grid">

            {{-- Profile card --}}
            <div class="lk-card lk-card--profile">
                <div class="lk-card__head">
                    <div class="lk-avatar">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}{{ $user->surname ? mb_strtoupper(mb_substr($user->surname, 0, 1)) : '' }}
                    </div>
                    <div class="lk-card__head-info">
                        <div class="lk-card__head-label">Личный кабинет абитуриента</div>
                        <div class="lk-card__head-name">
                            {{ $user->surname ? $user->surname . ' ' . $user->name : $user->name }}
                        </div>
                    </div>
                    <div class="lk-date-badge">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {{ $user->created_at?->format('d.m.Y') ?? '-' }}
                    </div>
                </div>
                <div class="lk-card__body">
                    <div class="lk-info-grid">
                        <div class="lk-info-item">
                            <span class="lk-info-label">Email</span>
                            <span class="lk-info-value">{{ $user->email }}</span>
                        </div>
                        <div class="lk-info-item">
                            <span class="lk-info-label">Телефон</span>
                            <span class="lk-info-value">{{ $user->phone ?: 'Не указан' }}</span>
                        </div>
                    </div>
                </div>
                <div class="lk-card__foot">
                    <a href="{{ route('profile.edit') }}" class="lk-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Редактировать профиль
                    </a>
                </div>
            </div>

            {{-- Stats card --}}
            <div class="lk-card lk-card--stats">
                <div class="lk-card__head">
                    <div class="lk-card__head-name">Мои заявки</div>
                </div>
                <div class="lk-card__body">
                    <div class="lk-stats-grid">
                        <div class="lk-stat">
                            <span class="lk-stat__num">{{ $stats['total'] }}</span>
                            <span class="lk-stat__label">Всего</span>
                        </div>
                        <div class="lk-stat lk-stat--pending">
                            <span class="lk-stat__num">{{ $stats['pending'] }}</span>
                            <span class="lk-stat__label">На рассмотрении</span>
                        </div>
                        <div class="lk-stat lk-stat--approved">
                            <span class="lk-stat__num">{{ $stats['approved'] }}</span>
                            <span class="lk-stat__label">Одобрено</span>
                        </div>
                        <div class="lk-stat lk-stat--rejected">
                            <span class="lk-stat__num">{{ $stats['rejected'] }}</span>
                            <span class="lk-stat__label">Отклонено</span>
                        </div>
                    </div>
                </div>
                <div class="lk-card__foot">
                    <span class="lk-foot-hint">
                        @if($latestApplication)
                        Последняя заявка от {{ $latestApplication->created_at?->format('d.m.Y') ?? '-' }}
                        @else
                        Заявки пока не поданы
                        @endif
                    </span>
                    <a href="{{ route('applications.create') }}" class="lk-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Новая заявка
                    </a>
                </div>
            </div>

        </div>{{-- /lk-top-grid --}}

        {{-- APPLICATIONS LIST --}}
        <div class="lk-section-head">
            <div>
                <h2 class="lk-section-title">Список заявок</h2>
                <p class="lk-section-sub">Отслеживайте статус поданных документов и параметры выбранных программ обучения</p>
            </div>
        </div>

        @if($applications->count() > 0)
        <div class="lk-apps-list">
            @foreach($applications as $application)
            @php
            try {
            $position = app(\App\Services\RankingService::class)->getPosition($application);
            } catch (\Throwable $e) {
            $position = '-';
            \Log::error('Ranking error: ' . $e->getMessage());
            }

            $specialty = $application->specialty;
            $budget = (int)($specialty?->budget_places ?? 0);
            $total = (int)($specialty?->total_places ?? $budget);

            $type = 'Не рассчитано';
            if (is_numeric($position)) {
            $type = $position <= $budget ? 'Бюджет'
                : ($position <=$total ? 'Платно' : 'Вне мест' );
                }

                $typeClass=match($type) { 'Бюджет'=> 'type-budget',
                'Платно' => 'type-paid',
                default => 'type-other',
                };

                $statusClass = match($application->status) {
                'На рассмотрении' => 'status-pending',
                'Одобрено' => 'status-approved',
                'Отклонено' => 'status-rejected',
                default => 'status-pending',
                };

                $code = $specialty?->code;
                $educationLevel = 'СПО';
                $selectedForm = $application->study_form ?: 'очная';
                $selectedFee = null;

                if ($specialty) {
                $normalized = mb_strtolower($selectedForm);
                $selectedFee = match($normalized) {
                'очная' => $specialty->cost_full_time,
                'заочная' => $specialty->cost_part_time,
                'очно-заочная'=> $specialty->cost_distance,
                default => null,
                } ?? $specialty->cost_full_time ?? $specialty->cost_part_time ?? $specialty->cost_distance;
                }

                $paidPlaces = max(0, (int)($specialty?->total_places ?? 0) - (int)($specialty?->budget_places ?? 0));

                $formValue = mb_strtolower($selectedForm);
                $formType = match($formValue) {
                'очная' => 'fulltime',
                'заочная' => 'parttime',
                'очно-заочная' => 'mixed',
                default => 'mixed',
                };
                @endphp

                <div class="lk-card lk-app-card">

                    {{-- Head --}}
                    <div class="lk-card__head lk-app-card__head">
                        <div class="lk-app-head-main">
                            <div class="lk-app-title-row">
                                @if($code)
                                <span class="lk-code-pill">{{ $code }}</span>
                                @endif
                                <span class="lk-app-title">{{ $specialty?->name ?? 'Специальность удалена' }}</span>
                            </div>
                            <div class="lk-app-tags">
                                <span class="lk-edu-pill">{{ $educationLevel }}</span>
                                <span class="lk-form-badge lk-form-badge--{{ $formType }}">
                                    {{ $application->study_form ?: 'Не указана' }}
                                </span>
                            </div>
                        </div>
                        <div class="lk-app-head-side">
                            <div class="lk-date-badge">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                {{ $application->created_at?->format('d.m.Y') ?? '-' }}
                            </div>
                            <span class="lk-status {{ $statusClass }}">{{ $application->status }}</span>
                        </div>
                    </div>

                    {{-- Meta strip --}}
                    <div class="lk-meta-strip">
                        <div class="lk-meta-item">
                            <span class="lk-meta-label">Срок обучения</span>
                            <span class="lk-meta-value">{{ $specialty?->duration ?? '—' }}</span>
                        </div>
                        <div class="lk-meta-item">
                            <span class="lk-meta-label">Стоимость / год</span>
                            <span class="lk-meta-value">
                                @if($selectedFee)
                                {{ number_format($selectedFee, 0, ',', ' ') }} ₽
                                @else
                                Уточняется
                                @endif
                            </span>
                        </div>
                        <div class="lk-meta-item">
                            <span class="lk-meta-label">Бюджетных мест</span>
                            <span class="lk-meta-value">{{ $specialty?->budget_places ?? '—' }}</span>
                        </div>
                        <div class="lk-meta-item">
                            <span class="lk-meta-label">Платных мест</span>
                            <span class="lk-meta-value">{{ $paidPlaces ?: 'Уточняется' }}</span>
                        </div>
                    </div>

                    {{-- Scores --}}
                    <div class="lk-card__body lk-scores">
                        <div class="lk-score-item">
                            <span class="lk-meta-label">Баллы ЕГЭ</span>
                            <span class="lk-score-val">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#FF5A30" stroke-width="2">
                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                </svg>
                                {{ $application->ege_score ?? '—' }}
                            </span>
                        </div>
                        <div class="lk-score-item">
                            <span class="lk-meta-label">Аттестат</span>
                            <span class="lk-score-val">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <line x1="16" y1="13" x2="8" y2="13" />
                                    <line x1="16" y1="17" x2="8" y2="17" />
                                </svg>
                                {{ $application->certificate_score ?? '—' }}
                            </span>
                        </div>
                        <div class="lk-score-item">
                            <span class="lk-meta-label">Место в рейтинге</span>
                            <span class="lk-score-val lk-score-val--rank">
                                <span class="lk-rank-num">{{ $position }}</span>
                                <span class="lk-type-badge {{ $typeClass }}">{{ $type }}</span>
                            </span>
                        </div>
                    </div>

                </div>
                @endforeach
        </div>

        @else
        <div class="lk-empty">
            <div class="lk-empty__icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="12" y1="18" x2="12" y2="12" />
                    <line x1="9" y1="15" x2="15" y2="15" />
                </svg>
            </div>
            <h3>У вас пока нет активных заявок</h3>
            <p>Подайте заявление на поступление, выбрав интересующую специальность.</p>
            <a href="{{ route('applications.create') }}" class="lk-empty__btn">Подать заявку</a>
        </div>
        @endif

    </div>
</main>
@endsection