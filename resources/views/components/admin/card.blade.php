{{--
    Kartu konten.
    title   = judul di header (opsional)
    flush   = tanpa padding, untuk membungkus tabel
    actions = slot tombol di kanan header
    footer  = slot footer, misalnya pagination
--}}
@props(['title' => null, 'flush' => false])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm rounded-3 mb-4']) }}>
    @if ($title || isset($actions))
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center gap-2">
            <span class="fw-semibold">{{ $title }}</span>
            {{ $actions ?? '' }}
        </div>
    @endif

    <div @class(['card-body', 'p-0' => $flush])>
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="card-footer bg-white py-3">
            {{ $footer }}
        </div>
    @endisset
</div>
