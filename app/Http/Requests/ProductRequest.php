<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\ProductImage;
use App\Support\YouTube;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Dipakai untuk store dan update produk.
 * has_variants = false: harga/stok/SKU diisi langsung (disimpan ke 1 varian "Default").
 * has_variants = true : nama pilihan (variant_label) + daftar varian, masing-masing punya harga/stok/SKU.
 */
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
     * Checkbox yang tidak dicentang tidak terkirim, jadi diisi manual.
     * Link YouTube diubah menjadi ID video (null kalau link tidak valid).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active'    => $this->boolean('is_active'),
            'has_variants' => $this->boolean('has_variants'),
            'youtube_id'   => YouTube::idFromUrl($this->input('video_url')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $image = ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        // SKU unik di semua varian produk lain (varian milik produk ini boleh dipakai ulang saat edit)
        $uniqueSku = Rule::unique('product_variants', 'sku')->where(fn ($q) => $q->where('product_id', '!=', $this->product?->id ?? 0));

        $rules = [
            'category_id'  => ['required', 'exists:categories,id'],
            'nama'         => ['required', 'string', 'max:255'],
            'deskripsi'    => ['nullable', 'string'],
            'is_active'    => ['boolean'],
            'has_variants' => ['boolean'],

            // Video YouTube (opsional, tapi minimal harus ada 1 foto atau video, lihat after())
            'video_url'  => ['nullable', 'string', 'max:255'],
            'youtube_id' => ['nullable', 'string'],

            // Foto umum (tidak terikat varian)
            'images'   => ['nullable', 'array'],
            'images.*' => $image,

            // Foto umum yang dicentang untuk dihapus (saat edit)
            'hapus_gambar'   => ['nullable', 'array'],
            'hapus_gambar.*' => ['integer'],
        ];

        if (!$this->boolean('has_variants')) {
            return $rules + [
                'harga'       => ['required', 'integer', 'min:0'],
                'harga_coret' => ['nullable', 'integer', 'gt:harga'],
                'stok'        => ['required', 'integer', 'min:0'],
                'sku'         => ['nullable', 'string', 'max:100', $uniqueSku],
            ];
        }

        return $rules + [
            'variant_label' => ['required', 'string', 'max:50'],

            // Minimal 1 varian
            'variants'               => ['required', 'array', 'min:1'],
            // id hanya boleh milik produk ini (saat store, product null sehingga id apa pun ditolak)
            'variants.*.id'          => ['nullable', 'integer', Rule::exists('product_variants', 'id')->where('product_id', $this->product?->id)],
            'variants.*.nama'        => ['required', 'string', 'max:100', 'distinct:ignore_case'],
            'variants.*.harga'       => ['required', 'integer', 'min:0'],
            'variants.*.harga_coret' => ['nullable', 'integer', 'gt:variants.*.harga'],
            'variants.*.stok'        => ['required', 'integer', 'min:0'],
            'variants.*.sku'         => ['nullable', 'string', 'max:100', 'distinct:ignore_case', $uniqueSku],
            'variants.*.is_active'   => ['nullable', 'boolean'],
            'variants.*.image'       => ['nullable', ...$image],
        ];
    }

    /**
     * Nama produk tidak boleh sama dengan produk lain, dan produk wajib punya minimal 1 foto atau 1 video
     * (dihitung dari foto yang tersisa setelah dihapus + foto baru + video).
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->has('nama') || !$duplicate = $this->duplicateProduct()) {
                    return;
                }

                $validator->errors()->add('nama', "Produk \"{$duplicate->nama}\" sudah ada. Kalau ini jenis lain dari produk yang sama, tambahkan sebagai varian di produk tersebut.");
                // Dipakai form untuk menampilkan link ke produk yang sudah ada
                session()->flash('duplicate_product', ['id' => $duplicate->id, 'nama' => $duplicate->nama]);
            },
            function (Validator $validator) {
                if (filled($this->input('video_url')) && !$this->input('youtube_id')) {
                    $validator->errors()->add('video_url', 'Link YouTube tidak valid. Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx');
                    return;
                }

                if ($this->input('youtube_id') || $this->mediaCount() > 0) {
                    return;
                }

                $validator->errors()->add('media', 'Produk wajib punya minimal 1 foto atau link video YouTube.');
            },
        ];
    }

    /**
     * Dibandingkan lewat slug supaya huruf besar/kecil dan tanda baca diabaikan ("Bantal" = "bantal!").
     * Produk yang sudah dihapus tidak dihitung; produk yang sedang diedit dikecualikan.
     */
    private function duplicateProduct(): ?Product
    {
        $slug = Str::slug((string) $this->input('nama'));
        if ($slug === '') {
            return null;
        }

        return Product::select('id', 'nama')
            ->when($this->product, fn ($q) => $q->whereKeyNot($this->product->id))
            ->cursor()
            ->first(fn (Product $product) => Str::slug($product->nama) === $slug);
    }

    private function mediaCount(): int
    {
        $hasVariants = $this->boolean('has_variants');
        $variantRows = $hasVariants ? (array) $this->input('variants', []) : [];

        $newUploads = count((array) $this->file('images', []))
            + collect((array) $this->file('variants', []))->filter(fn ($row) => !empty($row['image']))->count() * (int) $hasVariants;

        if (!$this->product) {
            return $newUploads;
        }

        // Foto umum yang tidak dicentang "hapus"
        $remaining = ProductImage::where('product_id', $this->product->id)
            ->whereNull('product_variant_id')
            ->whereNotIn('id', (array) $this->input('hapus_gambar', []))
            ->count();

        // Foto varian ikut terhapus kalau variannya dihapus (atau produk diubah menjadi tanpa varian)
        $keptVariantIds = collect($variantRows)->pluck('id')->filter()->all();
        if ($keptVariantIds) {
            $remaining += ProductImage::where('product_id', $this->product->id)->whereIn('product_variant_id', $keptVariantIds)->count();
        }

        return $remaining + $newUploads;
    }

    public function messages(): array
    {
        return [
            'harga_coret.gt'                => 'Harga coret harus lebih besar dari harga.',
            'variant_label.required'        => 'Nama pilihan wajib diisi, misalnya Warna atau Ukuran.',
            'variants.required'             => 'Produk minimal harus punya 1 varian.',
            'variants.*.nama.required'      => 'Nama varian wajib diisi.',
            'variants.*.nama.distinct'      => 'Ada nama varian yang dobel.',
            'variants.*.harga.required'     => 'Harga wajib diisi.',
            'variants.*.harga_coret.gt'     => 'Harga coret harus lebih besar dari harga varian.',
            'variants.*.stok.required'      => 'Stok wajib diisi.',
            'variants.*.sku.distinct'       => 'Ada SKU yang dobel.',
            'variants.*.sku.unique'         => 'SKU sudah dipakai produk lain.',
            'sku.unique'                    => 'SKU sudah dipakai produk lain.',
            'variants.*.image.image'        => 'Foto varian harus berupa gambar.',
            'variants.*.image.max'          => 'Foto varian maksimal 2 MB.',
            'images.*.image'                => 'Foto produk harus berupa gambar.',
            'images.*.max'                  => 'Foto produk maksimal 2 MB per file.',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'harga_coret' => 'harga coret',
            'video_url'   => 'link video',
        ];
    }
}
