@extends('layouts.frontend')

@section('title', 'Checkout')

@section('content')
<x-page-hero title="Checkout" :crumbs="['Keranjang' => route('cart.index'), 'Checkout' => null]" />

<div class="container py-4 mb-4">

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="bg-white rounded-3 border p-3 p-md-4">
                    <h2 class="h6 fw-bold mb-3">Alamat Pengiriman</h2>

                    <x-admin.input name="recipient_name" label="Nama Penerima" :value="$defaults['recipient_name']" required autocomplete="name" />
                    <x-admin.input name="phone" type="tel" label="Nomor HP / WhatsApp" :value="$defaults['phone']" required autocomplete="tel" />
                    <x-admin.input type="textarea" name="address" label="Alamat Lengkap" :value="$defaults['address']" rows="3" required
                                   hint="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, kode pos." />
                    <x-admin.input type="textarea" name="note" label="Catatan" optional rows="2" wrapper="mb-0"
                                   hint="Misalnya: kirim setelah jam 5 sore." />
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-white rounded-3 border p-3 p-md-4">
                    <h2 class="h6 fw-bold mb-3">Pesanan Anda</h2>

                    @foreach ($items as $item)
                        <div class="d-flex justify-content-between gap-2 small mb-2">
                            <div>
                                {{ $item->variant->product->nama }}
                                <div class="text-muted">@if ($item->variant->optionLabel()){{ $item->variant->optionLabel() }} &middot; @endif{{ $item->quantity }} x @rupiah($item->variant->harga)</div>
                            </div>
                            <div class="text-nowrap">@rupiah($item->subtotal())</div>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-2">
                        <span>Total</span>
                        <span>@rupiah($total)</span>
                    </div>
                    <p class="small text-muted">Belum termasuk ongkos kirim. Setelah pesanan dibuat, admin akan membalas melalui email untuk ongkir dan cara pembayaran.</p>

                    <button type="submit" class="btn btn-tatami w-100 py-2"><i class="fas fa-check me-1"></i> Buat Pesanan</button>
                    <a href="{{ route('cart.index') }}" class="btn btn-link color-tatami w-100 mt-1">Kembali ke keranjang</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
