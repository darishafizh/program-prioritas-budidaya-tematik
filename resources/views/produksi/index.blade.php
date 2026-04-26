@extends('layouts.app')

@section('content')

    {{-- ═══ BARIS 1: Judul | Filter + Export ═══ --}}
    <div class="produksi-row1 mb-3">
        <div>
            <h1 class="page-title">Monitoring Produksi Budidaya Tematik</h1>
            <p class="page-subtitle">Pemantauan dan evaluasi perkembangan 100 KDMP Bioflok</p>
        </div>
        <form method="GET" action="{{ route('produksi.index') }}"
              class="produksi-filter-form">
            <div class="filter-inline-group">
                <label class="filter-label">Bulan</label>
                <select name="bulan" class="filter-select" onchange="this.form.submit()">
                    @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-inline-group">
                <label class="filter-label">Tahun</label>
                <select name="tahun" class="filter-select" onchange="this.form.submit()">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="search" value="{{ $search }}">
            <a href="{{ route('produksi.index') }}" class="filter-icon-btn filter-reset-btn" title="Reset Filter">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
            <a href="{{ route('produksi.pdf', request()->query()) }}"
               class="filter-icon-btn filter-pdf-btn" target="_blank" title="Export PDF">
                <i class="fa-solid fa-file-pdf"></i> <span class="ms-1 d-none d-sm-inline" style="font-size:0.8rem;font-weight:600;">PDF</span>
            </a>
            <a href="{{ route('produksi.excel', request()->query()) }}"
               class="filter-icon-btn filter-excel-btn" target="_blank" title="Export Excel">
                <i class="fa-solid fa-file-excel"></i> <span class="ms-1 d-none d-sm-inline" style="font-size:0.8rem;font-weight:600;">Excel</span>
            </a>
        </form>
    </div>

    {{-- ═══ BARIS 2: KPI Cards ═══ --}}
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
                <div class="kpi-sub">Berjalan baik &amp; selesai</div>
            </div>
        </div>
        <div class="kpi-card kpi-sr danger">
            <div class="kpi-icon"><i class="fa-solid fa-triangle-exclamation" style="font-size:1rem;"></i></div>
            <div>
                <div class="kpi-value">{{ $stats['underperforming'] }}</div>
                <div class="kpi-label">UNDERPERFORMING</div>
                <div class="kpi-sub">Bermasalah &amp; vakum</div>
            </div>
        </div>
    </div>

    {{-- ═══ BARIS 3: Scatter Chart ═══ --}}
    <div class="scatter-card mb-3">
        <div class="scatter-card-header">
            <span class="scatter-card-title">Sebaran Performa Seluruh Lokasi</span>
            <span class="scatter-period-badge">{{ $bulanList[$bulan] ?? $bulan }} {{ $tahun }}</span>
        </div>
        <p class="scatter-card-subtitle">Titik sebaran KDKMP berdasar Volume Panen (x) dan Nilai Produksi (y)</p>
        <div id="scatterChart" style="height: 300px;"></div>
    </div>

    {{-- ═══ BARIS 4: Tabel ═══ --}}
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body">
            {{-- Card Header: Title + Add Button --}}
            <div class="dt-card-header">
                <h4 class="dt-card-title">
                    Data Produksi KDKMP
                    <span class="table-period-badge">{{ $bulanList[$bulan] ?? $bulan }} {{ $tahun }}</span>
                </h4>
                <a href="{{ route('produksi.create') }}" class="btn-tambah-data">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Laporan
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
                                $volume     = $lastRecord ? (float) $lastRecord->volume_panen_kg : 0;
                                $nilai      = $lastRecord ? (float) $lastRecord->nilai_produksi : 0;
                                $biaya      = $lastRecord ? (float) $lastRecord->biaya_operasional : 0;
                                $hargaJual  = ($volume > 0) ? ($nilai / $volume) : 0;
                                $keuntungan = $nilai - $biaya;
                                $statusLabel = '-'; $statusColor = 'secondary';
                                if ($lastRecord) {
                                    $statusLabel = $keuntungan >= 15000000 ? 'On Track' : 'Underperform';
                                    $statusColor = $keuntungan >= 15000000 ? 'success' : 'danger';
                                }
                            @endphp
                            <tr>
                                <td class="text-center fw-bold text-muted">{{ $kdmp->no }}</td>
                                <td>
                                    <a href="{{ route('produksi.show', $kdmp->id) }}"
                                       class="fw-bold text-decoration-none" style="color:var(--kkp-teal)">
                                        {{ $kdmp->nama_kdkmp }}
                                    </a>
                                    <div class="text-muted" style="font-size:0.8em;">
                                        {{ $kdmp->kabupaten }}, {{ $kdmp->provinsi }}
                                    </div>
                                </td>
                                <td class="text-end">{{ $lastRecord ? number_format($biaya, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $lastRecord && $volume > 0 ? number_format($volume, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $lastRecord && $nilai > 0 ? number_format($nilai, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $lastRecord && $volume > 0 ? number_format($hargaJual, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">
                                    <div style="color:{{ $keuntungan >= 0 ? 'var(--kkp-teal)' : '#DC2626' }};
                                                font-weight:{{ $lastRecord ? '600' : 'normal' }};">
                                        {{ $lastRecord ? number_format($keuntungan, 0, ',', '.') : '-' }}
                                    </div>
                                    @if($lastRecord)
                                        <span class="badge bg-{{ $statusColor }} text-uppercase rounded-pill"
                                              style="font-size:0.65em; padding:0.3em 0.6em;">
                                            {{ $statusLabel }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('produksi.show', $kdmp->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;padding:2rem;color:var(--gray-400);">
                                    Tidak ada data untuk periode ini
                                </td>
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
        /* ── Responsive: Row 1 ──────────────────────────────────────────────── */
        .produksi-row1 {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            min-width: 0;
        }
        .produksi-row1 > div { min-width: 0; flex-shrink: 1; }

        /* ── Filter ─────────────────────────────────────────────────────────── */
        .produksi-filter-form {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            flex-shrink: 0;
            margin-top: 4px;
        }
        .filter-inline-group { display:flex; align-items:center; gap:6px; }
        .filter-label {
            font-size:0.78rem; font-weight:600;
            color:var(--gray-600,#6B7280); white-space:nowrap; margin:0;
        }
        .filter-select {
            font-size:0.82rem; padding:5px 10px; border-radius:8px;
            border:1px solid var(--gray-300,#D1D5DB);
            background:var(--bg-surface,#fff); color:var(--gray-800,#1F2937);
            cursor:pointer; transition:border-color 0.2s;
        }
        .filter-select:focus {
            outline:none; border-color:var(--kkp-teal,#0891B2);
            box-shadow:0 0 0 3px rgba(8,145,178,0.15);
        }
        [data-theme="dark"] .filter-select { background:#1F2937; border-color:#374151; color:#E5E7EB; }

        .filter-icon-btn {
            display:inline-flex; align-items:center; justify-content:center;
            height:34px; padding:0 10px; border-radius:8px;
            font-size:0.82rem; text-decoration:none; transition:background 0.2s, color 0.2s;
        }
        .filter-reset-btn {
            background:var(--gray-100,#F3F4F6); color:var(--gray-600,#6B7280);
            border:1px solid var(--gray-300,#D1D5DB);
        }
        .filter-reset-btn:hover { background:var(--gray-200,#E5E7EB); color:var(--gray-800); }
        .filter-pdf-btn { background:#EF4444; color:#fff; border:1px solid #EF4444; }
        .filter-pdf-btn:hover { background:#DC2626; color:#fff; }
        .filter-excel-btn { background:#10B981; color:#fff; border:1px solid #10B981; }
        .filter-excel-btn:hover { background:#059669; color:#fff; }
        [data-theme="dark"] .filter-reset-btn { background:#1F2937; border-color:#374151; color:#9CA3AF; }

        /* ── Scatter Card ───────────────────────────────────────────────────── */
        .scatter-card {
            background:var(--bg-surface,#fff); border:1px solid var(--gray-200,#E5E7EB);
            border-radius:12px; padding:16px 18px 10px;
            box-shadow:0 1px 4px rgba(0,0,0,0.06); transition:background 0.3s, border-color 0.3s;
        }
        [data-theme="dark"] .scatter-card { background:#111827 !important; border-color:#1F2937 !important; }
        .scatter-card-header { display:flex; align-items:center; gap:8px; margin-bottom:3px; }
        .scatter-card-title {
            font-weight:700; font-size:0.95rem; color:var(--kkp-navy,#1e3a5f); transition:color 0.3s;
        }
        [data-theme="dark"] .scatter-card-title { color:#E5E7EB !important; }
        .scatter-period-badge {
            font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:20px;
            background:rgba(8,145,178,0.1); color:var(--kkp-teal,#0891B2);
            border:1px solid rgba(8,145,178,0.2);
        }
        [data-theme="dark"] .scatter-period-badge {
            background:rgba(8,145,178,0.15); color:#22D3EE; border-color:rgba(8,145,178,0.3);
        }
        .scatter-card-subtitle { font-size:0.75rem; color:var(--gray-500,#6B7280); margin-bottom:6px; }

        /* ApexCharts transparent background */
        .apexcharts-canvas, .apexcharts-svg { background:transparent !important; }

        /* ── Table Card Title ───────────────────────────────────────────────── */
        .table-card-title { color:var(--kkp-navy,#1e3a5f); font-size:1rem; }
        [data-theme="dark"] .table-card-title { color:#E5E7EB !important; }
        .table-period-badge {
            font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:20px;
            background:rgba(16,185,129,0.1); color:#10B981;
            border:1px solid rgba(16,185,129,0.25); margin-left:6px; vertical-align:middle;
        }

        /* ── Table head / body (sama dengan progres-fisik) ─────────────────── */
        .table thead, .table thead th, .table thead td, .table th {
            color: #333 !important; background: #f0f0f0 !important;
        }
        [data-theme="dark"] .table thead, [data-theme="dark"] .table thead th,
        [data-theme="dark"] .table thead td, [data-theme="dark"] .table th {
            color: #E5E7EB !important; background: #1F2937 !important; border-color: #374151 !important;
        }
        [data-theme="dark"] .table tbody td {
            background: var(--bg-surface) !important; color: #D1D5DB !important; border-color: #374151 !important;
        }
        [data-theme="dark"] .table tbody tr:hover td { background: #1F2937 !important; }
        .table { border: 1px solid #ccc !important; border-collapse: collapse !important; }
        .table th, .table td { border: 1px solid #ccc !important; }
        [data-theme="dark"] .table { border-color: #374151 !important; }
        [data-theme="dark"] .table th, [data-theme="dark"] .table td { border-color: #374151 !important; }
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
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: { first: "<<", last: ">>", next: ">", previous: "<" }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                pageLength: 10,
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
                orderCellsTop: true,
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: [7] }]
            });

            // ── Scatter Chart ─────────────────────────────────────────────────────
            const scatterRaw  = `{!! $chartScatter ?? '[]' !!}`;
            const scatterData = JSON.parse(scatterRaw);
            let scatterChart  = null;

            function getThemeVars() {
                const dark = document.documentElement.getAttribute('data-theme') === 'dark';
                return {
                    isDark    : dark,
                    mode      : dark ? 'dark' : 'light',
                    textColor : dark ? '#D1D5DB' : '#374151',
                    gridColor : dark ? '#374151' : '#E5E7EB',
                };
            }

            function renderScatterChart() {
                const tv = getThemeVars();
                const hasData = scatterData.some(s => s.data && s.data.length > 0);
                if (scatterChart) { scatterChart.destroy(); scatterChart = null; }
                const el = document.querySelector('#scatterChart');
                if (!hasData) {
                    el.innerHTML = '<div class="d-flex h-100 align-items-center justify-content-center text-muted" style="font-size:0.85rem;">Belum ada data untuk periode ini</div>';
                    return;
                }
                scatterChart = new ApexCharts(el, {
                    series: scatterData,
                    chart: {
                        height: 300, type: 'scatter',
                        background: 'transparent',
                        zoom: { enabled: true, type: 'xy' },
                        toolbar: { show: true, tools: { download: false } },
                        fontFamily: 'Poppins, sans-serif',
                        animations: { enabled: true, speed: 400 },
                    },
                    theme: { mode: tv.mode },
                    colors: ['#10B981', '#EF4444'],
                    markers: { size: 6, strokeWidth: 1, hover: { size: 9 } },
                    dataLabels: { enabled: false },
                    tooltip: {
                        custom: function({ seriesIndex, dataPointIndex, w }) {
                            const point  = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
                            const status = w.globals.initialSeries[seriesIndex].name;
                            const color  = w.globals.colors[seriesIndex];
                            const bg     = tv.isDark ? '#1F2937' : '#ffffff';
                            const txt    = tv.isDark ? '#E5E7EB' : '#111827';
                            return `<div style="padding:10px;font-family:Poppins,sans-serif;font-size:0.8rem;
                                        background:${bg};color:${txt};border:1px solid ${color};
                                        border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                                <strong>${point.kdmpName}</strong>
                                <span style="font-size:0.65rem;padding:2px 6px;border-radius:4px;
                                    color:#fff;background:${color};margin-left:4px;">${status}</span>
                                <div style="margin-top:6px;line-height:1.8;">
                                    Volume: <strong>${point.x.toLocaleString('id-ID')} kg</strong><br/>
                                    Nilai: <strong>Rp ${point.y.toLocaleString('id-ID')}</strong><br/>
                                    Harga Jual: <strong>Rp ${point.hargaJual.toLocaleString('id-ID')} / kg</strong>
                                </div>
                            </div>`;
                        }
                    },
                    xaxis: {
                        title: { text: 'Volume Produksi (kg)', style: { color: tv.textColor } },
                        labels: { style: { colors: tv.textColor } },
                        tickAmount: 5,
                    },
                    yaxis: {
                        title: { text: 'Nilai Produksi (Rp)', style: { color: tv.textColor } },
                        labels: {
                            style: { colors: tv.textColor },
                            formatter: v => v >= 1000000 ? 'Rp ' + (v / 1000000).toFixed(1) + 'Jt' : v,
                        },
                        tickAmount: 5,
                    },
                    legend: { show: true, labels: { colors: tv.textColor } },
                    grid: {
                        borderColor: tv.gridColor,
                        xaxis: { lines: { show: true } },
                        yaxis: { lines: { show: true } },
                    },
                });
                scatterChart.render();
            }

            renderScatterChart();

            const _origToggleTheme = window.toggleTheme;
            window.toggleTheme = function () {
                _origToggleTheme();
                setTimeout(() => { renderScatterChart(); }, 50);
            };
        });
    </script>
@endpush