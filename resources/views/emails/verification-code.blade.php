{{-- Email memakai inline style karena banyak aplikasi email tidak membaca file CSS --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Email</title>
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
                <p style="margin: 0 0 16px;">Gunakan kode berikut untuk memverifikasi email Anda:</p>

                <div style="margin: 0 0 16px; padding: 16px; background-color: #e8f1ec; border-radius: 8px; text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #245237;">
                    {{ $code }}
                </div>

                <p style="margin: 0 0 16px;">Kode ini berlaku selama <strong>{{ $expiresMinutes }} menit</strong>. Jangan berikan kode ini kepada siapa pun, termasuk yang mengaku dari Tatami Indonesia.</p>
                <p style="margin: 0; color: #6c757d; font-size: 13px;">Jika Anda tidak merasa mendaftar di Tatami Indonesia, abaikan email ini.</p>
            </td>
        </tr>
    </table>
</body>
</html>
