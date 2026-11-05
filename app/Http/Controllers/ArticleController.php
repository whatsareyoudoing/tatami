<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;

/**
 * Halaman artikel di website. Tidak ada halaman daftar: /artikel langsung membuka artikel terbaru,
 * daftar semua artikel ada di sidebar "Artikel Terbaru".
 */
class ArticleController extends Controller
{
    public const SIDEBAR_PER_PAGE = 5;

    public function index()
    {
        $article = Article::published()->latest('published_at')->latest('id')->first();

        return $article ? $this->detail($article) : view('frontend.artikel');
    }

    public function show(string $slug)
    {
        return $this->detail(Article::published()->where('slug', $slug)->firstOrFail());
    }

    private function detail(Article $article)
    {
        $article->load('author:id,name');

        // Produk yang dicantumkan, hanya yang aktif, sesuai urutan pilihan admin
        $ids = $article->products()->pluck('products.id')->all();
        $products = Product::active()->forCard()->whereKey($ids)->get()
            ->sortBy(fn (Product $product) => array_search($product->id, $ids))
            ->values();

        // Sidebar: semua artikel terbit, terbaru di atas
        $latest = Article::published()->latest('published_at')->latest('id')
            ->paginate(self::SIDEBAR_PER_PAGE, ['id', 'judul', 'slug', 'sampul', 'published_at']);

        return view('frontend.artikel-detail', compact('article', 'products', 'latest'));
    }
}
