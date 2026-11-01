{{--
    Field form produk, dipakai bersama oleh productCreate dan productEdit.
    $product null saat tambah, berisi model saat edit.
--}}
@php
    $product = $product ?? null;
    $hasOld = session()->hasOldInput();

    // Saklar varian: input lama (setelah gagal validasi) > data produk > default mati untuk produk baru
    $hasVariants = $hasOld ? (bool) old('has_variants') : (bool) $product?->has_variants;

    // Produk tanpa varian menyimpan harga/stok/SKU di 1 varian "Default"
    $default = $product && !$product->has_variants ? $product->variants->first() : null;

    // Baris varian: dari input lama, dari DB (produk bervarian), atau 1 baris kosong
    $emptyRow = ['nama' => '', 'harga' => '', 'harga_coret' => '', 'stok' => 0, 'sku' => '', 'is_active' => true];
    $variantRows = old('variants', $product?->has_variants
        ? $product->variants->map(fn ($v) => [
            'id' => $v->id, 'nama' => $v->nama, 'harga' => $v->harga, 'harga_coret' => $v->harga_coret,
            'stok' => $v->stok, 'sku' => $v->sku, 'is_active' => $v->is_active,
        ])->all()
        : [$emptyRow]);

    $generalImages = $product ? $product->images->whereNull('product_variant_id') : collect();
    $hapusGambar = old('hapus_gambar', []);
    $videoUrl = old('video_url', $product?->youtube_id ? \App\Support\YouTube::watchUrl($product->youtube_id) : null);
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
        <div class="col-md-8">
            <x-admin.input name="nama" label="Nama Produk" :value="$product?->nama" wrapper="" />
            @if ($duplicate = session('duplicate_product'))
                <a href="{{ route('product.edit', $duplicate['id']) }}" class="small d-inline-block mt-1">
                    <i class="fas fa-arrow-right me-1"></i> Buka produk "{{ $duplicate['nama'] }}"
                </a>
            @endif
        </div>
        <x-admin.select name="category_id" label="Kategori" :options="$categories->pluck('name', 'id')" :value="$product?->category_id" placeholder="Pilih kategori" wrapper="col-md-4" />

        <x-admin.input type="textarea" name="deskripsi" label="Deskripsi" :value="$product?->deskripsi" rows="5" wrapper="col-12" />

        <div class="col-12">
            <div class="form-check form-switch">
                {{-- Setelah gagal validasi pakai input lama; checkbox mati tidak terkirim, jadi tidak bisa pakai default old() --}}
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked($hasOld ? old('is_active') : ($product?->is_active ?? true))>
                <label class="form-check-label" for="is_active">Tampilkan produk di website</label>
            </div>
        </div>
    </div>
</x-admin.card>

<x-admin.card title="Foto & Video">
    @error('media')
        <div class="alert alert-danger py-2">{{ $message }}</div>
    @enderror
    <p class="small text-muted">Wajib diisi minimal 1 foto <strong>atau</strong> link video YouTube.</p>

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
                   multiple accept="image/jpeg,image/png,image/webp" :extraErrors="['images.*']"
                   hint="Bisa pilih beberapa file sekaligus. Format JPG, PNG, atau WEBP, maksimal 2 MB per file. Foto otomatis dikecilkan dan diubah ke WebP." />

    <div class="row g-3 align-items-end">
        <x-admin.input name="video_url" label="Link Video YouTube" :value="$videoUrl" optional wrapper="col-md-8 mb-0"
                       placeholder="https://www.youtube.com/watch?v=..." hint="Boleh link youtube.com/watch, youtu.be, atau youtube.com/shorts. Kosongkan untuk menghapus video." />
        <div class="col-md-4">
            <img id="video-preview" src="" alt="Pratinjau video" class="rounded border d-none" style="width: 160px; aspect-ratio: 16 / 9; object-fit: cover;">
        </div>
    </div>
</x-admin.card>

