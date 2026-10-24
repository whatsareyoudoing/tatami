@extends('app')

@section('content')
{{-- navbar --}}
<nav class="navbar navbar-expand-lg bg-white shadow-2xs sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary d-none d-lg-block" href="{{ url('/') }}">
        <img src="https://tatamiindonesia.id/wp-content/uploads/2025/06/cropped-icon-website-1.png" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav fw-bold gap-4 fs-6">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="{{ url('/') }}">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/produk') }}">Produk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{{ url('/tentang') }}">Tentang</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/custom-tatami') }}">Custom Tatami</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/kontak') }}">Kontak</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/artikel') }}">Artikel</a>
        </li>
      </ul>
    </div>
    <div class="d-flex justify-between gap-3">
        <i class="fas fa-user fs-4 color-tatami"></i>
        <i class="fas fa-shopping-cart fs-4 color-tatami"></i>
    </div>
  </div>
</nav>

<div class="container my-5 pt-3">
    <div class="row mx-3 mx-md-0">
        <div class="col-12 col-lg-6">
            <h3 class="fw-bold">Tentang Kami</h3>
            <h1 class="fw-bold">
                Produsen Tatami Igusa Pertama dan Satu-satunya di Indonesia
            </h1>
            <p class="text-justify">
                TATAMI Indonesia merupakan brand produk utama dari PT Global Dunia Usaha. PT Global Dunia Usaha (GDU) adalah anak perusahaan dari HAGIHARA berkantor pusat di Kurashiki, Osaka – Jepang. (https://e-hagihara.co.jp).
            </p>
            <p class="text-justify">
                Berdiri pada tahun 2013 – PT GDU hanya ekspor mebel dengan bahan baku utama kayu dan rotan ke Jepang. Dalam perkembangannya PT GDU menjual tatami berbahan dasar igusa untuk pasar Indonesia.
            </p>
            <p class="text-justify">
                Tahun 2015 sampai 2020 PT GDU mulai menjual mebel-mebel Jepang dengan membuka Toko Offline (TEXENS) di Surabaya dengan misi awal memperkenalkan budaya Jepang.
            </p>
            <p class="text-justify">
                Bulan Juni 2020, PT GDU membuat brand dengan nama TATAMI Indonesia untuk memperkenalkan tatami ke seluruh Indonesia dengan fokus online baik melalui sosial media maupun marketplace.
            </p>
        </div>
        <div class="col-12 col-lg-6">
            <img src="https://tatamiindonesia.id/wp-content/uploads/2024/02/01-Foto-Guri.jpg" class="img-fluid border rounded-4" alt="...">
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col text-center mt-5 mb-4">
            <h3 class="fw-bolder">Dipercaya Berpengalaman</h3>
        </div>
    </div>
    <div class="row text-center">
        <div class="col-md-4">
            <span class="fw-bold">
                1.000+
            </span>
            <p>Produk Tatami Indonesia Terjual</p>
        </div>
        <div class="col-md-4">
            <span class="fw-bold">
                1.000+
            </span>
            <p>Produk Tatami Indonesia Terjual</p>
        </div>
        <div class="col-md-4">
            <span class="fw-bold">
                1.000+
            </span>
            <p>Produk Tatami Indonesia Terjual</p>
        </div>
    </div>
</div>

<div class="container-fluid bg-white">
<div class="container py-5 px-5">
    <div class="row">
        <h3 class="fw-bolder text-center mb-5 pb-3">
            Brand yang Menggunakan Tatami Indonesia
        </h3>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-6 col-md-2">
            <a href="https://tarakanita.sch.id/page/ppdb" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/02-Logo-Tarakanita.jpg" alt="Logo Tarakanita">
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="https://linktr.ee/archid.db" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/03-Logo-Archid.jpg" alt="Logo Archid">
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="https://linktr.ee/guriramen.id" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/01-Logo-Guri.jpg" alt="Logo Guri">
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="https://www.mysantika.com/indonesia/bandung/hotel-santika-pasir-kaliki" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/04-Logo-Santika.jpg" alt="Logo Santika">
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="https://theonsenresort.com/" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/05-Logo-Onsen.jpg" alt="Logo Onsen">
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-6 col-md-2">
            <a href="https://linktr.ee/ryokan.bsd" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/07-Logo-Machiya.jpg" alt="Logo Machiya">
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="https://linktr.ee/soomaison" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/06-Logo-Soo-Maison.jpg" alt="Logo Soo Maison">
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="https://linktr.ee/kikugawa_jkt" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/08-Logo-Kikugawa.jpg" alt="Logo Kikugawa">
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="https://theamartya.com/v2/" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/10-Logo-the-Amartya.jpg" alt="Logo The Amartya">
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="https://heylink.me/malinohighlands/" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/11-Logo-Malino.jpg" alt="Logo Malino">
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="https://www.sushimibali.com/" target="_blank">
                <img class="img-fluid" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/12-Logo-Sushimi.jpg" alt="Logo Sushimi">
            </a>
        </div>
    </div>
</div>
</div>

  <!-- Footer -->
  <footer class="bg-white text-dark pt-5 pb-4 border-top">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <h5 class="fw-bold mb-3">Tatami Indonesia</h5>
          <p class="text-muted small">Central Industrial Park (CIP), Blok Gamma No. 09 (Jl. Raya Lingkar Timur Km. 4), Desa Kemiri, Sidoarjo, Jawa Timur, 61234</p>
          <div class="mt-3">
            <a href="#" class="text-muted me-3 fs-5"><i class="fab fa-facebook"></i></a>
            <a href="#" class="text-muted me-3 fs-5"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-muted me-3 fs-5"><i class="fab fa-instagram"></i></a>
          </div>
        </div>

        <div class="col-6 col-lg-2 col-6 mb-4">
          <h6 class="text-uppercase fw-bold mb-3">Produk</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-muted text-decoration-none small">Fitur</a></li>
            <li><a href="#" class="text-muted text-decoration-none small">Harga</a></li>
            <li><a href="#" class="text-muted text-decoration-none small">Keamanan</a></li>
          </ul>
        </div>

        <div class="col-6 col-lg-2 col-6 mb-4">
          <h6 class="text-uppercase fw-bold mb-3">Perusahaan</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-muted text-decoration-none small">Tentang Kami</a></li>
            <li><a href="#" class="text-muted text-decoration-none small">Karir</a></li>
            <li><a href="#" class="text-muted text-decoration-none small">Kontak</a></li>
          </ul>
        </div>

        <div class="col-md-4 mb-4">
          <h6 class="text-uppercase fw-bold mb-3">Berlangganan Newsletter</h6>
          <p class="text-muted small">Dapatkan info pembaruan dan tips terbaru langsung ke email Anda.</p>
          <div class="input-group">
            <input type="email" class="form-control" placeholder="Email Anda">
            <button class="btn btn-tatami" type="button"><i class="fas fa-paper-plane"></i></button>
          </div>
        </div>
      </div>

      <hr class="mt-2 mb-3 border-secondary">
      <div class="text-center small">
        &copy; © 2026-2027 Tatami Indonesia. All Rights Reserved.
      </div>
    </div>
  </footer>


@endsection

<!-- Script khusus untuk halaman ini -->
@push('scripts')
<script>
    $(document).ready(function() {

        // TES 1: jQuery murni (manipulasi class dan HTML)
        $('#btn-ubah-teks').click(function() {
            $('#kotak-status')
                .removeClass('alert-warning')
                .addClass('alert-success')
                .html('<strong>Sukses!</strong> jQuery berhasil mendeteksi klik dan mengubah isi kotak ini tanpa me-refresh halaman.');
        });

        // TES 2: jQuery memicu fungsi JavaScript bawaan Bootstrap 5
        $('#btn-tampil-modal').click(function() {
            // Inisialisasi modal menggunakan standar Bootstrap 5
            var myModal = new bootstrap.Modal(document.getElementById('modalTester'));

            // Perintah untuk menampilkan modal
            myModal.show();
        });

    });
</script>
@endpush

@push('styles')
<style>
    .color-tatami{
        color: #245237;
    }
    .btn-tatami{
        background-color: #245237;
        color: #ffffff;
    }
    .btn:hover {
        color: #ffffff !important;
        background-color: #239452 !important;
        border-color: var(--bs-btn-hover-border-color);
    }
    .page-link:hover {
        color:#245237!important;
    }
</style>
@endpush

