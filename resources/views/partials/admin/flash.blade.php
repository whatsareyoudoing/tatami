{{-- Pesan dari ->with('success', ...) atau ->with('error', ...) di controller --}}
@foreach (['success' => ['success', 'fa-check-circle'], 'error' => ['danger', 'fa-exclamation-circle']] as $key => [$color, $icon])
    @if (session($key))
        <div class="alert alert-{{ $color }} alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="fas {{ $icon }} fs-5"></i>
            <div>{{ session($key) }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
@endforeach
