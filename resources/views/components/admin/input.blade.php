{{--
    Label + input + pesan error dalam satu tag. old() dan class is-invalid diurus otomatis.
    <x-admin.input name="nama" label="Nama Produk" :value="$product?->nama" />
    <x-admin.input type="textarea" name="deskripsi" label="Deskripsi" rows="5" />

    wrapper     = class pembungkus (default mb-3, di dalam grid bisa "col-md-6")
    extraErrors = key error lain yang ikut ditampilkan di field ini
    Atribut lain (min, required, ...) diteruskan ke <input>.
    placeholder otomatis "Masukkan <label>" (label "Ulangi ..." dipakai apa adanya), bisa ditimpa lewat atribut placeholder.
--}}
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'hint' => null,
    'optional' => false,
    'wrapper' => 'mb-3',
    'extraErrors' => [],
])

@php
    // name="variants[0][nama]" -> key old()/error "variants.0.nama"
    $key = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $id = $attributes->get('id', str_replace('.', '_', $key));
    // Password dan file tidak pernah diisi ulang
    $current = in_array($type, ['password', 'file']) ? null : old($key, $value);

    $messages = collect([$key, ...$extraErrors])->map(fn ($k) => $errors->first($k))->filter();
    // "Harga (Rp)" -> "Masukkan harga"; singkatan/merek (HP, SKU, WhatsApp) tidak dikecilkan
    $placeholderText = collect(explode(' ', trim(preg_replace('/\s*\(.*?\)/', '', (string) $label))))
        ->map(fn ($word) => preg_match('/^\p{Lu}\p{Ll}*$/u', $word) ? mb_strtolower($word) : $word)
        ->implode(' ');
    $placeholder = $attributes->get('placeholder', match (true) {
        ! $label || $type === 'file' => null,
        str_starts_with($label, 'Ulangi ') => ucfirst($placeholderText),
        default => 'Masukkan ' . $placeholderText,
    });
    $inputAttributes = $attributes->except(['id', 'placeholder'])->merge(['placeholder' => $placeholder])->class(['form-control', 'is-invalid' => $messages->isNotEmpty()]);
@endphp

<div class="{{ $wrapper }}">
    @if ($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if ($optional) <small class="text-muted">(opsional)</small> @endif
        </label>
    @endif

    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" {{ $inputAttributes }}>{{ $current }}</textarea>
    @else
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" @unless (is_null($current)) value="{{ $current }}" @endunless {{ $inputAttributes }}>
    @endif

    @if ($hint)
        <div class="form-text">{{ $hint }}</div>
    @endif

    @foreach ($messages as $message)
        <div class="invalid-feedback">{{ $message }}</div>
    @endforeach
</div>
