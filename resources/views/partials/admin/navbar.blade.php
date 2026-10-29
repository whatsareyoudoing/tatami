<nav class="navbar navbar-dark bg-tatami sticky-top shadow-sm admin-navbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            {{-- Tombol buka sidebar, hanya tampil di HP/Tablet --}}
            <button class="btn btn-link text-white d-md-none me-2 p-1" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Buka menu">
                <i class="fas fa-bars fa-lg"></i>
            </button>
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">Tatami Indonesia</a>
        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="text-white small d-none d-sm-inline">
                <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
            </span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-sign-out-alt me-1"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</nav>
