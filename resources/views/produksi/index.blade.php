@extends('layouts.app')

@section('content')
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Monitoring Lokasi Budidaya Tematik</h1>
            <p class="page-subtitle">Pemantauan dan evaluasi perkembangan 100 KDMP Bioflok</p>
        </div>
    </div>

    {{-- Stats Cards — Row 1: Lokasi --}}
    <div class="grid grid-cols-3 mb-3">
        <div class="kpi-card kpi-produksi">
            <div class="kpi-icon"><i class="fa-solid fa-building" style="font-size:1rem;"></i></div>
            <div>
                <div class="kpi-value">{{ $stats['total_kdmp'] }}</div>
                <div class="kpi-label">TOTAL KDMP</div>
                <div class="kpi-sub">Sudah melapor: {{ $stats['sudah_lapor'] }}</div>
            </div>
        </div>
        <div class="kpi-card kpi-sr success">
            <div class="kpi-icon"><i class="fa-solid fa-circle-check" style="font-size:1rem;"></i></div>
            <div>
                <div class="kpi-value">{{ $stats['on_track'] }}</div>
                <div class="kpi-label">ON TRACK</div>
                <div class="kpi-sub">Berjalan baik & selesai</div>
            </div>
        </div>
        <div class="kpi-card kpi-sr danger">
            <div class="kpi-icon"><i class="fa-solid fa-triangle-exclamation" style="font-size:1rem;"></i></div>
            <div>
                <div class="kpi-value">{{ $stats['underperforming'] }}</div>
                <div class="kpi-label">UNDERPERFORMING</div>
                <div class="kpi-sub">Bermasalah & vakum</div>
            </div>
        </div>
    </div>

    {{-- Analytical Charts --}}
    <div class="grid grid-cols-2 lg:grid-cols-2 md:grid-cols-1 mb-4 gap-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; height: 100%;">
            <div class="card-body">
                <h5 class="fw-bold mb-1" style="color: var(--kkp-navy); font-size: 1rem;">Trend Rata-rata Produksi ({{ $tahun }})</h5>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Trend rata-rata volume dan nilai produksi seluruh KDKMP aktif</p>
                <div id="trendChart" style="height: 280px;"></div>
            </div>
        </div>
        <div class="card shadow-sm border-0" style="border-radius: 12px; height: 100%;">
            <div class="card-body">
                <h5 class="fw-bold mb-1" style="color: var(--kkp-navy); font-size: 1rem;">Sebaran Performa Seluruh Lokasi</h5>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Titik sebaran 100 KDKMP berdasar Volume (x) dan Nilai Produksi (y)</p>
                <div id="scatterChart" style="height: 280px;"></div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <form method="GET" action="{{ route('produksi.index') }}" class="d-flex gap-3 flex-wrap align-items-end">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-control form-select">
                        @foreach($bulanList as $num => $nama)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-control form-select">
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('produksi.index') }}" class="btn btn-outline">Reset</a>
                <a href="{{ route('produksi.pdf', request()->query()) }}" class="btn btn-primary" target="_blank"
                    style="background:#EF4444; border-color:#EF4444;">
                    <i class="fa-solid fa-file-pdf mr-1"></i> Export PDF
                </a>
            </form>
        </div>
    </div>

    {{-- Tabel KDMP --}}
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body">

            <h4 class="mb-1 fw-bold" style="color: var(--kkp-navy);">Data Produksi KDKMP</h4>

            <!-- Toolbar: Add Button -->
            <div class="d-flex justify-content-end mb-3">
                 <a href="{{ route('produksi.create') }}" class="btn btn-sm btn-success d-flex align-items-center gap-2 rounded-pill px-4 shadow-sm">
                    <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="fw-bold">Tambah Laporan</span>
                </a>
            </div>

            <div class="table-responsive">
                <table id="monitoringTable" class="table table-hover table-sm align-middle w-100 mb-0">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width:40px; vertical-align:middle; text-align:center;">No</th>
                            <th rowspan="2" style="vertical-align:middle; text-align:center;">KDKMP</th>
                            <th rowspan="2" style="vertical-align:middle; text-align:center;">Biaya Produksi (Rp)</th>
                            <th colspan="2" style="text-align:center;">Hasil Panen</th>
                            <th rowspan="2" style="vertical-align:middle; text-align:center;">Harga Jual (Rp)</th>
                            <th rowspan="2" style="vertical-align:middle; text-align:center;">Keuntungan (Rp)</th>
                            <th rowspan="2" style="text-align:center; width:80px; vertical-align:middle;">Detail</th>
                        </tr>
                        <tr>
                            <th style="text-align:center;">Volume (kg)</th>
                            <th style="text-align:center;">Nilai (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kdmpList as $kdmp)
                            @php
                                $lastRecord = $kdmp->monitoringRecords->first();
                                $volume = $lastRecord ? (float) $lastRecord->volume_panen_kg : 0;
                                $nilai = $lastRecord ? (float) $lastRecord->nilai_produksi : 0;
                                $biaya = $lastRecord ? (float) $lastRecord->biaya_operasional : 0;
                                $hargaJual = ($volume > 0) ? ($nilai / $volume) : 0;
                                $keuntungan = $nilai - $biaya;
                                
                                $statusLabel = '-';
                                $statusColor = 'secondary';
                                if ($lastRecord) {
                                    if ($keuntungan >= 15000000) {
                                        $statusLabel = 'On Track';
                                        $statusColor = 'success';
                                    } else {
                                        $statusLabel = 'Underperform';
                                        $statusColor = 'danger';
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="text-center fw-bold text-muted">{{ $kdmp->no }}</td>
                                <td>
                                    <a href="{{ route('produksi.show', $kdmp->id) }}" class="fw-bold text-decoration-none" style="color:var(--kkp-teal)">{{ $kdmp->nama_kdkmp }}</a>
                                    <div class="text-muted" style="font-size:0.8em;">{{ $kdmp->kabupaten }}, {{ $kdmp->provinsi }}</div>
                                </td>
                                <!-- Biaya Produksi -->
                                <td class="text-end">{{ $lastRecord ? number_format($biaya, 0, ',', '.') : '-' }}</td>
                                <!-- Hasil Panen -->
                                <td class="text-end">{{ $lastRecord && $volume > 0 ? number_format($volume, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $lastRecord && $nilai > 0 ? number_format($nilai, 0, ',', '.') : '-' }}</td>
                                <!-- Harga Jual & Keuntungan -->
                                <td class="text-end">{{ $lastRecord && $volume > 0 ? number_format($hargaJual, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">
                                    <div style="color: {{ $keuntungan >= 0 ? 'var(--kkp-teal)' : '#DC2626' }}; font-weight:{{ $lastRecord ? '600' : 'normal' }}; ">
                                        {{ $lastRecord ? number_format($keuntungan, 0, ',', '.') : '-' }}
                                    </div>
                                    @if($lastRecord)
                                    <div>
                                        <span class="badge bg-{{ $statusColor }} text-uppercase rounded-pill" style="font-size:0.65em; padding:0.3em 0.6em;">{{ $statusLabel }}</span>
                                    </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('produksi.show', $kdmp->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;padding:2rem;color:var(--gray-400);">Tidak ada KDMP
                                    ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        /* Table header - theme aware */
        .table thead,
        .table thead th,
        .table thead td,
        .table th {
            color: #333 !important;
            background: #f0f0f0 !important;
        }

        [data-theme="dark"] .table thead,
        [data-theme="dark"] .table thead th,
        [data-theme="dark"] .table thead td,
        [data-theme="dark"] .table th {
            color: #E5E7EB !important;
            background: #1F2937 !important;
            border-color: #374151 !important;
        }

        /* Table body - dark mode */
        [data-theme="dark"] .table tbody td {
            background: var(--bg-surface) !important;
            color: #D1D5DB !important;
            border-color: #374151 !important;
        }

        [data-theme="dark"] .table tbody tr:hover td {
            background: #1F2937 !important;
        }

        /* Visible borders between all columns and rows */
        .table {
            border: 1px solid #ccc !important;
            border-collapse: collapse !important;
        }

        .table th,
        .table td {
            border: 1px solid #ccc !important;
        }

        [data-theme="dark"] .table {
            border-color: #374151 !important;
        }

        [data-theme="dark"] .table th,
        [data-theme="dark"] .table td {
            border-color: #374151 !important;
        }

        /* Fix text-dark in dark mode */
        [data-theme="dark"] .text-dark {
            color: #E5E7EB !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(document).ready(function () {
            $('#monitoringTable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ Data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: { first: "<<", last: ">>", next: ">", previous: "<" }
                },
                pageLength: 25,
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
                orderCellsTop: true,
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: [7] }]
            });

            // ApexCharts Setup
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const textColor = isDark ? '#D1D5DB' : '#374151';
            const gridColor = isDark ? '#374151' : '#E5E7EB';
            
            // Trend Chart
            const trendData = {!! json_encode($chartTrend ?? ['labels'=>[],'avg_volume'=>[],'avg_nilai'=>[], 'avg_harga'=>[]]) !!};
            if(trendData.labels && trendData.labels.length > 0) {
                const trendOptions = {
                    series: [{
                        name: 'Rata-rata Volume (kg)',
                        type: 'column',
                        data: trendData.avg_volume
                    }, {
                        name: 'Rata-rata Nilai (Rp)',
                        type: 'line',
                        data: trendData.avg_nilai
                    }, {
                        name: 'Rata-rata Harga (Rp/kg)',
                        type: 'line',
                        data: trendData.avg_harga
                    }],
                    chart: {
                        height: 280,
                        type: 'line',
                        toolbar: { show: false },
                        fontFamily: 'Manrope, sans-serif'
                    },
                    colors: ['#0891B2', '#10B981', '#F59E0B'],
                    stroke: { width: [0, 3, 3], curve: 'smooth' },
                    dataLabels: { 
                        enabled: false
                    },
                    labels: trendData.labels,
                    xaxis: {
                        labels: { style: { colors: textColor } }
                    },
                    yaxis: [{
                        title: { text: 'Volume (kg)' },
                        labels: { style: { colors: textColor } },
                        seriesName: 'Rata-rata Volume (kg)'
                    }, {
                        opposite: true,
                        title: { text: 'Nilai/Harga (Rp)' },
                        labels: { 
                            style: { colors: textColor }, 
                            formatter: value => {
                                if(value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'Jt';
                                return 'Rp ' + value;
                            }
                        },
                        seriesName: 'Rata-rata Nilai (Rp)'
                    }, {
                        show: false, // share y axis with nilai
                        seriesName: 'Rata-rata Nilai (Rp)'
                    }],
                    grid: { borderColor: gridColor },
                    theme: { mode: isDark ? 'dark' : 'light' }
                };
                new ApexCharts(document.querySelector("#trendChart"), trendOptions).render();
            } else {
                document.querySelector("#trendChart").innerHTML = '<div class="d-flex h-100 align-items-center justify-content-center text-muted">Belum ada data tahun ini</div>';
            }

            // Scatter Plot Seluruh Lokasi
            const scatterSeriesStr = `{!! $chartScatter ?? '[]' !!}`;
            const scatterData = JSON.parse(scatterSeriesStr);
            
            if(scatterData && scatterData.length > 0) {
                const scatterOptions = {
                    series: scatterData,
                    chart: {
                        height: 280,
                        type: 'scatter',
                        zoom: { enabled: true, type: 'xy' },
                        toolbar: { show: true, tools: { download: false } },
                        fontFamily: 'Manrope, sans-serif'
                    },
                    colors: ['#8B5CF6', '#10B981', '#F59E0B', '#EF4444', '#06B6D4', '#3B82F6'],
                    markers: {
                        size: 6,
                        strokeWidth: 1,
                        hover: { size: 8 }
                    },
                    dataLabels: { enabled: false },
                    tooltip: {
                        custom: function({series, seriesIndex, dataPointIndex, w}) {
                            const data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
                            return '<div style="padding: 10px; font-family: Manrope, sans-serif; font-size: 0.8rem; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 6px;">' +
                                '<strong>' + w.globals.initialSeries[seriesIndex].name + '</strong><br/>' +
                                '<div style="margin-top: 5px;">' +
                                'Volume: <strong>' + data[0].toLocaleString() + ' kg</strong><br/>' +
                                'Nilai: <strong>Rp ' + data[1].toLocaleString() + '</strong><br/>' +
                                'Harga Jual: <strong>Rp ' + data[2].toLocaleString() + ' / kg</strong>' +
                                '</div></div>';
                        }
                    },
                    xaxis: {
                        title: { text: 'Volume Produksi (kg)', style: { color: textColor } },
                        labels: { style: { colors: textColor } },
                        tickAmount: 5
                    },
                    yaxis: {
                        title: { text: 'Nilai Produksi (Rp)', style: { color: textColor } },
                        labels: { 
                            style: { colors: textColor },
                            formatter: value => {
                                if(value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'Jt';
                                return value;
                            }
                        },
                        tickAmount: 5
                    },
                    legend: { show: false }, // Hide legend because there are up to 100 series
                    grid: { borderColor: gridColor, xaxis: { lines: { show: true } }, yaxis: { lines: { show: true } } },
                    theme: { mode: isDark ? 'dark' : 'light' }
                };
                new ApexCharts(document.querySelector("#scatterChart"), scatterOptions).render();
            } else {
                document.querySelector("#scatterChart").innerHTML = '<div class="d-flex h-100 align-items-center justify-content-center text-muted">Belum ada data bulan ini</div>';
            }
        });
    </script>
@endpush