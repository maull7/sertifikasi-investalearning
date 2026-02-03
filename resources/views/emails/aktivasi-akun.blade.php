<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Diaktifkan - InvestaLearning</title>
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

        .hero {
            padding: 32px 24px;
            text-align: center;
            background: linear-gradient(180deg, #eef2ff 0%, #ffffff 100%);
        }

        .hero-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px auto;
            border-radius: 20px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 28px rgba(79, 70, 229, 0.4);
        }

        .hero-icon svg {
            width: 32px;
            height: 32px;
            color: #ffffff;
        }

        .hero-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .hero-subtitle {
            font-size: 14px;
            line-height: 1.6;
            color: #6b7280;
            max-width: 420px;
            margin: 0 auto;
        }

        .body {
            padding: 24px;
        }

        .card {
            border-radius: 18px;
            border: 1px solid #e5e7eb;
            background: linear-gradient(145deg, #f9fafb, #ffffff);
            padding: 24px;
            margin-bottom: 20px;
        }

        .card-greeting {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 12px;
        }

        .card-message {
            font-size: 14px;
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 20px;
        }

        .card-message strong {
            color: #111827;
        }

        .btn-primary {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.02em;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.4);
        }

        .btn-primary:hover {
            opacity: 0.95;
        }

        .steps {
            border-radius: 16px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 20px;
            margin-bottom: 20px;
        }

        .steps-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 14px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 13px;
            line-height: 1.5;
            color: #4b5563;
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-num {
            width: 24px;
            height: 24px;
            border-radius: 8px;
            background: #c7d2fe;
            color: #4f46e5;
            font-weight: 700;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }

        .footer {
            padding-top: 8px;
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
                    <img src="{{ $logoUrl ?? config('app.logo_url', 'https://srv1289380.hstgr.cloud/img/favicon.png') }}" alt="InvestaLearning" width="30" height="32">
                </div>
                <div>
                    <div class="brand-text-title">InvestaLearning</div>
                    <div class="brand-text-subtitle">Sertifikasi Pelatihan</div>
                </div>
            </div>
            <div class="chip">Akun Diaktifkan</div>
        </div>

        <div class="hero">
            <div class="hero-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="hero-title">Akun Anda Sudah Aktif</div>
            <div class="hero-subtitle">Admin telah mengaktifkan akun InvestaLearning Anda. Anda sekarang dapat masuk dan menggunakan semua layanan.</div>
        </div>

        <div class="body">
            <div class="card">
                <div class="card-greeting">Halo, {{ $user->name }}!</div>
                <div class="card-message">
                    Akun Anda (<strong>{{ $user->email }}</strong>) telah berhasil <strong>diaktivasi</strong> oleh tim kami.
                    Silakan masuk ke dashboard dan mulailah mengakses materi pelatihan, ujian, serta sertifikat Anda.
                </div>
                <a href="{{ $loginUrl }}" class="btn-primary">Masuk ke Akun</a>
            </div>

            <div class="steps">
                <div class="steps-title">Setelah masuk, Anda dapat:</div>
                <div class="step">
                   
                    <span>Melihat dan mengikuti paket pelatihan yang tersedia</span>
                </div>
                <div class="step">
                  
                    <span>Mengerjakan ujian dan melihat nilai</span>
                </div>
                <div class="step">
                   
                    <span>Mendapatkan sertifikat setelah menyelesaikan persyaratan</span>
                </div>
            </div>

            <div class="divider"></div>

            <div class="footer">
                Jika Anda tidak mendaftar di InvestaLearning, abaikan email ini.<br>
                &copy; {{ date('Y') }} <a href="{{ $appUrl ?? url('/') }}">InvestaLearning</a>. All rights reserved.
            </div>
        </div>
    </div>
</div>
</body>
</html>