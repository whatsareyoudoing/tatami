@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <x-admin.page-header title="Edit Kategori" :back="route('category.index')" />

    <form action="{{ route('category.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <x-admin.card title="Data Kategori">
            <x-admin.input name="name" label="Nama Kategori" :value="$category->name" :extraErrors="['slug']" wrapper="col-md-6" />
        </x-admin.card>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
        </div>
    </form>
@endsection
