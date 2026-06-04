<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export Data Lokasi Budidaya</title>
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
            /* Balance the logo width so text is visually centered */
        }

        .kop-line {
            border: none;
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 5px;
            margin-bottom: 20px;
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
            $path = resource_path('images/logo-kkp.png');
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
                    <h2 style="font-size:16px; margin:0; font-weight:normal;">SEKRETARIAT JENDERAL</h2>
                </td>
            </tr>
        </table>
        <hr class="kop-line">
    </div>

    <div class="page-title">
        <h3 style="margin-bottom: 5px;">DATA PRODUKSI BUDI DAYA TEMATIK BIOFLOK TAHUN ANGGARAN 2025</h3>
        <p style="margin: 0; font-size: 11px;">Periode:
            {{ now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY') }}
        </p>
        @if($search)
            <p style="margin: 2px 0 0; font-size: 11px;">Pencarian: "{{ $search }}"</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Nama Koperasi Desa / Kelurahan Merah Putih (KDKMP)</th>
                {{-- <th colspan="3">Biaya Operasional</th> --}}
                <th colspan="2">Hasil Panen</th>
                <th rowspan="2">Harga Jual / Kg (Rp)</th>
                {{-- <th rowspan="2">Pendapatan</th> --}}
                {{-- <th rowspan="2">Status Kinerja</th> --}}
            </tr>
            <tr>
                {{-- <th>Bibit (Rp)</th> --}}
                {{-- <th>Pakan (Rp)</th> --}}
                {{-- <th>Lainnya (Rp)</th> --}}
                <th>Volume (Kg)</th>
                <th>Nilai Penjualan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kdmpList as $kdmp)
                @php
                    $lastRecord = $kdmp->monitoringRecords->first();
                @endphp
                <tr>
                    <td>
                        <strong>{{ $kdmp->nama_kdkmp }}</strong><br>
                        <span style="font-size: 10px; color: #555;">{{ $kdmp->kabupaten }}, {{ $kdmp->provinsi }}</span>
                    </td>
                    @if($lastRecord)
                        @php
                            $sumVolume = (float) $kdmp->monitoringRecords->sum('volume_panen_kg');
                            $sumNilai = (float) $kdmp->monitoringRecords->sum('nilai_produksi');
                            $hargaJual = $sumVolume > 0 ? ($sumNilai / $sumVolume) : 0;
                        @endphp
                        {{-- <td class="text-right">{{ number_format($lastRecord->biaya_bibit, 0, ',', '.') }}</td> --}}
                        {{-- <td class="text-right">{{ number_format($lastRecord->biaya_pakan, 0, ',', '.') }}</td> --}}
                        {{-- <td class="text-right">{{ number_format($lastRecord->biaya_lainnya, 0, ',', '.') }}</td> --}}
                        <td class="text-right">{{ number_format($sumVolume, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($sumNilai, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($hargaJual, 0, ',', '.') }}</td>
                        {{-- <td class="text-right">{{ number_format($untungRugi, 0, ',', '.') }}</td> --}}
                        {{-- <td class="text-center">{{ $lastRecord->status_label }}</td> --}}
                    @else
                        {{-- <td class="text-center">-</td> --}}
                        {{-- <td class="text-center">-</td> --}}
                        {{-- <td class="text-center">-</td> --}}
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        {{-- <td class="text-center">-</td> --}}
                        {{-- <td class="text-center">-</td> --}}
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @php
                $totalVolume = 0;
                $totalNilai = 0;
                foreach ($kdmpList as $k) {
                    foreach ($k->monitoringRecords as $rec) {
                        $totalVolume += (float) $rec->volume_panen_kg;
                        $totalNilai += (float) $rec->nilai_produksi;
                    }
                }
                $avgHargaJual = $totalVolume > 0 ? ($totalNilai / $totalVolume) : 0;
            @endphp
            <tr style="font-weight: bold; background: #e8e8e8;">
                <td style="text-align: center; font-weight: bold;">TOTAL</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($totalVolume, 2, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($totalNilai, 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($avgHargaJual, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>