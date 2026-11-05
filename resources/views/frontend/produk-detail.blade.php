@extends('layouts.frontend')

@section('title', $product->nama)

@section('content')
@php
    $images = $product->images;
    $variants = $product->activeVariants;
    $hasVariants = $product->has_variants;
    $soldOut = $product->stok_tersedia < 1;
    $isRange = $product->harga_max > $product->harga_min;

    // Produk tanpa varian: varian "Default" langsung dipakai. Produk bervarian dengan 1 pilihan: langsung terpilih.
    $preselected = !$hasVariants || $variants->count() === 1 ? $variants->first() : null;

    // Data varian untuk JavaScript: harga, stok, SKU, dan foto per varian
    $variantData = $variants->mapWithKeys(fn ($v) => [$v->id => [
        'harga'  => $v->harga,
        'coret'  => $v->harga_coret,
        'diskon' => $v->diskon_persen,
        'stok'   => $v->stok,
        'sku'    => $v->sku,
        'image'  => $v->images->first()?->url,
    ]]);
@endphp

{{-- Judul besar = kategori; nama produk tetap di samping foto, dekat harga --}}
<x-page-hero tag="div" :title="$product->category?->name ?? 'Produk'"
             :crumbs="array_merge(
                 ['Produk' => route('shop.index')],
                 $product->category ? [$product->category->name => route('shop.index', ['kategori' => $product->category->slug])] : [],
                 [$product->nama => null],
             )" />

