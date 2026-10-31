@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <x-admin.page-header title="Dashboard" />

    @if (!$orders)
        {{-- Editor: tidak punya akses pesanan --}}
        <x-admin.card>
            <h2 class="h5 fw-bold">Selamat datang, {{ auth()->user()->name }}</h2>
            <p class="text-muted mb-3">Gunakan menu di samping untuk mengelola produk.</p>
            @can('view_product')
                <a href="{{ route('product.index') }}" class="btn btn-tatami"><i class="fas fa-box me-1"></i> Kelola Produk</a>
            @endcan
        </x-admin.card>
    @else
        @php
            $metrics = [
                ['label' => 'Total Pendapatan', 'icon' => 'fa-wallet',       'color' => 'text-primary', 'value' => 'Rp' . number_format($revenue, 0, ',', '.'), 'note' => 'Pesanan dikirim & selesai, sampai hari ini'],
                ['label' => 'Pesanan Baru',     'icon' => 'fa-box-open',     'color' => 'text-warning', 'value' => number_format($newOrders, 0, ',', '.'),      'note' => 'Menunggu dikirim'],
                ['label' => 'Pengguna Aktif',   'icon' => 'fa-user-friends', 'color' => 'text-success', 'value' => number_format($activeCustomers, 0, ',', '.'), 'note' => 'Pelanggan berstatus aktif'],
            ];
        @endphp

        <div class="row g-3 mb-4">
            @foreach ($metrics as $metric)
                <div class="col-12 col-md-4">
                    <x-admin.card class="h-100 mb-0">
                        <h6 class="text-muted"><i class="fas {{ $metric['icon'] }} {{ $metric['color'] }} me-2"></i> {{ $metric['label'] }}</h6>
                        <h3 class="fw-bold mb-0">{{ $metric['value'] }}</h3>
                        <small class="text-muted">{{ $metric['note'] }}</small>
                    </x-admin.card>
                </div>
            @endforeach
        </div>

        <x-admin.card title="Semua Transaksi" flush>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-semibold text-nowrap">{{ $order->order_number }}</td>
                                <td>
                                    {{ $order->user->name }}
                                    @if ($order->recipient_name !== $order->user->name)
                                        <div class="small text-muted">Penerima: {{ $order->recipient_name }}</div>
                                    @endif
                                </td>
                                <td class="text-nowrap">{{ $order->created_at->translatedFormat('d M Y H:i') }}</td>
                                <td class="text-end text-nowrap">@rupiah($order->total)</td>
                                <td class="text-center"><span class="badge text-bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#order-{{ $order->id }}">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-slot:footer>
                <x-admin.pagination :paginator="$orders" label="transaksi" />
            </x-slot:footer>
        </x-admin.card>

        @foreach ($orders as $order)
            @include('backend.partials.orderModal', ['order' => $order])
        @endforeach
    @endif
@endsection

@if ($orders && session('open_order'))
    @push('scripts')
    <script>
        // Buka lagi popup pesanan yang barusan gagal diproses, supaya pesan errornya terlihat
        (function () {
            var modal = document.getElementById('order-{{ (int) session('open_order') }}');
            if (modal) {
                bootstrap.Modal.getOrCreateInstance(modal).show();
            }
        })();
    </script>
    @endpush
@endif
