@extends('layouts.frontend')

@section('title', $categoryLabel ? "Produk {$categoryLabel}" : 'Produk')

@section('content')
    <x-page-hero :title="$categoryLabel ?? 'Produk'"
                 :crumbs="$categoryLabel ? ['Produk' => route('shop.index'), $categoryLabel => null] : ['Produk' => null]" />

    <div class="container mt-4 mb-5">
        {{-- Filter kategori + urutan --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-2">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('shop.index', array_filter(['urut' => $sort !== 'terbaru' ? $sort : null])) }}"
                   @class(['btn btn-sm rounded-pill', 'btn-tatami' => !$activeSlug, 'btn-outline-secondary' => $activeSlug])>Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ route('shop.index', array_filter(['kategori' => $category->slug, 'urut' => $sort !== 'terbaru' ? $sort : null])) }}"
                       @class(['btn btn-sm rounded-pill', 'btn-tatami' => $activeSlug === $category->slug, 'btn-outline-secondary' => $activeSlug !== $category->slug])>{{ $category->name }}</a>
                @endforeach
            </div>

            <form action="{{ route('shop.index') }}" method="GET" class="d-flex align-items-center gap-2">
                @if ($activeSlug)
                    <input type="hidden" name="kategori" value="{{ $activeSlug }}">
                @endif
                <label for="urut" class="small text-muted text-nowrap">Urutkan</label>
                <select id="urut" name="urut" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach ($sorts as $value => $label)
                        <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <noscript><button type="submit" class="btn btn-sm btn-outline-secondary">Terapkan</button></noscript>
            </form>
        </div>

        <div class="row">
            @forelse ($products as $product)
                <div class="col-6 col-lg-3 py-3">
                    <x-product-card :product="$product" />
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-box-open fs-1 mb-3 d-block"></i>
                    {{ $categoryLabel ? "Belum ada produk untuk kategori {$categoryLabel}." : 'Belum ada produk.' }}
                    @if ($activeSlug)
                        <div class="mt-3"><a href="{{ route('shop.index') }}" class="color-tatami">Lihat semua produk</a></div>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($products->hasPages())
            <div class="d-flex justify-content-center pt-4">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
