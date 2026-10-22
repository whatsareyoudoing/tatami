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
          <a class="nav-link active" href="{{ url('/produk') }}">Produk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/tentang') }}">Tentang</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Custom Tatami</a>
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

