@extends('layouts.frontend')

@section('title', $article->judul)

@push('styles')
    <meta name="description" content="{{ $article->ringkasan ?: Str::limit(trim(html_entity_decode(strip_tags($article->isi))), 155) }}">
    {{-- /artikel menampilkan artikel terbaru juga; mesin pencari diarahkan ke alamat aslinya --}}
    <link rel="canonical" href="{{ route('blog.show', $article->slug) }}">
@endpush

@section('content')
<x-page-hero :title="$article->judul" :crumbs="['Artikel' => route('blog.index'), $article->judul => null]" :image="$article->sampul_url" />

<div class="container py-4 mb-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <article class="article-main">
                @if ($article->sampul_url)
                    <img src="{{ $article->sampul_url }}" alt="{{ $article->judul }}" class="article-main-img">
                @endif

                <div class="article-main-body">
                    <div class="article-meta">
                        @if ($article->author)
                            <span>oleh <span class="fw-semibold notranslate">{{ $article->author->name }}</span></span>
                            <span class="article-meta-dot"></span>
                        @endif
                        <span>{{ $article->published_at->translatedFormat('d F Y') }}</span>
                    </div>

                    {{-- Isi sudah dibersihkan saat disimpan (ArticleService::sanitize) --}}
                    <div class="article-content">
                        {!! $article->isi !!}
                    </div>

                    @if ($products->isNotEmpty())
                        <div class="article-products">
                            <h2 class="article-side-title">Produk di Artikel Ini</h2>
                            <div class="row">
                                @foreach ($products as $item)
                                    <div class="col-6 col-md-4 py-2">
                                        <x-product-card :product="$item" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </article>
        </div>

        <aside class="col-lg-4">
            <div class="article-sidebar">
                <h2 class="article-side-title">Artikel Terbaru</h2>
                {{-- Semua artikel terbit, terbaru di atas. Link membawa nomor halaman supaya daftar tidak lompat ke halaman 1. --}}
                @foreach ($latest as $item)
                    @php $isCurrent = $item->is($article); @endphp
                    <a href="{{ route('blog.show', array_filter([$item->slug, 'page' => $latest->currentPage() > 1 ? $latest->currentPage() : null])) }}"
                       @class(['article-latest-item', 'active' => $isCurrent]) @if ($isCurrent) aria-current="page" @endif>
                        @if ($item->sampul_thumb_url)
                            <img src="{{ $item->sampul_thumb_url }}" alt="" class="article-latest-thumb" loading="lazy">
                        @else
                            <div class="article-latest-thumb d-flex align-items-center justify-content-center color-tatami">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        @endif
                        <div>
                            <div class="article-latest-title">{{ $item->judul }}</div>
                            <div class="article-latest-date">{{ $item->published_at->translatedFormat('d F Y') }}</div>
                        </div>
                    </a>
                @endforeach

                @if ($latest->hasPages())
                    <div class="pt-3">
                        {{ $latest->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection
