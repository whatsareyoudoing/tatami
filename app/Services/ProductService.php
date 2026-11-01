<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductService
{
    /** File yang baru di-upload pada request ini (dihapus lagi kalau transaksi gagal) */
    private array $storedFiles = [];

    /** File lama yang sudah tidak dipakai (dihapus setelah transaksi berhasil) */
    private array $obsoleteFiles = [];

    private const FIELDS = ['category_id', 'nama', 'deskripsi', 'is_active', 'has_variants', 'variant_label', 'youtube_id'];

    public function __construct(
        protected ImageOptimizer $imageOptimizer
    ) {}

    public function createProduct(array $data): Product
    {
        return $this->run(function () use ($data) {
            $product = Product::create([
                ...$this->productFields($data),
                'slug' => $this->uniqueSlug($data['nama']),
            ]);

            $this->storeImages($product, $data['images'] ?? []);
            $this->syncPricing($product, $data, wasVariable: false);

            return $product;
        });
    }

    public function updateProduct(array $data, Product $product): Product
    {
        return $this->run(function () use ($data, $product) {
            $wasVariable = $product->has_variants;

            // URL produk hanya berubah kalau namanya diganti, supaya link yang sudah dibagikan tetap jalan
            $slug = $product->nama === $data['nama'] ? $product->slug : $this->uniqueSlug($data['nama'], $product->id);
            $product->update([...$this->productFields($data), 'slug' => $slug]);

            $this->deleteImages($product, $data['hapus_gambar'] ?? []);
            $this->storeImages($product, $data['images'] ?? []);
            $this->syncPricing($product, $data, $wasVariable);

            return $product;
        });
    }

    /**
     * Soft delete: data dan gambar tetap disimpan untuk riwayat pesanan.
     */
    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }

    /**
     * Slug dari nama; kalau sudah dipakai (termasuk produk yang sudah dihapus) diberi nomor: bantal, bantal-2, bantal-3.
     */
    public function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;

        for ($i = 2; Product::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    private function productFields(array $data): array
    {
        $fields = Arr::only($data, self::FIELDS);
        $fields['youtube_id'] = $data['youtube_id'] ?? null;
        $fields['variant_label'] = $data['has_variants'] ? $data['variant_label'] : null;

        return $fields;
    }

    /**
     * Jalankan dalam transaksi dan rapikan file di storage sesuai hasilnya.
     */
    private function run(callable $callback): Product
    {
        $this->storedFiles = $this->obsoleteFiles = [];

        try {
            $product = DB::transaction($callback);
        } catch (\Throwable $e) {
            $this->imageOptimizer->delete(...$this->storedFiles);

            // Lolos validasi tapi ditolak indeks unik DB (dua simpan bersamaan): tampilkan sebagai error form biasa
            if ($e instanceof UniqueConstraintViolationException && str_contains($e->getMessage(), 'nama_aktif')) {
                throw ValidationException::withMessages(['nama' => 'Produk dengan nama ini sudah ada.']);
            }
            throw $e;
        }

        $this->imageOptimizer->delete(...$this->obsoleteFiles);

        return $product;
    }

    private function syncPricing(Product $product, array $data, bool $wasVariable): void
    {
        $data['has_variants']
            ? $this->syncVariants($product, $data['variants'])
            : $this->syncDefaultVariant($product, $data, $wasVariable);
    }

    /**
     * Produk tanpa varian: harga/stok/SKU disimpan di 1 varian "Default".
     * Kalau sebelumnya produk punya varian, semua varian lama diganti (pesanan lama tetap aman karena datanya disalin).
     */
    private function syncDefaultVariant(Product $product, array $data, bool $wasVariable): void
    {
        $attributes = [
            'nama'        => Product::DEFAULT_VARIANT,
            'harga'       => $data['harga'],
            'harga_coret' => $data['harga_coret'] ?? null,
            'stok'        => $data['stok'],
            'sku'         => $data['sku'] ?? null,
            'urutan'      => 0,
            'is_active'   => true,
        ];

        $variants = $product->variants()->with('images')->get();
        $default = $wasVariable ? null : $variants->first();

        foreach ($variants as $variant) {
            if ($variant->isNot($default)) {
                $this->discardImages($variant->images);
                $variant->delete();
            }
        }

        $default ? $default->update($attributes) : $product->variants()->create($attributes);
    }

    /**
     * Samakan varian di DB dengan isi form: yang hilang dihapus, yang ada diupdate, yang baru dibuat.
     * Urutan baris di form menjadi kolom urutan.
     */
    private function syncVariants(Product $product, array $rows): void
    {
        // validated() bisa mengacak urutan array (baris ber-id duluan), jadi urutkan lagi sesuai index form
        ksort($rows);

        $keepIds = collect($rows)->pluck('id')->filter()->all();

        // Varian yang dihapus dari form ikut menghapus fotonya
        $removed = $product->variants()->whereNotIn('id', $keepIds)->with('images')->get();
        foreach ($removed as $variant) {
            $this->discardImages($variant->images);
            $variant->delete();
        }

        foreach (array_values($rows) as $index => $row) {
            $attributes = [
                'nama'        => $row['nama'],
                'harga'       => $row['harga'],
                'harga_coret' => $row['harga_coret'] ?? null,
                'stok'        => $row['stok'],
                'sku'         => $row['sku'] ?? null,
                'urutan'      => $index,
                'is_active'   => !empty($row['is_active']),
            ];

            $variant = !empty($row['id'])
                ? tap($product->variants()->findOrFail($row['id']))->update($attributes)
                : $product->variants()->create($attributes);

            // Satu foto per varian: foto baru menggantikan yang lama
            if (!empty($row['image'])) {
                $this->discardImages($variant->images);

                $product->images()->create([
                    'product_variant_id' => $variant->id,
                    'image'              => $this->store($row['image']),
                    'urutan'             => $this->nextUrutan($product),
                ]);
            }
        }
    }

    /**
     * @param  UploadedFile[]  $files
     */
    private function storeImages(Product $product, array $files): void
    {
        foreach ($files as $file) {
            $product->images()->create([
                'image'  => $this->store($file),
                'urutan' => $this->nextUrutan($product),
            ]);
        }
    }

    /**
     * Hapus foto umum yang dicentang. Dibatasi ke foto milik produk ini saja.
     */
    private function deleteImages(Product $product, array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $images = ProductImage::where('product_id', $product->id)
            ->whereNull('product_variant_id')
            ->whereIn('id', $ids)
            ->get();

        $this->discardImages($images);
    }

    private function discardImages($images): void
    {
        $this->obsoleteFiles = [...$this->obsoleteFiles, ...$images->pluck('image')->all()];
        ProductImage::whereKey($images->modelKeys())->delete();
    }

    /**
     * Simpan sebagai WebP yang sudah diperkecil + thumbnail (lihat ImageOptimizer).
     */
    private function store(UploadedFile $file): string
    {
        $path = $this->imageOptimizer->store($file->getRealPath());
        $this->storedFiles[] = $path;

        return $path;
    }

    private function nextUrutan(Product $product): int
    {
        return (int) ProductImage::where('product_id', $product->id)->max('urutan') + 1;
    }
}
