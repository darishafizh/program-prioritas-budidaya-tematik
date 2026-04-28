<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Detail Lokasi Budidaya - {{ $kdmp->nama_kdkmp }}</title>
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

        /* Status badge for PDF */
        .status-on_track { color: #16A34A; font-weight: bold; }
        .status-bermasalah { color: #DC2626; font-weight: bold; }
        .status-selesai { color: #2563EB; font-weight: bold; }
        .status-vakum { color: #D97706; font-weight: bold; }

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
        <h3 style="margin-bottom: 5px;">LAPORAN MONITORING LOKASI BUDIDAYA TEMATIK</h3>
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
            <td class="label">Alamat</td>
            <td class="separator">:</td>
            <td>{{ implode(', ', array_filter([$kdmp->desa ?? null, $kdmp->kabupaten ?? null, $kdmp->provinsi ?? null])) ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Komoditas</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->komoditas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Ketua / Anggota (Telp)</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->ketua_anggota ?? '-' }}{{ $kdmp->no_hp ? ' ('.$kdmp->no_hp.')' : '' }}</td>
        </tr>
        <tr>
            <td class="label">Penyuluh (Telp)</td>
            <td class="separator">:</td>
            <td>{{ $kdmp->nama_penyuluh ?? '-' }}{{ $kdmp->no_hp_penyuluh ? ' ('.$kdmp->no_hp_penyuluh.')' : '' }}</td>
        </tr>
    </table>

    {{-- Ringkasan --}}
    @php
        $totalNilai = $records->sum('nilai_produksi');
        $totalBiaya = $records->sum('biaya_operasional');
        $keuntungan = $totalNilai - $totalBiaya;
    @endphp
    <div class="summary-box">
        <h4>Ringkasan Data</h4>
        <table class="summary-grid">
            <tr>
                <td>Total Laporan</td>
                <td class="val">{{ $records->count() }} laporan</td>
                <td style="width:30px;"></td>
                <td>Total Panen</td>
                <td class="val">{{ number_format($records->sum('volume_panen_kg'), 2, ',', '.') }} kg</td>
            </tr>
            <tr>
                <td>Total Nilai Produksi</td>
                <td class="val">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                <td></td>
                <td>Total Biaya Operasional</td>
                <td class="val">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Keuntungan</td>
                <td class="val" style="color: {{ $keuntungan >= 0 ? '#16A34A' : '#DC2626' }}">Rp {{ number_format($keuntungan, 0, ',', '.') }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    {{-- Tabel Riwayat --}}
    <h4 style="margin-bottom: 5px;">Riwayat Laporan Monitoring</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Periode</th>
                <th>Status</th>
                <th>Volume<br>Panen (kg)</th>
                <th>Nilai<br>Produksi (Rp)</th>
                <th>Biaya<br>Opr (Rp)</th>
                <th>Keuntungan<br>(Rp)</th>
                <th>SR (%)</th>
                <th>Kolam<br>Aktif/Total</th>
                <th>Pembudidaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php
                    $profit = (float)$record->nilai_produksi - (float)$record->biaya_operasional;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $record->bulan_label }} {{ $record->tahun }}</td>
                    <td class="text-center" style="color: {{ $record->status_label === 'On Track' ? '#16A34A' : '#DC2626' }}; font-weight: bold;">{{ $record->status_label }}</td>
                    <td class="text-right">{{ number_format($record->volume_panen_kg, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($record->nilai_produksi, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($record->biaya_operasional, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: {{ $profit >= 0 ? '#16A34A' : '#DC2626' }}">{{ number_format($profit, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $record->survival_rate !== null ? number_format($record->survival_rate, 1) . '%' : '-' }}</td>
                    <td class="text-center">{{ $record->jumlah_kolam_aktif ?? '-' }} / {{ $record->jumlah_kolam_total ?? '-' }}</td>
                    <td class="text-center">{{ $record->jumlah_pembudidaya_aktif ?? '-' }}</td>
                </tr>
                @if($record->kendala || $record->tindak_lanjut || $record->catatan)
                <tr>
                    <td></td>
                    <td colspan="9" style="text-align:left; font-size:10px; background:#fff8f8;">
                        @if($record->kendala)
                            <strong>Kendala:</strong> {{ $record->kendala }}
                        @endif
                        @if($record->tindak_lanjut)
                            <br><strong>Tindak Lanjut:</strong> {{ $record->tindak_lanjut }}
                        @endif
                        @if($record->catatan)
                            <br><strong>Catatan:</strong> {{ $record->catatan }}
                        @endif
                    </td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="10" class="text-center">Belum ada data laporan monitoring.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Dokumentasi Foto dari Progres Fisik --}}
    @if(isset($progresFisikRecords) && $progresFisikRecords->isNotEmpty())
        @php
            $allFotoSesudah = [];
            foreach($progresFisikRecords as $record) {
                if(!empty($record->foto_sesudah) && is_array($record->foto_sesudah)) {
                    $allFotoSesudah = array_merge($allFotoSesudah, $record->foto_sesudah);
                }
            }
            $hasFoto = count($allFotoSesudah) > 0;
        @endphp

        @if($hasFoto)
        <div style="margin-top: 30px;">
            <div style="margin-bottom: 15px;">
                <h4 style="margin: 0; font-size: 16px; font-weight: bold;">Dokumentasi Foto Lokasi</h4>
            </div>
            
            <div style="width: 100%;">
                @foreach($allFotoSesudah as $path)
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
                    <div style="float: left; margin-right: 20px; margin-bottom: 20px;">
                        <img src="{{ $base64Image }}" style="width: 260px; height: 260px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px;">
                    </div>
                    @endif
                @endforeach
                <div style="clear: both;"></div>
            </div>
        </div>
        @endif
    @endif
    <div style="margin-top: 20px; font-size: 10px; color: #777;">
        <p>Dicetak pada: {{ now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</p>
    </div>
</body>

</html>
