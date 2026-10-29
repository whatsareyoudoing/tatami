@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')
    <x-admin.page-header title="Daftar Kategori">
        @can('create_category')
            <a href="{{ route('category.create') }}" class="btn btn-tatami"><i class="fas fa-plus me-1"></i> Tambah Kategori</a>
        @endcan
    </x-admin.page-header>

    <x-admin.card flush>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Nama</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td class="text-center text-muted">{{ $categories->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="text-center text-nowrap">
                                @can('update_category')
                                    <a href="{{ route('category.edit', $category) }}" class="btn btn-sm btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                @endcan
                                @can('delete_category')
                                    <x-admin.delete-button :action="route('category.destroy', $category)" confirm="Yakin ingin menghapus kategori ini?" />
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot:footer>
            <x-admin.pagination :paginator="$categories" label="kategori" />
        </x-slot:footer>
    </x-admin.card>
@endsection
