<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLEARN │ Sertifikat</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #1c1826;
            text-align: center;
        }
        .container {
            padding: 40px;
            border: 2px solid #eee;
            margin: 20px;
            position: relative;
        }
        .header {
            color: #A487F8;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .title {
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        .student-name {
            font-size: 50px;
            font-weight: bold;
            color: #A487F8;
            margin-bottom: 30px;
        }
        .course-title {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .date {
            font-size: 14px;
            color: #666;
            margin-bottom: 50px;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px dashed #ccc;
            padding-top: 30px;
            display: table;
            width: 100%;
        }
        .signature-block {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .signature-title {
            font-size: 12px;
            color: #888;
        }
        .cert-id {
            position: absolute;
            bottom: 20px;
            left: 40px;
            font-size: 12px;
            color: #888;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">CLEARN PLATFORM</div>
        
        <div class="title">Sertifikat Prestasi</div>
        <div class="subtitle">Dengan ini menyatakan bahwa</div>
        
        <div class="student-name">{{ $certificate->enrollment->student->name }}</div>
        
        <div class="subtitle">telah berhasil menyelesaikan kursus</div>
        
        <div class="course-title">{{ $certificate->enrollment->course->course_title }}</div>
        
        <div class="date">Selesai pada {{ $certificate->issue_date->translatedFormat('d F Y') }}</div>
        
        <div class="footer">
            <div class="signature-block">
                <div class="signature-name">{{ $certificate->enrollment->course->mentor->name ?? 'Pengajar Clearn' }}</div>
                <div class="signature-title">Tanda Tangan Pengajar</div>
            </div>
            <div class="signature-block">
                <div class="signature-name">Admin Clearn</div>
                <div class="signature-title">Administrator Platform</div>
            </div>
        </div>

        <div class="cert-id">
            <strong>ID:</strong> {{ $certificate->certificate_number }}
        </div>

        <!-- Tambahan Kode QR Code -->
        <div style="position: absolute; bottom: 10px; right: 40px; text-align: center;">
            <img src="data:image/svg+xml;base64, {!! base64_encode(app('qrcode')->format('svg')->size(60)->generate(route('student.certificate.show', $certificate->id))) !!}" alt="QR Code">
            <div style="font-size: 8px; color: #888; margin-top: 5px;">Scan to Verify</div>
        </div>
    </div>
</body>
</html>