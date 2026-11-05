<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Halaman toko di website: beranda (sorotan), daftar produk, detail produk.
 */
class ShopController extends Controller
{
    public const PER_PAGE = 12;

    public const SORTS = [
        'terbaru'  => 'Terbaru',
        'termurah' => 'Harga termurah',
        'termahal' => 'Harga termahal',
    ];

    public function home()
    {
        $sorotan = Product::active()->forCard()->latest()->take(8)->get();
        // Kotak "Belanja Sesuai Kebutuhanmu", urut sesuai urutan dibuat di admin
        $kategori = Category::oldest('id')->get(['id', 'name', 'slug', 'image']);
        // Bagian "HIGHLIGHTS" = 4 artikel terbit terbaru
        $artikel = Article::published()->latest('published_at')->take(4)->get(['id', 'judul', 'slug', 'sampul', 'published_at']);

        return view('frontend.welcome', compact('sorotan', 'kategori', 'artikel'));
    }

    public function index(Request $request)
    {
        // Tombol filter hanya untuk kategori yang punya produk aktif
        $categories = Category::whereHas('products', fn ($q) => $q->active())
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $slug = $request->query('kategori');
        $category = $slug ? Category::where('slug', $slug)->first(['id', 'name', 'slug']) : null;
        // Kategori di URL tidak ada di database -> tampilkan kosong, bukan semua produk
        $categoryLabel = $category?->name ?? ($slug ? Str::headline($slug) : null);

        $sort = array_key_exists($request->query('urut'), self::SORTS) ? $request->query('urut') : 'terbaru';

        $products = Product::active()->forCard()
            ->when($slug, fn ($q) => $q->where('category_id', $category?->id ?? 0))
            // harga_min = harga varian aktif termurah (dari scope forCard)
            ->when($sort === 'termurah', fn ($q) => $q->orderBy('harga_min'))
            ->when($sort === 'termahal', fn ($q) => $q->orderByDesc('harga_min'))
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('frontend.produk', [
            'products'      => $products,
            'categories'    => $categories,
            'activeSlug'    => $slug,
            'categoryLabel' => $categoryLabel,
            'sort'          => $sort,
            'sorts'         => self::SORTS,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category:id,name,slug', 'images', 'activeVariants.images'])
            ->firstOrFail();

        $related = Product::active()->forCard()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.produk-detail', compact('product', 'related'));
    }

    /**
     * Tujuan tombol "Masuk untuk membeli" (route ini wajib login).
     * Tamu diarahkan ke login dulu, lalu kembali ke produk ini setelah berhasil masuk.
     */
    public function buy(string $slug)
    {
        return redirect()->to(route('shop.show', $slug) . '#beli');
    }
}
