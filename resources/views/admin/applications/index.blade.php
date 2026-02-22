@extends('layouts.admin')

@section('title', 'Управление заявками')

@section('content')
<div class="admin-header">
    <h1>Заявки абитуриентов</h1>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>ФИО</th>
            <th>Специальность</th>
            <th>Место</th>
            <th>Рейтинг</th>
            <th>Балл аттестата</th>
            <th>Балл ЕГЭ</th>
            <th>Дата подачи</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach($applications as $application)
            <tr>
                <td>{{ $application->id }}</td>
                <td>{{ $application->user->name }}</td>
                <td>{{ $application->specialty->name }}</td>
                <td>{{ app(\App\Services\RankingService::class)->getPosition($application) }}</td>
                <td>{{ number_format($application->rating, 1) }}</td>
                <td>{{ $application->certificate_score }}</td>
                <td>{{ $application->ege_score ?? '-' }}</td>
                <td>{{ $application->created_at->format('d.m.Y H:i') }}</td>
                <td>
                    <span class="status-badge status-{{ match($application->status) {
                        'Требует подтверждения' => 'pending',
                        'На рассмотрении' => 'pending',
                        'Проверено' => 'approved',
                        'Одобрено' => 'approved',
                        'Отклонено' => 'rejected',
                        default => 'pending'
                    } }}">
                        {{ $application->status }}
                    </span>
                </td>
                <td>
                    <form action="{{ route('admin.applications.update-status', $application) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" style="padding: 4px; border-radius: 4px;">
                            <option value="Требует подтверждения" {{ $application->status == 'Требует подтверждения' ? 'selected' : '' }}>Требует подтверждения</option>
                            <option value="На рассмотрении" {{ $application->status == 'На рассмотрении' ? 'selected' : '' }}>На рассмотрении</option>
                            <option value="Проверено" {{ $application->status == 'Проверено' ? 'selected' : '' }}>Проверено</option>
                            <option value="Одобрено" {{ $application->status == 'Одобрено' ? 'selected' : '' }}>Одобрено</option>
                            <option value="Отклонено" {{ $application->status == 'Отклонено' ? 'selected' : '' }}>Отклонено</option>
                        </select>
                    </form>
                    <form action="{{ route('admin.applications.update-scores', $application) }}" method="POST" style="margin-top: 8px; display: grid; grid-template-columns: repeat(4, auto); gap: 6px; align-items: center;">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="ege_score" value="{{ $application->ege_score }}" min="0" max="300" placeholder="ЕГЭ" style="width: 90px; padding: 4px;">
                        <input type="number" step="0.1" name="certificate_score" value="{{ $application->certificate_score }}" min="3" max="5" placeholder="Аттестат" style="width: 90px; padding: 4px;">
                        <input type="text" name="verification_notes" value="{{ $application->verification_notes }}" placeholder="Замечания" style="width: 160px; padding: 4px;">
                        <label style="display: inline-flex; align-items: center; gap: 6px;">
                            <input type="checkbox" name="is_verified" value="1" {{ $application->is_verified ? 'checked' : '' }}>
                            <span style="font-size: 12px;">Проверено</span>
                        </label>
                        <button class="btn btn-primary btn-sm" type="submit">Сохранить</button>
                    </form>
                    <a href="{{ route('applications.verify', $application->id) }}" class="btn btn-primary btn-sm" style="margin-top: 5px;">Просмотр</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('styles')
<style>
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }
    .status-pending { background: #FFF3CD; color: #856404; }
    .status-approved { background: #D4EDDA; color: #155724; }
    .status-rejected { background: #F8D7DA; color: #721C24; }
</style>
@endpush
@endsection
