@extends('layouts.main')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

    <style>
        .profile-page-wrapper {
            --accent: #FF5A30;
            --accent-hover: #e04820;
            --accent-soft: rgba(255,90,48,0.08);
            --ink: #111318;
            --ink-2: #3A3D4A;
            --muted: #8A8FA3;
            --muted-2: #C0C4D0;
            --surface: #FFFFFF;
            --surface-2: #F5F6FA;
            --surface-3: #ECEEF5;
            --border: rgba(0,0,0,0.07);
            --border-strong: rgba(0,0,0,0.12);
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --shadow-sm: 0 1px 4px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.07);
            --shadow-accent: 0 6px 20px rgba(255,90,48,0.22);
            --transition: 0.22s cubic-bezier(0.4,0,0.2,1);

            font-family: 'Manrope', sans-serif;
            background: #F2F3F8;
            padding: 40px 20px 80px;
            color: #111318;
            min-height: 100%;
        }

        /* ===== NAV ===== */
        .profile-nav-links {
            display: flex;
            justify-content: center;
            gap: 36px;
            margin-bottom: 36px;
        }
        .profile-nav-links a {
            font-size: 15px;
            font-weight: 600;
            color: #8A8FA3;
            text-decoration: none;
            padding-bottom: 6px;
            position: relative;
            transition: color 0.2s;
        }
        .profile-nav-links a:hover { color: #FF5A30; }
        .profile-nav-links a.active { color: #FF5A30; }
        .profile-nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: #FF5A30;
            border-radius: 2px;
        }

        /* ===== LAYOUT ===== */
        .profile-container {
            max-width: 860px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ===== MAIN CARD ===== */
        .profile-main-card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-md);
            padding: 36px 40px 32px;
            display: flex;
            flex-direction: column;
            gap: 28px;
            position: relative;
            overflow: hidden;
        }
        .profile-main-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #FF5A30 0%, #FF9F80 60%, #FFD3C4 100%);
        }

        /* ===== TOP ===== */
        .profile-main-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .profile-avatar-wrap { display: flex; align-items: center; gap: 16px; }
        .profile-avatar {
            width: 68px; height: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF5A30 0%, #FF9261 100%);
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-size: 24px; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-accent);
            letter-spacing: 1px;
        }
        .profile-main-info { display: flex; flex-direction: column; gap: 5px; }
        .profile-name { font-size: 22px; font-weight: 800; color: var(--ink); letter-spacing: -0.4px; line-height: 1.2; }
        .profile-tag {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 12px; font-weight: 700; letter-spacing: 0.06em;
            text-transform: uppercase; color: var(--accent);
            background: var(--accent-soft); padding: 3px 10px;
            border-radius: 99px; width: fit-content;
        }
        .profile-meta { font-size: 13px; color: var(--muted); font-weight: 500; }

        /* ===== EDIT BTN ===== */
        .profile-edit-toggle {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px; border-radius: 99px;
            background: var(--accent); color: #fff;
            font-family: 'Manrope', sans-serif;
            font-size: 14px; font-weight: 700;
            border: none; cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            box-shadow: var(--shadow-accent);
        }
        .profile-edit-toggle:hover { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(255,90,48,0.32); }

        /* ===== PROGRESS ===== */
        .profile-progress-wrapper { display: flex; flex-direction: column; gap: 8px; }
        .profile-progress-header { display: flex; justify-content: space-between; align-items: center; }
        .profile-progress-title { font-size: 13px; font-weight: 600; color: var(--muted); letter-spacing: 0.04em; text-transform: uppercase; }
        .profile-progress-value { font-size: 13px; font-weight: 800; color: var(--accent); }
        .profile-progress-bar { height: 6px; background: var(--surface-3); border-radius: 99px; overflow: hidden; }
        .profile-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #FF5A30, #FF9261);
            border-radius: 99px;
            transition: width 1s cubic-bezier(0.4,0,0.2,1);
        }

        /* ===== BADGES ===== */
        .profile-badges { display: flex; gap: 10px; flex-wrap: wrap; }
        .profile-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 99px; font-size: 13px; font-weight: 600; }
        .badge-success { background: #E8F8EE; color: #1A7A40; border: 1px solid #B8EACE; }
        .badge-warning { background: #FFF7E6; color: #9A5A00; border: 1px solid #FFE0A0; }
        .badge-neutral { background: var(--surface-2); color: var(--ink-2); border: 1px solid var(--border-strong); }

        /* ===== TABS ===== */
        .profile-tabs {
            display: flex; gap: 4px;
            background: var(--surface-2); border-radius: var(--radius-md);
            padding: 4px; width: fit-content;
            border: 1px solid var(--border);
        }
        .profile-tab-button {
            padding: 8px 22px; border-radius: 10px;
            border: none; background: transparent;
            color: var(--muted);
            font-family: 'Manrope', sans-serif;
            font-size: 14px; font-weight: 600;
            cursor: pointer; transition: var(--transition);
        }
        .profile-tab-button:hover { color: var(--ink-2); background: rgba(0,0,0,0.04); }
        .profile-tab-button.active { background: var(--surface); color: var(--ink); box-shadow: var(--shadow-sm); }

        /* ===== TAB CONTENT ===== */
        .profile-tab-content { display: none; }
        .profile-tab-content.active {
            display: flex; flex-direction: column; gap: 14px;
            animation: fadeSlideIn 0.22s ease;
        }
        @keyframes fadeSlideIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

        /* ===== SCROLL CARD ===== */
        .profile-scroll-card {
            background: var(--surface-2);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: var(--transition);
        }
        .profile-scroll-card:hover { border-color: rgba(255,90,48,0.2); box-shadow: 0 4px 16px rgba(255,90,48,0.06); }
        .scroll-card-header { display: flex; align-items: center; gap: 14px; padding: 18px 22px 14px; }
        .scroll-card-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--accent-soft);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .scroll-card-icon svg { width:18px; height:18px; stroke:var(--accent); fill:none; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; }
        .scroll-card-header-text { flex:1; min-width:0; }
        .section-title { font-size: 15px; font-weight: 700; color: var(--ink); }
        .scroll-card-subtitle { font-size: 12px; color: var(--muted); font-weight: 500; margin-top: 2px; }
        .scroll-card-progress-value { font-size: 13px; font-weight: 800; color: var(--accent); background: var(--accent-soft); padding: 3px 10px; border-radius: 99px; white-space: nowrap; }
        .scroll-card-progress { height: 3px; background: var(--surface-3); margin: 0 22px; }
        .scroll-card-progress-fill { height: 100%; background: linear-gradient(90deg, #FF5A30, #FF9261); border-radius: 99px; }
        .scroll-card-body { padding: 14px 22px 20px; display: flex; flex-direction: column; gap: 0; }
        .info-row { display: flex; justify-content: space-between; align-items: baseline; gap: 16px; padding: 10px 0; border-bottom: 1px solid var(--border); }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-row .info-label { font-size: 12px; font-weight: 600; color: var(--muted); letter-spacing: 0.05em; text-transform: uppercase; flex-shrink: 0; min-width: 100px; }
        .info-row .info-value { font-size: 14px; font-weight: 600; color: var(--ink-2); text-align: right; }

        /* ===== TIMELINE ===== */
        .profile-education-timeline { display: flex; flex-direction: column; }
        .timeline-item { display: flex; gap: 18px; }
        .timeline-marker { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; padding-top: 22px; }
        .timeline-dot { width: 12px; height: 12px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 0 4px rgba(255,90,48,0.15); flex-shrink: 0; }
        .timeline-line { width: 2px; flex: 1; background: var(--surface-3); margin-top: 8px; min-height: 40px; }
        .timeline-card { flex:1; background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 16px; }
        .timeline-card-header { padding: 16px 20px; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; border-bottom: 1px solid var(--border); }
        .timeline-card-title-row { display: flex; align-items: center; gap: 10px; }
        .timeline-card-title { font-size: 15px; font-weight: 700; color: var(--ink); }
        .timeline-tag { padding: 2px 10px; border-radius: 99px; background: var(--accent-soft); color: var(--accent); font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; }
        .timeline-date-badge { display: flex; flex-direction: column; align-items: flex-end; gap: 2px; }
        .timeline-date-label { font-size: 11px; color: var(--muted); font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; }
        .timeline-date-value { font-size: 14px; font-weight: 700; color: var(--ink); }
        .timeline-card-body { padding: 16px 20px 20px; display: flex; flex-direction: column; gap: 10px; }
        .timeline-row { display: flex; gap: 12px; }
        .timeline-label { font-size: 12px; color: var(--muted); font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; min-width: 70px; flex-shrink: 0; padding-top: 2px; }
        .timeline-value { font-size: 14px; color: var(--ink-2); font-weight: 500; line-height: 1.5; }
        .timeline-progress { height: 4px; background: var(--surface-3); border-radius: 99px; overflow: hidden; margin-top: 6px; }
        .timeline-progress-fill { height: 100%; background: linear-gradient(90deg, #FF5A30, #FF9261); border-radius: 99px; }

        /* ===== EDIT PANEL ===== */
        .profile-edit-panel {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-md);
            padding: 28px 32px 32px;
            display: none;
            flex-direction: column;
            gap: 0;
            position: relative;
            overflow: hidden;
            margin-top: 24px;
        }
        .profile-edit-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #FF5A30, #FF9261);
        }
        .profile-edit-panel.active {
            display: flex;
            animation: fadeSlideIn 0.25s ease;
        }

        /* Секции */
        .profile-form-section {
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding-bottom: 22px;
            margin-bottom: 22px;
            border-bottom: 1px solid var(--border);
        }
        .profile-form-section:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        /* Заголовок секции */
        .profile-form-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 0.09em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-form-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-strong);
        }

        /* Сетки */
        .profile-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 18px;
        }
        .profile-form-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 0.55fr;
            gap: 12px 18px;
        }

        /* Поле */
        .profile-field {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .profile-field-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* Инпуты */
        .text-input {
            width: 100%;
            box-sizing: border-box;
            padding: 9px 12px;
            border: 1.5px solid var(--border-strong);
            border-radius: var(--radius-sm);
            background: var(--surface-2);
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--ink-2);
            outline: none;
            transition: var(--transition);
            appearance: none;
            -webkit-appearance: none;
        }
        .text-input:focus {
            border-color: var(--accent);
            background: var(--surface);
            box-shadow: 0 0 0 3px rgba(255,90,48,0.11);
        }
        .text-input::placeholder {
            color: var(--muted-2);
            font-weight: 400;
        }

        /* Селект со стрелкой */
        .select-wrap { position: relative; }
        .select-wrap::after {
            content: '';
            pointer-events: none;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 0; height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid var(--muted);
        }
        .select-wrap .text-input { padding-right: 30px; cursor: pointer; }

        /* Сообщение об успехе */
        .success-message {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            background: #E8F8EE;
            color: #1A7A40;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid #B8EACE;
            display: none;
            margin-bottom: 6px;
        }
        .success-message.active { display: block; }

        /* Кнопки формы */
        .profile-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
            margin-top: 22px;
        }
        .secondary-button {
            padding: 9px 20px;
            border-radius: 99px;
            border: 1.5px solid var(--border-strong);
            background: var(--surface);
            color: var(--ink-2);
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        .secondary-button:hover { border-color: var(--muted); background: var(--surface-2); }

        .profile-main-grid { display: flex; flex-direction: column; gap: 14px; }

        @media (max-width: 768px) {
            .profile-form-grid,
            .profile-form-grid-3 { grid-template-columns: 1fr; }
            .profile-main-top { flex-direction: column; align-items: center; text-align: center; }
            .profile-avatar-wrap { flex-direction: column; }
            .profile-edit-panel { padding: 22px 18px 24px; }
            .profile-main-card { padding: 24px 20px; }
        }
    </style>

    <div class="profile-page-wrapper">
        <div class="profile-nav-links">
            <a href="{{ route('applications.create') }}" class="{{ request()->routeIs('applications.create') ? 'active' : '' }}">Подать заявку на поступление</a>
            <a href="{{ route('applications.index') }}" class="{{ request()->routeIs('applications.index') ? 'active' : '' }}">Мои заявки</a>
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Мой профиль</a>
        </div>

        <div class="profile-page">
            <div class="profile-container">

                <div class="profile-main-card">
                    <div class="profile-main-top">
                        <div class="profile-avatar-wrap">
                            <div class="profile-avatar">
                                {{ mb_substr($user->name, 0, 1) }}{{ $user->surname ? mb_substr($user->surname, 0, 1) : '' }}
                            </div>
                            <div class="profile-main-info">
                                <div class="profile-name">
                                    {{ $user->surname ? $user->surname . ' ' . $user->name : $user->name }}
                                </div>
                                <div class="profile-tag">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                    Абитуриент
                                </div>
                                <div class="profile-meta">
                                    В кабинете с {{ $user->created_at?->format('d.m.Y') ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <button type="button" class="profile-edit-toggle">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Редактировать профиль
                        </button>
                    </div>

                    <div class="profile-progress-wrapper">
                        <div class="profile-progress-header">
                            <span class="profile-progress-title">Заполненность профиля</span>
                            <span class="profile-progress-value">{{ $completion ?? 0 }}%</span>
                        </div>
                        <div class="profile-progress-bar">
                            <div class="profile-progress-fill" style="width: {{ $completion ?? 0 }};"></div>
                        </div>
                    </div>

                    <div class="profile-badges">
                        @php $completionValue = $completion ?? 0; @endphp
                        @if($completionValue >= 80)
                            <span class="profile-badge badge-success">Почти готов к подаче документов</span>
                        @elseif($completionValue >= 50)
                            <span class="profile-badge badge-warning">Заполните ещё несколько полей</span>
                        @else
                            <span class="profile-badge badge-neutral">Начните заполнять профиль</span>
                        @endif
                    </div>

                    <div class="profile-tabs">
                        <button type="button" class="profile-tab-button active" data-tab="personal">Личная информация</button>
                        <button type="button" class="profile-tab-button" data-tab="education">Образование</button>
                    </div>

                    <div class="profile-main-grid">
                        <div class="profile-tab-content active" data-tab="personal">
                            @php
                                $personalValues = [$user->name, $user->surname, $user->birthdate, $user->citizenship];
                                $personalFilled = collect($personalValues)->filter(fn($v) => !empty($v))->count();
                                $personalProgress = (int) round($personalFilled / (count($personalValues) ?: 1) * 100);

                                $contactValues = [$user->email, $user->phone];
                                $contactFilled = collect($contactValues)->filter(fn($v) => !empty($v))->count();
                                $contactProgress = (int) round($contactFilled / (count($contactValues) ?: 1) * 100);

                                $addressValues = [$user->city, $user->street, $user->house];
                                $addressFilled = collect($addressValues)->filter(fn($v) => !empty($v))->count();
                                $addressProgress = (int) round($addressFilled / (count($addressValues) ?: 1) * 100);
                            @endphp

                            <div class="profile-scroll-card">
                                <div class="scroll-card-header">
                                    <div class="scroll-card-icon">
                                        <svg viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"></circle><path d="M4 20c0-4 3-7 8-7s8 3 8 7"></path></svg>
                                    </div>
                                    <div class="scroll-card-header-text">
                                        <div class="section-title">Личные данные</div>
                                        <div class="scroll-card-subtitle">Имя, дата рождения и гражданство</div>
                                    </div>
                                    <div class="scroll-card-progress-value">{{ $personalProgress }}%</div>
                                </div>
                                <div class="scroll-card-progress">
                                    <div class="scroll-card-progress-fill" style="width: {{ $personalProgress }}%;"></div>
                                </div>
                                <div class="scroll-card-body">
                                    <div class="info-row">
                                        <span class="info-label">Имя</span>
                                        <span class="info-value">{{ $user->name ?: '—' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Фамилия</span>
                                        <span class="info-value">{{ $user->surname ?: '—' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Дата рождения</span>
                                        <span class="info-value">{{ $user->birthdate ? $user->birthdate->format('d.m.Y') : '—' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Гражданство</span>
                                        <span class="info-value">{{ $user->citizenship ?: '—' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-scroll-card">
                                <div class="scroll-card-header">
                                    <div class="scroll-card-icon">
                                        <svg viewBox="0 0 24 24"><path d="M4 4h16v16H4z"></path><path d="M4 9h16"></path><circle cx="8" cy="7" r="1"></circle><circle cx="12" cy="7" r="1"></circle></svg>
                                    </div>
                                    <div class="scroll-card-header-text">
                                        <div class="section-title">Контакты</div>
                                        <div class="scroll-card-subtitle">Почта и телефон для связи</div>
                                    </div>
                                    <div class="scroll-card-progress-value">{{ $contactProgress }}%</div>
                                </div>
                                <div class="scroll-card-progress">
                                    <div class="scroll-card-progress-fill" style="width: {{ $contactProgress }}%;"></div>
                                </div>
                                <div class="scroll-card-body">
                                    <div class="info-row">
                                        <span class="info-label">Email</span>
                                        <span class="info-value">{{ $user->email }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Телефон</span>
                                        <span class="info-value">{{ $user->phone ?: '—' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-scroll-card">
                                <div class="scroll-card-header">
                                    <div class="scroll-card-icon">
                                        <svg viewBox="0 0 24 24"><path d="M3 11l9-8 9 8"></path><path d="M5 10v10h14V10"></path></svg>
                                    </div>
                                    <div class="scroll-card-header-text">
                                        <div class="section-title">Адрес</div>
                                        <div class="scroll-card-subtitle">Город и место проживания</div>
                                    </div>
                                    <div class="scroll-card-progress-value">{{ $addressProgress }}%</div>
                                </div>
                                <div class="scroll-card-progress">
                                    <div class="scroll-card-progress-fill" style="width: {{ $addressProgress }}%;"></div>
                                </div>
                                <div class="scroll-card-body">
                                    <div class="info-row">
                                        <span class="info-label">Адрес проживания</span>
                                        <span class="info-value">
                                            @if($user->city || $user->street || $user->house)
                                                {{ $user->city }}{{ $user->street ? ', ' . $user->street : '' }}{{ $user->house ? ', д. ' . $user->house : '' }}
                                            @else
                                                Не указан
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-tab-content" data-tab="education">
                            @php
                                $educationValues = [$user->school, $user->graduation_year];
                                $educationFilled = collect($educationValues)->filter(fn($v) => !empty($v))->count();
                                $educationProgress = (int) round($educationFilled / (count($educationValues) ?: 1) * 100);
                            @endphp
                            <div class="profile-education-timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-line"></div>
                                    </div>
                                    <div class="timeline-card">
                                        <div class="timeline-card-header">
                                            <div class="timeline-card-title-row">
                                                <div class="timeline-card-title">Основное образование</div>
                                                <span class="timeline-tag">Учёба</span>
                                            </div>
                                            <div class="timeline-date-badge">
                                                <span class="timeline-date-label">Дата</span>
                                                <span class="timeline-date-value">{{ $user->graduation_year ?: '—' }}</span>
                                            </div>
                                        </div>
                                        <div class="timeline-card-body">
                                            <div class="timeline-row">
                                                <div class="timeline-label">Место</div>
                                                <div class="timeline-value">{{ $user->school ?: 'Учебное заведение не указано' }}</div>
                                            </div>
                                            <div class="timeline-row">
                                                <div class="timeline-label">Результат</div>
                                                <div class="timeline-value">
                                                    @if($user->school || $user->graduation_year)
                                                        Завершено среднее образование
                                                    @else
                                                        Информация об образовании пока не заполнена
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-progress">
                                                <div class="timeline-progress-fill" style="width: {{ $educationProgress }}%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="edit-profile-section" class="profile-edit-panel {{ $errors->any() ? 'active' : '' }}">
                    <form class="profile-edit-form" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        @if (session('success'))
                            <div class="success-message active">{{ session('success') }}</div>
                        @endif

                        <div class="profile-form-section">
                            <div class="profile-form-title">Личные данные</div>
                            <div class="profile-form-grid">
                                <div class="profile-field">
                                    <label for="name" class="profile-field-label">Имя</label>
                                    <input id="name" type="text" name="name" class="text-input" required
                                           placeholder="Введите имя" value="{{ old('name', $user->name) }}">
                                </div>
                                <div class="profile-field">
                                    <label for="surname" class="profile-field-label">Фамилия</label>
                                    <input id="surname" type="text" name="surname" class="text-input"
                                           placeholder="Введите фамилию" value="{{ old('surname', $user->surname) }}">
                                </div>
                                <div class="profile-field">
                                    <label for="birthdate" class="profile-field-label">Дата рождения</label>
                                    <input id="birthdate" type="date" name="birthdate" class="text-input"
                                           value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d')) }}">
                                </div>
                                <div class="profile-field">
                                    <label class="profile-field-label">Гражданство</label>
                                    <div class="select-wrap">
                                        @php $c = old('citizenship', $user->citizenship); @endphp
                                        <select name="citizenship" class="text-input">
                                            <option value="" {{ !$c ? 'selected' : '' }}>Не выбрано</option>
                                            <option value="Россия"    {{ $c === 'Россия'    ? 'selected' : '' }}>Россия</option>
                                            <option value="Казахстан" {{ $c === 'Казахстан' ? 'selected' : '' }}>Казахстан</option>
                                            <option value="Беларусь"  {{ $c === 'Беларусь'  ? 'selected' : '' }}>Беларусь</option>
                                            <option value="Другое"    {{ $c === 'Другое'    ? 'selected' : '' }}>Другое</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-form-section">
                            <div class="profile-form-title">Контактная информация</div>
                            <div class="profile-form-grid">
                                <div class="profile-field">
                                    <label for="phone" class="profile-field-label">Телефон</label>
                                    <input id="phone" type="tel" name="phone" class="text-input"
                                           placeholder="+7 (___) ___-__-__" value="{{ old('phone', $user->phone) }}">
                                </div>
                                <div class="profile-field">
                                    <label for="email" class="profile-field-label">Email</label>
                                    <input id="email" type="email" name="email" class="text-input" required
                                           placeholder="example@mail.ru" value="{{ old('email', $user->email) }}">
                                </div>
                            </div>
                        </div>

                        <div class="profile-form-section">
                            <div class="profile-form-title">Адрес проживания</div>
                            <div class="profile-form-grid-3">
                                <div class="profile-field">
                                    <label for="city" class="profile-field-label">Город</label>
                                    <input id="city" type="text" name="city" class="text-input"
                                           placeholder="Москва" value="{{ old('city', $user->city) }}">
                                </div>
                                <div class="profile-field">
                                    <label for="street" class="profile-field-label">Улица</label>
                                    <input id="street" type="text" name="street" class="text-input"
                                           placeholder="ул. Ленина" value="{{ old('street', $user->street) }}">
                                </div>
                                <div class="profile-field">
                                    <label for="house" class="profile-field-label">Дом</label>
                                    <input id="house" type="text" name="house" class="text-input"
                                           placeholder="12" value="{{ old('house', $user->house) }}">
                                </div>
                            </div>
                        </div>

                        <div class="profile-form-section">
                            <div class="profile-form-title">Образование</div>
                            <div class="profile-form-grid">
                                <div class="profile-field">
                                    <label for="school" class="profile-field-label">Учебное заведение</label>
                                    <input id="school" type="text" name="school" class="text-input"
                                           placeholder="МБОУ Школа №1" value="{{ old('school', $user->school) }}">
                                </div>
                                <div class="profile-field">
                                    <label for="graduation_year" class="profile-field-label">Год окончания</label>
                                    <input id="graduation_year" type="text" name="graduation_year" class="text-input"
                                           placeholder="2025" value="{{ old('graduation_year', $user->graduation_year) }}">
                                </div>
                            </div>
                        </div>

                        <div class="profile-form-actions">
                            <button type="button" class="secondary-button profile-edit-cancel">Отмена</button>
                            <button type="submit" class="profile-edit-toggle">Сохранить изменения</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.querySelector('.profile-edit-panel');
            const toggle = document.querySelector('.profile-main-top .profile-edit-toggle');
            const cancel = document.querySelector('.profile-edit-cancel');
            const tabs = document.querySelectorAll('.profile-tab-button');
            const tabContents = document.querySelectorAll('.profile-tab-content');

            function openPanel() {
                panel.classList.add('active');
                document.getElementById('edit-profile-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            function closePanel() {
                panel.classList.remove('active');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            if (toggle) toggle.addEventListener('click', () => panel.classList.contains('active') ? closePanel() : openPanel());
            if (cancel) cancel.addEventListener('click', (e) => { e.preventDefault(); closePanel(); });

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    const target = tab.getAttribute('data-tab');
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    tabContents.forEach(c => c.classList.toggle('active', c.getAttribute('data-tab') === target));
                });
            });

            @if($errors->any() || session('success'))
                openPanel();
            @endif
        });
    </script>
@endsection