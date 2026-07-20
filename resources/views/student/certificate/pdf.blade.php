<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>CLEARN │ Sertifikat</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #0f0a19;
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1c1826;
        }

        /* Bingkai luar gelap sebagai "jarak" ke tepi kertas */
        .page-wrapper {
            padding: 25px;
        }

        /* Kartu putih melengkung, ini bagian utamanya */
        .certificate-card {
            background-color: #ffffff;
            border-radius: 28px;
            padding: 45px 70px;
            text-align: center;
            box-shadow: 0 0 25px rgba(124, 58, 237, 0.15);
        }

        .logo-img {
            height: 65px;
            margin: 0 auto 20px auto;
            display: block;
        }

        h2.cert-title {
            font-size: 26px;
            font-weight: 800;
            margin: 0 0 18px 0;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
            display: inline-block;
        }

        p.small-label {
            font-size: 13px;
            color: #64748b;
            margin: 0 0 14px 0;
            font-weight: 500;
        }

        h1.student-name {
            font-size: 40px;
            font-weight: 900;
            color: #7C3AED;
            margin: 0 0 14px 0;
        }

        h3.course-title {
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 20px 0;
            line-height: 1.3;
        }

        p.date {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 25px 0;
        }

        .divider {
            border-top: 1px dashed #e2e8f0;
            margin-bottom: 25px;
        }

        table.signatures {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.signatures td {
            width: 50%;
            text-align: center;
            padding: 0 15px;
        }

        .sign-name {
            font-weight: 800;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .sign-title {
            font-size: 10px;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        table.footer-table {
            width: 100%;
            border-top: 1px solid #f1f5f9;
            padding-top: 18px;
            border-collapse: collapse;
        }

        table.footer-table td {
            vertical-align: bottom;
        }

        .cert-id-label {
            font-size: 9px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
            text-align: left;
        }

        .cert-id-value {
            font-size: 13px;
            font-weight: 800;
            text-align: left;
        }

        .qr-cell {
            text-align: right;
        }

        .qr-cell img {
            width: 55px;
            height: 55px;
        }

        .qr-caption {
            font-size: 9px;
            color: #64748b;
            margin-top: 4px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="certificate-card">

            @php
                $logoPath = public_path('images/logo-dark.png');
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoSrc = 'data:image/png;base64,' . $logoData;
            @endphp
            <img src="{{ $logoSrc }}" class="logo-img" alt="Logo Clearn">

            <h2 class="cert-title">Sertifikat Penghargaan</h2>

            <p class="small-label">Dengan ini menyatakan bahwa</p>

            <h1 class="student-name">{{ $certificate->enrollment->student->name }}</h1>

            <p class="small-label">telah berhasil menyelesaikan kursus</p>

            <h3 class="course-title">{{ $certificate->enrollment->course->course_title }}</h3>

            <p class="date">Selesai pada {{ $certificate->issue_date->translatedFormat('d F Y') }}</p>

            <div class="divider"></div>

            <table class="signatures">
                <tr>
                    <td>
                        <div class="sign-name">{{ $certificate->enrollment->course->mentor->name ?? 'Pengajar Clearn' }}</div>
                        <div class="sign-title">TANDA TANGAN PENGAJAR</div>
                    </td>
                    <td>
                        <div class="sign-name">Admin Clearn</div>
                        <div class="sign-title">ADMINISTRATOR PLATFORM</div>
                    </td>
                </tr>
            </table>

            <table class="footer-table">
                <tr>
                    <td>
                        <div class="cert-id-label">ID Sertifikat</div>
                        <div class="cert-id-value">{{ $certificate->certificate_number }}</div>
                    </td>
                    <td class="qr-cell">
                        <img src="data:image/svg+xml;base64,{{ base64_encode(app('qrcode')->format('svg')->size(150)->generate(route('student.certificate.show', $certificate->id))) }}" alt="QR Code">
                        <div class="qr-caption">Pindai untuk verifikasi</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>