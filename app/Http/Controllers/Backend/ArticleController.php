<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Product;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view_article', only: ['index']),
            new Middleware('can:create_article', only: ['create', 'store']),
            new Middleware('can:update_article', only: ['edit', 'update']),
            new Middleware('can:delete_article', only: ['destroy']),
        ];
    }

    public function __construct(
        protected ArticleService $articles
    ) {}

    public function index()
    {
        $articles = Article::with('author:id,name')->withCount('products')->latest()->paginate(10);

        return view('backend.article', compact('articles'));
    }

    public function create()
    {
        return view('backend.articleCreate', ['products' => $this->productOptions()]);
    }

    public function store(ArticleRequest $request)
    {
        $this->articles->create($request->validated(), $request->user());

        return redirect()->route('article.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function edit(Article $article)
    {
        $article->load('products:id');

        return view('backend.articleEdit', ['article' => $article, 'products' => $this->productOptions()]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $this->articles->update($request->validated(), $article);

        return redirect()->route('article.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Article $article)
    {
        $this->articles->delete($article);

        return redirect()->route('article.index')->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Upload gambar dari tombol gambar di CKEditor (format respons sesuai SimpleUploadAdapter).
     */
    public function uploadImage(Request $request)
    {
        // Dipakai di form tambah maupun edit
        abort_unless($request->user()->can('create_article') || $request->user()->can('update_article'), 403);

        $validator = Validator::make($request->all(), [
            'upload' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => ['message' => 'Gambar harus JPG, PNG, atau WEBP, maksimal 2 MB.']], 422);
        }

        try {
            $url = $this->articles->storeContentImage($request->file('upload'));
        } catch (\RuntimeException) {
            return response()->json(['error' => ['message' => 'File gambar tidak bisa dibaca.']], 422);
        }

        return response()->json(['url' => $url]);
    }

    // Pilihan produk di form: semua produk (termasuk yang nonaktif, ditandai di form)
    private function productOptions()
    {
        return Product::with('cover')->orderBy('nama')->get(['id', 'nama', 'is_active']);
    }
}
