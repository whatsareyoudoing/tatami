{{-- Email memakai inline style karena banyak aplikasi email tidak membaca file CSS --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password</title>
</head>
<body style="margin: 0; padding: 24px 12px; background-color: #f4f6f5; font-family: Arial, Helvetica, sans-serif; color: #212529;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 480px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="background-color: #245237; padding: 20px 24px; color: #ffffff; font-size: 20px; font-weight: bold;">
                Tatami Indonesia
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <p style="margin: 0 0 16px;">Halo {{ $user->name }},</p>
                <p style="margin: 0 0 16px;">Kami menerima permintaan untuk mengatur ulang password akun Anda. Klik tombol di bawah untuk membuat password baru:</p>

                <p style="margin: 0 0 16px; text-align: center;">
                    <a href="{{ $url }}" style="display: inline-block; padding: 12px 24px; background-color: #245237; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;">Buat Password Baru</a>
                </p>

                <p style="margin: 0 0 16px;">Link ini berlaku selama <strong>{{ $expiresMinutes }} menit</strong> dan hanya bisa dipakai sekali.</p>
                <p style="margin: 0 0 16px; color: #6c757d; font-size: 13px; word-break: break-all;">Kalau tombol tidak bisa diklik, salin link ini ke browser: {{ $url }}</p>
                <p style="margin: 0; color: #6c757d; font-size: 13px;">Jika Anda tidak meminta ini, abaikan email ini. Password Anda tidak berubah.</p>
            </td>
        </tr>
    </table>
</body>
</html>
