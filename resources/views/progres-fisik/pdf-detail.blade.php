<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Detail Progres Fisik - {{ $kdmp->nama_kdkmp }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
        }

        td {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Kop Surat Styles */
        .kop-table {
            border: none;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .kop-table th,
        .kop-table td {
            border: none;
            padding: 0;
            background: transparent;
        }

        .kop-logo {
            width: 90px;
            text-align: left;
        }

        .kop-logo img {
            width: 80px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 90px;
        }

        .kop-line {
            border: none;
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        /* Info Box */
        .info-table {
            border: none;
            margin-bottom: 15px;
        }

        .info-table td {
            border: none;
            padding: 3px 8px;
            font-size: 11px;
            background: transparent;
            text-align: left;
        }

        .info-table .label {
            font-weight: bold;
            width: 160px;
        }

        .info-table .separator {
            width: 10px;
        }

        /* Summary Box */
        .summary-box {
            border: 1px solid #333;
            padding: 10px 15px;
            margin-bottom: 15px;
            background: #f9f9f9;
        }

        .summary-box h4 {
            margin: 0 0 8px 0;
            font-size: 12px;
        }

        .summary-grid {
            border: none;
            margin: 0;
        }

        .summary-grid td {
            border: none;
            padding: 3px 10px;
            background: transparent;
            text-align: left;
            font-size: 11px;
        }

        .summary-grid .val {
            font-weight: bold;
            font-size: 12px;
        }

        /* Footer / Page Number */
        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 30px;
            font-size: 10px;
            color: #555;
            text-align: right;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body>
    <footer>
        <span class="pagenum"></span>
    </footer>

    <div class="header-kop">
        @php
            $path = public_path('logo-kkp.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        @endphp
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    <img src="{{ $base64 }}" alt="Logo KKP">
                </td>
                <td class="kop-text">
                    <h1 style="font-size:18px; margin:0 0 5px 0;">KEMENTERIAN KELAUTAN DAN PERIKANAN</h1>
                    <h2 style="font-size:16px; margin:0; font-weight:normal;">BIRO PERENCANAAN</h2>
                </td>
            </tr>
        </table>
        <hr class="kop-line">
    </div>

    <div class="page-title">
        <h3 style="margin-bottom: 5px;">PROGRES FISIK PEMBANGUNAN</h3>
        <p style="margin: 0; font-size: 12px; font-weight: bold;">{{ $kdmp->nama_kdkmp }}</p>
    </div>

    {{-- Info Lokasi --}}
    <table class="info-table">
        <tr>
            <td class="label">Nama KDKMP</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->nama_kdkmp }}</td>
        </tr>
        <tr>
            <td class="label">Desa/Kelurahan</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->desa ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kabupaten</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->kabupaten ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Provinsi</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->provinsi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Komoditas</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->komoditas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Ketua / Anggota</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->ketua_anggota ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Penyuluh</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->nama_penyuluh ?? '-' }}</td>
        </tr>
    </table>

    {{-- Ringkasan Progres Terakhir --}}
    @php
        $lastRecord = $records->first();
    @endphp
    @if($lastRecord)
    <div class="summary-box">
        <h4>Ringkasan Progres Terakhir ({{ $lastRecord->periode_label }})</h4>
        <table class="summary-grid">
            <tr>
                <td>Bangunan</td>
                <td class="val">{{ $lastRecord->progres_bangunan }}%</td>
                <td style="width:30px;"></td>
                <td>Kolam</td>
                <td class="val">{{ $lastRecord->progres_kolam }}%</td>
                <td style="width:30px;"></td>
                <td>Listrik</td>
                <td class="val">{{ $lastRecord->progres_listrik }}%</td>
            </tr>
            <tr>
                <td>Air</td>
                <td class="val">{{ $lastRecord->progres_air }}%</td>
                <td></td>
                <td>Aerasi</td>
                <td class="val">{{ $lastRecord->progres_aerasi }}%</td>
                <td></td>
                <td>Rata-rata</td>
                <td class="val" style="color: {{ $lastRecord->average_progress >= 100 ? '#16A34A' : ($lastRecord->average_progress >= 50 ? '#2563EB' : '#D97706') }};">{{ $lastRecord->average_progress }}%</td>
            </tr>
        </table>
    </div>
    @endif

    {{-- Tabel Riwayat --}}
    <h4 style="margin-bottom: 5px;">Riwayat Data Progres Fisik</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Periode</th>
                <th>Bangunan</th>
                <th>Kolam</th>
                <th>Listrik</th>
                <th>Air</th>
                <th>Aerasi</th>
                <th>Rata-rata</th>
                <th>Kendala</th>
                <th>Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php $avg = $record->average_progress; @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $record->periode_label }}</td>
                    <td class="text-center">{{ $record->progres_bangunan }}%</td>
                    <td class="text-center">{{ $record->progres_kolam }}%</td>
                    <td class="text-center">{{ $record->progres_listrik }}%</td>
                    <td class="text-center">{{ $record->progres_air }}%</td>
                    <td class="text-center">{{ $record->progres_aerasi }}%</td>
                    <td class="text-center" style="font-weight:bold; color:{{ $avg >= 100 ? '#16A34A' : ($avg >= 50 ? '#2563EB' : '#D97706') }};">{{ $avg }}%</td>
                    <td style="font-size:10px;">{{ $record->kendala ?? '-' }}</td>
                    <td style="font-size:10px;">{{ $record->tindak_lanjut ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Belum ada data progres fisik.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Dokumentasi Foto --}}
    @php
        $hasFoto = $records->filter(function($r) { return !empty($r->foto_sebelum) || !empty($r->foto_sesudah); })->isNotEmpty();
    @endphp
    @if($hasFoto)
    <div style="page-break-before: always;"></div>
    <h4 style="margin-bottom: 10px;">Dokumentasi Foto Pembangunan</h4>
    
    @foreach($records as $record)
        @if(!empty($record->foto_sebelum) || !empty($record->foto_sesudah))
        <div style="margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 15px;">
            <p style="font-weight: bold; margin-bottom: 8px; font-size: 12px; background: #f0f0f0; padding: 4px 8px; display: inline-block;">Periode: {{ $record->periode_label }}</p>
            
            <table style="width: 100%; border: none; margin-top: 0;">
                <tr>
                    {{-- Foto Sebelum --}}
                    <td style="width: 50%; vertical-align: top; border: none; padding: 0 10px 0 0; text-align: left;">
                        <p style="margin: 0 0 5px 0; font-weight: bold; color: #d97706;">Foto Sebelum Pembangunan</p>
                        @if(!empty($record->foto_sebelum) && is_array($record->foto_sebelum))
                            <div>
                                @foreach($record->foto_sebelum as $path)
                                    @php
                                        $fullPath = storage_path('app/public/' . $path);
                                        $base64Image = '';
                                        if (file_exists($fullPath)) {
                                            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                                            $data = file_get_contents($fullPath);
                                            $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                        }
                                    @endphp
                                    @if($base64Image)
                                    <img src="{{ $base64Image }}" style="width: 180px; height: 180px; object-fit: cover; margin-right: 5px; margin-bottom: 5px; border: 1px solid #ccc;">
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p style="margin: 0; color: #777; font-style: italic;">Tidak ada foto</p>
                        @endif
                    </td>
                    
                    {{-- Foto Sesudah --}}
                    <td style="width: 50%; vertical-align: top; border: none; padding: 0 0 0 10px; text-align: left;">
                        <p style="margin: 0 0 5px 0; font-weight: bold; color: #16a34a;">Foto Sesudah Pembangunan</p>
                        @if(!empty($record->foto_sesudah) && is_array($record->foto_sesudah))
                            <div>
                                @foreach($record->foto_sesudah as $path)
                                    @php
                                        $fullPath = storage_path('app/public/' . $path);
                                        $base64Image = '';
                                        if (file_exists($fullPath)) {
                                            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                                            $data = file_get_contents($fullPath);
                                            $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                        }
                                    @endphp
                                    @if($base64Image)
                                    <img src="{{ $base64Image }}" style="width: 180px; height: 180px; object-fit: cover; margin-right: 5px; margin-bottom: 5px; border: 1px solid #ccc;">
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p style="margin: 0; color: #777; font-style: italic;">Tidak ada foto</p>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        @endif
    @endforeach
    @endif

    <div style="margin-top: 20px; font-size: 10px; color: #777;">
        <p>Dicetak pada: {{ now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</p>
    </div>
</body>

</html>
