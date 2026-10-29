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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Edit Pengguna</h2>
            <a href="{{ route('user') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm rounded-3">
            <div class="card-body">
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror">
                            <option value="" disabled>Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role', $userRoleId) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-tatami">Simpan Perubahan</button>
                    </div>
                </form>
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
