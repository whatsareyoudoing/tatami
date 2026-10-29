@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <x-admin.page-header title="Ringkasan Hari Ini" />

    {{-- Angka di bawah masih contoh (dummy), nanti diganti data asli setelah modul pesanan dibuat --}}
    @php
        $metrics = [
            ['label' => 'Total Pendapatan', 'icon' => 'fa-wallet',       'color' => 'text-primary', 'value' => 'Rp 12.500.000', 'note' => '+5% dari kemarin',  'noteClass' => 'text-success', 'trend' => 'fa-arrow-up'],
            ['label' => 'Pesanan Baru',     'icon' => 'fa-box-open',     'color' => 'text-success', 'value' => '145',           'note' => '+12% dari kemarin', 'noteClass' => 'text-success', 'trend' => 'fa-arrow-up'],
            ['label' => 'Pengguna Aktif',   'icon' => 'fa-user-friends', 'color' => 'text-warning', 'value' => '8.230',         'note' => '-2% dari kemarin',  'noteClass' => 'text-danger',  'trend' => 'fa-arrow-down'],
            ['label' => 'Pesan Masuk',      'icon' => 'fa-comments',     'color' => 'text-danger',  'value' => '34',            'note' => 'Menunggu balasan',  'noteClass' => 'text-muted',   'trend' => null],
        ];
    @endphp

    <div class="row g-3 mb-4">
        @foreach ($metrics as $metric)
            <div class="col-12 col-sm-6 col-xl-3">
                <x-admin.card class="h-100 mb-0">
                    <h6 class="text-muted"><i class="fas {{ $metric['icon'] }} {{ $metric['color'] }} me-2"></i> {{ $metric['label'] }}</h6>
                    <h3 class="fw-bold mb-0">{{ $metric['value'] }}</h3>
                    <small class="{{ $metric['noteClass'] }}">
                        @if ($metric['trend']) <i class="fas {{ $metric['trend'] }} me-1"></i> @endif
                        {{ $metric['note'] }}
                    </small>
                </x-admin.card>
            </div>
        @endforeach
    </div>

    <x-admin.card title="Transaksi Terakhir" flush>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#INV-001</td>
                        <td>Budi Santoso</td>
                        <td>21 Okt 2026</td>
                        <td><span class="badge text-bg-success">Selesai</span></td>
                        <td>Rp 250.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-admin.card>
@endsection
