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
        .header-brand {
            text-align: center;
        }

        .header-left,
        .header-center,
        .header-right {
            display: inline-block;
            vertical-align: middle;
            padding: 0 8px;
        }

        .header-left,
        .header-right {
            width: 18%;
        }

        .header-center {
            width: 60%;
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
        $bgFrontData = null;
        if (isset($template) && $template?->front_background_path) {
            $bgPath = public_path('storage/'.$template->front_background_path);
            $bgFrontData = is_readable($bgPath) ? base64_encode(file_get_contents($bgPath)) : null;
        }

        $leftSignData = null;
        if (isset($template) && $template?->left_signature_image_path) {
            $leftPath = public_path('storage/'.$template->left_signature_image_path);
            $leftSignData = is_readable($leftPath) ? base64_encode(file_get_contents($leftPath)) : null;
        }

        $rightSignData = null;
        if (isset($template) && $template?->right_signature_image_path) {
            $rightPath = public_path('storage/'.$template->right_signature_image_path);
            $rightSignData = is_readable($rightPath) ? base64_encode(file_get_contents($rightPath)) : null;
        }
    @endphp

    {{-- PAGE 1 --}}
    <div class="page">
        <div class="frame" @if($bgFrontData) style="background-image: url('data:image/png;base64,{{ $bgFrontData }}'); background-size: cover; background-position: center;" @endif>
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

                <div class="participant-desc">
                    As Participant In Examination Prepation Training of<br>
                    <strong>"{{ $certificate->type?->name_type ?? '-' }}"</strong><br>
                    @if ($certificate->training_date_start && $certificate->training_date_end)
                        Jakarta, {{ \Carbon\Carbon::parse($certificate->training_date_start)->format('d F') }}
                        -
                        {{ \Carbon\Carbon::parse($certificate->training_date_end)->format('d F Y') }}
                    @elseif ($certificate->training_date_start)
                        Jakarta, {{ \Carbon\Carbon::parse($certificate->training_date_start)->format('d F Y') }}
                    @else
                        Jakarta, 12 - 14 Month Years
                    @endif
                </div>

                <div class="signatures">
                    <div class="signature">
                        @if($leftSignData)
                            <img src="data:image/png;base64,{{ $leftSignData }}" alt="Tanda tangan kiri" style="max-height:80px; margin-bottom:4px;">
                        @endif
                        <div class="signature-line"></div>
                        <div class="signature-name">
                            {{ $template->left_signature_name ?? ' ' }}
                        </div>
                        <div class="signature-role">
                            {{ $template->left_signature_title ?? ' ' }}
                        </div>
                    </div>
                    <div class="signature">
                        @if($rightSignData)
                            <img src="data:image/png;base64,{{ $rightSignData }}" alt="Tanda tangan kanan" style="max-height:80px; margin-bottom:4px;">
                        @endif
                        <div class="signature-line"></div>
                        <div class="signature-name">
                            {{ $template->right_signature_name ?? ' ' }}
                        </div>
                        <div class="signature-role">
                            {{ $template->right_signature_title ?? ' ' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PAGE 2 --}}
    <div class="page">
        <div class="frame">
            <div class="page2-title-block">
                <h2>{{ $template->schema_title ?? 'SKEMA PELATIHAN' }}</h2>
            </div>

            <div class="page2-paragraphs">
                @if(!empty($template->schema_description))
                    {!! nl2br(e($template->schema_description)) !!}
                @else
                    <p>
                        Deskripsi skema pelatihan akan ditampilkan di sini. Atur konten ini melalui menu Desain Sertifikat
                        pada panel admin.
                    </p>
                @endif
            </div>

            <div class="table-wrapper">
                <table class="cert-table">
                    <thead>
                        <tr>
                            <th class="td-no">No</th>
                            <th class="td-code">Kode</th>
                            <th class="td-desc">Deskripsi Unit Kompetensi (UK)</th>
                            <th class="td-topic">Topik Utama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $ukLines = collect(preg_split('/\r\n|\r|\n/', (string) ($template->uk_list ?? '')))
                                ->map(fn ($line) => trim($line))
                                ->filter();
                        @endphp
                        @forelse($ukLines as $index => $line)
                            <tr>
                                <td class="td-no">{{ $index + 1 }}</td>
                                <td class="td-code">UK-{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="td-desc">{{ $line }}</td>
                                <td class="td-topic">{{ $certificate->package?->title ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="td-desc">
                                    Tidak ada daftar UK yang diisi. Daftar UK dapat diatur di menu Desain Sertifikat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer-facilitator">
                <strong>Daftar Fasilitator:</strong>
                @php
                    $facLines = collect(preg_split('/\r\n|\r|\n/', (string) ($template->facilitator_list ?? '')))
                        ->map(fn ($line) => trim($line))
                        ->filter();
                @endphp
                @if($facLines->isEmpty())
                    <span>Belum ada daftar fasilitator yang diisi.</span>
                @else
                    @foreach($facLines as $line)
                        <span>{{ $line }}</span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</body>
</html>