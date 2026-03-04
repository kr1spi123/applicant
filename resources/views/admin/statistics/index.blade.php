@extends('layouts.admin')

@section('title', 'Статистика')

@section('content')
<div class="admin-header">
    <div>
        <h1 style="font-size:26px;font-weight:800;color:#1E212C;margin:0 0 4px;">Статистика подачи заявлений</h1>
        <p style="margin:0;font-size:14px;color:#aaa;">Обзор количества поданных заявлений по специальностям</p>
    </div>
    @if($stats->count() > 0)
    <div style="display:flex;gap:20px;">
        <div style="background:#fff;border:1px solid #E5E8ED;border-radius:12px;padding:14px 24px;text-align:center;min-width:120px;">
            <div style="font-size:28px;font-weight:800;color:#FF5A30;">{{ $stats->sum('total_applications') }}</div>
            <div style="font-size:12px;color:#aaa;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Всего заявлений</div>
        </div>
        <div style="background:#fff;border:1px solid #E5E8ED;border-radius:12px;padding:14px 24px;text-align:center;min-width:120px;">
            <div style="font-size:28px;font-weight:800;color:#15803D;">+{{ $stats->sum('today_applications') }}</div>
            <div style="font-size:12px;color:#aaa;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Сегодня</div>
        </div>
        <div style="background:#fff;border:1px solid #E5E8ED;border-radius:12px;padding:14px 24px;text-align:center;min-width:120px;">
            <div style="font-size:28px;font-weight:800;color:#1D4ED8;">{{ $stats->count() }}</div>
            <div style="font-size:12px;color:#aaa;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Специальностей</div>
        </div>
    </div>
    @endif
</div>

@if($stats->count() > 0)
<div style="background:#fff;border-radius:14px;border:1px solid #E5E8ED;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.04);">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#F8F9FA;border-bottom:2px solid #E5E8ED;">
                <th style="padding:14px 20px;text-align:left;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:.06em;width:120px;">Код</th>
                <th style="padding:14px 16px;text-align:left;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:.06em;">Специальность</th>
                <th style="padding:14px 16px;text-align:center;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:.06em;width:200px;">Всего заявлений</th>
                <th style="padding:14px 16px;text-align:center;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:.06em;width:180px;">Сегодня</th>
                <th style="padding:14px 20px;text-align:left;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:.06em;">Заполненность</th>
            </tr>
        </thead>
        <tbody>
            @php $maxApps = $stats->max('total_applications') ?: 1; @endphp
            @foreach($stats as $stat)
            <tr style="border-bottom:1px solid #F4F5F6;transition:background .15s;" onmouseover="this.style.background='#FAFBFC'" onmouseout="this.style.background=''">
                <td style="padding:16px 20px;">
                    <span style="font-family:monospace;font-size:13px;font-weight:700;color:#555;background:#F4F5F6;padding:4px 10px;border-radius:6px;border:1px solid #E5E8ED;white-space:nowrap;">
                        {{ $stat->code ?? '—' }}
                    </span>
                </td>
                <td style="padding:16px;font-weight:600;font-size:15px;color:#1E212C;">{{ $stat->name }}</td>
                <td style="padding:16px;text-align:center;">
                    <span style="font-size:22px;font-weight:800;color:#1E212C;">{{ $stat->total_applications }}</span>
                </td>
                <td style="padding:16px;text-align:center;">
                    @if($stat->today_applications > 0)
                        <span style="font-size:16px;font-weight:700;color:#15803D;background:#F0FDF4;padding:4px 12px;border-radius:8px;border:1px solid #BBF7D0;">
                            +{{ $stat->today_applications }}
                        </span>
                    @else
                        <span style="color:#ddd;font-size:15px;">0</span>
                    @endif
                </td>
                <td style="padding:16px 20px;">
                    @php $pct = $maxApps > 0 ? round($stat->total_applications / $maxApps * 100) : 0; @endphp
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="flex:1;height:8px;background:#F4F5F6;border-radius:999px;overflow:hidden;">
                            <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#FF5A30,#ff8a65);border-radius:999px;transition:width .4s;"></div>
                        </div>
                        <span style="font-size:12px;font-weight:700;color:#888;min-width:36px;text-align:right;">{{ $pct }}%</span>
                    </div>
                </td>
            </tr>
            @endforeach

            {{-- Итого --}}
            <tr style="background:#F8F9FA;border-top:2px solid #E5E8ED;">
                <td colspan="2" style="padding:16px 20px;font-size:13px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.08em;">Итого</td>
                <td style="padding:16px;text-align:center;">
                    <span style="font-size:24px;font-weight:800;color:#FF5A30;">{{ $stats->sum('total_applications') }}</span>
                </td>
                <td style="padding:16px;text-align:center;">
                    @if($stats->sum('today_applications') > 0)
                        <span style="font-size:16px;font-weight:700;color:#15803D;background:#F0FDF4;padding:4px 12px;border-radius:8px;border:1px solid #BBF7D0;">
                            +{{ $stats->sum('today_applications') }}
                        </span>
                    @else
                        <span style="color:#ddd;">0</span>
                    @endif
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
@else
<div style="text-align:center;padding:80px;color:#bbb;font-size:16px;background:#fff;border-radius:14px;border:1px solid #E5E8ED;">
    Данных пока нет
</div>
@endif

@push('styles')
<style>
    .admin-main { max-width:100% !important; padding:24px 30px; }
    .admin-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:16px; }
</style>
@endpush

@endsection
