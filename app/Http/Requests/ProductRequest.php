<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Slug dibuat dari nama sebelum validasi, supaya keunikannya ikut dicek.
     * Checkbox yang tidak dicentang tidak terkirim, jadi is_active diisi manual.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug'      => Str::slug((string) $this->nama),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     * Dipakai untuk store dan update; saat update, produk yang sedang diedit diabaikan.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $image = ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'nama'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', Rule::unique('products')->ignore($this->product)],
            'sku'         => ['nullable', 'string', 'max:100', Rule::unique('products')->ignore($this->product)],
            'harga_dasar' => ['required', 'integer', 'min:0'],
            'harga_coret' => ['nullable', 'integer', 'gt:harga_dasar'],
            'deskripsi'   => ['nullable', 'string'],
            'is_active'   => ['boolean'],

            // Minimal 1 warna per produk
            'variants'         => ['required', 'array', 'min:1'],
            // id hanya boleh milik produk ini (saat store, product null sehingga id apa pun ditolak)
            'variants.*.id'    => ['nullable', 'integer', Rule::exists('product_variants', 'id')->where('product_id', $this->product?->id)],
            'variants.*.warna' => ['required', 'string', 'max:100', 'distinct:ignore_case'],
            'variants.*.stok'  => ['required', 'integer', 'min:0'],
            'variants.*.is_active' => ['nullable', 'boolean'],
            'variants.*.image' => ['nullable', ...$image],

            // Foto umum (tidak terikat warna)
            'images'   => ['nullable', 'array'],
            'images.*' => $image,

            // Foto umum yang dicentang untuk dihapus (saat edit)
            'hapus_gambar'   => ['nullable', 'array'],
            'hapus_gambar.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.required'            => 'Nama produk harus mengandung huruf atau angka.',
            'slug.unique'              => 'Nama produk terlalu mirip dengan produk yang sudah ada.',
            'harga_coret.gt'           => 'Harga coret harus lebih besar dari harga asli.',
            'variants.required'        => 'Produk minimal harus punya 1 warna.',
            'variants.*.warna.required' => 'Nama warna wajib diisi.',
            'variants.*.warna.distinct' => 'Ada nama warna yang dobel.',
            'variants.*.stok.required' => 'Stok wajib diisi.',
            'variants.*.image.image'   => 'Foto warna harus berupa gambar.',
            'variants.*.image.max'     => 'Foto warna maksimal 2 MB.',
            'images.*.image'           => 'Foto produk harus berupa gambar.',
            'images.*.max'             => 'Foto produk maksimal 2 MB per file.',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'harga_dasar' => 'harga asli',
            'harga_coret' => 'harga coret',
        ];
    }
}
