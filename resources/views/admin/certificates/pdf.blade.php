<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat</title>
    <style>
        @page {
            margin: 28px;
        }

        * {
            box-sizing: border-box;
            font-family: DejaVu Sans, Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #111827;
            font-size: 12px;
        }

        .page {
            width: 100%;
            page-break-after: always;
        }

        .frame {
            border: 3px solid rgba(79, 70, 229, 0.25);
            padding: 28px 28px 24px;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .qr-box {
            text-align: right;
            font-size: 9px;
            color: #6b7280;
        }

        .qr-box img {
            height: 70px;
            width: 70px;
        }

        .logo-box {
            display: flex;
            align-items: center;
        }

        .logo-box img {
            height: 50px;
            width: auto;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 4px;
        }

        .logo-text {
            margin-left: 10px;
        }

        .logo-text-title {
            font-size: 10px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #9ca3af;
        }

        .logo-text-sub {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
        }

        .header-right {
            text-align: right;
            font-size: 11px;
        }

        .header-right-label {
            color: #9ca3af;
        }

        .header-right-value {
            font-weight: 600;
        }

        .title-section {
            text-align: center;
            margin-top: 28px;
        }

        .title-small {
            font-size: 9px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #4f46e5;
            font-weight: 700;
        }

        .title-main {
            font-size: 26px;
            font-weight: 800;
            margin-top: 6px;
            color: #111827;
        }

        .title-desc {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }

        .center-block {
            text-align: center;
            margin-top: 28px;
        }

        .center-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .center-name {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .center-sub {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .grid-3 {
            display: table;
            width: 100%;
            margin-top: 28px;
        }

        .grid-3-col {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
            padding: 0 6px;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 10px 12px;
            background-color: #f9fafb;
        }

        .card-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 0.14em;
            margin-bottom: 4px;
        }

        .card-value {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
        }

        .sign-row {
            display: table;
            width: 100%;
            margin-top: 40px;
        }

        .sign-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        .sign-line {
            border-bottom: 1px solid #d1d5db;
            margin: 0 20px 4px;
        }

        .sign-label {
            font-size: 10px;
            color: #6b7280;
        }

        .mt-large {
            margin-top: 30px;
        }

        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .list-box {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 10px 12px;
        }

        .list-item {
            padding: 6px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-title {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
        }

        .list-sub {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        .list-topic {
            font-size: 10px;
            color: #4f46e5;
            margin-top: 2px;
        }

        .footer-note {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    {{-- PAGE 1 --}}
    <div class="page">
        <div class="frame">
            <div class="header">
                <div class="logo-box">
                    <img src="https://investalearning.com/public/assets/logo/investalearning2.jpeg" alt="Logo">
                    <div class="logo-text">
                        <div class="logo-text-title">InvestaLearning</div>
                        <div class="logo-text-sub">Sertifikasi</div>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header-right-label">Tanggal</div>
                    <div class="header-right-value">
                        {{ $certificate->created_at?->format('d M Y') }}
                    </div>
                </div>
            </div>

            <div class="title-section">
                <div class="title-small">Certificate of Completion</div>
                <div class="title-main">SERTIFIKAT</div>
                <div class="title-desc">
                    Diberikan kepada peserta yang telah menyelesaikan ujian sesuai dengan ketentuan yang berlaku.
                </div>
            </div>

            <div class="center-block">
                <div class="center-label">Nama Peserta</div>
                <div class="center-name">
                    {{ $certificate->user?->name ?? '-' }}
                </div>
                <div class="center-sub">
                    {{ $certificate->user?->email ?? '' }}
                </div>
            </div>

            <div class="grid-3">
                <div class="grid-3-col">
                    <div class="card">
                        <div class="card-label">Jenis</div>
                        <div class="card-value">
                            {{ $certificate->type?->name_type ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="grid-3-col">
                    <div class="card">
                        <div class="card-label">Paket</div>
                        <div class="card-value">
                            {{ $certificate->package?->title ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="grid-3-col">
                    <div class="card">
                        <div class="card-label">Nomor</div>
                        <div class="card-value">
                            CERT-{{ str_pad((string) $certificate->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-large">
                <div class="qr-box">
                    @php
                        $verifyUrl = route('certificates.verify', $certificate);
                    @endphp
                    <div>Scan untuk verifikasi sertifikat:</div>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($verifyUrl) }}" alt="QR Verifikasi">
                </div>
            </div>

            <div class="sign-row">
                <div class="sign-col">
                    <div class="sign-line"></div>
                    <div class="sign-label">Tanda Tangan</div>
                </div>
                <div class="sign-col">
                    <div class="sign-line"></div>
                    <div class="sign-label">Penyelenggara</div>
                </div>
            </div>
        </div>
    </div>

    {{-- PAGE 2 --}}
    <div class="page">
        <div class="frame">
            <div class="header">
                <div class="logo-box">
                    <img src="https://investalearning.com/public/assets/logo/investalearning2.jpeg" alt="Logo">
                    <div class="logo-text">
                        <div class="logo-text-title">InvestaLearning</div>
                        <div class="logo-text-sub">Sertifikasi</div>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header-right-label">Halaman</div>
                    <div class="header-right-value">2 / 2</div>
                </div>
            </div>

            <div class="title-section">
                <div class="title-small">Certificate Details</div>
                <div class="title-main">PENGAJAR &amp; TOPIK</div>
                <div class="title-desc">
                    Rangkuman pengajar yang terlibat dan materi yang tercakup pada paket sertifikasi ini.
                </div>
            </div>

            <div class="mt-large">
                <div class="section-title">Daftar Pengajar</div>
                <div class="list-box">
                    @forelse($certificate->teachers as $teacher)
                        <div class="list-item">
                            <div class="list-title">{{ $teacher->name }}</div>
                            <div class="list-sub">
                                {{ $teacher->email ?? '-' }}
                                @if($teacher->nip)
                                    • NIP: {{ $teacher->nip }}
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="list-item">
                            <div class="list-sub">Belum ada pengajar terpilih.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-large">
                <div class="section-title">Topik Materi</div>
                <div class="list-box">
                    @forelse($certificate->package?->materials ?? [] as $material)
                        <div class="list-item">
                            <div class="list-title">{{ $material->title ?? '-' }}</div>
                            @if(!empty($material->description))
                                <div class="list-sub">{{ $material->description }}</div>
                            @endif
                            @if(!empty($material->topic))
                                <div class="list-topic">Topik: {{ $material->topic }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="list-item">
                            <div class="list-sub">Belum ada materi pada paket ini.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="footer-note">
                <span>Dokumen ini merupakan cetakan otomatis sistem • Dummy layout dapat disesuaikan</span>
                <span>InvestaLearning Sertifikasi</span>
            </div>
        </div>
    </div>
</body>
</html>



