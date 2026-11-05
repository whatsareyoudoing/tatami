@extends('layouts.admin')

@section('title', 'Edit Artikel')

@section('content')
    <x-admin.page-header title="Edit Artikel" :back="route('article.index')" />

    <form action="{{ route('article.update', $article) }}" method="POST" enctype="multipart/form-data" id="article-form">
        @csrf
        @method('PUT')
        @include('backend.partials.articleForm')

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
        </div>
    </form>
@endsection
