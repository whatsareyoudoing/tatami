@extends('layouts.admin')

@section('title', 'Artikel')

@section('content')
    <x-admin.page-header title="Daftar Artikel">
        @can('create_article')
            <a href="{{ route('article.create') }}" class="btn btn-tatami"><i class="fas fa-plus me-1"></i> Tambah Artikel</a>
        @endcan
    </x-admin.page-header>

    <x-admin.card flush>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Sampul</th>
                        <th>Judul</th>
                        <th class="text-center">Produk</th>
                        <th class="text-center">Status</th>
                        <th>Tanggal Terbit</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($articles as $index => $article)
                        <tr>
                            <td class="text-center text-muted">{{ $articles->firstItem() + $index }}</td>
                            <td>
                                @if ($article->sampul_thumb_url)
                                    <img src="{{ $article->sampul_thumb_url }}" alt="" class="thumb rounded border" loading="lazy">
                                @else
                                    <div class="thumb rounded border bg-light d-flex align-items-center justify-content-center text-muted"><i class="fas fa-image"></i></div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $article->judul }}</div>
                                <div class="small text-muted">Oleh {{ $article->author?->name ?? '–' }}</div>
                            </td>
                            <td class="text-center">{{ $article->products_count ?: '–' }}</td>
                            <td class="text-center">
                                <span @class(['badge', 'text-bg-success' => $article->is_published, 'text-bg-secondary' => !$article->is_published])>
                                    {{ $article->is_published ? 'Terbit' : 'Draft' }}
                                </span>
                            </td>
                            <td class="text-nowrap small">{{ $article->published_at?->translatedFormat('d M Y') ?? '–' }}</td>
                            <td class="text-center text-nowrap">
                                @if ($article->is_published)
                                    <a href="{{ route('blog.show', $article->slug) }}" class="btn btn-sm btn-icon" title="Lihat di website" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i></a>
                                @endif
                                @can('update_article')
                                    <a href="{{ route('article.edit', $article) }}" class="btn btn-sm btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                @endcan
                                @can('delete_article')
                                    <x-admin.delete-button :action="route('article.destroy', $article)" confirm="Yakin ingin menghapus artikel ini? Gambar di dalamnya ikut terhapus." />
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot:footer>
            <x-admin.pagination :paginator="$articles" label="artikel" />
        </x-slot:footer>
    </x-admin.card>
@endsection
