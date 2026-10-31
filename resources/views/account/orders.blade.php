@extends('account.layout')

@section('account')
    <h1 class="h3 fw-bold mb-4">Pesanan Saya</h1>

    @forelse ($orders as $order)
        <a href="{{ route('account.orders.show', $order) }}" class="text-decoration-none text-reset">
            <div class="bg-white rounded-3 border p-3 mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <div class="fw-semibold">{{ $order->order_number }}</div>
                    <div class="small text-muted">{{ $order->created_at->translatedFormat('d M Y H:i') }} &middot; {{ $order->items_count }} barang</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold">@rupiah($order->total)</div>
                    <span class="badge text-bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </div>
            </div>
        </a>
    @empty
        <div class="bg-white rounded-3 border text-center text-muted py-5">
            <i class="fas fa-shopping-bag fs-2 mb-2 d-block"></i>
            Belum ada pesanan.
            <div class="mt-3"><a href="{{ route('shop.index') }}" class="btn btn-tatami btn-sm">Mulai Belanja</a></div>
        </div>
    @endforelse

    @if ($orders->hasPages())
        <div class="d-flex justify-content-center">{{ $orders->links('pagination::bootstrap-4') }}</div>
    @endif
@endsection
