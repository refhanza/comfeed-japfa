<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background-color: #f4f4f4; 
            line-height: 1.6;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header { 
            text-align: center; 
            color: #6366f1; 
            font-size: 24px; 
            font-weight: bold; 
            margin-bottom: 20px; 
        }
        .otp-box { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            padding: 25px; 
            border-radius: 15px; 
            text-align: center; 
            margin: 25px 0; 
        }
        .otp-code { 
            font-size: 36px; 
            font-weight: bold; 
            letter-spacing: 8px; 
            margin: 15px 0; 
            font-family: 'Courier New', monospace; 
            background: rgba(255,255,255,0.2);
            padding: 15px;
            border-radius: 10px;
        }
        .warning { 
            background: #fef3cd; 
            border-left: 4px solid #facc15; 
            padding: 15px; 
            margin: 20px 0; 
            border-radius: 5px; 
        }
        .footer { 
            text-align: center; 
            font-size: 12px; 
            color: #666; 
            margin-top: 30px; 
            padding-top: 20px; 
            border-top: 1px solid #eee; 
        }
        .info { 
            background: #e0f2fe; 
            border-left: 4px solid #0288d1; 
            padding: 15px; 
            margin: 20px 0; 
            border-radius: 5px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">{{ config('app.name') }}</div>
        
        <h2 style="color: #333; text-align: center;">🔐 Kode OTP Reset Password</h2>
        
        <p>Halo,</p>
        <p>Kami menerima permintaan untuk reset password akun Anda yang terdaftar dengan email: <strong>{{ $email }}</strong></p>

        <div class="otp-box">
            <h3 style="margin: 0; font-size: 18px;">Kode OTP Anda:</h3>
            <div class="otp-code">{{ $otp }}</div>
            <p style="margin: 0; font-size: 14px; opacity: 0.9;">
                ⏰ Berlaku selama {{ $expires_in }} menit
            </p>
        </div>

        <p>Silakan masukkan kode OTP di atas pada halaman verifikasi untuk melanjutkan proses reset password.</p>

        <div class="warning">
            <strong>⚠️ Peringatan Keamanan:</strong>
            <ul style="margin: 10px 0 0 20px; padding: 0;">
                <li>Jangan bagikan kode OTP ini kepada siapapun</li>
                <li>Kode ini hanya berlaku selama {{ $expires_in }} menit</li>
                <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
                <li>Untuk keamanan, segera ganti password setelah berhasil reset</li>
            </ul>
        </div>

        <div class="info">
            <strong>ℹ️ Bantuan:</strong>
            <p style="margin: 5px 0;">
                Jika Anda mengalami masalah atau membutuhkan bantuan, silakan hubungi tim support kami di 
                <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
            </p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis dari sistem {{ config('app.name') }}.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p style="color: #999; font-size: 10px;">
                Dikirim pada: {{ now()->format('d/m/Y H:i:s') }} WIB
            </p>
        </div>
    </div>
</body>
</html>