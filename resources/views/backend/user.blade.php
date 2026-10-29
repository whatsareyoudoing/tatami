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
          <a href="{{ route('dashboard') }}" class="nav-link text-dark mb-2"><i class="fas fa-home fa-fw me-2"></i> Dashboard</a>
          <a href="{{ route('user') }}" class="nav-link active bg-tatami mb-2"><i class="fas fa-users fa-fw me-2"></i> Pengguna</a>
          <a href="#" class="nav-link text-dark mb-2"><i class="fas fa-shopping-cart fa-fw me-2"></i> Pesanan</a>
          <a href="#" class="nav-link text-dark mb-2"><i class="fas fa-cog fa-fw me-2"></i> Pengaturan</a>
        </div>
      </nav>

      <!-- Konten Utama (Kanan) -->
      <main class="col-md-9 col-lg-10 px-md-4 py-4">
        <h1>Daftar Pengguna</h1>
        <a class="btn btn-tatami mb-3" href="{{ route('user.create') }}">Tambah User <i class="fas fa-plus-square"></i></a>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-0 p-3 mb-2 d-flex align-items-center gap-2" role="alert">
                <i class="fas fa-check-circle fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="table-responsive rounded-3 overflow-hidden border">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <th scope="row" class="text-center">{{ $index + 1 }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                @can('update_user')
                                    <a class="px-2 btn" href="{{ route('user.edit', $user->id) }}"><i class="color-tatami fas fa-edit"></i></a>
                                @endcan
                                @can('delete_user')
                                <form action="{{ route('user.destory', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menonaktifkan user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn">
                                        <i class="color-tatami fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top gap-3">
            <!-- Keterangan Jumlah Data -->
            <div class="text-secondary small">
                Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong> - <strong>{{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna
            </div>

            <!-- Tombol Pagination -->
            <div>
                {{ $users->links() }}
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

    nav div p.small {
        display: none !important;
    }

    /* Warna teks tombol pagination biasa */
    .pagination .page-link {
        color: #198754 !important; /* Warna hijau Bootstrap success */
    }

    /* Efek saat kursor diarahkan (Hover) */
    .pagination .page-link:hover {
        color: #146c43 !important;
        background-color: #e8f5e9;
    }

    /* Tampilan halaman yang sedang aktif */
    .pagination .page-item.active .page-link {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: #ffffff !important; /* Teks tetap putih agar kontras dengan background hijau */
    }

    /* Efek outline/ring saat tombol diklik (Focus) */
    .pagination .page-link:focus {
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }
</style>
@endpush
