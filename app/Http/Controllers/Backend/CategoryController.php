<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
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

    public function index()
    {
        // Tampilkan semua kategori, urutkan dari yang terbaru
        $categories = Category::select('id', 'name')->latest()->paginate(10);
        return view('backend.category', compact('categories'));
    }

    public function create()
    {
        return view('backend.categoryCreate');
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('backend.categoryEdit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

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

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
