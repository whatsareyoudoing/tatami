<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Simpan/hapus artikel. Isi dari CKEditor dibersihkan dulu (tanpa script/atribut berbahaya),
 * gambar di dalam isi disimpan di articles/content dan dihapus kalau sudah tidak dipakai.
 */
class ArticleService
{
    public const CONTENT_DIR = 'articles/content';

    public function __construct(
        protected ImageOptimizer $imageOptimizer
    ) {}

    public function create(array $data, User $author): Article
    {
        $sampul = isset($data['sampul']) ? $this->imageOptimizer->store($data['sampul']->getRealPath(), 'articles') : null;

        try {
            return DB::transaction(function () use ($data, $author, $sampul) {
                $article = Article::create([
                    ...$this->fields($data, null),
                    'user_id' => $author->id,
                    'slug'    => $this->uniqueSlug($data['judul']),
                    'sampul'  => $sampul,
                ]);
                $this->syncProducts($article, $data['products'] ?? []);

                return $article;
            });
        } catch (\Throwable $e) {
            $sampul && $this->imageOptimizer->delete($sampul);
            throw $e;
        }
    }

    public function update(array $data, Article $article): Article
    {
        $oldSampul = $article->sampul;
        $oldImages = $this->contentImages($article->isi);
        $newSampul = isset($data['sampul']) ? $this->imageOptimizer->store($data['sampul']->getRealPath(), 'articles') : null;

        try {
            DB::transaction(function () use ($data, $article, $newSampul) {
                $article->update([
                    ...$this->fields($data, $article),
                    // URL hanya berubah kalau judul diganti, supaya link yang sudah dibagikan tetap jalan
                    'slug'   => $article->judul === $data['judul'] ? $article->slug : $this->uniqueSlug($data['judul'], $article->id),
                    'sampul' => $newSampul ?? (!empty($data['hapus_sampul']) ? null : $article->sampul),
                ]);
                $this->syncProducts($article, $data['products'] ?? []);
            });
        } catch (\Throwable $e) {
            $newSampul && $this->imageOptimizer->delete($newSampul);
            throw $e;
        }

        // File lama dihapus setelah tersimpan: sampul yang diganti/dihapus + gambar yang dibuang dari isi
        if ($oldSampul && $oldSampul !== $article->sampul) {
            $this->imageOptimizer->delete($oldSampul);
        }
        $this->imageOptimizer->delete(...array_diff($oldImages, $this->contentImages($article->isi)));

        return $article;
    }

    public function delete(Article $article): void
    {
        $files = array_filter([$article->sampul, ...$this->contentImages($article->isi)]);
        $article->delete();
        $this->imageOptimizer->delete(...$files);
    }

    /**
     * Gambar yang di-upload dari CKEditor. Dikembalikan sebagai path relatif (/storage/...)
     * supaya isi artikel tetap benar walaupun domain website berganti.
     */
    public function storeContentImage(UploadedFile $file): string
    {
        $path = $this->imageOptimizer->store($file->getRealPath(), self::CONTENT_DIR);

        return parse_url(asset('storage/' . $path), PHP_URL_PATH);
    }

    /**
     * Hanya tag/atribut aman yang lolos: tidak ada <script>, onclick, javascript:, dll.
     */
    public function sanitize(string $html): string
    {
        $config = (new HtmlSanitizerConfig())
            ->allowSafeElements()
            ->allowLinkSchemes(['http', 'https', 'mailto', 'tel'])
            ->allowMediaSchemes(['http', 'https'])
            ->allowRelativeLinks()
            ->allowRelativeMedias()
            ->allowAttribute('class', ['figure', 'img', 'table'])
            ->forceAttribute('a', 'rel', 'noopener noreferrer')
            ->withMaxInputLength(500_000);

        return (new HtmlSanitizer($config))->sanitize($html);
    }

    private function fields(array $data, ?Article $article): array
    {
        $publish = (bool) ($data['is_published'] ?? false);

        return [
            'judul'        => $data['judul'],
            'ringkasan'    => $data['ringkasan'] ?? null,
            'isi'          => $this->sanitize($data['isi']),
            'is_published' => $publish,
            // Tanggal terbit diisi saat pertama kali diterbitkan, tidak berubah saat artikel diedit lagi
            'published_at' => $publish ? ($article?->published_at ?? now()) : $article?->published_at,
        ];
    }

    private function syncProducts(Article $article, array $productIds): void
    {
        $article->products()->sync(
            collect(array_values(array_unique($productIds)))->mapWithKeys(fn ($id, $i) => [$id => ['urutan' => $i]])->all()
        );
    }

    /**
     * Path file gambar (articles/content/xxx.webp) yang dipakai di dalam isi artikel.
     */
    private function contentImages(?string $html): array
    {
        preg_match_all('#/storage/(' . preg_quote(self::CONTENT_DIR, '#') . '/[A-Za-z0-9]+\.webp)#', (string) $html, $matches);

        return array_values(array_unique($matches[1]));
    }

    /**
     * Slug dari judul; kalau sudah dipakai diberi nomor: tips-merawat-tatami, tips-merawat-tatami-2.
     */
    private function uniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $base = Str::slug($judul) ?: 'artikel';
        $slug = $base;

        for ($i = 2; Article::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }
}
