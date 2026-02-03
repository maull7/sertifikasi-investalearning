<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - InvestaLearning</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #111827;
        }

        .wrapper {
            width: 100%;
            background-color: #f3f4f6;
            padding: 24px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .header {
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 50%, #c7d2fe 100%);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 18px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
        }

        .brand-logo img {
            width: 28px;
            height: 28px;
            border-radius: 999px;
        }

        .brand-text-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .brand-text-subtitle {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #4f46e5;
        }

        .chip {
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid rgba(79, 70, 229, 0.25);
            background-color: rgba(79, 70, 229, 0.12);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #4338ca;
        }

        .body {
            padding: 24px;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            line-height: 1.6;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .card {
            border-radius: 18px;
            border: 1px solid #e5e7eb;
            background: linear-gradient(145deg, #f9fafb, #ffffff);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-label {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .card-email {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
        }

        .btn-primary {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 999px;
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.45);
        }

        .btn-primary:hover {
            opacity: 0.95;
        }

        .info-text {
            font-size: 12px;
            line-height: 1.6;
            color: #6b7280;
            margin-top: 16px;
        }

        .info-text strong {
            color: #111827;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }

        .secondary-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .secondary-text {
            font-size: 12px;
            line-height: 1.6;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .secondary-text:last-of-type {
            margin-bottom: 0;
        }

        .secondary-text a {
            color: #4f46e5;
            text-decoration: none;
            word-break: break-all;
        }

        .footer {
            padding: 8px 24px 24px 24px;
            text-align: center;
            font-size: 11px;
            line-height: 1.6;
            color: #9ca3af;
        }

        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="header">
            <div class="brand">
                <div class="brand-logo">
                    <img src="{{ $logoUrl ?? config('app.logo_url', 'https://srv1289380.hstgr.cloud/img/favicon.png') }}" alt="InvestaLearning" width="28" height="28">
                </div>
                <div>
                    <div class="brand-text-title">InvestaLearning</div>
                    <div class="brand-text-subtitle">Be Champion With Us</div>
                </div>
            </div>
            <div class="chip">Reset Password</div>
        </div>

        <div class="body">
            <div class="title">Permintaan Ubah Password</div>
            <div class="subtitle">
                Kami menerima permintaan untuk mengubah password akun InvestaLearning Anda. Jika ini benar, silakan klik tombol di bawah untuk mengatur password baru.
            </div>

            <div class="card">
                <div class="card-label">Akun yang ingin diubah</div>
                <div class="card-email">{{ $notifiable->email }}</div>

                <a href="{{ $resetUrl }}" class="btn-primary">Atur Ulang Password</a>

                <div class="info-text">
                    Link ini hanya berlaku selama <strong>{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') ?? 60 }} menit</strong>.
                    Jika waktu sudah lewat, Anda perlu mengirim permintaan baru.
                </div>
            </div>

            <div class="secondary-title">Tidak merasa meminta reset password?</div>
            <div class="secondary-text">
                Abaikan email ini jika Anda tidak meminta reset password. Password Anda saat ini akan tetap aman dan tidak berubah.
            </div>

            <div class="divider"></div>

            <div class="secondary-title">Tidak bisa menekan tombol?</div>
            <div class="secondary-text">
                Salin dan tempel link berikut di browser Anda:<br>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} <a href="{{ $appUrl ?? config('app.url') }}">InvestaLearning</a>. All rights reserved.
        </div>
    </div>
</div>
</body>
</html>