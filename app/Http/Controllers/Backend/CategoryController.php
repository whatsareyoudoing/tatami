<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\ImageOptimizer;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view_category', only: ['index']),
            new Middleware('can:create_category', only: ['create', 'store']),
            new Middleware('can:update_category', only: ['edit', 'update']),
            new Middleware('can:delete_category', only: ['destroy']),
        ];
    }

    public function __construct(
        protected ImageOptimizer $imageOptimizer
    ) {}

    public function index()
    {
        // Tampilkan semua kategori, urutkan dari yang terbaru
        $categories = Category::select('id', 'name', 'image')->latest()->paginate(10);
        return view('backend.category', compact('categories'));
    }

    public function create()
    {
        return view('backend.categoryCreate');
    }

    public function store(CategoryRequest $request)
    {
        $image = $this->imageOptimizer->store($request->file('image')->getRealPath(), 'categories');
        Category::create([...$request->safe()->only(['name', 'slug']), 'image' => $image]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('backend.categoryEdit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $oldImage = $category->image;
        $data = $request->safe()->only(['name', 'slug']);
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageOptimizer->store($request->file('image')->getRealPath(), 'categories');
        }

        $category->update($data);

        // Gambar lama dihapus setelah gambar baru tersimpan
        if (isset($data['image']) && $oldImage) {
            $this->imageOptimizer->delete($oldImage);
        }

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        // Tabel products memakai restrictOnDelete(), jadi cek dulu sebelum menghapus.
        // withTrashed: produk yang sudah di-soft delete tetap mengunci kategori di DB.
        if ($category->products()->withTrashed()->exists()) {
            return redirect()->route('category.index')->with('error', 'Kategori tidak bisa dihapus karena masih ada produk di dalamnya!');
        }

        $category->delete();
        if ($category->image) {
            $this->imageOptimizer->delete($category->image);
        }

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
