<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use App\Services\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeProductImages extends Command
{
    protected $signature = 'products:optimize-images';

    protected $description = 'Ubah foto produk lama (sebelum ada optimizer) menjadi WebP + thumbnail. File asli dihapus setelah berhasil.';

    public function handle(ImageOptimizer $optimizer): int
    {
        $disk = Storage::disk('public');
        $images = ProductImage::where('image', 'not like', '%.webp')->get();

        if ($images->isEmpty()) {
            $this->info('Semua foto sudah dioptimasi.');
            return self::SUCCESS;
        }

        foreach ($images as $image) {
            if (!$disk->exists($image->image)) {
                $this->warn("Lewati #{$image->id}: file {$image->image} tidak ditemukan.");
                continue;
            }

            $before = $disk->size($image->image);
            $old = $image->image;

            $image->update(['image' => $optimizer->store($disk->path($old), dirname($old))]);
            $disk->delete($old);

            $this->line(sprintf('#%d  %s KB -> %s KB', $image->id, round($before / 1024), round($disk->size($image->image) / 1024)));
        }

        $this->info('Selesai.');

        return self::SUCCESS;
    }
}
