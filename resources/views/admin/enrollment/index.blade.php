@extends('layouts.admin')

@section('title', 'Рейтинги по специальностям')

@section('content')
<div class="admin-content">
    <div class="admin-header">
        <h1>Рейтинги по специальностям</h1>
        <p class="admin-subheader">Таблицы заявлений, отсортированные по рейтингу. Верхние — бюджет.</p>
    </div>

    @foreach($specialties as $specialty)
        @php
            $budget = (int) ($specialty->budget_places ?? 0);
            $total = (int) ($specialty->total_places ?? $budget);
            $apps = $specialty->applications;
        @endphp

        <div class="table-container" style="margin-bottom: 32px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <h2 style="margin:0;font-size:20px;">{{ $specialty->name }}</h2>
                <div style="font-size:14px;color:#666;">
                    Бюджетных мест: <strong>{{ $budget }}</strong> • Всего мест: <strong>{{ $total }}</strong> • Заявлений: <strong>{{ $apps->count() }}</strong>
                </div>
            </div>

            @if($apps->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:6%">Место</th>
                        <th style="width:30%">ФИО</th>
                        <th style="width:14%">Рейтинг</th>
                        <th style="width:14%">Аттестат</th>
                        <th style="width:14%">ЕГЭ</th>
                        <th style="width:12%">Тип</th>
                        <th style="width:10%">Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($apps as $i => $app)
                        @php
                            $position = $i + 1;
                            $type = $position <= $budget
                                ? 'Бюджет'
                                : ($position <= $total ? 'Платно' : 'Вне мест');
                            $badgeClass = $type === 'Бюджет' ? 'status-approved'
                                : ($type === 'Платно' ? 'status-pending' : 'status-rejected');
                        @endphp
                        <tr>
                            <td>{{ $position }}</td>
                            <td>{{ $app->user->name }}</td>
                            <td>{{ number_format($app->rating, 1) }}</td>
                            <td>{{ $app->certificate_score }}</td>
                            <td>{{ $app->ege_score ?? '-' }}</td>
                            <td>
                                <span class="status-badge {{ $badgeClass }}">{{ $type }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($app->status) {
                                        'Требует подтверждения' => 'pending',
                                        'На рассмотрении' => 'pending',
                                        'Проверено' => 'approved',
                                        'Одобрено' => 'approved',
                                        'Отклонено' => 'rejected',
                                        default => 'pending',
                                    };
                                @endphp
                                <span class="status-badge status-{{ $statusClass }}">{{ $app->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div style="color:#999;font-size:14px;">Заявлений пока нет</div>
            @endif
        </div>
    @endforeach
</div>
@endsection
