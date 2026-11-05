{{-- Hanya tampil kalau belum ada artikel yang terbit; selain itu /artikel langsung membuka artikel terbaru --}}
@extends('layouts.frontend')

@section('title', 'Artikel')

@section('content')
    <x-page-hero title="Artikel" :crumbs="['Artikel' => null]" />

    <div class="container text-center text-muted py-5 my-4">
        <i class="fas fa-newspaper fs-1 mb-3 d-block"></i>
        Belum ada artikel.
    </div>
@endsection
