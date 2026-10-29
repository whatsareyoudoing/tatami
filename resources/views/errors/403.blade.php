{{-- User login tampil di dalam panel admin, selain itu pakai layout polos --}}
@extends(auth()->check() ? 'layouts.admin' : 'layouts.guest')

@section('title', 'Akses Ditolak')

@section('content')
    <div class="text-center py-5">
        <div class="display-1 fw-bold text-tatami">403</div>
        <h1 class="h4 fw-bold">Anda tidak punya akses ke halaman ini</h1>
        <p class="text-muted mb-4">Hubungi admin jika Anda merasa seharusnya bisa membuka halaman ini.</p>

        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-tatami"><i class="fas fa-home me-1"></i> Kembali ke Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-tatami"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
        @endauth
    </div>
@endsection
