<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // Tampilkan semua kategori, urutkan dari yang terbaru
        $categories = Category::select('id','name')->paginate(10);
        return view('backend.category', compact('categories'));
    }

    public function create(){
        return view('backend.categoryCreate');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('category')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id){
        $category=Category::select('id','name')->where('id',$id)->first();

        return view('backend.categoryEdit',compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // 1. Validasi: Abaikan ID kategori ini saat cek nama unique
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // 2. Gunakan method update(), bukan create()
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // 3. Redirect ke route yang benar (sesuaikan nama route di sistemmu)
        return redirect()->route('category')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        // Karena di tabel products kita pakai restrictOnDelete(),
        // pastikan kita handle error jika kategori masih dipakai produk
        try {
            $category->delete();
            return redirect()->route('category')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('category')->with('error', 'Kategori tidak bisa dihapus karena masih ada produk di dalamnya!');
        }
    }
}
