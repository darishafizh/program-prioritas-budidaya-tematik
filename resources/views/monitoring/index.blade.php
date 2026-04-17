@extends('layouts.app')

@section('content')
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Monitoring Lokasi Budidaya Tematik</h1>
            <p class="page-subtitle">Pemantauan dan evaluasi perkembangan 100 KDMP Bioflok</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-4 mb-5">
        <div class="kpi-card kpi-produksi">
            <div class="kpi-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <div class="kpi-value">{{ $stats['total_kdmp'] }}</div>
                <div class="kpi-label">TOTAL KDMP</div>
                <div class="kpi-sub">Sudah melapor: {{ $stats['sudah_lapor'] }}</div>
            </div>
        </div>
        <div class="kpi-card kpi-sr success">
            <div class="kpi-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="kpi-value">{{ $stats['on_track'] }}</div>
                <div class="kpi-label">ON TRACK</div>
                <div class="kpi-sub">Berjalan dengan baik</div>
            </div>
        </div>
        <div class="kpi-card kpi-sr warning">
            <div class="kpi-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.963-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div>
                <div class="kpi-value">{{ $stats['bermasalah'] }}</div>
                <div class="kpi-label">BERMASALAH</div>
                <div class="kpi-sub">Perlu evaluasi lebih lanjut</div>
            </div>
        </div>
        <div class="kpi-card kpi-utilisasi">
            <div class="kpi-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                </svg>
            </div>
            <div>
                <div class="kpi-value">{{ number_format($stats['total_panen'], 0, ',', '.') }} <span style="font-size:0.6em;font-weight:600;">kg</span></div>
                <div class="kpi-label">TOTAL PANEN</div>
                <div class="kpi-sub">Total volume panen tercatat</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <form method="GET" action="{{ route('monitoring.index') }}" class="d-flex gap-3 flex-wrap align-items-end">
                <div class="form-group mb-0" style="min-width:200px;">
                    <label class="form-label">Cari KDMP</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Nama, kabupaten, provinsi...">
                </div>
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
                <a href="{{ route('monitoring.index') }}" class="btn btn-outline">Reset</a>
                <a href="{{ route('monitoring.pdf', request()->query()) }}" class="btn btn-primary" target="_blank"
                    style="background:#EF4444; border-color:#EF4444;">
                    <i class="fa-solid fa-file-pdf mr-1"></i> Export PDF
                </a>
            </form>
        </div>
    </div>

    {{-- Tabel KDMP --}}
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body">

            <!-- Toolbar: Add Button -->
            <div class="d-flex justify-content-end mb-3">
                 <a href="{{ route('monitoring.create') }}" class="btn btn-sm btn-success d-flex align-items-center gap-2 rounded-pill px-4 shadow-sm">
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
                            <th rowspan="2"
                                style="width:40px; vertical-align:middle; text-align:center;">
                                No</th>
                            <th rowspan="2"
                                style="vertical-align:middle; text-align:center;">
                                KDKMP</th>
                            <th colspan="2"
                                style="text-align:center;">Hasil Panen
                            </th>
                            <th rowspan="2"
                                style="vertical-align:middle; text-align:center;">
                                Biaya opr</th>
                            <th rowspan="2"
                                style="vertical-align:middle; text-align:center;">
                                Harga Jual</th>
                            <th rowspan="2"
                                style="text-align:center; width:100px; vertical-align:middle;">
                                Aksi</th>
                        </tr>
                        <tr>
                            <th style="text-align:center;">Volume (kg)
                            </th>
                            <th style="text-align:center;">Nilai (Rp)
                            </th>
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
                            @endphp
                            <tr>
                                <td class="text-center fw-bold text-muted">{{ $kdmp->no }}</td>
                                <td>
                                    <div class="fw-bold">{{ $kdmp->nama_kdkmp }}</div>
                                    <div class="text-muted" style="font-size:0.8em;">{{ $kdmp->kabupaten }},
                                        {{ $kdmp->provinsi }}
                                    </div>
                                </td>
                                <td class="text-end">{{ $lastRecord ? number_format($volume, 2, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $lastRecord ? number_format($nilai, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $lastRecord ? number_format($biaya, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">
                                    {{ $lastRecord && $volume > 0 ? number_format($hargaJual, 0, ',', '.') : '-' }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('monitoring.show', $kdmp->id) }}"
                                            class="btn btn-sm btn-primary">Detail</a>
                                        <a href="{{ route('monitoring.create', ['kdmp_id' => $kdmp->id]) }}"
                                            class="btn btn-sm btn-outline" title="Tambah laporan">+</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;padding:2rem;color:var(--gray-400);">Tidak ada KDMP
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
                columnDefs: [{ orderable: false, targets: [6] }]
            });
        });
    </script>
@endpush