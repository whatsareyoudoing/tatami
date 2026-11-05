@extends('layouts.admin')

@section('title', 'Tambah Artikel')

@section('content')
    <x-admin.page-header title="Tambah Artikel" :back="route('article.index')" />

    <form action="{{ route('article.store') }}" method="POST" enctype="multipart/form-data" id="article-form">
        @csrf
        @include('backend.partials.articleForm')

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan</button>
        </div>
    </form>
@endsection
