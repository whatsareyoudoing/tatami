@extends('layouts.admin')

@section('title', 'Pelanggan')

@section('content')
    <x-admin.page-header title="Daftar Pelanggan">
        <form action="{{ route('customer.index') }}" method="GET" class="d-flex gap-2" role="search">
            <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Cari nama atau email" aria-label="Cari pelanggan">
            <button type="submit" class="btn btn-outline-secondary" title="Cari"><i class="fas fa-search"></i></button>
        </form>
    </x-admin.page-header>

    <p class="text-muted small">Pelanggan mendaftar sendiri lewat website. Di sini admin hanya bisa melihat dan memblokir akun.</p>

    <x-admin.card flush>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Terdaftar</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $index => $customer)
                        <tr>
                            <td class="text-center text-muted">{{ $customers->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $customer->name }}</td>
                            <td>
                                {{ $customer->email }}
                                @if ($customer->email_verified_at)
                                    <i class="fas fa-check-circle text-success ms-1" title="Email terverifikasi"></i>
                                @else
                                    <div class="small text-warning-emphasis"><i class="fas fa-clock me-1"></i>Belum verifikasi email</div>
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $customer->created_at->translatedFormat('d M Y') }}</td>
                            <td class="text-center">
                                <span @class(['badge', 'text-bg-success' => $customer->status === 'aktif', 'text-bg-danger' => $customer->status !== 'aktif'])>
                                    {{ $customer->status === 'aktif' ? 'Aktif' : 'Diblokir' }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                @can('update_customer')
                                    @if ($customer->status === 'aktif')
                                        <form action="{{ route('customer.block', $customer) }}" method="POST" class="d-inline"
                                              data-confirm="Blokir {{ $customer->name }}? Pelanggan ini tidak akan bisa login sampai blokirnya dibuka.">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-ban me-1"></i> Blokir</button>
                                        </form>
                                    @else
                                        <form action="{{ route('customer.unblock', $customer) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success"><i class="fas fa-unlock me-1"></i> Buka blokir</button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                {{ $search !== '' ? "Tidak ada pelanggan yang cocok dengan \"{$search}\"." : 'Belum ada pelanggan.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot:footer>
            <x-admin.pagination :paginator="$customers" label="pelanggan" />
        </x-slot:footer>
    </x-admin.card>
@endsection
