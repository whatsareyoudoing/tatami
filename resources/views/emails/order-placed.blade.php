{{-- Email checkout: $forAdmin true = notifikasi ke toko, false = konfirmasi ke customer --}}
@extends('emails.layouts.order')

@section('intro')
    @if ($forAdmin)
        <p style="margin: 0 0 16px;">Ada pesanan baru yang perlu dibalas:</p>
    @else
        <p style="margin: 0 0 16px;">Halo {{ $order->user->name }},</p>
        <p style="margin: 0 0 16px;">Terima kasih, pesanan Anda sudah kami terima. <strong>Admin akan membalas melalui email</strong> untuk informasi ongkos kirim dan pembayaran.</p>
    @endif
@endsection
