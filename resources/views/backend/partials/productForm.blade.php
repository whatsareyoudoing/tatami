{{--
    Field form produk, dipakai bersama oleh productCreate dan productEdit.
    $product null saat tambah, berisi model saat edit.
--}}
@php
    $product = $product ?? null;

    // Baris warna: dari input lama (setelah gagal validasi), dari DB (edit), atau 1 baris kosong (tambah)
    $variantRows = old('variants', $product
        ? $product->variants->map(fn ($v) => ['id' => $v->id, 'warna' => $v->warna, 'stok' => $v->stok, 'is_active' => $v->is_active])->all()
        : [['warna' => '', 'stok' => 0, 'is_active' => true]]);

    $generalImages = $product ? $product->images->whereNull('product_variant_id') : collect();
    $hapusGambar = old('hapus_gambar', []);
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-1"><i class="fas fa-exclamation-circle me-1"></i> Periksa kembali isian berikut:</div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<x-admin.card title="Informasi Produk">
    <div class="row g-3">
        <x-admin.input name="nama" label="Nama Produk" :value="$product?->nama" :extraErrors="['slug']" wrapper="col-md-8" />
        <x-admin.input name="sku" label="SKU" :value="$product?->sku" optional wrapper="col-md-4" />

        <x-admin.select name="category_id" label="Kategori" :options="$categories->pluck('name', 'id')" :value="$product?->category_id" placeholder="Pilih kategori" wrapper="col-md-4" />
        <x-admin.input type="number" name="harga_dasar" label="Harga Asli (Rp)" :value="$product?->harga_dasar" min="0" placeholder="193000" wrapper="col-md-4" />
        <x-admin.input type="number" name="harga_coret" label="Harga Coret (Rp)" :value="$product?->harga_coret" min="0" placeholder="230000" optional wrapper="col-md-4" />

        <x-admin.input type="textarea" name="deskripsi" label="Deskripsi" :value="$product?->deskripsi" rows="5" wrapper="col-12" />

        <div class="col-12">
            <div class="form-check form-switch">
                {{-- Setelah gagal validasi pakai input lama; checkbox mati tidak terkirim, jadi tidak bisa pakai default old() --}}
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(session()->hasOldInput() ? old('is_active') : ($product?->is_active ?? true))>
                <label class="form-check-label" for="is_active">Tampilkan produk di website</label>
            </div>
        </div>
    </div>
</x-admin.card>

<x-admin.card title="Foto Produk">
    @if ($generalImages->isNotEmpty())
        <p class="small text-muted mb-2">Centang foto yang ingin dihapus. Foto paling kiri menjadi foto utama.</p>
        <div class="d-flex flex-wrap gap-3 mb-3">
            @foreach ($generalImages as $image)
                <label class="text-center" style="cursor: pointer;">
                    <img src="{{ $image->thumb_url }}" alt="" class="thumb thumb-lg rounded border d-block mb-1" loading="lazy">
                    <input type="checkbox" class="form-check-input" name="hapus_gambar[]" value="{{ $image->id }}" @checked(in_array($image->id, $hapusGambar))>
                    <small class="text-danger">Hapus</small>
                </label>
            @endforeach
        </div>
    @endif

    <x-admin.input type="file" name="images[]" :label="$generalImages->isNotEmpty() ? 'Tambah foto' : 'Upload foto'"
                   multiple accept="image/jpeg,image/png,image/webp" :extraErrors="['images.*']" wrapper="mb-0"
                   hint="Bisa pilih beberapa file sekaligus. Format JPG, PNG, atau WEBP, maksimal 2 MB per file. Foto otomatis dikecilkan dan diubah ke WebP." />
</x-admin.card>

<x-admin.card title="Warna">
    <x-slot:actions>
        <button type="button" class="btn btn-sm btn-tatami" id="add-variant"><i class="fas fa-plus me-1"></i> Tambah Warna</button>
    </x-slot:actions>

    <p class="small text-muted">Harga semua warna mengikuti harga produk. Foto warna akan tampil saat warna tersebut dipilih pembeli.</p>

    <div id="variant-rows">
        @foreach ($variantRows as $i => $row)
            @include('backend.partials.productVariantRow', [
                'i' => $i,
                'row' => $row,
                'existingImage' => $product?->variants->firstWhere('id', $row['id'] ?? null)?->images->first(),
            ])
        @endforeach
    </div>
</x-admin.card>

{{-- Template baris warna baru, __i__ diganti nomor unik oleh JS --}}
<template id="variant-template">
    @include('backend.partials.productVariantRow', ['i' => '__i__', 'row' => ['warna' => '', 'stok' => 0, 'is_active' => true], 'existingImage' => null])
</template>

@push('scripts')
<script>
    (function () {
        var rows = document.getElementById('variant-rows');
        var template = document.getElementById('variant-template');

        // Mulai dari index terbesar + 1 supaya tidak bentrok dengan baris yang sudah ada
        var nextIndex = {{ count($variantRows) ? max(array_map('intval', array_keys($variantRows))) + 1 : 0 }};

        document.getElementById('add-variant').addEventListener('click', function () {
            rows.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__i__', nextIndex++));
        });

        rows.addEventListener('click', function (event) {
            var button = event.target.closest('.remove-variant');
            if (!button) {
                return;
            }
            if (rows.querySelectorAll('.variant-row').length <= 1) {
                alert('Produk minimal harus punya 1 warna.');
                return;
            }
            button.closest('.variant-row').remove();
        });
    })();
</script>
@endpush
