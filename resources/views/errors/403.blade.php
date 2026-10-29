{{-- Admin/editor melihat halaman ini di dalam panel admin, selain itu (customer/tamu) pakai layout polos --}}
@php $inAdmin = auth()->user()?->can('access_admin'); @endphp

@extends($inAdmin ? 'layouts.admin' : 'layouts.guest')

@section('title', 'Akses Ditolak')

@section('content')
    <div class="text-center py-5">
        <div class="display-1 fw-bold text-tatami">403</div>
        <h1 class="h4 fw-bold">Anda tidak punya akses ke halaman ini</h1>
        <p class="text-muted mb-4">Hubungi admin jika Anda merasa seharusnya bisa membuka halaman ini.</p>

        @if ($inAdmin)
            <a href="{{ route('dashboard') }}" class="btn btn-tatami"><i class="fas fa-home me-1"></i> Kembali ke Dashboard</a>
        @elseauth
            <a href="{{ route('home') }}" class="btn btn-tatami"><i class="fas fa-home me-1"></i> Kembali ke Beranda</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-tatami"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
        @endif
    </div>
@endsection
