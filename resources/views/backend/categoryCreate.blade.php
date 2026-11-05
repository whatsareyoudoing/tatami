@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
    <x-admin.page-header title="Tambah Kategori" :back="route('category.index')" />

    <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <x-admin.card title="Data Kategori">
            <x-admin.input name="name" label="Nama Kategori" :extraErrors="['slug']" wrapper="col-md-6 mb-3" />
            <x-admin.input type="file" name="image" label="Gambar" accept="image/jpeg,image/png,image/webp" wrapper="col-md-6"
                           hint="Tampil di beranda. Format JPG, PNG, atau WEBP, maksimal 2 MB. PNG transparan tetap transparan." />
        </x-admin.card>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan</button>
        </div>
    </form>
@endsection
