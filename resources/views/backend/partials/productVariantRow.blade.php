{{-- Satu baris input warna. $i = index array, $row = data baris, $existingImage = foto warna di DB (edit) --}}
<div class="variant-row border rounded-3 p-3 mb-3">
    @if (!empty($row['id']))
        <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $row['id'] }}">
    @endif

    <div class="row g-3 align-items-end">
        <x-admin.input name="variants[{{ $i }}][warna]" label="Nama Warna" :value="$row['warna'] ?? ''" placeholder="Light Blue"
                       id="variant-warna-{{ $i }}" wrapper="col-md-4" />

        <x-admin.input type="number" name="variants[{{ $i }}][stok]" label="Stok" :value="$row['stok'] ?? 0" min="0"
                       id="variant-stok-{{ $i }}" wrapper="col-md-2" />

        <div class="col-md-4">
            <label class="form-label" for="variant-image-{{ $i }}">Foto Warna <small class="text-muted">(opsional)</small></label>
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

        <div class="col-md-1">
            <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" name="variants[{{ $i }}][is_active]" value="1" id="variant-active-{{ $i }}" @checked(!empty($row['is_active']))>
                <label class="form-check-label small" for="variant-active-{{ $i }}">Aktif</label>
            </div>
        </div>

        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-outline-danger remove-variant" title="Hapus warna"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</div>
