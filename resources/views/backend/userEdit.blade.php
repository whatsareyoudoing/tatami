@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
    <x-admin.page-header title="Edit Pengguna" :back="route('user.index')" />

    <form action="{{ route('user.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <x-admin.card title="Data Pengguna">
            <div class="row g-3">
                <x-admin.input name="name" label="Nama Lengkap" :value="$user->name" wrapper="col-md-6" />
                <x-admin.input name="username" label="Username" :value="$user->username" wrapper="col-md-6" />
                <x-admin.input name="email" type="email" label="Email" :value="$user->email" wrapper="col-md-6" />
                <x-admin.select name="role" label="Role" :options="$roles->pluck('label', 'id')" :value="$userRoleId" placeholder="Pilih role" wrapper="col-md-6" />
                @if ($user->is(auth()->user()))
                    <div class="col-12 mt-0">
                        <div class="form-text"><i class="fas fa-info-circle me-1"></i> Ini akun Anda sendiri, role-nya tidak bisa diubah.</div>
                    </div>
                @endif
                <x-admin.input name="password" type="password" label="Password" hint="Kosongkan jika tidak ingin diubah." wrapper="col-md-6" />
            </div>
        </x-admin.card>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
        </div>
    </form>
@endsection
