{{--
    Judul halaman di bawah navbar: breadcrumb + judul di atas latar hijau bermotif anyaman igusa.
    <x-page-hero title="Keranjang Belanja" :crumbs="['Keranjang Belanja' => null]" />
    <x-page-hero :title="$article->judul" :crumbs="['Artikel' => route('blog.index'), $article->judul => null]" :image="$article->sampul_url" />

    crumbs = [label => url], "Beranda" otomatis paling depan; url null = halaman yang sedang dibuka.
    image  = foto samar di belakang lapisan hijau (opsional)
    tag    = tag judul, default h1 (pakai div kalau halaman sudah punya h1 sendiri)
--}}
@props([
    'title',
    'crumbs' => [],
    'image' => null,
    'tag' => 'h1',
])

<div class="page-hero" @if ($image) style="--hero-img: url('{{ $image }}')" @endif>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                @foreach ($crumbs as $label => $url)
                    @if ($url)
                        <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($label, 50) }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
        <{{ $tag }} {{ $attributes->class('page-hero-title') }}>{{ $title }}</{{ $tag }}>
    </div>
</div>
