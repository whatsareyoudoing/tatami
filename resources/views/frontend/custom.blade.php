@extends('app')

@section('content')
{{-- navbar --}}
<nav class="navbar navbar-expand-lg bg-white shadow-2xs sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary d-none d-lg-block" href="{{ url('/') }}">
        <img src="https://tatamiindonesia.id/wp-content/uploads/2025/06/cropped-icon-website-1.png" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <h6 class="navbar-toggler-icon"></h6>
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
          <a class="nav-link active" href="{{ url('/custom-tatami') }}">Custom Tatami</a>
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

{{-- content --}}
<div class="container my-5">
    <div class="row p-0">
        <div class="col-md-8 py-1 px-3 px-md-1 ">
            <img src="https://tatamiindonesia.id/wp-content/uploads/2025/06/1-2.jpg" alt="" class="img-fluid">
        </div>
        <div class="col-md-4 py-1 px-3 px-md-1 d-flex items-center">
            <img src="https://tatamiindonesia.id/wp-content/uploads/2025/06/2.jpg" alt="" class="img-fluid">
        </div>
    </div>
    <div class="row p-0">
        <div class="col-md-4 py-1 px-3 px-md-1 d-flex items-center">
            <img src="https://tatamiindonesia.id/wp-content/uploads/2025/06/3-1.jpg" alt="" class="img-fluid">
        </div>
        <div class="col-md-8 py-1 px-3 px-md-1">
            <img src="https://tatamiindonesia.id/wp-content/uploads/2025/06/4-1.jpg" alt="" class="img-fluid">
        </div>
    </div>
</div>

<div class="container mb-3">
    <div class="row text-center color-tatami">
        <h3 class="fw-bold">Custom Tatami</h3>
        <h1 class="fw-bold">Original Japanase Igusa</h1>
        <p class="fs-4 ">Lebar Tatami dengan Pita Maksimal 90 cm dan 95 cm (Khusus Natural Green).
            Lihat simulasi perhitungan harga Tatami scroll ke bawah.</p>
    </div>
</div>

<div class="container-fluid bg-white pt-1 pb-5">
    <div class="container color-tatami text-center ">
        <div class="row py-3">
            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Igusa-3.jpg" alt="Natural Green">
                <h6>Natural Green (Original Color Tatami)</h6>
            </div>

            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Igusa-1.jpg" alt="Stripes Blue">
                <h6>Stripes Blue</h6>
            </div>

            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Igusa-6.jpg" alt="Dark Brown">
                <h6>Dark Brown</h6>
            </div>

            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Igusa-4.jpg" alt="Light Brown">
                    <h6>Light Brown</h6>
                </div>
        </div>
        <div class="row py-3">
            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Igusa-7.jpg" alt="Dark Blue">
                <h6>Dark Blue</h6>
            </div>

            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Igusa-5.jpg" alt="Light Blue">
                <h6>Light Blue</h6>
            </div>

            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Igusa-2.jpg" alt="Natural Soft">
                <h6>Natural Soft</h6>
            </div>

            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Igusa-8.jpg" alt="Crimson Red">
                <h6>Crimson Red</h6>
            </div>
        </div>
    </div>
    <div class="container mt-5 text-center color-tatami">
        <h3 class="fw-bold">
            Warna Pita Tatami
        </h3>
        <div class="row py-3">
            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Pita-5.jpg" alt="Brown Matte">
                <h6>Brown Matte</h6>
            </div>

            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Pita-1.jpg" alt="Brown Line 10 cm">
                <h6>Brown Line 10 cm</h6>
            </div>

            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Pita-6.jpg" alt="Brown Motive 7 cm">
                <h6>Brown Motive 7 cm</h6>
            </div>

            <div class="col-md-3">
                <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Pita-2.jpg" alt="Green Motive 7 cm">
                <h6>Green Motive 7 cm</h6>
            </div>
        </div>
        <div class="row py-3">
            <div class="col-md-3">
            <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Pita-7.jpg" alt="Blue Matte">
            <h6>Blue Matte</h6>
            </div>

            <div class="col-md-3">
            <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Pita-3.jpg" alt="Blue Line 10 cm">
            <h6>Blue Line 10 cm</h6>
            </div>

            <div class="col-md-3">
            <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Pita-8.jpg" alt="Green Line 10 cm">
            <h6>Green Line 10 cm</h6>
            </div>

            <div class="col-md-3">
            <img class="img-fluid rounded-4" src="https://tatamiindonesia.id/wp-content/uploads/2024/02/Pita-4.jpg" alt="Blue Motive 7 cm">
            <h6>Blue Motive 7 cm</h6>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col col-md-6">
                <form action="" id="form">
                    <div class="py-2">
                        <label for="">Panjang</label>
                        <select class=" form-select" aria-label="pilih panjang" id="panjang">
                            <option value="range1">10 cm ~ 100 cm</option>
                            <option value="range2">101 cm ~ 150 cm</option>
                            <option value="range3">151 cm ~ 175 cm</option>
                            <option value="range4">176 cm ~ 200 cm</option>
                        </select>
                    </div>

                    <div class="py-2">
                        <label for="">Tebal</label>
                        <select class=" form-select" aria-label="pilih tebal" id="tebal">
                            <option value="2.5">2.5 cm</option>
                            <option value="3.5">3.5 cm</option>
                            <option value="4.5">4.5 cm</option>
                        </select>
                    </div>

                    <div class="py-2">
                        <label for="">Jumlah</label>
                        <input type="number" class=" form-control" id="jumlah" min="0" step="any" value="0">
                    </div>

                    <div class="pt-5">
                        <label for="">Total Simulasi Harga</label><br>
                        <h5 class="pt-2 fw-bold" id="total">Rp. 0</h5>
                    </div>

                </form>
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
        // 1. Kamus Harga (Format: hargaTatami[kode_panjang][kode_lebar] = harga)
        const hargaTatami = {
            "range1": {
            "2.5": 890000,
            "3.5": 1090000,
            "4.5": 1290000
            },
            "range2": {
            "2.5": 1335000, // Ganti dengan harga asli untuk 101~150cm (Lebar 2.5)
            "3.5": 1635000, // Ganti dengan harga asli untuk 101~150cm (Lebar 3.5)
            "4.5": 1935000  // Ganti dengan harga asli untuk 101~150cm (Lebar 4.5)
            },
            "range3": {
            "2.5": 1557500, // Ganti dengan harga asli untuk 151~175cm
            "3.5": 1907500,
            "4.5": 2257500
            },
            "range4": {
            "2.5": 1780000, // Ganti dengan harga asli untuk 176~200cm
            "3.5": 2180000,
            "4.5": 2580000
            }
        };

        // 2. Fungsi untuk menghitung dan menampilkan hasil
        function kalkulasiHarga() {
            let panjang = $('#panjang').val();
            let tebal = $('#tebal').val();
            let jumlah = parseFloat($('#jumlah').val()) || 0;
            console.log(panjang,tebal,jumlah);

            // Ambil harga dari kamus data di atas
            let hargaSatuan = hargaTatami[panjang][tebal];

            // Cegah angka minus
            if (jumlah < 0) {
            jumlah = 0;
            $('#jumlah').val(0);
            }

            let total = hargaSatuan * jumlah;

            // Tampilkan hasil
            $('#total').text('Rp ' + total.toLocaleString('id-ID'));
            console.log(total)
        }

        // 3. Jalankan fungsi setiap kali dropdown atau input jumlah diubah
        $('#panjang, #tebal, #jumlah').on('input change', kalkulasiHarga);

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

