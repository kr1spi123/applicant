@extends('layouts.admin')

@section('title', 'Статистика')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/statistics.css') }}">
@endpush

@section('content')
<div class="admin-content">
    <div class="admin-header">
        <h1>Статистика подачи заявлений</h1>
        <p class="admin-subheader">Обзор количества поданных заявлений по специальностям</p>
    </div>

    @if($stats->count() > 0)
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50%">Специальность</th>
                    <th style="width: 25%">Всего заявлений</th>
                    <th style="width: 25%">Подано сегодня</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats as $stat)
                <tr>
                    <td>{{ $stat->name }}</td>
                    <td>
                        <span class="total-count">{{ $stat->total_applications }}</span>
                    </td>
                    <td>
                        @if($stat->today_applications > 0)
                            <span class="today-count">+{{ $stat->today_applications }}</span>
                        @else
                            <span style="color: #999; font-size: 14px;">0</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                
                <!-- Summary Row -->
                <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td style="text-transform: uppercase; letter-spacing: 1px;">Итого</td>
                    <td>
                        <span class="total-count">{{ $stats->sum('total_applications') }}</span>
                    </td>
                    <td>
                        @if($stats->sum('today_applications') > 0)
                            <span class="today-count">+{{ $stats->sum('today_applications') }}</span>
                        @else
                            <span style="color: #999; font-size: 14px;">0</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @else
    <div class="no-data">
        <p>Данных пока нет</p>
    </div>
    @endif
</div>
@endsection
