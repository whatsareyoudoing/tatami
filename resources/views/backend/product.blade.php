@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
    <x-admin.page-header title="Daftar Produk">
        @can('create_product')
            <a href="{{ route('product.create') }}" class="btn btn-tatami"><i class="fas fa-plus me-1"></i> Tambah Produk</a>
        @endcan
    </x-admin.page-header>

    <x-admin.card flush>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th class="text-center">Varian</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td class="text-center text-muted">{{ $products->firstItem() + $index }}</td>
                            @php
                                $minPrice = $product->variants->min('harga');
                                $maxPrice = $product->variants->max('harga');
                            @endphp
                            <td>
                                @if ($product->cover_url)
                                    <img src="{{ $product->cover_url }}" alt="{{ $product->nama }}" class="thumb rounded border" loading="lazy">
                                @else
                                    <div class="thumb rounded border bg-light d-flex align-items-center justify-content-center text-muted"><i class="fas fa-image"></i></div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $product->nama }}</div>
                                @if (!$product->has_variants && $product->variants->first()?->sku)
                                    <div class="small text-muted">{{ $product->variants->first()->sku }}</div>
                                @endif
                                @if ($product->youtube_id)
                                    <div class="small text-danger"><i class="fab fa-youtube me-1"></i>Ada video</div>
                                @endif
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td class="text-nowrap">
                                @rupiah($minPrice)
                                @if ($maxPrice > $minPrice)
                                    – @rupiah($maxPrice)
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($product->has_variants)
                                    {{ $product->variants->count() }}
                                    <div class="small text-muted">{{ $product->variant_label }}</div>
                                @else
                                    <span class="text-muted">–</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $product->variants->sum('stok') }}</td>
                            <td class="text-center">
                                <span @class(['badge', 'text-bg-success' => $product->is_active, 'text-bg-secondary' => !$product->is_active])>
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                @can('update_product')
                                    <a href="{{ route('product.edit', $product) }}" class="btn btn-sm btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                @endcan
                                @can('delete_product')
                                    <x-admin.delete-button :action="route('product.destroy', $product)" confirm="Yakin ingin menghapus produk ini?" />
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot:footer>
            <x-admin.pagination :paginator="$products" label="produk" />
        </x-slot:footer>
    </x-admin.card>
@endsection
