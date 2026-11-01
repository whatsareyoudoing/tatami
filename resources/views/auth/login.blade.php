@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 400px;">
        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold text-tatami mb-1 notranslate">Tatami Indonesia</h1>
                <p class="text-muted small mb-0">Masuk ke akun Anda</p>
            </div>

            <form action="{{ url('/login') }}" method="POST">
                @csrf {{-- WAJIB untuk keamanan dari serangan CSRF --}}

                <x-admin.input name="username" label="Username atau Email" required autofocus autocomplete="username" />
                <x-admin.input name="password" type="password" label="Password" required autocomplete="current-password" wrapper="mb-4" />

                <button type="submit" class="btn btn-tatami w-100 py-2">
                    <i class="fas fa-sign-in-alt me-1"></i> Masuk
                </button>
            </form>

            <p class="text-center small text-muted mt-4 mb-0">
                Belum punya akun? <a href="{{ route('register') }}" class="text-tatami fw-semibold">Daftar</a>
            </p>
        </div>
    </div>
@endsection
