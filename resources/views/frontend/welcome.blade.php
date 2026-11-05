@extends('layouts.frontend')

@section('title', 'Beranda')

@section('content')

{{-- content --}}
    <div class="container-fluid bg-light">
        <div class="container d-flex justify-content-center">
            <img src="@asset('images/site/welcome-image-New.jpg')" class="img-fluid" alt="...">
        </div>
    </div>

    <div class="container my-5">
        <h2 class="fw-bold color-tatami mb-4">
            CREATED BY CULTURE. <br>
            DESIGN BY NATURE.
        </h2>
        <p>
            <b class="color-tatami">TATAMI Indonesia</b>, Pertama dan satu-satunya produsen Tatami Igusa yang ada di Indonesia. Mendatangkan anyaman Igusa Jepang asli dan membawa budaya tatami ke Indonesia.
        </p>

        <p>
            Ukuran Tatami bisa custom menyesuaikan ruangan. Mebel kami memiliki standar ekspor Jepang karena kami pada awalnya merupakan eksportir Mebel ke Jepang. Dari pemilihan bahan Baku, pemrosesan Bahan Baku, Fumigasi, Perakitan dan Finishing semua berdasarkan standar ekspor ke Jepang.
        </p>
    </div>

    {{-- HIGHLIGHTS: 4 artikel terbit terbaru, disembunyikan kalau belum ada artikel --}}
    @if ($artikel->isNotEmpty())
        <div class="container">
            <div class="row pb-4">
                <div class="col">
                    <h2 class="color-tatami text-center">HIGHLIGHTS</h2>
                </div>
            </div>
            <div class="row">
                @foreach ($artikel as $item)
                    <div class="col-md-3 py-3 px-5 px-lg-2">
                        <a href="{{ route('blog.show', $item->slug) }}" class="card h-100 text-decoration-none text-reset article-card">
                            @if ($item->sampul_thumb_url)
                                <img src="{{ $item->sampul_thumb_url }}" class="card-img-top article-card-img article-card-img-square" alt="{{ $item->judul }}" loading="lazy">
                            @else
                                <div class="card-img-top article-card-img article-card-img-square d-flex align-items-center justify-content-center color-tatami">
                                    <i class="fas fa-newspaper fs-1"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <p class="card-text fs-5">{{ $item->judul }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="row pt-4">
                <div class="col d-flex justify-content-center">
                    <a href="{{ route('blog.index') }}" class="btn btn-tatami">Artikel Lainnya</a>
                </div>
            </div>
        </div>
    @endif
    <div class="container bg-white my-5 rounded-4">
        <div class="row p-4">
            <h2 class="fw-bold color-tatami" style="font-size: clamp(2rem, 5vw, 3.5rem);">Belanja Tatami dan Furniture Jepang Paling Nyaman</h2>
            <div class="col py-3">
                <a href="#" class="btn btn-tatami">Lihat Produk</a>
            </div>
        </div>
    </div>

    @if ($kategori->isNotEmpty())
        <div class="container">
            <h3 class="fw-bold text-center color-tatami">Belanja Sesuai Kebutuhanmu</h3>
            <div class="row">
                @foreach ($kategori as $item)
                    <div class="col-6 col-lg-2 p-4">
                        <a href="{{ route('shop.index', ['kategori' => $item->slug]) }}" class="text-decoration-none text-reset">
                            <div class="d-flex justify-content-center flex-column text-center p-2">
                                @if ($item->thumb_url)
                                    <img src="{{ $item->thumb_url }}" alt="{{ $item->name }}" class="img-fluid" loading="lazy">
                                @endif
                                <p class="py-3">
                                    {{ $item->name }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col d-flex justify-content-between">
                <h3 class="fw-bold color-tatami">Sorotan</h3>
                <a href="{{ route('shop.index') }}" class="color-tatami">lihat semua</a>
            </div>
        </div>
    </div>

    <div class="container mt-3 mb-5">
        <div class="row">
            @forelse ($sorotan as $product)
                <div class="col-6 col-lg-3 py-3">
                    <x-product-card :product="$product" />
                </div>
            @empty
                <div class="col-12 text-center text-muted py-4">Produk akan segera hadir.</div>
            @endforelse
        </div>
    </div>
{{--  --}}



@endsection
