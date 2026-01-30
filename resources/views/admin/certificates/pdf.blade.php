<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat</title>
    <style>
        /* A4 Landscape: 297mm x 210mm */
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            background-color: #ffffff;
            color: #111827;
            font-size: 11px;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        .page {
            width: 100%;
            page-break-after: always;
            page-break-inside: avoid;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .frame {
            border: 3px solid rgba(99, 102, 241, 0.3);
            margin: 8mm;
            padding: 8mm 10mm;
            background: linear-gradient(to bottom, rgba(238, 242, 255, 0.3), #ffffff);
            display: flex;
            flex-direction: column;
        }

<<<<<<< HEAD
        /* HEADER STYLES */
        .header-brand {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            table-layout: fixed;
=======
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
>>>>>>> 273ffd0f80f68914a7a41fa0a1280da68866bd31
        }

        .header-left,
        .header-center,
        .header-right {
            display: table-cell;
            vertical-align: middle;
            padding: 0 8px;
        }

        .header-left,
        .header-right {
            width: 18%;
        }

        .header-center {
            width: 64%;
            text-align: center;
        }

        .header-right {
            text-align: right;
        }

        .brand-logo {
            height: 70px;
            width: auto;
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }

        .header-right .brand-logo {
            margin: 0 0 0 auto;
        }

        .brand-title {
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            color: #0ea5e9;
            text-decoration: underline;
            text-underline-offset: 6px;
            font-family: "Times New Roman", serif;
            margin: 0;
            line-height: 1.2;
            letter-spacing: 0.08em;
        }

        .brand-subtitle {
            font-size: 11px;
            font-weight: 700;
            color: #0ea5e9;
            letter-spacing: 0.12em;
            font-family: "Times New Roman", serif;
            margin-top: 4px;
        }

        /* PAGE 1 STYLES */
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .title-main {
            text-align: center;
            color: #111827;
            margin-bottom: 10px;
        }

        .title-main h1 {
            font-size: 26px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 8px 0;
            line-height: 1.2;
            letter-spacing: 0.06em;
        }

        .title-main span {
            display: block;
            font-size: 10px;
            color: #111827;
            letter-spacing: 0.03em;
        }

        .given-to {
            margin: 10px 0 8px 0;
            text-align: center;
            font-size: 14px;
            text-transform: uppercase;
            color: #374151;
            letter-spacing: 0.12em;
            font-weight: 600;
        }

        .participant-name {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            line-height: 1.3;
            letter-spacing: 0.02em;
            margin: 0 0 6px 0;
        }

        .participant-line {
            height: 3px;
            width: 45%;
            max-width: 400px;
            background-color: #3b82f6;
            margin: 0 auto 10px auto;
        }

        .participant-desc {
            font-size: 11px;
            text-align: center;
            color: #374151;
            line-height: 1.6;
            padding: 0 60px;
            font-weight: 600;
            margin: 0 0 15px 0;
        }

        .signatures {
            margin-top: 30px;
            display: table;
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .signature {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 30px;
            vertical-align: top;
        }

        .signature-line {
            height: 2px;
            width: 180px;
            max-width: 100%;
            margin: 0 auto 8px auto;
            background-color: #38bdf8;
        }

        .signature-name {
            font-size: 14px;
            color: #111827;
            font-weight: 600;
            margin: 6px 0 3px 0;
        }

        .signature-role {
            font-size: 13px;
            color: #374151;
            margin: 0;
        }

        /* PAGE 2 STYLES */
        .page2-title-block {
            margin: 14px 0 8px 0;
            text-align: center;
        }

        .page2-title-block h2 {
            font-size: 13px;
            font-weight: 800;
            color: #111827;
            line-height: 1.4;
            margin: 0;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .page2-paragraphs {
            margin: 8px 0 10px 0;
            font-size: 9px;
            color: #111827;
            line-height: 1.5;
            text-align: justify;
            font-weight: 500;
        }

        .page2-paragraphs p {
            margin: 0 0 6px 0;
        }

        .page2-paragraphs p:last-child {
            margin-bottom: 0;
        }

        .table-wrapper {
            margin: 12px 0;
            border: 1px solid #d1d5db;
            overflow: hidden;
        }

        table.cert-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            font-size: 9px;
            table-layout: fixed;
        }

        table.cert-table thead tr {
            background-color: #f3f4f6;
        }

        table.cert-table th,
        table.cert-table td {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        table.cert-table th {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #374151;
            background-color: #f3f4f6;
            line-height: 1.3;
        }

        table.cert-table td {
            color: #111827;
            font-size: 9px;
            line-height: 1.4;
        }
        
        table.cert-table tbody tr td:first-child,
        table.cert-table tbody tr td:nth-child(2) {
            font-weight: 600;
        }

        .td-no {
            width: 6%;
            text-align: center;
        }

        .td-code {
            width: 14%;
            text-align: center;
        }

        .td-desc {
            width: 42%;
            text-align: left;
        }

        .td-topic {
            width: 38%;
            text-align: left;
            color: #4f46e5;
            font-weight: 500;
        }

        .footer-facilitator {
            margin-top: 8px;
            font-size: 9px;
            color: #374151;
            text-align: left;
            line-height: 1.5;
            padding-top: 6px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-facilitator span {
            display: block;
        }

        .footer-facilitator strong {
            font-weight: 700;
            color: #111827;
        }

        /* Prevent content overflow */
        .frame * {
            max-width: 100%;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('img/favicon.png');
        $logoData = is_readable($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
    @endphp

    {{-- PAGE 1 --}}
    <div class="page">
        <div class="frame">
            <div class="header-brand">
                <div class="header-left">
                    @if($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" alt="Logo" class="brand-logo">
                    @endif
                </div>
                <div class="header-center">
                    <div class="brand-title">INVESTALEARNING</div>
                    <div class="brand-subtitle">Be Champion With Us</div>
                </div>
                <div class="header-right">
                    @if($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" alt="Logo" class="brand-logo">
                    @endif
                </div>
            </div>

            <div class="content-wrapper">
                <div class="title-main">
                    <h1>Certificate of Training</h1>
                    <span>
                        No.
                        {{
                            $certificate->certificate_number
                                ? $certificate->certificate_number . '/BIB/Training/' .
                                  \Carbon\Carbon::parse($certificate->training_date_start)->format('d F') .
                                  ' - ' .
                                  \Carbon\Carbon::parse($certificate->training_date_end)->format('d F Y')
                                : '---/BIB/Training/Month/Years'
                        }}
                    </span>
                </div>

                <div class="given-to">GIVEN TO</div>

                <div class="participant-name">
                    {{ $certificate->user?->name ?? '-' }}
                </div>

                <div class="participant-line"></div>

<<<<<<< HEAD
                <div class="participant-desc">
                    As Participant In Examination Prepation Training of<br>
                    <strong>"{{ $certificate->type?->name_type ?? '-' }}"</strong><br>
                    @if($certificate->training_date_start && $certificate->training_date_end)
                        Jakarta, {{ \Carbon\Carbon::parse($certificate->training_date_start)->format('d F') }} - {{ \Carbon\Carbon::parse($certificate->training_date_end)->format('d F Y') }}
                    @elseif($certificate->training_date_start)
                        Jakarta, {{ \Carbon\Carbon::parse($certificate->training_date_start)->format('d F Y') }}
                    @else
                        Jakarta, 12 - 14 Month Years
                    @endif
=======
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
>>>>>>> 273ffd0f80f68914a7a41fa0a1280da68866bd31
                </div>

                <div class="signatures">
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-name">Lucas Bonardo</div>
                        <div class="signature-role">Director</div>
                    </div>
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-name">Lisbeth Rosaria</div>
                        <div class="signature-role">Senior Vice President</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PAGE 2 --}}
    <div class="page">
        <div class="frame">
            <div class="header-brand">
                <div class="header-left">
                    @if($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" alt="Logo" class="brand-logo">
                    @endif
                </div>
                <div class="header-center">
                    <div class="brand-title">INVESTALEARNING</div>
                    <div class="brand-subtitle">Be Champion With Us</div>
                </div>
                <div class="header-right">
                    @if($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" alt="Logo" class="brand-logo">
                    @endif
                </div>
            </div>

            <div class="page2-title-block">
                <h2>
                    JENJANG KUALIFIKASI 4 BIDANG PASAR MODAL<br>
                    SUBBIDANG PERANTARA PEDAGANG EFEK PEMASARAN
                </h2>
            </div>

            <div class="page2-paragraphs">
                <p>
                    Sesuai keputusan Menteri ketenagakerjaan Republik Indonesia Nomor 20 Tahun 2024 Tentang Penetapan
                    Standar Kompetensi Kerja Nasional Indonesia Kategori Aktivitas Keuangan Dan Asuransi Golongan Pokok
                    Aktivitas Penunjang Jasa Keuangan, Bukan Asuransi Dan Dana Pensiun Bidang Pasar Modal.
                </p>
                <p>
                    Dan Keputusan Anggota Dewan Komisioner Otoritas Jasa Keuangan Nomor Kep-11/D.02/2024 Tentang
                    Kerangka Kualitas Nasional Indonesia Bidang Pasar Modal.
                </p>
            </div>

            <div class="table-wrapper">
                <table class="cert-table">
                    <thead>
                        <tr>
                            <th class="td-no">NO</th>
                            <th class="td-code">KODE</th>
                            <th class="td-desc">DESKRIPSI</th>
                            <th class="td-topic">TOPIK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @forelse($certificate->package?->materials ?? [] as $material)
                            <tr>
                                <td class="td-no">{{ $i }}</td>
                                <td class="td-code">{{ $material->subject->code ?? '-' }}</td>
                                <td class="td-desc">{{ $material->description ?? '-' }}</td>
                                <td class="td-topic">{{ $material->topic ?? '-' }}</td>
                            </tr>
                            @php $i++; @endphp
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 15px; font-size: 10px; color: #6b7280; font-style: italic;">
                                    Belum ada materi pada paket ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer-facilitator">
                <span>
                    <strong>NAMA FASILITATOR:</strong>
                    @forelse ($certificate->teachers ?? [] as $teacher)
                        {{ $teacher->name }}@if(!$loop->last), @endif
                    @empty
                        Nama Fasilitator belum tersedia.
                    @endforelse
                </span>
            </div>
        </div>
    </div>
</body>
</html>