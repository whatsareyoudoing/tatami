@extends('layouts.frontend')

@section('title', 'Keranjang')

@section('content')
<x-page-hero title="Keranjang Belanja" :crumbs="['Keranjang Belanja' => null]" />

<div class="container py-4 mb-4">

    @include('partials.admin.flash')
    @error('quantity')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @if ($items->isEmpty())
        <div class="bg-white rounded-3 border text-center text-muted py-5">
            <i class="fas fa-shopping-cart fs-1 mb-3 d-block"></i>
            Keranjang Anda masih kosong.
            <div class="mt-3"><a href="{{ route('shop.index') }}" class="btn btn-tatami">Mulai Belanja</a></div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="bg-white rounded-3 border">
                    @foreach ($items as $item)
                        @php
                            $variant = $item->variant;
                            $product = $variant->product;
                            $image = $variant->images->first() ?? $product?->cover;
                            $available = $item->isAvailable();
                        @endphp
                        <div @class(['d-flex gap-3 p-3', 'border-top' => !$loop->first, 'opacity-75' => !$available])>
                            <div class="flex-shrink-0">
                                @if ($image)
                                    <img src="{{ $image->thumb_url }}" alt="" class="rounded border cart-thumb" loading="lazy">
                                @else
                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted cart-thumb"><i class="fas fa-image"></i></div>
                                @endif
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        @if ($available)
                                            <a href="{{ route('shop.show', $product->slug) }}" class="fw-semibold text-reset text-decoration-none">{{ $product->nama }}</a>
                                        @else
                                            <span class="fw-semibold">{{ $product?->nama ?? 'Produk' }}</span>
                                        @endif
                                        @if ($variant->optionLabel())
                                            <div class="small text-muted">{{ $variant->optionLabel() }}</div>
                                        @endif
                                        <div class="small">@rupiah($variant->harga)</div>
                                    </div>
                                    <x-admin.delete-button :action="route('cart.destroy', $item)" confirm="Hapus produk ini dari keranjang?" />
                                </div>

                                @if (!$available)
                                    <div class="small text-danger mt-2"><i class="fas fa-exclamation-circle me-1"></i>Produk ini sudah tidak tersedia. Silakan hapus dari keranjang.</div>
                                @else
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <label for="qty-{{ $item->id }}" class="small text-muted">Jumlah</label>
                                            <input type="number" id="qty-{{ $item->id }}" name="quantity" value="{{ $item->quantity }}"
                                                   min="1" max="{{ max(1, min($variant->stok, \App\Http\Controllers\CartController::MAX_QUANTITY)) }}"
                                                   class="form-control form-control-sm text-center" style="width: 80px;">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Ubah</button>
                                        </form>
                                        <span class="fw-bold">@rupiah($item->subtotal())</span>
                                    </div>
                                    @if ($item->exceedsStock())
                                        <div class="small text-danger mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $variant->stok < 1 ? 'Stok sedang habis.' : "Stok tinggal {$variant->stok}, kurangi jumlahnya." }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-white rounded-3 border p-3 position-sticky" style="top: 90px;">
                    <h2 class="h6 fw-bold mb-3">Ringkasan</h2>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">Total barang</span>
                        <span>{{ $totalItems }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                        <span>Total</span>
                        <span>@rupiah($total)</span>
                    </div>
                    <p class="small text-muted">Belum termasuk ongkos kirim. Admin akan menghubungi Anda melalui email setelah checkout.</p>

                    @if ($hasProblem)
                        <div class="small text-danger mb-2">Perbaiki dulu barang yang ditandai merah.</div>
                    @endif
                    @if ($hasProblem || $totalItems < 1)
                        <button type="button" class="btn btn-tatami w-100" disabled>Checkout</button>
                    @else
                        <a href="{{ route('checkout.create') }}" class="btn btn-tatami w-100">Checkout</a>
                    @endif
                    <a href="{{ route('shop.index') }}" class="btn btn-link color-tatami w-100 mt-1">Lanjut belanja</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
