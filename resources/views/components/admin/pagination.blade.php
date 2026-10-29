{{--
    Keterangan jumlah data + tombol halaman.
    <x-admin.pagination :paginator="$products" label="produk" />
--}}
@props(['paginator', 'label' => 'data'])

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
    <div class="text-secondary small">
        @if ($paginator->total())
            Menampilkan <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
            dari <strong>{{ $paginator->total() }}</strong> {{ $label }}
        @else
            Tidak ada {{ $label }}
        @endif
    </div>

    {{-- Template bootstrap-4 dipakai karena tidak membawa teks "Showing ..." bawaan (kelasnya tetap cocok dengan Bootstrap 5) --}}
    {{ $paginator->links('pagination::bootstrap-4') }}
</div>
