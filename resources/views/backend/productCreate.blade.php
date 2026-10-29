@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
    <x-admin.page-header title="Tambah Produk" :back="route('product.index')" />

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('backend.partials.productForm')

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan</button>
        </div>
    </form>
@endsection
