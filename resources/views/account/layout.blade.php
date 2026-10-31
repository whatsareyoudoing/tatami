{{--
    Kerangka halaman akun customer: layout website (navbar + footer) + menu akun di kiri.
    Halaman turunan mengisi @section('account').
--}}
@extends('layouts.frontend')

@section('title', 'Akun Saya')

@push('styles')
    <link rel="stylesheet" href="@asset('css/admin.css')">
@endpush

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-tatami text-white d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px;">
                            {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-semibold text-truncate">{{ auth()->user()->name }}</div>
                            <small class="text-muted text-truncate d-block">{{ auth()->user()->email }}</small>
                        </div>
                    </div>

                    <nav class="nav nav-pills flex-column gap-1 admin-sidebar">
                        <a href="{{ route('account.index') }}" @class(['nav-link', 'active' => request()->routeIs('account.index')])>
                            <i class="fas fa-user fa-fw me-2"></i>Akun Saya
                        </a>
                        <a href="{{ route('account.orders') }}" @class(['nav-link', 'active' => request()->routeIs('account.orders*')])>
                            <i class="fas fa-shopping-bag fa-fw me-2"></i>Pesanan Saya
                        </a>
                        <a href="{{ route('account.edit') }}" @class(['nav-link', 'active' => request()->routeIs('account.edit')])>
                            <i class="fas fa-user-edit fa-fw me-2"></i>Ubah Profil & Password
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="nav-link text-danger w-100 text-start border-0 bg-transparent">
                                <i class="fas fa-sign-out-alt fa-fw me-2"></i>Keluar
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
        </aside>

        <div class="col-lg-9">
            @include('partials.admin.flash')
            @yield('account')
        </div>
    </div>
</div>
@endsection
