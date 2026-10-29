<?php

namespace App\Services;

use GdImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Mengecilkan gambar upload menjadi WebP + membuat thumbnail-nya, memakai GD bawaan PHP.
 *
 * Hasil: products/abc.webp (sisi terpanjang maks 1200px)
 *        products/thumbs/abc.webp (sisi terpanjang maks 300px)
 */
class ImageOptimizer
{
    public const MAX_SIZE = 1200;
    public const THUMB_SIZE = 300;
    public const QUALITY = 80;

    /**
     * Proses file sumber, simpan ke disk public, kembalikan path gambar utama.
     */
    public function store(string $sourceFile, string $directory = 'products'): string
    {
        $image = $this->load($sourceFile);
        $path = $directory . '/' . Str::random(40) . '.webp';

        $disk = Storage::disk('public');
        $disk->put($path, $this->encode($this->resize($image, self::MAX_SIZE)));
        $disk->put(self::thumbnailPath($path), $this->encode($this->resize($image, self::THUMB_SIZE)));

        return $path;
    }

    /**
     * Hapus gambar beserta thumbnail-nya.
     */
    public function delete(string ...$paths): void
    {
        foreach ($paths as $path) {
            Storage::disk('public')->delete([$path, self::thumbnailPath($path)]);
        }
    }

    public static function thumbnailPath(string $path): string
    {
        return dirname($path) . '/thumbs/' . basename($path);
    }

    private function load(string $file): GdImage
    {
        $image = @imagecreatefromstring((string) file_get_contents($file));
        if (!$image) {
            throw new RuntimeException('File gambar tidak bisa dibaca.');
        }

        // Jaga transparansi PNG/WebP
        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        return $this->fixOrientation($image, $this->readJpegOrientation($file));
    }

    /**
     * Perkecil supaya sisi terpanjang <= $maxSize. Gambar kecil tidak diperbesar.
     */
    private function resize(GdImage $image, int $maxSize): GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $scale = $maxSize / max($width, $height);

        if ($scale >= 1) {
            return $image;
        }

        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        return $resized;
    }

    private function encode(GdImage $image): string
    {
        ob_start();
        imagewebp($image, null, self::QUALITY);

        return (string) ob_get_clean();
    }

    /**
     * Foto dari HP sering disimpan miring dengan tag orientasi EXIF. GD membuang tag itu,
     * jadi gambar harus diputar dulu supaya tidak tampil miring setelah dikonversi.
     */
    private function fixOrientation(GdImage $image, int $orientation): GdImage
    {
        return match ($orientation) {
            2 => $this->flip($image),
            3 => imagerotate($image, 180, 0),
            4 => $this->flip($image, IMG_FLIP_VERTICAL),
            5 => $this->flip(imagerotate($image, -90, 0)),
            6 => imagerotate($image, -90, 0),
            7 => $this->flip(imagerotate($image, 90, 0)),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    private function flip(GdImage $image, int $mode = IMG_FLIP_HORIZONTAL): GdImage
    {
        imageflip($image, $mode);

        return $image;
    }

    /**
     * Baca tag Orientation (0x0112) dari header EXIF JPEG tanpa ekstensi exif PHP.
     * Mengembalikan 1 (normal) kalau bukan JPEG atau tag tidak ditemukan.
     */
    private function readJpegOrientation(string $file): int
    {
        try {
            $data = (string) file_get_contents($file, false, null, 0, 65536);
            if (!str_starts_with($data, "\xFF\xD8")) {
                return 1;
            }

            $offset = 2;
            while ($offset + 4 <= strlen($data) && $data[$offset] === "\xFF") {
                $marker = ord($data[$offset + 1]);
                $length = unpack('n', substr($data, $offset + 2, 2))[1];

                if ($marker === 0xE1 && substr($data, $offset + 4, 6) === "Exif\0\0") {
                    $tiff = substr($data, $offset + 10, $length - 8);
                    $littleEndian = str_starts_with($tiff, 'II');
                    $u16 = fn (int $at) => unpack($littleEndian ? 'v' : 'n', substr($tiff, $at, 2))[1];
                    $u32 = fn (int $at) => unpack($littleEndian ? 'V' : 'N', substr($tiff, $at, 4))[1];

                    $ifd = $u32(4);
                    for ($i = 0, $count = $u16($ifd); $i < $count; $i++) {
                        $entry = $ifd + 2 + $i * 12;
                        if ($u16($entry) === 0x0112) {
                            return $u16($entry + 8);
                        }
                    }

                    return 1;
                }

                if ($marker === 0xDA) { // awal data gambar, EXIF tidak ada
                    return 1;
                }

                $offset += 2 + $length;
            }
        } catch (\Throwable) {
            // Header rusak/aneh: anggap orientasi normal
        }

        return 1;
    }
}
