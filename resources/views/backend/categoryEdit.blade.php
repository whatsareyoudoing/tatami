@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <x-admin.page-header title="Edit Kategori" :back="route('category.index')" />

    <form action="{{ route('category.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <x-admin.card title="Data Kategori">
            <x-admin.input name="name" label="Nama Kategori" :value="$category->name" :extraErrors="['slug']" wrapper="col-md-6 mb-3" />

            @if ($category->thumb_url)
                <img src="{{ $category->thumb_url }}" alt="" class="thumb thumb-lg rounded border d-block mb-2">
            @endif
            <x-admin.input type="file" name="image" :label="$category->image ? 'Ganti Gambar' : 'Gambar'" accept="image/jpeg,image/png,image/webp" wrapper="col-md-6"
                           :optional="(bool) $category->image" hint="Kosongkan kalau tidak ingin mengganti gambar. Format JPG, PNG, atau WEBP, maksimal 2 MB." />
        </x-admin.card>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
        </div>
    </form>
@endsection
