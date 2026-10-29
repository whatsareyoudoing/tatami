@php
    // Daftar menu admin. Tambah modul baru cukup dengan menambah satu baris di sini.
    // route  = nama route tujuan
    // active = pola nama route yang membuat menu ini tersorot
    // can    = permission yang dibutuhkan (null = semua user login)
    $menu = [
        ['label' => 'Dashboard', 'icon' => 'fa-home',    'route' => 'dashboard',      'active' => 'dashboard',  'can' => null],
        ['label' => 'Pengguna',  'icon' => 'fa-users',   'route' => 'user.index',     'active' => 'user.*',     'can' => 'view_user'],
        ['label' => 'Kategori',  'icon' => 'fa-archive', 'route' => 'category.index', 'active' => 'category.*', 'can' => 'view_category'],
        ['label' => 'Produk',    'icon' => 'fa-box',     'route' => 'product.index',  'active' => 'product.*',  'can' => 'view_product'],
    ];
@endphp

<aside class="col-md-3 col-lg-2 p-0 bg-white border-end admin-sidebar">
    {{-- offcanvas-md: di desktop tampil biasa, di HP menjadi panel geser --}}
    <div class="offcanvas-md offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header bg-tatami text-white">
            <h5 class="offcanvas-title" id="sidebarLabel">Tatami Indonesia</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Tutup"></button>
        </div>

        <div class="offcanvas-body p-3">
            <nav class="nav nav-pills flex-column gap-1 w-100">
                @foreach ($menu as $item)
                    @if (is_null($item['can']) || auth()->user()->can($item['can']))
                        @php $isActive = request()->routeIs($item['active']); @endphp
                        <a href="{{ route($item['route']) }}" @class(['nav-link', 'active' => $isActive]) @if ($isActive) aria-current="page" @endif>
                            <i class="fas {{ $item['icon'] }} fa-fw me-2"></i>{{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
    </div>
</aside>
