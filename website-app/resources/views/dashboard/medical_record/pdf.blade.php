<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis - {{ $pasien->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 100px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 10px 0;
        }
        .header p {
            margin: 5px 0;
            font-size: 11pt;
            color: #555;
        }
        .patient-info, .record {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .patient-info h2, .record h2 {
            font-size: 14pt;
            color: #2c3e50;
            margin-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
        }
        .patient-info p, .record p {
            margin: 5px 0;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .record {
            page-break-inside: avoid;
        }
        .record h3 {
            font-size: 13pt;
            color: #34495e;
            margin: 15px 0 10px;
        }
        @page {
            margin: 40px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/images/logo.png') }}" alt="Logo Puskesmas">
        <h1>Rekam Medis Pasien</h1>
        <p>Puskesmas [Nama Puskesmas]</p>
        <p>Jl. Contoh No. 123, Kota, Provinsi</p>
        <p>Telp: (021) 12345678 | Email: info@puskesmas.com</p>
    </div>

    <div class="patient-info">
        <h2>Informasi Pasien</h2>
        <p><span class="label">Nama Pasien:</span> {{ $pasien->nama }}</p>
        <p><span class="label">NIK:</span> {{ $pasien->nik }}</p>
        <p><span class="label">No. RM:</span> {{ $pasien->no_rm }}</p>
        <p><span class="label">Alamat:</span> {{ $pasien->alamat }}</p>
    </div>

    @forelse($medicalRecords as $record)
        <div class="record">
            <h2>Kunjungan: {{ $record->tanggal_kunjungan->format('d F Y') }}</h2>
            <h3>Informasi Kunjungan</h3>
            <p><span class="label">No. Antrian:</span> {{ $record->pendaftaran->antrian->no_antrian }}</p>
            <p><span class="label">Status Antrian:</span> {{ $record->pendaftaran->antrian->status->value }}</p>
            <p><span class="label">Cluster:</span> {{ $record->pendaftaran->cluster->nama }}</p>

            <h3>Informasi Klinis</h3>
            <p><span class="label">Keluhan:</span> {{ $record->keluhan }}</p>
            <p><span class="label">Diagnosis:</span> {{ $record->diagnosis }}</p>
            <p><span class="label">Pengobatan:</span> {{ $record->pengobatan }}</p>
            <p><span class="label">Hasil Pemeriksaan:</span> {{ $record->hasil_pemeriksaan ?? '-' }}</p>
            <p><span class="label">Tinggi Badan:</span> {{ $record->tinggi_badan ? $record->tinggi_badan . ' cm' : '-' }}</p>
            <p><span class="label">Berat Badan:</span> {{ $record->berat_badan ? $record->berat_badan . ' kg' : '-' }}</p>
            <p><span class="label">Tekanan Darah:</span> {{ $record->tekanan_darah ?? '-' }}</p>
            <p><span class="label">Suhu Badan:</span> {{ $record->suhu_badan ? $record->suhu_badan . ' °C' : '-' }}</p>
        </div>
    @empty
        <p>Tidak ada rekam medis untuk pasien ini.</p>
    @endforelse
</body>
</html>