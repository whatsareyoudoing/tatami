@extends('app')

@section('content')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
      <!-- Tambahan d-flex agar tombol toggle sidebar dan brand sejajar -->
      <div class="d-flex align-items-center">
        <!-- Tombol Toggle Sidebar (Hanya tampil di HP/Tablet) -->
        <button class="btn btn-dark d-md-none me-2 border-0" id="sidebar-toggle">
          <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand fw-bold" id="btn-side" href="#">AdminPanel</a>
      </div>

      <div class="justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link text-white" href="#"><i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name ?? 'User' }}</a>
          </li>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link text-danger border-0 bg-transparent" style="cursor: pointer;"><i class="fas fa-sign-out-alt me-1"></i> Keluar</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
</nav>

  <div class="container-fluid">
    <div class="row">

      <!-- Sidebar (Kiri) - Menggunakan class custom "sidebar-custom" -->
      <nav class="col-md-3 col-lg-2 bg-white border-end min-vh-100 py-3 shadow-sm sidebar-custom" id="sidebar">
        <div class="nav flex-column nav-pills">
          <a href="{{ route('dashboard') }}" class="nav-link active bg-tatami mb-2"><i class="fas fa-home fa-fw me-2"></i> Dashboard</a>
          @can('view_user')
            <a href="{{ route('user') }}" class="nav-link text-dark mb-2"><i class="fas fa-users fa-fw me-2"></i> Pengguna</a>
          @endcan
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle class 'show-sidebar' saat tombol diklik
        $('#sidebar-toggle').click(function(e) {
            e.preventDefault();
            $('#sidebar').toggleClass('show-sidebar');
        });

        // Menutup sidebar jika area di luar sidebar diklik (opsional, UX lebih baik)
        $(document).click(function(event) {
            var $target = $(event.target);
            if(!$target.closest('#sidebar').length &&
               !$target.closest('#sidebar-toggle').length &&
               $('#sidebar').hasClass('show-sidebar')) {
                $('#sidebar').removeClass('show-sidebar');
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* CSS Animasi dan Posisi Sidebar */
    .sidebar-custom {
        transition: transform 0.3s ease-in-out;
    }

    /* Tampilan HP & Tablet */
    @media (max-width: 767.98px) {
        .sidebar-custom {
            position: fixed;
            top: 56px; /* Menyesuaikan tinggi navbar default bootstrap */
            left: 0;
            width: 250px;
            height: calc(100vh - 56px);
            z-index: 1040;
            transform: translateX(-100%); /* Disembunyikan di luar layar */
            background-color: #fff;
            overflow-y: auto;
        }

        /* Class yang ditambahkan via jQuery untuk menampilkan sidebar */
        .sidebar-custom.show-sidebar {
            transform: translateX(0);
        }
    }

    /* Tampilan Desktop (d-block secara default) */
    @media (min-width: 768px) {
        .sidebar-custom {
            display: block !important;
            transform: translateX(0) !important;
        }
    }

    .bg-tatami{
        background-color: #245237!important;
    }
    .color-tatami{
        color: #245237!important;
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
