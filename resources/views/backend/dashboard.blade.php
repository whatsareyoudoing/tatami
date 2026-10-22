@extends('layouts.app')

@section('content')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">AdminPanel</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link text-white" href="#"><i class="fas fa-user-circle me-1"></i> Profil Saya</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="#"><i class="fas fa-sign-out-alt me-1"></i> Keluar</a>
          </li>
        </ul>
      </div>
    </div>
</nav>

  <div class="container-fluid">
    <div class="row">

      <!-- Sidebar (Kiri) - Disembunyikan di layar HP -->
      <nav class="col-md-3 col-lg-2 d-none d-md-block bg-white border-end min-vh-100 py-3 shadow-sm">
        <div class="nav flex-column nav-pills">
          <a href="#" class="nav-link active mb-2"><i class="fas fa-home fa-fw me-2"></i> Dashboard</a>
          <a href="#" class="nav-link text-dark mb-2"><i class="fas fa-users fa-fw me-2"></i> Pengguna</a>
          <a href="#" class="nav-link text-dark mb-2"><i class="fas fa-shopping-cart fa-fw me-2"></i> Pesanan</a>
          <a href="#" class="nav-link text-dark mb-2"><i class="fas fa-cog fa-fw me-2"></i> Pengaturan</a>
        </div>
      </nav>

      <!-- Konten Utama (Kanan) -->
      <main class="col-md-9 col-lg-10 px-md-4 py-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
          <h2 class="fw-bold">Ringkasan Hari Ini</h2>
          <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download me-1"></i> Unduh Laporan</button>
        </div>

        <!-- Baris Card Metrik -->
        <div class="row g-3 mb-4">

          <!-- Card 1 -->
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
              <div class="card-body">
                <h6 class="text-muted"><i class="fas fa-wallet text-primary me-2"></i> Total Pendapatan</h6>
                <h3 class="fw-bold mb-0">Rp 12.500.000</h3>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i> +5% dari kemarin</small>
              </div>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
              <div class="card-body">
                <h6 class="text-muted"><i class="fas fa-box-open text-success me-2"></i> Pesanan Baru</h6>
                <h3 class="fw-bold mb-0">145</h3>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i> +12% dari kemarin</small>
              </div>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
              <div class="card-body">
                <h6 class="text-muted"><i class="fas fa-user-friends text-warning me-2"></i> Pengguna Aktif</h6>
                <h3 class="fw-bold mb-0">8.230</h3>
                <small class="text-danger"><i class="fas fa-arrow-down me-1"></i> -2% dari kemarin</small>
              </div>
            </div>
          </div>

          <!-- Card 4 -->
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3">
              <div class="card-body">
                <h6 class="text-muted"><i class="fas fa-comments text-danger me-2"></i> Pesan Masuk</h6>
                <h3 class="fw-bold mb-0">34</h3>
                <small class="text-muted">Menunggu balasan</small>
              </div>
            </div>
          </div>

        </div>

        <!-- Baris Card Tabel -->
        <div class="row">
          <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
              <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold">Transaksi Terakhir</h6>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover table-borderless align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>#INV-001</td>
                        <td>Budi Santoso</td>
                        <td>21 Okt 2026</td>
                        <td><span class="badge bg-success">Selesai</span></td>
                        <td>Rp 250.000</td>
                      </tr>
                      <tr>
                        <td>#INV-002</td>
                        <td>Siti Aminah</td>
                        <td>21 Okt 2026</td>
                        <td><span class="badge bg-warning text-dark">Diproses</span></td>
                        <td>Rp 125.000</td>
                      </tr>
                      <tr>
                        <td>#INV-003</td>
                        <td>Andi Wijaya</td>
                        <td>20 Okt 2026</td>
                        <td><span class="badge bg-danger">Batal</span></td>
                        <td>Rp 450.000</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </main>
    </div>
  </div>

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

</style>
@endpush
