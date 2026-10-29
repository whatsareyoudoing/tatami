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
            <a class="nav-link text-white" href="#"><i class="fas fa-category-circle me-1"></i> {{ Auth::user()->name ?? 'User' }}</a>
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
          <a href="{{ route('dashboard') }}" class="nav-link text-dark mb-2"><i class="fas fa-home fa-fw me-2"></i> Dashboard</a>
          <a href="{{ route('user') }}" class="nav-link text-dark mb-2"><i class="fas fa-users fa-fw me-2"></i> Pengguna</a>
          <a href="{{ route('category') }}" class="nav-link active bg-tatami mb-2"><i class="fas fa-archive"></i> Kategori</a>
          <a href="#" class="nav-link text-dark mb-2"><i class="fas fa-shopping-cart fa-fw me-2"></i> Pesanan</a>
          <a href="#" class="nav-link text-dark mb-2"><i class="fas fa-cog fa-fw me-2"></i> Pengaturan</a>
        </div>
      </nav>

      <!-- Konten Utama (Kanan) -->
      <main class="col-md-9 col-lg-10 px-md-4 py-4">
        <h1>Tambah Kategori</h1>
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color: red;">{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form action="{{ route('category.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Nama</label>
                <input name="name" type="text" class="form-control">
            </div>
            <button class="btn btn-tatami" type="submit">Simpan</button>
        </form>


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
