@extends('layouts.frontend')

@section('title', 'Beranda')

@section('content')

{{-- content --}}
    <div class="container-fluid bg-light">
        <div class="container d-flex justify-content-center">
            <img src="https://tatamiindonesia.id/wp-content/uploads/2025/07/welcome-image-New.jpg" class="img-fluid" alt="...">
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

    <div class="container">
        <div class="row pb-4">
            <div class="col">
                <h2 class="color-tatami text-center">HIGHLIGHTS</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 py-3 px-5 px-lg-2">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2025/07/low4-2-300x300.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text fs-5">Low Furniture: Luas & Tenang</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 py-3 px-5 px-lg-2">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2025/07/memotong-1-300x300.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text fs-5">Tips Merawat Tatami</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 py-3 px-5 px-lg-2">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2025/08/cb1716e0c72337ea3c76333d2b0ef35bresize-300x300.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text fs-5">Apa itu Matsuri?</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 py-3 px-5 px-lg-2">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2025/07/6-300x300.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text fs-5">Inspirasi Ruangan Santai ala Jepang</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col d-flex justify-content-center">
                <a href="#" class="btn btn-tatami">Artikel Lainnya</a>
            </div>
        </div>

    </div>
    <div class="container bg-white my-5 rounded-4">
        <div class="row p-4">
            <h2 class="fw-bold color-tatami" style="font-size: clamp(2rem, 5vw, 3.5rem);">Belanja Tatami dan Furniture Jepang Paling Nyaman</h2>
            <div class="col py-3">
                <a href="#" class="btn btn-tatami">Lihat Produk</a>
            </div>
        </div>
    </div>

    @php
        // Kotak kategori: tampilan sama seperti sebelumnya, sekarang menuju daftar produk per kategori.
        // Slug dibuat dari nama, jadi nama kategori di admin harus sama (misalnya "Sekat Ruang" -> sekat-ruang).
        $kategoriBeranda = [
            ['Tatami', 'ico-facade.png'],
            ['Sekat Ruang', 'ico-cupboard.png'],
            ['Furniture', 'ico-vase.png'],
            ['Dekorasi', 'ico-pillow.png'],
            ['Bantal', 'ico-lamp.png'],
            ['Lampu Jepang', 'ico-tatami.png'],
        ];
    @endphp
    <div class="container">
        <h3 class="fw-bold text-center color-tatami">Belanja Sesuai Kebutuhanmu</h3>
        <div class="row">
            @foreach ($kategoriBeranda as [$nama, $ikon])
                <div class="col-6 col-lg-2 p-4">
                    <a href="{{ route('shop.index', ['kategori' => Str::slug($nama)]) }}" class="text-decoration-none text-reset">
                        <div class="d-flex justify-content-center flex-column text-center p-2">
                            <img src="https://tatamiindonesia.id/wp-content/uploads/2024/02/{{ $ikon }}" alt="">
                            <p class="py-3">
                                {{ $nama }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

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
