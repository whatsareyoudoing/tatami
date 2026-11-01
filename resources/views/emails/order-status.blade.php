{{--
    Email perubahan status pesanan:
    - dikirim    -> ke customer, berisi kurir + no. resi
    - dibatalkan -> ke customer (dibatalkan admin) atau ke toko (dibatalkan customer, $forAdmin = true)
--}}
@extends('emails.layouts.order')

@section('intro')
    @if ($forAdmin)
        <p style="margin: 0 0 16px;">Pesanan berikut <strong>dibatalkan oleh customer</strong>. Tidak perlu diproses lagi.</p>
    @else
        <p style="margin: 0 0 16px;">Halo {{ $order->user->name }},</p>
        @if ($order->status === \App\Models\Order::DIKIRIM)
            <p style="margin: 0 0 16px;">Kabar baik, pesanan Anda <strong>sudah dikirim</strong>.</p>
        @else
            <p style="margin: 0 0 16px;">Mohon maaf, pesanan Anda <strong>dibatalkan</strong> oleh admin.</p>
            @if ($order->cancel_reason)
                <p style="margin: 0 0 16px;"><strong>Alasan:</strong> {{ $order->cancel_reason }}</p>
            @endif
            <p style="margin: 0 0 16px;">Kalau ada pertanyaan atau ingin memesan ulang, silakan balas email ini.</p>
        @endif
    @endif
@endsection

@section('highlight')
    @if ($order->status === \App\Models\Order::DIKIRIM)
        <div style="background-color: #e8f3ec; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
            @if ($order->courier)
                <p style="margin: 0 0 4px; font-size: 13px; color: #6c757d;">Kurir</p>
                <p style="margin: 0 0 12px; font-size: 16px; font-weight: bold;">{{ $order->courier }}</p>
            @endif
            <p style="margin: 0 0 4px; font-size: 13px; color: #6c757d;">No. resi</p>
            <p style="margin: 0; font-size: 18px; font-weight: bold; letter-spacing: 1px;">{{ $order->tracking_number }}</p>
        </div>
    @endif
@endsection
