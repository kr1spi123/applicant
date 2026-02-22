@extends('layouts.main')

@section('title', 'Проверка заявления')

@push('styles')
<style>
    .verify-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .verify-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .verify-status {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        margin-top: 15px;
    }
    
    .status-pending { background: #FFF4E5; color: #FF9800; }
    .status-approved { background: #E8F5E9; color: #4CAF50; }
    .status-rejected { background: #FFEBEE; color: #F44336; }
    
    .verify-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .verify-item label {
        display: block;
        font-size: 12px;
        color: #787A80;
        margin-bottom: 5px;
    }
    
    .verify-item div {
        font-size: 16px;
        color: #424551;
        font-weight: 500;
    }
    
    .ranking-info {
        background: #F4F5F7;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="verify-container">
    <div class="verify-header">
        <h1 style="margin: 0;">Заявление #{{ $application->id }}</h1>
        <p style="color: #787A80; margin-top: 10px;">Официальная информация о заявлении</p>
        
        @php
            $status_class = '';
            switch($application->status) {
                case 'На рассмотрении': $status_class = 'status-pending'; break;
                case 'Одобрено': $status_class = 'status-approved'; break;
                case 'Отклонено': $status_class = 'status-rejected'; break;
            }
        @endphp
        <div class="verify-status {{ $status_class }}">
            {{ $application->status }}
        </div>
    </div>

    <div class="verify-grid">
        <div class="verify-item">
            <label>Абитуриент</label>
            <div>{{ $application->full_name }}</div>
        </div>
        <div class="verify-item">
            <label>Специальность</label>
            <div>{{ $application->specialty->name }}</div>
        </div>
        <div class="verify-item">
            <label>Дата подачи</label>
            <div>{{ $application->created_at->format('d.m.Y H:i') }}</div>
        </div>
        <div class="verify-item">
            <label>Email</label>
            <div>{{ $application->email }}</div>
        </div>
    </div>

    <div class="ranking-info">
        <h3 style="margin-top: 0;">Рейтинговые показатели</h3>
        <div class="verify-grid">
            <div class="verify-item">
                <label>Баллы ЕГЭ</label>
                <div>{{ $application->ege_score }}</div>
            </div>
            <div class="verify-item">
                <label>Средний балл аттестата</label>
                <div>{{ $application->certificate_score }}</div>
            </div>
            <div class="verify-item">
                <label>Индивидуальные достижения</label>
                <div>{{ $application->has_achievements ? 'Есть' : 'Нет' }}</div>
            </div>
            <div class="verify-item">
                <label>Итоговый рейтинг</label>
                <div style="color: #FF5A30; font-size: 20px; font-weight: bold;">{{ $application->rating }}</div>
            </div>
        </div>
        @if($application->certificate_file)
        <div class="verify-item" style="margin-top: 10px;">
            <label>Скан аттестата</label>
            <div>
                <a href="{{ route('applications.certificate', $application) }}" style="color: #FF5A30; text-decoration: none;">Открыть загруженный файл</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
