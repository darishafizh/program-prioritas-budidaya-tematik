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
    </style>
</head>

<body>
    <div class="page-title">
        <h2>Data Lokasi Budidaya</h2>
        <p>Periode: {{ $bulanList[$bulan] ?? $bulan }} {{ $tahun }}</p>
        @if($search)
            <p>Pencarian: "{{ $search }}"</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">No</th>
                <th rowspan="2">KDKMP</th>
                <th colspan="2">Hasil panen</th>
                <th rowspan="2">Biaya Opr</th>
                <th rowspan="2">Harga jual</th>
            </tr>
            <tr>
                <th>Volume (kg)</th>
                <th>Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kdmpList as $kdmp)
                @php
                    $lastRecord = $kdmp->monitoringRecords->first();
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $kdmp->nama_kdkmp }}</strong><br>
                        <span style="font-size: 10px; color: #555;">{{ $kdmp->kabupaten }}, {{ $kdmp->provinsi }}</span>
                    </td>
                    @if($lastRecord)
                        @php
                            $hargaJual = $lastRecord->volume_panen_kg > 0 ? ($lastRecord->nilai_produksi / $lastRecord->volume_panen_kg) : 0;
                        @endphp
                        <td class="text-right">{{ number_format($lastRecord->volume_panen_kg, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($lastRecord->nilai_produksi, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($lastRecord->biaya_operasional, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($hargaJual, 0, ',', '.') }}</td>
                    @else
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>