<div class="container py-4 mb-4">

    <div class="row g-4">
        {{-- Galeri: video (kalau ada) lalu foto-foto --}}
        <div class="col-lg-6">
            <div class="bg-white rounded-3 border p-2">
                <div class="ratio ratio-1x1 bg-light rounded-2 overflow-hidden">
                    @if ($product->youtube_id)
                        {{-- Pemutar YouTube baru dimuat saat diklik, supaya halaman tetap ringan --}}
                        <div id="main-video">
                            <button type="button" class="video-thumb" id="play-video" data-embed="{{ \App\Support\YouTube::embedUrl($product->youtube_id) }}" aria-label="Putar video">
                                <img src="{{ $product->youtube_thumbnail }}" alt="Video {{ $product->nama }}">
                                <i class="fab fa-youtube play-icon"></i>
                            </button>
                        </div>
                    @endif
                    @if ($images->isNotEmpty())
                        <img id="main-image" src="{{ $images->first()->url }}" alt="{{ $product->nama }}"
                             @class(['object-fit-contain', 'd-none' => (bool) $product->youtube_id])>
                    @elseif (!$product->youtube_id)
                        <div class="d-flex align-items-center justify-content-center text-muted"><i class="fas fa-image display-4"></i></div>
                    @endif
                </div>

                @if ($images->count() + ($product->youtube_id ? 1 : 0) > 1)
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @if ($product->youtube_id)
                            <button type="button" class="gallery-thumb active p-0 border rounded-2 overflow-hidden bg-dark position-relative" data-video aria-label="Lihat video">
                                <img src="{{ $product->youtube_thumbnail }}" alt="" loading="lazy">
                                <i class="fab fa-youtube play-icon position-absolute top-50 start-50 translate-middle text-danger"></i>
                            </button>
                        @endif
                        @foreach ($images as $image)
                            <button type="button" @class(['gallery-thumb p-0 border rounded-2 overflow-hidden bg-light', 'active' => $loop->first && !$product->youtube_id])
                                    data-image="{{ $image->url }}" aria-label="Lihat foto {{ $loop->iteration }}">
                                <img src="{{ $image->thumb_url }}" alt="" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Info + beli --}}
        <div class="col-lg-6" id="beli">
            @if ($product->category)
                <span class="fw-bold text-muted small">{{ $product->category->name }}</span>
            @endif
            <h1 class="fw-bold color-tatami mb-3">{{ $product->nama }}</h1>

            {{-- Harga: range sebelum varian dipilih, lalu harga varian terpilih (diganti oleh JavaScript) --}}
            <div class="d-flex align-items-center flex-wrap gap-2 mb-4">
                <span class="fs-3 fw-bold" id="price">
                    @if ($preselected)
                        @rupiah($preselected->harga)
                    @else
                        @rupiah($product->harga_min)@if ($isRange) – @rupiah($product->harga_max)@endif
                    @endif
                </span>
                <span class="text-muted text-decoration-line-through" id="price-coret">
                    @if ($preselected?->harga_coret) @rupiah($preselected->harga_coret) @endif
                </span>
                <span @class(['badge text-bg-danger', 'd-none' => !$preselected?->diskon_persen]) id="price-badge">
                    -{{ $preselected?->diskon_persen }}%
                </span>
                @if ($soldOut)
                    <span class="badge text-bg-dark">Stok habis</span>
                @endif
            </div>

            @include('partials.admin.flash')
            @if (session('success'))
                <div class="mb-3 small"><a href="{{ route('cart.index') }}" class="color-tatami fw-semibold">Lihat keranjang &raquo;</a></div>
            @endif

            <form action="{{ route('cart.store') }}" method="POST" id="buy-form">
                @csrf

                @if ($hasVariants)
                    <div class="row g-3 align-items-center mb-3">
                        <label for="variant" class="col-sm-3 col-form-label text-uppercase small fw-semibold">{{ $product->variant_label ?: 'Varian' }}</label>
                        <div class="col-sm-9">
                            <select id="variant" name="product_variant_id" required @disabled($soldOut)
                                    @class(['form-select', 'is-invalid' => $errors->has('product_variant_id')])>
                                @unless ($preselected)
                                    <option value="" disabled @selected(!old('product_variant_id'))>Pilih {{ Str::lower($product->variant_label ?: 'varian') }}</option>
                                @endunless
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}" @disabled($variant->stok < 1)
                                            @selected(old('product_variant_id', $preselected?->id) == $variant->id)>
                                        {{ $variant->nama }}{{ $variant->stok < 1 ? ' (habis)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_variant_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text" id="stock-info"></div>
                        </div>
                    </div>
                @else
                    <input type="hidden" id="variant" name="product_variant_id" value="{{ $preselected?->id }}">
                    @unless ($soldOut)
                        <div class="form-text mb-3" id="stock-info"></div>
                    @endunless
                @endif

                @unless ($soldOut)
                    <div class="row g-3 align-items-center mb-4">
                        <label for="quantity" class="col-sm-3 col-form-label text-uppercase small fw-semibold">Jumlah</label>
                        <div class="col-sm-9">
                            <div class="input-group quantity-input">
                                <button type="button" class="btn btn-outline-secondary" data-step="-1" aria-label="Kurangi">&minus;</button>
                                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="{{ \App\Http\Controllers\CartController::MAX_QUANTITY }}"
                                       @class(['form-control text-center', 'is-invalid' => $errors->has('quantity')])>
                                <button type="button" class="btn btn-outline-secondary" data-step="1" aria-label="Tambah">+</button>
                            </div>
                            @error('quantity') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                @endunless

                @if ($soldOut)
                    <button type="button" class="btn btn-secondary btn-lg px-4" disabled>
                        <i class="fas fa-ban me-2"></i>Stok habis
                    </button>
                @else
                    @can('manage_cart')
                        <button type="submit" class="btn btn-tatami btn-lg px-4">
                            <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                        </button>
                    @else
                        @guest
                            <a href="{{ route('shop.buy', $product->slug) }}" class="btn btn-tatami btn-lg px-4">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk untuk membeli
                            </a>
                        @else
                            <div class="alert alert-secondary small mb-0">Akun Anda tidak bisa berbelanja. Gunakan akun pelanggan.</div>
                        @endguest
                    @endcan
                @endif
            </form>

            @if ($product->deskripsi)
                <hr class="my-4">
                <h2 class="h6 fw-bold text-uppercase">Deskripsi</h2>
                <div class="text-muted">{!! nl2br(e($product->deskripsi)) !!}</div>
            @endif

            <p @class(['small text-muted mt-3 mb-0', 'd-none' => !$preselected?->sku]) id="sku-info">SKU: <span class="notranslate">{{ $preselected?->sku }}</span></p>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <div class="mt-5">
            <h2 class="h4 fw-bold color-tatami">Produk Terkait</h2>
            <div class="row">
                @foreach ($related as $item)
                    <div class="col-6 col-lg-3 py-3">
                        <x-product-card :product="$item" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var variants = @json($variantData);
        var maxQuantity = {{ \App\Http\Controllers\CartController::MAX_QUANTITY }};
        var mainImage = document.getElementById('main-image');
        var mainVideo = document.getElementById('main-video');
        var thumbs = document.querySelectorAll('.gallery-thumb');
        var select = document.getElementById('variant');
        var quantity = document.getElementById('quantity');
        var stockInfo = document.getElementById('stock-info');

        function rupiah(value) {
            return 'Rp' + Number(value).toLocaleString('id-ID');
        }

        function setActiveThumb(matcher) {
            thumbs.forEach(function (thumb) { thumb.classList.toggle('active', matcher(thumb)); });
        }

        // Kembalikan tombol putar (menghentikan video yang sedang diputar)
        function resetVideo() {
            if (mainVideo && !document.getElementById('play-video')) {
                mainVideo.innerHTML = mainVideo.dataset.thumbHtml;
                bindPlay();
            }
        }

        function showImage(url) {
            if (!mainImage || !url) {
                return;
            }
            resetVideo();
            if (mainVideo) {
                mainVideo.classList.add('d-none');
            }
            mainImage.classList.remove('d-none');
            mainImage.src = url;
            setActiveThumb(function (thumb) { return thumb.dataset.image === url; });
        }

        function showVideo() {
            if (mainImage) {
                mainImage.classList.add('d-none');
            }
            mainVideo.classList.remove('d-none');
            setActiveThumb(function (thumb) { return thumb.hasAttribute('data-video'); });
        }

        // Klik tombol putar -> ganti dengan pemutar YouTube
        function bindPlay() {
            var button = document.getElementById('play-video');
            if (!button) {
                return;
            }
            button.addEventListener('click', function () {
                mainVideo.innerHTML = '<iframe src="' + button.dataset.embed + '" title="Video produk" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen style="border:0;"></iframe>';
            });
        }
        if (mainVideo) {
            mainVideo.dataset.thumbHtml = mainVideo.innerHTML;
            bindPlay();
        }

        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                thumb.hasAttribute('data-video') ? showVideo() : showImage(thumb.dataset.image);
            });
        });

        // Pilih varian -> harga, harga coret, diskon, stok, SKU, dan foto varian
        function onVariantChange() {
            var variant = variants[select.value];
            if (!variant) {
                return;
            }
            document.getElementById('price').textContent = rupiah(variant.harga);
            document.getElementById('price-coret').textContent = variant.coret ? rupiah(variant.coret) : '';
            var badge = document.getElementById('price-badge');
            badge.textContent = variant.diskon ? '-' + variant.diskon + '%' : '';
            badge.classList.toggle('d-none', !variant.diskon);

            var sku = document.getElementById('sku-info');
            sku.querySelector('span').textContent = variant.sku || '';
            sku.classList.toggle('d-none', !variant.sku);

            showImage(variant.image);
            if (stockInfo) {
                stockInfo.textContent = 'Stok: ' + variant.stok;
            }
            if (quantity) {
                quantity.max = Math.min(variant.stok, maxQuantity);
                if (parseInt(quantity.value, 10) > variant.stok) {
                    quantity.value = variant.stok;
                }
            }
        }
        if (select) {
            select.addEventListener('change', onVariantChange);
            // Foto varian hanya diganti otomatis saat user memilih; saat halaman dibuka cukup harga & stok
            var initial = variants[select.value];
            if (initial) {
                var image = initial.image;
                initial.image = null;
                onVariantChange();
                initial.image = image;
            }
        }

        // Tombol - dan + jumlah
        document.querySelectorAll('.quantity-input [data-step]').forEach(function (button) {
            button.addEventListener('click', function () {
                var next = (parseInt(quantity.value, 10) || 1) + parseInt(button.dataset.step, 10);
                quantity.value = Math.max(1, Math.min(next, parseInt(quantity.max, 10)));
            });
        });
    })();
</script>
@endpush
