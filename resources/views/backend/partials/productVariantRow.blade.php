{{-- Satu baris input varian. $i = index array, $row = data baris, $existingImage = foto varian di DB (edit) --}}
<div class="variant-row border rounded-3 p-3 mb-3">
    @if (!empty($row['id']))
        <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $row['id'] }}">
    @endif

    <div class="row g-3">
        <x-admin.input name="variants[{{ $i }}][nama]" label="Nama Varian" :value="$row['nama'] ?? ''"
                       id="variant-nama-{{ $i }}" wrapper="col-md-4" />
        <x-admin.input type="number" name="variants[{{ $i }}][harga]" label="Harga (Rp)" :value="$row['harga'] ?? ''" min="0"
                       id="variant-harga-{{ $i }}" wrapper="col-md-3" />
        <x-admin.input type="number" name="variants[{{ $i }}][harga_coret]" label="Harga Coret (Rp)" :value="$row['harga_coret'] ?? ''" min="0" optional
                       id="variant-coret-{{ $i }}" wrapper="col-md-3" />
        <x-admin.input type="number" name="variants[{{ $i }}][stok]" label="Stok" :value="$row['stok'] ?? 0" min="0"
                       id="variant-stok-{{ $i }}" wrapper="col-md-2" />

        <x-admin.input name="variants[{{ $i }}][sku]" label="SKU" :value="$row['sku'] ?? ''" optional
                       id="variant-sku-{{ $i }}" wrapper="col-md-3" />

        <div class="col-md-5">
            <label class="form-label" for="variant-image-{{ $i }}">Foto Varian <small class="text-muted">(opsional)</small></label>
            <div class="d-flex align-items-center gap-2">
                @if ($existingImage)
                    <img src="{{ $existingImage->thumb_url }}" alt="" loading="lazy" class="thumb thumb-sm rounded border" title="Upload foto baru untuk mengganti">
                @endif
                <input type="file" id="variant-image-{{ $i }}" name="variants[{{ $i }}][image]" accept="image/jpeg,image/png,image/webp"
                       @class(['form-control', 'is-invalid' => $errors->has("variants.$i.image")])>
            </div>
            @if ($errors->has("variants.$i.image"))
                <div class="invalid-feedback d-block">{{ $errors->first("variants.$i.image") }}</div>
            @endif
        </div>

        <div class="col-md-3 d-flex align-items-end justify-content-between">
            <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" name="variants[{{ $i }}][is_active]" value="1" id="variant-active-{{ $i }}" @checked(!empty($row['is_active']))>
                <label class="form-check-label small" for="variant-active-{{ $i }}">Aktif</label>
            </div>
            <button type="button" class="btn btn-outline-danger remove-variant" title="Hapus varian"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</div>
