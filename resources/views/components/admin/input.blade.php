{{--
    Label + input + pesan error dalam satu tag. old() dan class is-invalid diurus otomatis.
    <x-admin.input name="nama" label="Nama Produk" :value="$product?->nama" />
    <x-admin.input type="textarea" name="deskripsi" label="Deskripsi" rows="5" />

    wrapper     = class pembungkus (default mb-3, di dalam grid bisa "col-md-6")
    extraErrors = key error lain yang ikut ditampilkan di field ini
    Atribut lain (placeholder, min, required, ...) diteruskan ke <input>.
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
    $inputAttributes = $attributes->except('id')->class(['form-control', 'is-invalid' => $messages->isNotEmpty()]);
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
