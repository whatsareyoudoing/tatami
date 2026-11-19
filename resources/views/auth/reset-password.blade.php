@extends('layouts.guest')

@section('title', 'Password Baru')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 400px;">
        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold text-tatami mb-1 notranslate">Tatami Indonesia</h1>
                <p class="text-muted small mb-0">Buat password baru untuk akun Anda</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <x-admin.input name="email" type="email" label="Email" :value="$email" required autocomplete="email" />
                <x-admin.input name="password" type="password" label="Password Baru" required autofocus autocomplete="new-password" hint="Minimal 8 karakter." />
                <x-admin.input name="password_confirmation" type="password" label="Ulangi Password Baru" required autocomplete="new-password" wrapper="mb-4" />

                <button type="submit" class="btn btn-tatami w-100 py-2">
                    <i class="fas fa-key me-1"></i> Simpan Password
                </button>
            </form>
        </div>
    </div>
@endsection
