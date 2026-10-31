{{-- Email checkout: $forAdmin true = notifikasi ke toko, false = konfirmasi ke customer. Inline style karena aplikasi email tidak membaca file CSS. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 24px 12px; background-color: #f4f6f5; font-family: Arial, Helvetica, sans-serif; color: #212529;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="background-color: #245237; padding: 20px 24px; color: #ffffff; font-size: 20px; font-weight: bold;">
                Tatami Indonesia
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                @if ($forAdmin)
                    <p style="margin: 0 0 16px;">Ada pesanan baru yang perlu dibalas:</p>
                @else
                    <p style="margin: 0 0 16px;">Halo {{ $order->user->name }},</p>
                    <p style="margin: 0 0 16px;">Terima kasih, pesanan Anda sudah kami terima. <strong>Admin akan membalas melalui email</strong> untuk informasi ongkos kirim dan pembayaran.</p>
                @endif

                <p style="margin: 0 0 4px; font-size: 13px; color: #6c757d;">Nomor pesanan</p>
                <p style="margin: 0 0 16px; font-size: 18px; font-weight: bold; color: #245237;">{{ $order->order_number }}</p>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 14px; margin-bottom: 16px;">
                    @foreach ($order->items as $item)
                        <tr>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                                {{ $item->product_name }}<br>
                                <span style="color: #6c757d; font-size: 12px;">@if ($item->variant_name){{ $item->variant_name }} &middot; @endif{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                            </td>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef; text-align: right; white-space: nowrap;">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="padding: 12px 0 0; font-weight: bold;">Total (belum termasuk ongkir)</td>
                        <td style="padding: 12px 0 0; font-weight: bold; text-align: right; white-space: nowrap;">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <div style="background-color: #f8f9fa; border-radius: 8px; padding: 12px 16px; font-size: 14px;">
                    <strong>Dikirim ke</strong><br>
                    {{ $order->recipient_name }} ({{ $order->phone }})<br>
                    {!! nl2br(e($order->address)) !!}
                    @if ($order->note)
                        <br><br><strong>Catatan:</strong> {{ $order->note }}
                    @endif
                    @if ($forAdmin)
                        <br><br><strong>Email customer:</strong> {{ $order->user->email }}
                    @endif
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
