@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <x-admin.page-header title="Edit Produk" :back="route('product.index')" />

    <form action="{{ route('product.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('backend.partials.productForm', ['product' => $product])

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
        </div>
    </form>
@endsection
