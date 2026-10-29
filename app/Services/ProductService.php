<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /** File yang baru di-upload pada request ini (dihapus lagi kalau transaksi gagal) */
    private array $storedFiles = [];

    /** File lama yang sudah tidak dipakai (dihapus setelah transaksi berhasil) */
    private array $obsoleteFiles = [];

    private const FIELDS = ['category_id', 'nama', 'slug', 'sku', 'harga_dasar', 'harga_coret', 'deskripsi', 'is_active'];

    public function __construct(
        protected ImageOptimizer $imageOptimizer
    ) {}

    public function createProduct(array $data): Product
    {
        return $this->run(function () use ($data) {
            $product = Product::create(Arr::only($data, self::FIELDS));

            $this->storeImages($product, $data['images'] ?? []);
            $this->syncVariants($product, $data['variants']);

            return $product;
        });
    }

    public function updateProduct(array $data, Product $product): Product
    {
        return $this->run(function () use ($data, $product) {
            $product->update(Arr::only($data, self::FIELDS));

            $this->deleteImages($product, $data['hapus_gambar'] ?? []);
            $this->storeImages($product, $data['images'] ?? []);
            $this->syncVariants($product, $data['variants']);

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
     * Jalankan dalam transaksi dan rapikan file di storage sesuai hasilnya.
     */
    private function run(callable $callback): Product
    {
        $this->storedFiles = $this->obsoleteFiles = [];

        try {
            $product = DB::transaction($callback);
        } catch (\Throwable $e) {
            $this->imageOptimizer->delete(...$this->storedFiles);
            throw $e;
        }

        $this->imageOptimizer->delete(...$this->obsoleteFiles);

        return $product;
    }

    /**
     * Samakan warna di DB dengan isi form: yang hilang dihapus, yang ada diupdate, yang baru dibuat.
     * Urutan baris di form menjadi kolom urutan.
     */
    private function syncVariants(Product $product, array $rows): void
    {
        // validated() bisa mengacak urutan array (baris ber-id duluan), jadi urutkan lagi sesuai index form
        ksort($rows);

        $keepIds = collect($rows)->pluck('id')->filter()->all();

        // Warna yang dihapus dari form ikut menghapus fotonya
        $removed = $product->variants()->whereNotIn('id', $keepIds)->with('images')->get();
        foreach ($removed as $variant) {
            $this->discardImages($variant->images);
            $variant->delete();
        }

        foreach (array_values($rows) as $index => $row) {
            $attributes = [
                'warna'     => $row['warna'],
                'stok'      => $row['stok'],
                'urutan'    => $index,
                'is_active' => !empty($row['is_active']),
            ];

            $variant = !empty($row['id'])
                ? tap($product->variants()->findOrFail($row['id']))->update($attributes)
                : $product->variants()->create($attributes);

            // Satu foto per warna: foto baru menggantikan yang lama
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
