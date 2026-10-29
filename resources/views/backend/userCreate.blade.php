@extends('layouts.admin')

@section('title', 'Tambah Pengguna')

@section('content')
    <x-admin.page-header title="Tambah Pengguna" :back="route('user.index')" />

    <form action="{{ route('user.store') }}" method="POST">
        @csrf

        <x-admin.card title="Data Pengguna">
            <div class="row g-3">
                <x-admin.input name="name" label="Nama Lengkap" wrapper="col-md-6" />
                <x-admin.input name="username" label="Username" wrapper="col-md-6" />
                <x-admin.input name="email" type="email" label="Email" placeholder="email@example.com" wrapper="col-md-6" />
                <x-admin.select name="role" label="Role" :options="$roles->pluck('name', 'id')" placeholder="Pilih role" wrapper="col-md-6" />
                <x-admin.input name="password" type="password" label="Password" hint="Minimal 8 karakter." wrapper="col-md-6" />
            </div>
        </x-admin.card>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan</button>
        </div>
    </form>
@endsection
