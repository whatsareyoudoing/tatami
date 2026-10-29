{{--
    Label + select + pesan error.
    <x-admin.select name="role" label="Role" :options="$roles->pluck('name', 'id')" :value="$userRoleId" placeholder="Pilih role" />

    options = [value => label]
--}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'optional' => false,
    'wrapper' => 'mb-3',
])

@php
    $key = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $id = $attributes->get('id', str_replace('.', '_', $key));
    $current = (string) old($key, $value);
@endphp

<div class="{{ $wrapper }}">
    @if ($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if ($optional) <small class="text-muted">(opsional)</small> @endif
        </label>
    @endif

    <select id="{{ $id }}" name="{{ $name }}" {{ $attributes->except('id')->class(['form-select', 'is-invalid' => $errors->has($key)]) }}>
        @if ($placeholder)
            <option value="" disabled @selected($current === '')>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($current === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>

    @error($key)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
