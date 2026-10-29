@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 440px;">
        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold text-tatami mb-1">Daftar Akun</h1>
                <p class="text-muted small mb-0">Buat akun untuk mulai belanja di Tatami Indonesia</p>
            </div>

            @error('form')
                <div class="alert alert-danger small">{{ $message }}</div>
            @enderror
            @error('website')
                <div class="alert alert-danger small">{{ $message }}</div>
            @enderror

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <input type="hidden" name="form_token" value="{{ $formToken }}">

                {{-- Honeypot: tidak terlihat oleh manusia, biasanya diisi bot. Jangan dihapus. --}}
                <div style="position: absolute; left: -9999px;" aria-hidden="true">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <x-admin.input name="name" label="Nama Lengkap" required autofocus autocomplete="name" />
                <x-admin.input name="email" type="email" label="Email" required autocomplete="email"
                               hint="Kode verifikasi akan dikirim ke email ini." />
                <x-admin.input name="password" type="password" label="Password" required autocomplete="new-password" hint="Minimal 8 karakter." />
                <x-admin.input name="password_confirmation" type="password" label="Ulangi Password" required autocomplete="new-password" wrapper="mb-4" />

                <button type="submit" class="btn btn-tatami w-100 py-2">
                    <i class="fas fa-user-plus me-1"></i> Daftar
                </button>
            </form>

            <p class="text-center small text-muted mt-4 mb-0">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-tatami fw-semibold">Masuk</a>
            </p>
        </div>
    </div>
@endsection
