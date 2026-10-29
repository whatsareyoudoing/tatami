@extends('layouts.admin')

@section('title', 'Pengguna')

@section('content')
    <x-admin.page-header title="Daftar Pengguna">
        @can('create_user')
            <a href="{{ route('user.create') }}" class="btn btn-tatami"><i class="fas fa-plus me-1"></i> Tambah Pengguna</a>
        @endcan
    </x-admin.page-header>

    <x-admin.card flush>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td class="text-center text-muted">{{ $users->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span @class([
                                        'badge',
                                        'text-bg-success' => $role->name === 'admin',
                                        'text-bg-primary' => $role->name === 'editor',
                                        'text-bg-secondary' => !in_array($role->name, ['admin', 'editor']),
                                    ])>{{ $role->label }}</span>
                                @endforeach
                            </td>
                            <td class="text-center text-nowrap">
                                @can('update_user')
                                    <a href="{{ route('user.edit', $user) }}" class="btn btn-sm btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                @endcan
                                @can('delete_user')
                                    <x-admin.delete-button :action="route('user.destroy', $user)" confirm="Yakin ingin menonaktifkan pengguna ini?" title="Nonaktifkan" />
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot:footer>
            <x-admin.pagination :paginator="$users" label="pengguna" />
        </x-slot:footer>
    </x-admin.card>
@endsection
