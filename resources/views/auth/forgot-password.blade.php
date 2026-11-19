@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 400px;">
        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold text-tatami mb-1 notranslate">Tatami Indonesia</h1>
                <p class="text-muted small mb-0">Masukkan email akun Anda. Kami kirim link untuk membuat password baru.</p>
            </div>

            @include('partials.admin.flash')

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <x-admin.input name="email" type="email" label="Email" required autofocus autocomplete="email" wrapper="mb-4" />

                <button type="submit" class="btn btn-tatami w-100 py-2">
                    <i class="fas fa-paper-plane me-1"></i> Kirim Link
                </button>
            </form>

            <p class="text-center small text-muted mt-4 mb-0">
                Ingat password? <a href="{{ route('login') }}" class="text-tatami fw-semibold">Masuk</a>
            </p>
        </div>
    </div>
@endsection
