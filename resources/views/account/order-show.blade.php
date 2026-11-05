@extends('account.layout')

@php $hero = ['title' => 'Detail Pesanan', 'crumbs' => ['Akun Saya' => route('account.index'), 'Pesanan Saya' => route('account.orders'), $order->order_number => null]]; @endphp

@section('account')
    @php
        // Pesan untuk customer per status
        $statusMessage = match ($order->status) {
            \App\Models\Order::BARU       => 'Pesanan telah di-checkout, silakan tunggu balasan admin dari email Anda.',
            \App\Models\Order::DIKIRIM    => 'Pesanan Anda sedang dalam pengiriman.',
            \App\Models\Order::SELESAI    => 'Pesanan selesai. Terima kasih sudah berbelanja!',
            \App\Models\Order::DIBATALKAN => 'Pesanan ini dibatalkan.',
            default => null,
        };
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="h3 fw-bold mb-0 notranslate">{{ $order->order_number }}</h2>
        <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Pesanan Saya</a>
    </div>

    <x-admin.card>
        <div class="d-flex align-items-start gap-3">
            <span class="badge text-bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
            <div>
                <div>{{ $statusMessage }}</div>
                @if ($order->tracking_number)
                    @if ($order->courier)
                        <div class="mt-1">Kurir: <strong class="notranslate">{{ $order->courier }}</strong></div>
                    @endif
                    <div class="mt-1">No. resi: <strong class="notranslate">{{ $order->tracking_number }}</strong> <span class="text-muted small">(dikirim {{ $order->shipped_at?->translatedFormat('d M Y') }})</span></div>
                @endif
                @if ($order->cancel_reason)
                    <div class="mt-1">Alasan: {{ $order->cancel_reason }}</div>
                @endif
            </div>
        </div>
    </x-admin.card>

    <x-admin.card title="Barang">
        @foreach ($order->items as $item)
            @php $image = $item->variant?->images->first(); @endphp
            <div @class(['d-flex gap-3 align-items-center', 'border-top pt-3 mt-3' => !$loop->first])>
                @if ($image)
                    <img src="{{ $image->thumb_url }}" alt="" class="rounded border cart-thumb" loading="lazy">
                @endif
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $item->product_name }}</div>
                    <div class="small text-muted">@if ($item->variant_name){{ $item->variant_name }} &middot; @endif{{ $item->quantity }} x @rupiah($item->price)</div>
                </div>
                <div class="fw-semibold text-nowrap">@rupiah($item->subtotal)</div>
            </div>
        @endforeach

        <x-slot:footer>
            <div class="d-flex justify-content-between fw-bold">
                <span>Total <small class="text-muted fw-normal">(belum termasuk ongkir)</small></span>
                <span>@rupiah($order->total)</span>
            </div>
        </x-slot:footer>
    </x-admin.card>

    <x-admin.card title="Pengiriman">
        <div class="fw-semibold">{{ $order->recipient_name }}</div>
        <div class="small">{{ $order->phone }}</div>
        <div class="small">{!! nl2br(e($order->address)) !!}</div>
        @if ($order->note)
            <div class="small text-muted mt-2">Catatan: {{ $order->note }}</div>
        @endif
        <div class="small text-muted mt-2">Dipesan {{ $order->created_at->translatedFormat('d M Y H:i') }}</div>
    </x-admin.card>

    @if ($order->isCancellable())
        <form action="{{ route('account.orders.cancel', $order) }}" method="POST" class="text-end"
              data-confirm="Batalkan pesanan {{ $order->order_number }}?">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-times me-1"></i> Batalkan Pesanan</button>
        </form>
    @endif
@endsection
