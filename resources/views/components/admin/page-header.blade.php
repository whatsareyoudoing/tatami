{{--
    Judul halaman + tombol aksi di kanan.
    <x-admin.page-header title="Daftar Produk"> ...tombol... </x-admin.page-header>
    <x-admin.page-header title="Edit Produk" :back="route('product.index')" />
--}}
@props(['title', 'back' => null])

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h1 class="h3 fw-bold mb-0">{{ $title }}</h1>

    <div class="d-flex gap-2">
        {{ $slot }}
        @if ($back)
            <a href="{{ $back }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        @endif
    </div>
</div>
