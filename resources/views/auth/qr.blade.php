@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="container-auth">
        <div class="auth-message">
            <div class="auth-message-content">
                <h3>Вход по QR-коду</h3>
                <p>Отсканируйте этот QR-код на устройстве, где хотите войти.</p>
                <div>
                    {!! QrCode::size(260)->generate(route('auth.qr.consume', ['token' => $token->token])) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
