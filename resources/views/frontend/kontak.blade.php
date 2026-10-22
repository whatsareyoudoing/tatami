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
          <a class="nav-link " href="{{ url('/produk') }}">Produk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/tentang') }}">Tentang</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Custom Tatami</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{{ url('/kontak') }}">Kontak</a>
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

{{-- content --}}
<div class="container-fluid">
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-md-6">
                <h1 class="fw-bold">
                    Jangan ragu untuk bertanya kami siap melayani Anda dengan sepenuh hati.
                </h1>
                <p class="text-justify">
                    Central Industrial Park (CIP), Blok Gamma No. 09 (Jl. Raya Lingkar Timur Km. 4), Desa Kemiri, Sidoarjo, Jawa Timur, 61234
                </p>
                <span class="color-tatami fw-bold fs-5">
                    Email : tatamiindonesia@gmail.com
                </span>
            </div>
            <div class="col-12 col-md-6">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Email</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Pesan</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <button class="btn btn-tatami">Kirim</button>
                </form>
            </div>
        </div>
        <div class="row">
            <iframe class="mt-5" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.161957171134!2d112.7528401!3d-7.447327100000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e6bcee50df29%3A0x55297bf7259e46d0!2sGudang%20CIP%20Central%20Industrial%20Park!5e0!3m2!1sid!2sid!4v1792680371847!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </div>
    </div>
</div>


  <!-- Footer -->
  <footer class="bg-white text-dark pt-5 pb-4">
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

