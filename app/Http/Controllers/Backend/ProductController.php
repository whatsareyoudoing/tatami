<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view_product', only: ['index']),
            new Middleware('can:create_product', only: ['create', 'store']),
            new Middleware('can:update_product', only: ['edit', 'update']),
            new Middleware('can:delete_product', only: ['destroy']),
        ];
    }

    public function __construct(
        protected ProductService $productService
    ) {}

    public function index()
    {
        // Harga/stok/SKU ada di varian; foto cukup foto utama
        $products = Product::with(['category:id,name', 'cover', 'variants:id,product_id,nama,harga,sku,stok,urutan'])
            ->latest()
            ->paginate(10);

        return view('backend.product', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('backend.productCreate', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $this->productService->createProduct($request->validated());

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $product->load(['variants.images', 'images']);

        return view('backend.productEdit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->productService->updateProduct($request->validated(), $product);

        return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus!');
    }
}
