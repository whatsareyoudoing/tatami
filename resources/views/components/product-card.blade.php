{{--
    Kartu produk di website (daftar produk, sorotan beranda, produk terkait).
    $product harus dimuat lewat scope Product::forCard().
    Stok habis: kartu tetap tampil tapi abu-abu dan tidak bisa diklik.
--}}
@props(['product'])

@php
    $soldOut = $product->stok_tersedia < 1;
    $isRange = $product->harga_max > $product->harga_min;
@endphp

<div @class(['card h-100 product-card', 'product-card-disabled' => $soldOut]) @if ($soldOut) aria-disabled="true" @endif>
    <a @unless ($soldOut) href="{{ route('shop.show', $product->slug) }}" @endunless class="text-decoration-none text-reset">
        <div class="ratio ratio-1x1 bg-light rounded-top overflow-hidden">
            @if ($product->cover_url)
                <img src="{{ $product->cover_url }}" class="card-img-top object-fit-cover" alt="{{ $product->nama }}" loading="lazy">
            @else
                <div class="d-flex align-items-center justify-content-center text-muted"><i class="fas fa-image fs-1"></i></div>
            @endif

            <div class="d-flex flex-column align-items-start gap-1 p-2 product-card-badges">
                @if ($soldOut)
                    <span class="badge text-bg-dark">Stok habis</span>
                @elseif ($product->diskon_persen)
                    {{-- Diskon dihitung otomatis dari harga coret; untuk range harga = diskon terbesar --}}
                    <span class="badge text-bg-danger">{{ $isRange ? 'Diskon s/d ' : '-' }}{{ $product->diskon_persen }}%</span>
                @endif
                @if ($product->youtube_id)
                    <span class="badge text-bg-light"><i class="fab fa-youtube text-danger me-1"></i>Video</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <span class="fw-bold text-muted small">{{ $product->category?->name }}</span>
            <p class="card-text mb-1">{{ $product->nama }}</p>
            <span class="fw-bold text-decoration-underline">
                @rupiah($product->harga_min)@if ($isRange) – @rupiah($product->harga_max)@endif
            </span>
            @if (!$isRange && $product->harga_coret)
                <small class="text-muted text-decoration-line-through ms-1">@rupiah($product->harga_coret)</small>
            @endif
        </div>
    </a>
</div>
