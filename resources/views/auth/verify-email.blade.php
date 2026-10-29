@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 440px;">
        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <div class="display-6 text-tatami mb-2"><i class="fas fa-envelope-open-text"></i></div>
                <h1 class="h4 fw-bold text-tatami mb-2">Verifikasi Email</h1>
                <p class="text-muted small mb-0">
                    Kami sudah mengirim kode 6 angka ke<br>
                    <strong class="text-body">{{ $user->email }}</strong><br>
                    Kode berlaku {{ $expiresMinutes }} menit. Cek juga folder spam.
                </p>
            </div>

            @include('partials.admin.flash')

            <form action="{{ route('verification.verify') }}" method="POST">
                @csrf
                <x-admin.input name="code" label="Kode Verifikasi" required autofocus
                               inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code"
                               placeholder="000000" class="text-center fs-4 fw-bold" style="letter-spacing: .5rem;" />

                <button type="submit" class="btn btn-tatami w-100 py-2">
                    <i class="fas fa-check me-1"></i> Verifikasi
                </button>
            </form>

            <form action="{{ route('verification.resend') }}" method="POST" class="text-center mt-3">
                @csrf
                <span class="small text-muted">Tidak menerima kode?</span>
                <button type="submit" id="resend-button" class="btn btn-link btn-sm text-tatami fw-semibold p-0 align-baseline"
                        data-wait="{{ $secondsUntilResend }}" @disabled($secondsUntilResend > 0)>
                    Kirim ulang kode<span id="resend-timer">{{ $secondsUntilResend > 0 ? " ({$secondsUntilResend})" : '' }}</span>
                </button>
            </form>

            <hr class="my-4">

            <form action="{{ route('verification.cancel') }}" method="POST" class="text-center">
                @csrf
                <span class="small text-muted">Salah memasukkan email?</span>
                <button type="submit" class="btn btn-link btn-sm text-tatami fw-semibold p-0 align-baseline">Daftar ulang</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Hitung mundur tombol "Kirim ulang kode" (server tetap mengecek jedanya)
    (function () {
        var button = document.getElementById('resend-button');
        var timer = document.getElementById('resend-timer');
        var seconds = parseInt(button.dataset.wait, 10);

        if (seconds <= 0) {
            return;
        }

        var interval = setInterval(function () {
            seconds--;
            if (seconds <= 0) {
                clearInterval(interval);
                button.disabled = false;
                timer.textContent = '';
            } else {
                timer.textContent = ' (' + seconds + ')';
            }
        }, 1000);
    })();
</script>
@endpush
