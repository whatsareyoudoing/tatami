@extends('account.layout')

@section('account')
    <h1 class="h3 fw-bold mb-4">Akun Saya</h1>

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

    {{-- Diisi daftar pesanan di tahap 3 --}}
    <x-admin.card title="Pesanan Saya">
        <div class="text-center text-muted py-4">
            <i class="fas fa-shopping-bag fs-2 mb-2 d-block"></i>
            Belum ada pesanan.
            <div class="mt-3">
                <a href="{{ url('/produk') }}" class="btn btn-tatami btn-sm">Mulai Belanja</a>
            </div>
        </div>
    </x-admin.card>
@endsection
