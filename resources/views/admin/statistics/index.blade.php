@extends('layouts.admin')

@section('title', 'Статистика')

@section('content')

    <div class="stat-header">
        <div>
            <h1 class="stat-title">Статистика заявлений</h1>
            <p class="stat-sub">Обзор по специальностям</p>
        </div>
        @if($stats->count() > 0)
            <div class="stat-kpis">
                <div class="kpi">
                    <div class="kpi__val kpi__val--orange">{{ $stats->sum('total_applications') }}</div>
                    <div class="kpi__label">Всего заявлений</div>
                </div>
                <div class="kpi">
                    <div class="kpi__val kpi__val--green">+{{ $stats->sum('today_applications') }}</div>
                    <div class="kpi__label">Сегодня</div>
                </div>
                <div class="kpi">
                    <div class="kpi__val kpi__val--blue">{{ $stats->count() }}</div>
                    <div class="kpi__label">Специальностей</div>
                </div>
            </div>
        @endif
    </div>

    @if($stats->count() > 0)
        <div class="stat-table-wrap">
            <table class="stat-table">
                <thead>
                    <tr>
                        <th class="th-code">Код</th>
                        <th>Специальность</th>
                        <th class="th-num">Всего</th>
                        <th class="th-num">Сегодня</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = $stats->sum('total_applications') ?: 1;
                        $maxApps = $stats->max('total_applications') ?: 1;
                    @endphp
                    @foreach($stats->sortByDesc('total_applications') as $stat)
                        @php $pct = round($stat->total_applications / $maxApps * 100); @endphp
                        <tr>
                            <td>
                                <span class="code-badge">{{ $stat->code ?? '—' }}</span>
                            </td>
                            <td>
                                <div class="spec-name">{{ $stat->name }}</div>
                                <div class="spec-bar">
                                    <div class="spec-bar__fill" style="width:{{ $pct }}%"></div>
                                </div>
                            </td>
                            <td class="td-num">
                                <span class="num-total">{{ $stat->total_applications }}</span>
                                <span
                                    class="num-share">{{ $total > 0 ? round($stat->total_applications / $total * 100) : 0 }}%</span>
                            </td>
                            <td class="td-num">
                                @if($stat->today_applications > 0)
                                    <span class="today-badge">+{{ $stat->today_applications }}</span>
                                @else
                                    <span class="today-empty">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="tf-label">Итого</td>
                        <td class="td-num">
                            <span class="num-total num-total--orange">{{ $stats->sum('total_applications') }}</span>
                        </td>
                        <td class="td-num">
                            @if($stats->sum('today_applications') > 0)
                                <span class="today-badge">+{{ $stats->sum('today_applications') }}</span>
                            @else
                                <span class="today-empty">—</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="stat-empty">Данных пока нет</div>
    @endif

    @push('styles')
        <style>
            .admin-main {
                max-width: 100% !important;
                padding: 24px 30px;
            }

            /* Header */
            .stat-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 28px;
                flex-wrap: wrap;
                gap: 16px;
            }

            .stat-title {
                font-size: 26px;
                font-weight: 800;
                color: #1E212C;
                margin: 0 0 4px;
            }

            .stat-sub {
                font-size: 14px;
                color: #aaa;
                margin: 0;
            }

            /* KPIs */
            .stat-kpis {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
            }

            .kpi {
                background: #fff;
                border: 1px solid #E5E8ED;
                border-radius: 14px;
                padding: 16px 28px;
                text-align: center;
                min-width: 110px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            }

            .kpi__val {
                font-size: 30px;
                font-weight: 800;
                line-height: 1.1;
            }

            .kpi__val--orange {
                color: #FF5A30;
            }

            .kpi__val--green {
                color: #15803D;
            }

            .kpi__val--blue {
                color: #1D4ED8;
            }

            .kpi__label {
                font-size: 11px;
                font-weight: 700;
                color: #aaa;
                text-transform: uppercase;
                letter-spacing: .06em;
                margin-top: 4px;
            }

            /* Table */
            .stat-table-wrap {
                background: #fff;
                border-radius: 14px;
                border: 1px solid #E5E8ED;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            }

            .stat-table {
                width: 100%;
                border-collapse: collapse;
            }

            .stat-table thead tr {
                background: #F8F9FA;
                border-bottom: 2px solid #E5E8ED;
            }

            .stat-table th {
                padding: 13px 20px;
                text-align: left;
                font-size: 11px;
                font-weight: 700;
                color: #888;
                text-transform: uppercase;
                letter-spacing: .06em;
                white-space: nowrap;
            }

            .th-code {
                width: 130px;
            }

            .th-num {
                width: 130px;
                text-align: center !important;
            }

            .stat-table tbody tr {
                border-bottom: 1px solid #F4F5F6;
                transition: background .12s;
            }

            .stat-table tbody tr:last-child {
                border-bottom: none;
            }

            .stat-table tbody tr:hover {
                background: #FAFBFC;
            }

            .stat-table td {
                padding: 14px 20px;
                vertical-align: middle;
            }

            /* Code badge */
            .code-badge {
                font-family: monospace;
                font-size: 13px;
                font-weight: 700;
                color: #555;
                background: #F4F5F6;
                padding: 5px 10px;
                border-radius: 8px;
                border: 1px solid #E5E8ED;
                white-space: nowrap;
                display: inline-block;
            }

            /* Spec name + inline bar */
            .spec-name {
                font-size: 15px;
                font-weight: 600;
                color: #1E212C;
                margin-bottom: 6px;
            }

            .spec-bar {
                height: 4px;
                background: #F0F1F3;
                border-radius: 999px;
                overflow: hidden;
                max-width: 400px;
            }

            .spec-bar__fill {
                height: 100%;
                background: linear-gradient(90deg, #FF5A30, #FFAA80);
                border-radius: 999px;
                transition: width .5s cubic-bezier(.4, 0, .2, 1);
            }

            /* Numbers */
            .td-num {
                text-align: center;
            }

            .num-total {
                font-size: 22px;
                font-weight: 800;
                color: #1E212C;
                display: block;
                line-height: 1;
            }

            .num-total--orange {
                color: #FF5A30;
            }

            .num-share {
                font-size: 11px;
                font-weight: 600;
                color: #C0C4CC;
                display: block;
                margin-top: 2px;
            }

            /* Today badge */
            .today-badge {
                display: inline-block;
                font-size: 14px;
                font-weight: 700;
                color: #15803D;
                background: #F0FDF4;
                padding: 4px 12px;
                border-radius: 20px;
                border: 1px solid #BBF7D0;
            }

            .today-empty {
                color: #ddd;
                font-size: 16px;
            }

            /* Footer */
            .stat-table tfoot tr {
                background: #F8F9FA;
                border-top: 2px solid #E5E8ED;
            }

            .stat-table tfoot td {
                padding: 14px 20px;
            }

            .tf-label {
                font-size: 12px;
                font-weight: 700;
                color: #888;
                text-transform: uppercase;
                letter-spacing: .08em;
            }

            .stat-empty {
                text-align: center;
                padding: 80px;
                color: #bbb;
                font-size: 16px;
                background: #fff;
                border-radius: 14px;
                border: 1px solid #E5E8ED;
            }
        </style>
    @endpush

@endsection