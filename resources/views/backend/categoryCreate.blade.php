@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
    <x-admin.page-header title="Tambah Kategori" :back="route('category.index')" />

    <form action="{{ route('category.store') }}" method="POST">
        @csrf

        <x-admin.card title="Data Kategori">
            <x-admin.input name="name" label="Nama Kategori" placeholder="Tatami Mat" :extraErrors="['slug']" wrapper="col-md-6" />
        </x-admin.card>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan</button>
        </div>
    </form>
@endsection
