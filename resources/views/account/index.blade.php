@extends('account.layout')

@php $hero = ['title' => 'Akun Saya', 'crumbs' => ['Akun Saya' => null]]; @endphp

@section('account')
    <x-admin.card title="Data Akun">
        <x-slot:actions>
            <a href="{{ route('account.edit') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pen me-1"></i> Ubah</a>
        </x-slot:actions>

        <dl class="row mb-0">
            <dt class="col-sm-4 text-muted fw-normal">Nama</dt>
            <dd class="col-sm-8">{{ $user->name }}</dd>

            <dt class="col-sm-4 text-muted fw-normal">Email</dt>
            <dd class="col-sm-8">
                {{ $user->email }}
                <span class="badge text-bg-success ms-1"><i class="fas fa-check me-1"></i>Terverifikasi</span>
            </dd>

            <dt class="col-sm-4 text-muted fw-normal">Username</dt>
            <dd class="col-sm-8">{{ $user->username }} <small class="text-muted">(bisa dipakai untuk login selain email)</small></dd>

            <dt class="col-sm-4 text-muted fw-normal">Bergabung sejak</dt>
            <dd class="col-sm-8 mb-0">{{ $user->created_at->translatedFormat('d F Y') }}</dd>
        </dl>
    </x-admin.card>

    <x-admin.card title="Pesanan Terakhir">
        @if ($recentOrders->isNotEmpty())
            <x-slot:actions>
                <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-secondary">Lihat semua</a>
            </x-slot:actions>
        @endif

        @forelse ($recentOrders as $order)
            <a href="{{ route('account.orders.show', $order) }}" @class(['d-flex justify-content-between align-items-center gap-2 text-decoration-none text-reset py-2', 'border-top' => !$loop->first])>
                <div>
                    <div class="fw-semibold notranslate">{{ $order->order_number }}</div>
                    <div class="small text-muted">{{ $order->created_at->translatedFormat('d M Y') }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">@rupiah($order->total)</div>
                    <span class="badge text-bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </div>
            </a>
        @empty
            <div class="text-center text-muted py-4">
                <i class="fas fa-shopping-bag fs-2 mb-2 d-block"></i>
                Belum ada pesanan.
                <div class="mt-3">
                    <a href="{{ route('shop.index') }}" class="btn btn-tatami btn-sm">Mulai Belanja</a>
                </div>
            </div>
        @endforelse
    </x-admin.card>
@endsection
