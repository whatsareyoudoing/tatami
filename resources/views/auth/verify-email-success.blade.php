@extends('layouts.guest')

@section('title', 'Email Terverifikasi')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 w-100 text-center" style="max-width: 440px;">
        <div class="card-body p-4 p-sm-5">
            <div class="display-5 text-success mb-2"><i class="fas fa-check-circle"></i></div>
            <h1 class="h4 fw-bold text-tatami mb-2">Email Terverifikasi</h1>
            <p class="text-muted mb-4">Terima kasih, {{ $user->name }}. Akun Anda sudah aktif dan siap dipakai belanja.</p>

            <div class="bg-light rounded-3 p-3 mb-4 small">
                Anda bisa login memakai email<br>
                <strong>{{ $user->email }}</strong><br>
                atau username <strong>{{ $user->username }}</strong>
            </div>

            <a href="{{ url('/produk') }}" class="btn btn-tatami w-100 py-2">
                <i class="fas fa-shopping-bag me-1"></i> Mulai Belanja
            </a>
        </div>
    </div>
@endsection
