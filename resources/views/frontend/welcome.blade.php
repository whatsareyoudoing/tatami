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

    <div class="container">
        <h3 class="fw-bold text-center color-tatami">Belanja Sesuai Kebutuhanmu</h3>
        <div class="row">
            <div class="col-6 col-lg-2 p-4">
                <div class="d-flex justify-content-center flex-column text-center p-2">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/02/ico-facade.png" alt="">
                    <p class="py-3">
                        Tatami
                    </p>
                </div>
            </div>
            <div class="col-6 col-lg-2 p-4">
                <div class="d-flex justify-content-center flex-column text-center p-2">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/02/ico-cupboard.png" alt="">
                    <p class="py-3">
                        Sekat Ruang
                    </p>
                </div>
            </div>
            <div class="col-6 col-lg-2 p-4">
                <div class="d-flex justify-content-center flex-column text-center p-2">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/02/ico-vase.png" alt="">
                    <p class="py-3">
                        Furniture
                    </p>
                </div>
            </div>
            <div class="col-6 col-lg-2 p-4">
                <div class="d-flex justify-content-center flex-column text-center p-2">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/02/ico-pillow.png" alt="">
                    <p class="py-3">
                        Dekorasi
                    </p>
                </div>
            </div>
            <div class="col-6 col-lg-2 p-4">
                <div class="d-flex justify-content-center flex-column text-center p-2">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/02/ico-lamp.png" alt="">
                    <p class="py-3">
                        Bantal
                    </p>
                </div>
            </div>
            <div class="col-6 col-lg-2 p-4">
                <div class="d-flex justify-content-center flex-column text-center p-2">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/02/ico-tatami.png" alt="">
                    <p class="py-3">
                        Lampu Jepang
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col d-flex justify-content-between">
                <h3 class="fw-bold color-tatami">Sorotan</h3>
                <a href="#" class="color-tatami">lihat semua</a>
            </div>
        </div>
    </div>

    <div class="container mt-3 mb-5">
        <div class="row">
            <div class="col-6 col-lg-3 py-3">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/04/2asdfasfd.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="fw-bold text-muted">Bantal</span>
                        <p class="card-text">Zabuton Igusa – Bantal Duduk</p>
                        <span class="fw-bold text-decoration-underline">Rp. 220.000</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 py-3">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2026/09/DSCF0891-rotated.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="fw-bold text-muted">Bantal</span>
                        <p class="card-text">Zabuton Igusa – Bantal Duduk</p>
                        <span class="fw-bold text-decoration-underline">Rp. 220.000</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 py-3">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/11/1asdadffg.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="fw-bold text-muted">Bantal</span>
                        <p class="card-text">Zabuton Igusa – Bantal Duduk</p>
                        <span class="fw-bold text-decoration-underline">Rp. 220.000</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 py-3">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/03/TST-22S-BK-compress.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="fw-bold text-muted">Bantal</span>
                        <p class="card-text">Zabuton Igusa – Bantal Duduk</p>
                        <span class="fw-bold text-decoration-underline">Rp. 220.000</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 py-3">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/03/TST-22M-NA-compress.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="fw-bold text-muted">Bantal</span>
                        <p class="card-text">Zabuton Igusa – Bantal Duduk</p>
                        <span class="fw-bold text-decoration-underline">Rp. 220.000</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 py-3">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2026/09/20240820_155125.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="fw-bold text-muted">Bantal</span>
                        <p class="card-text">Zabuton Igusa – Bantal Duduk</p>
                        <span class="fw-bold text-decoration-underline">Rp. 220.000</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 py-3">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/05/9xcbvcb.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="fw-bold text-muted">Bantal</span>
                        <p class="card-text">Zabuton Igusa – Bantal Duduk</p>
                        <span class="fw-bold text-decoration-underline">Rp. 220.000</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 py-3">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2024/05/15cvgdfg.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="fw-bold text-muted">Bantal</span>
                        <p class="card-text">Zabuton Igusa – Bantal Duduk</p>
                        <span class="fw-bold text-decoration-underline">Rp. 220.000</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 py-3">
                <div class="card h-100">
                    <img src="https://tatamiindonesia.id/wp-content/uploads/2025/02/90-1.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="fw-bold text-muted">Bantal</span>
                        <p class="card-text">Zabuton Igusa – Bantal Duduk</p>
                        <span class="fw-bold text-decoration-underline">Rp. 220.000</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 d-flex justify-content-center pt-5">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link color-tatami" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link color-tatami" href="#">1</a></li>
                        <li class="page-item"><a class="page-link color-tatami" href="#">2</a></li>
                        <li class="page-item"><a class="page-link color-tatami" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link color-tatami" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
{{--  --}}



@endsection