<x-admin.card title="Harga & Stok">
    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" role="switch" id="has_variants" name="has_variants" value="1" @checked($hasVariants)
               data-was-variable="{{ $product?->has_variants ? 1 : 0 }}">
        <label class="form-check-label fw-semibold" for="has_variants">Produk ini punya varian</label>
        <div class="form-text">Aktifkan kalau harga/stok berbeda per pilihan, misalnya per warna, ukuran, atau jumlah daun.</div>
    </div>

    {{-- Tanpa varian: harga/stok/SKU langsung --}}
    <fieldset id="simple-pricing" @disabled($hasVariants) @class(['d-none' => $hasVariants])>
        <div class="row g-3">
            <x-admin.input type="number" name="harga" label="Harga (Rp)" :value="$default?->harga" min="0" placeholder="193000" wrapper="col-md-3" />
            <x-admin.input type="number" name="harga_coret" label="Harga Coret (Rp)" :value="$default?->harga_coret" min="0" placeholder="230000" optional wrapper="col-md-3" />
            <x-admin.input type="number" name="stok" label="Stok" :value="$default?->stok ?? 0" min="0" wrapper="col-md-2" />
            <x-admin.input name="sku" label="SKU" :value="$default?->sku" optional wrapper="col-md-4" />
        </div>
    </fieldset>

    {{-- Dengan varian: nama pilihan + daftar varian --}}
    <fieldset id="variant-pricing" @disabled(!$hasVariants) @class(['d-none' => !$hasVariants])>
        <x-admin.input name="variant_label" label="Nama Pilihan" :value="$product?->variant_label" list="variant-label-options"
                       placeholder="Warna" wrapper="col-md-4 mb-3" hint="Tampil sebagai judul pilihan di halaman produk." />
        <datalist id="variant-label-options">
            <option value="Warna"><option value="Ukuran"><option value="Jumlah Daun"><option value="Tebal"><option value="Model">
        </datalist>

        <p class="small text-muted">Foto varian akan tampil saat varian tersebut dipilih pembeli.</p>

        <div id="variant-rows">
            @foreach ($variantRows as $i => $row)
                @include('backend.partials.productVariantRow', [
                    'i' => $i,
                    'row' => $row,
                    'existingImage' => $product?->variants->firstWhere('id', $row['id'] ?? null)?->images->first(),
                ])
            @endforeach
        </div>

        <button type="button" class="btn btn-sm btn-tatami" id="add-variant"><i class="fas fa-plus me-1"></i> Tambah Varian</button>
    </fieldset>
</x-admin.card>

{{-- Template baris varian baru, __i__ diganti nomor unik oleh JS --}}
<template id="variant-template">
    @include('backend.partials.productVariantRow', ['i' => '__i__', 'row' => $emptyRow, 'existingImage' => null])
</template>

@push('scripts')
<script>
    (function () {
        var toggle = document.getElementById('has_variants');
        var simple = document.getElementById('simple-pricing');
        var variable = document.getElementById('variant-pricing');
        var rows = document.getElementById('variant-rows');
        var template = document.getElementById('variant-template');

        // Saklar varian: tampilkan satu bagian, matikan bagian lain supaya isiannya tidak ikut terkirim
        toggle.addEventListener('change', function () {
            simple.disabled = toggle.checked;
            simple.classList.toggle('d-none', toggle.checked);
            variable.disabled = !toggle.checked;
            variable.classList.toggle('d-none', !toggle.checked);
        });

        var form = toggle.closest('form');
        var submitButtons = form.querySelectorAll('button[type="submit"]');
        submitButtons.forEach(function (button) {
            button.dataset.label = button.innerHTML;
        });

        // Produk bervarian diubah menjadi tanpa varian: minta konfirmasi dulu.
        // Setelah terkirim, tombol Simpan dikunci supaya klik dua kali tidak membuat produk dobel.
        form.addEventListener('submit', function (event) {
            if (toggle.dataset.wasVariable === '1' && !toggle.checked
                && !confirm('Semua varian lama akan dihapus dan diganti harga/stok tunggal. Lanjutkan?')) {
                event.preventDefault();
                return;
            }
            submitButtons.forEach(function (button) {
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
            });
        });

        // Kembali ke halaman ini lewat tombol Back browser: buka lagi kuncinya
        window.addEventListener('pageshow', function () {
            submitButtons.forEach(function (button) {
                button.disabled = false;
                button.innerHTML = button.dataset.label;
            });
        });

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
                alert('Produk bervarian minimal harus punya 1 varian.');
                return;
            }
            button.closest('.variant-row').remove();
        });

        // Pratinjau thumbnail video dari link YouTube
        var videoInput = document.getElementById('video_url');
        var preview = document.getElementById('video-preview');
        var pattern = /(?:youtube\.com\/(?:watch\?(?:.*&)?v=|shorts\/|embed\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/;

        function updatePreview() {
            var match = pattern.exec(videoInput.value);
            preview.classList.toggle('d-none', !match);
            if (match) {
                preview.src = 'https://i.ytimg.com/vi/' + match[1] + '/hqdefault.jpg';
            }
        }
        videoInput.addEventListener('input', updatePreview);
        updatePreview();
    })();
</script>
@endpush
