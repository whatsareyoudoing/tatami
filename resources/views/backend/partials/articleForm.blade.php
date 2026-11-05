{{--
    Field form artikel, dipakai bersama oleh articleCreate dan articleEdit.
    $article null saat tambah. $products = semua produk untuk dipilih.
    CKEditor 5 (self-hosted di public/vendor/ckeditor5) hanya dimuat di halaman ini.
--}}
@php
    $article = $article ?? null;
    $hasOld = session()->hasOldInput();
    $isPublished = $hasOld ? (bool) old('is_published') : (bool) $article?->is_published;

    // Produk terpilih tampil paling atas, sesuai urutan pilihan
    $selectedIds = array_map('intval', old('products', $article?->products->pluck('id')->all() ?? []));
    $productList = $products->sortBy(fn ($p) => in_array($p->id, $selectedIds) ? array_search($p->id, $selectedIds) : PHP_INT_MAX)->values();
@endphp

@push('styles')
    <link rel="stylesheet" href="@asset('vendor/ckeditor5/ckeditor5.css')">
    <style>
        .ck-editor__editable_inline { min-height: 420px; }
        .product-picker { max-height: 360px; overflow-y: auto; }
    </style>
@endpush

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

<div class="row g-3">
    <div class="col-lg-8">
        <x-admin.card title="Isi Artikel">
            <x-admin.input name="judul" label="Judul" :value="$article?->judul" />

            <x-admin.input type="textarea" name="ringkasan" label="Ringkasan" :value="$article?->ringkasan" rows="2" maxlength="300" optional
                           hint="Maksimal 300 karakter, tampil di daftar artikel. Kosongkan untuk memakai awal isi artikel." />

            <div class="mb-0">
                <label for="isi" class="form-label">Isi</label>
                {{-- CKEditor mengisi textarea ini otomatis saat form dikirim --}}
                <textarea id="isi" name="isi" @class(['form-control', 'is-invalid' => $errors->has('isi')]) rows="12">{{ old('isi', $article?->isi) }}</textarea>
                @error('isi')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="form-text">Gambar bisa di-upload lewat tombol gambar di editor (JPG, PNG, WEBP, maks 2 MB). Gambar otomatis dikecilkan dan diubah ke WebP.</div>
            </div>
        </x-admin.card>
    </div>

    <div class="col-lg-4">
        <x-admin.card title="Terbitkan">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked($isPublished)>
                <label class="form-check-label" for="is_published">Terbitkan di website</label>
            </div>
            <div class="form-text">
                @if ($article?->published_at)
                    Terbit sejak {{ $article->published_at->translatedFormat('d M Y H:i') }}.
                @else
                    Matikan untuk menyimpan sebagai draft.
                @endif
            </div>
        </x-admin.card>

        <x-admin.card title="Sampul">
            @if ($article?->sampul_thumb_url)
                <img src="{{ $article->sampul_thumb_url }}" alt="" class="img-fluid rounded border mb-2">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="hapus_sampul" name="hapus_sampul" value="1" @checked(old('hapus_sampul'))>
                    <label class="form-check-label text-danger small" for="hapus_sampul">Hapus sampul</label>
                </div>
            @endif
            <x-admin.input type="file" name="sampul" :label="$article?->sampul ? 'Ganti sampul' : 'Upload sampul'" accept="image/jpeg,image/png,image/webp" optional wrapper="mb-0"
                           hint="JPG, PNG, atau WEBP, maks 2 MB." />
        </x-admin.card>

        <x-admin.card title="Produk di Artikel Ini">
            <p class="small text-muted">Produk yang dicentang tampil sebagai kartu di bawah artikel (maks 12). Produk nonaktif tidak ditampilkan di website.</p>
            @error('products')
                <div class="alert alert-danger py-2 small">{{ $message }}</div>
            @enderror

            <input type="search" class="form-control form-control-sm mb-2" id="product-search" placeholder="Cari produk..." aria-label="Cari produk">
            <div class="product-picker border rounded">
                @forelse ($productList as $product)
                    <label class="d-flex align-items-center gap-2 px-2 py-1 border-bottom product-option" data-name="{{ Str::lower($product->nama) }}">
                        <input type="checkbox" class="form-check-input mt-0" name="products[]" value="{{ $product->id }}" @checked(in_array($product->id, $selectedIds))>
                        @if ($product->cover_url)
                            <img src="{{ $product->cover_url }}" alt="" class="thumb thumb-sm rounded border" loading="lazy">
                        @endif
                        <span class="small">
                            {{ $product->nama }}
                            @unless ($product->is_active)
                                <span class="badge text-bg-secondary">Nonaktif</span>
                            @endunless
                        </span>
                    </label>
                @empty
                    <div class="small text-muted p-2">Belum ada produk.</div>
                @endforelse
            </div>
            <div class="small text-muted mt-1"><span id="product-count">{{ count($selectedIds) }}</span> produk dipilih</div>
        </x-admin.card>
    </div>
</div>

@push('scripts')
<script src="@asset('vendor/ckeditor5/ckeditor5.umd.js')"></script>
<script src="@asset('vendor/ckeditor5/translations/id.umd.js')"></script>
<script>
    (function () {
        var C = CKEDITOR;

        C.ClassicEditor.create(document.getElementById('isi'), {
            licenseKey: 'GPL',
            language: 'id',
            placeholder: 'Masukkan isi',
            plugins: [
                C.Essentials, C.Paragraph, C.Heading, C.Bold, C.Italic, C.Underline, C.Strikethrough,
                C.Link, C.AutoLink, C.List, C.BlockQuote, C.HorizontalLine, C.Autoformat, C.PasteFromOffice,
                C.Image, C.ImageUpload, C.ImageToolbar, C.ImageCaption, C.ImageStyle, C.ImageTextAlternative,
                C.SimpleUploadAdapter, C.Table, C.TableToolbar
            ],
            toolbar: [
                'undo', 'redo', '|', 'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|',
                'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'uploadImage', 'insertTable', 'horizontalLine'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                    { model: 'heading2', view: 'h2', title: 'Judul Bagian', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Sub Judul', class: 'ck-heading_heading3' }
                ]
            },
            image: { toolbar: ['imageTextAlternative', 'toggleImageCaption'] },
            table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] },
            link: { defaultProtocol: 'https://', addTargetToExternalLinks: true },
            simpleUpload: {
                uploadUrl: @json(route('article.upload-image')),
                headers: { 'X-CSRF-TOKEN': @json(csrf_token()), 'Accept': 'application/json' }
            }
        }).catch(function (error) {
            // Editor gagal dimuat: textarea biasa tetap bisa dipakai
            console.error(error);
        });

        // Cari produk + hitung yang dipilih
        var search = document.getElementById('product-search');
        var options = document.querySelectorAll('.product-option');
        var count = document.getElementById('product-count');

        search.addEventListener('input', function () {
            var keyword = search.value.trim().toLowerCase();
            options.forEach(function (option) {
                option.classList.toggle('d-none', keyword !== '' && option.dataset.name.indexOf(keyword) === -1);
            });
        });
        document.querySelector('.product-picker').addEventListener('change', function () {
            count.textContent = document.querySelectorAll('.product-option input:checked').length;
        });
    })();
</script>
@endpush
