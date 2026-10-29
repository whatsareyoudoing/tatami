@php
    // Menu utama website. 'match' = pola URL yang membuat menu ini aktif.
    $menu = [
        ['label' => 'Beranda',       'url' => '/',             'match' => '/'],
        ['label' => 'Produk',        'url' => '/produk',       'match' => 'produk*'],
        ['label' => 'Tentang',       'url' => '/tentang',      'match' => 'tentang'],
        ['label' => 'Custom Tatami', 'url' => '/custom-tatami', 'match' => 'custom-tatami'],
        ['label' => 'Kontak',        'url' => '/kontak',       'match' => 'kontak'],
        ['label' => 'Artikel',       'url' => '/artikel',      'match' => 'artikel*'],
    ];
@endphp

<nav class="navbar navbar-expand-lg bg-white shadow-2xs sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary d-none d-lg-block" href="{{ url('/') }}">
        <img src="https://tatamiindonesia.id/wp-content/uploads/2025/06/cropped-icon-website-1.png" alt="Tatami Indonesia">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav fw-bold gap-4 fs-6">
        @foreach ($menu as $item)
          @php $isActive = request()->is($item['match']); @endphp
          <li class="nav-item">
            <a @class(['nav-link', 'active' => $isActive]) @if ($isActive) aria-current="page" @endif href="{{ url($item['url']) }}">{{ $item['label'] }}</a>
          </li>
        @endforeach
      </ul>
    </div>
    <div class="d-flex justify-between align-items-center gap-3">
        @guest
            <a href="{{ route('login') }}" class="text-decoration-none" title="Masuk / Daftar">
                <i class="fas fa-user fs-4 color-tatami"></i>
            </a>
        @else
            <div class="dropdown">
                <a href="#" class="text-decoration-none dropdown-toggle d-flex align-items-center gap-2 color-tatami" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Akun">
                    <i class="fas fa-user-circle fs-4"></i>
                    <span class="d-none d-md-inline fw-semibold small">{{ Str::limit(auth()->user()->name, 15) }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <div class="dropdown-header">
                            <div class="fw-semibold text-body">{{ auth()->user()->name }}</div>
                            <small>{{ auth()->user()->email }}</small>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>

                    @can('access_admin')
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt fa-fw me-2"></i>Panel Admin</a></li>
                    @elseif (!auth()->user()->hasVerifiedEmail())
                        <li><a class="dropdown-item text-warning-emphasis" href="{{ route('verification.notice') }}"><i class="fas fa-envelope fa-fw me-2"></i>Verifikasi Email</a></li>
                    @else
                        <li><a class="dropdown-item" href="{{ route('account.index') }}"><i class="fas fa-user fa-fw me-2"></i>Akun Saya</a></li>
                    @endcan

                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt fa-fw me-2"></i>Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endguest

        {{-- Keranjang menyusul di tahap 3 --}}
        <i class="fas fa-shopping-cart fs-4 color-tatami"></i>
    </div>
  </div>
</nav>
