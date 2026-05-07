<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Jadwal Tatap Muka - InvestaLearning</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #111827;
        }
        .wrapper { width: 100%; background-color: #f3f4f6; padding: 24px 0; }
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
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-logo {
            width: 42px; height: 42px; border-radius: 18px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
        }
        .brand-logo img { width: 28px; height: 28px; border-radius: 999px; }
        .brand-text-title { font-size: 18px; font-weight: 700; color: #111827; }
        .brand-text-subtitle { font-size: 11px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: #4f46e5; }
        .chip {
            padding: 6px 12px; border-radius: 999px;
            border: 1px solid rgba(79, 70, 229, 0.25);
            background-color: rgba(79, 70, 229, 0.12);
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.1em; color: #4338ca;
        }
        .hero {
            padding: 32px 24px; text-align: center;
            background: linear-gradient(180deg, #eef2ff 0%, #ffffff 100%);
        }
        .hero-icon {
            width: 64px; height: 64px; margin: 0 auto 16px auto;
            border-radius: 20px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 12px 28px rgba(79, 70, 229, 0.4);
        }
        .hero-icon svg { width: 32px; height: 32px; color: #ffffff; }
        .hero-title { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .hero-subtitle { font-size: 14px; line-height: 1.6; color: #6b7280; max-width: 420px; margin: 0 auto; }
        .body { padding: 24px; }
        .card {
            border-radius: 18px; border: 1px solid #e5e7eb;
            background: linear-gradient(145deg, #f9fafb, #ffffff);
            padding: 24px; margin-bottom: 20px;
        }
        .card-greeting { font-size: 15px; font-weight: 600; color: #111827; margin-bottom: 12px; }
        .card-message { font-size: 14px; line-height: 1.6; color: #4b5563; margin-bottom: 20px; }
        .card-message strong { color: #111827; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 10px 12px; font-size: 13px; border-bottom: 1px solid #f3f4f6; }
        .info-table td:first-child { color: #6b7280; font-weight: 600; width: 40%; }
        .info-table td:last-child { color: #111827; font-weight: 500; }
        .info-table tr:last-child td { border-bottom: none; }
        .btn-primary {
            display: inline-block; padding: 12px 28px; border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: #ffffff !important; text-decoration: none;
            font-size: 14px; font-weight: 600; letter-spacing: 0.02em;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.4);
        }
        .note {
            border-radius: 12px; background: #fffbeb;
            border: 1px solid #fde68a; padding: 14px 16px;
            font-size: 12px; line-height: 1.6; color: #92400e;
            margin-bottom: 20px;
        }
        .divider { height: 1px; background-color: #e5e7eb; margin: 20px 0; }
        .footer { padding-top: 8px; text-align: center; font-size: 11px; line-height: 1.6; color: #9ca3af; }
        .footer a { color: #4f46e5; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="header">
            <div class="brand">
                <div class="brand-logo">
                    <img src="{{ config('app.logo_url', url('img/favicon.png')) }}" alt="InvestaLearning">
                </div>
                <div>
                    <div class="brand-text-title">InvestaLearning</div>
                    <div class="brand-text-subtitle">Sertifikasi Pelatihan</div>
                </div>
            </div>
            <div class="chip">Jadwal Tatap Muka</div>
        </div>

        <div class="hero">
            <div class="hero-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
            <div class="hero-title">Pendaftaran Berhasil!</div>
            <div class="hero-subtitle">Anda telah terdaftar pada jadwal tatap muka. Berikut detail jadwal dan link bergabung Anda.</div>
        </div>

        <div class="body">
            <div class="card">
                <div class="card-greeting">Halo!</div>
                <div class="card-message">
                    Pendaftaran Anda untuk sesi <strong>{{ $schedule->title }}</strong> telah berhasil dikonfirmasi.
                    Simpan detail berikut dan pastikan Anda hadir tepat waktu.
                </div>

                <table class="info-table">
                    <tr>
                        <td>Judul Sesi</td>
                        <td>{{ $schedule->title }}</td>
                    </tr>
                    @if($schedule->package)
                    <tr>
                        <td>Paket</td>
                        <td>{{ $schedule->package->title }}</td>
                    </tr>
                    @endif
                    @if($schedule->subject)
                    <tr>
                        <td>Mata Pelajaran</td>
                        <td>{{ $schedule->subject->name }}</td>
                    </tr>
                    @endif
                    @if($schedule->teacher)
                    <tr>
                        <td>Pengajar</td>
                        <td>{{ $schedule->teacher->name }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Tanggal</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Waktu</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB</td>
                    </tr>
                    @if($schedule->room_name)
                    <tr>
                        <td>Ruangan</td>
                        <td>{{ $schedule->room_name }}</td>
                    </tr>
                    @endif
                    @if($schedule->zoom_passcode)
                    <tr>
                        <td>Passcode Zoom</td>
                        <td><strong>{{ $schedule->zoom_passcode }}</strong></td>
                    </tr>
                    @endif
                </table>

                @if($joinUrl && $joinUrl !== '#')
                    <a href="{{ $joinUrl }}" class="btn-primary">Bergabung ke Zoom</a>
                @endif
            </div>

            @if($joinUrl && $joinUrl !== '#')
            <div class="note">
                ⚠️ <strong>Penting:</strong> Link bergabung di atas bersifat personal dan hanya untuk Anda. Jangan bagikan link ini kepada orang lain.
            </div>
            @endif

            <div class="divider"></div>

            <div class="footer">
                Jika Anda tidak mendaftar jadwal ini, abaikan email ini atau hubungi admin.<br>
                &copy; {{ date('Y') }} <a href="{{ url('/') }}">InvestaLearning</a>. All rights reserved.
            </div>
        </div>
    </div>
</div>
</body>
</html>
