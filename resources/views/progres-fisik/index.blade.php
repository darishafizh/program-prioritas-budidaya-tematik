@extends('layouts.app')

@section('content')
<div class="page-header-row">
    <div>
        <h1 class="page-title">Progres Fisik Pembangunan</h1>
        <p class="page-subtitle">Monitoring progres pembangunan infrastruktur 100 KDMP Bioflok</p>
    </div>
</div>

{{-- KPI Summary Cards --}}
<div class="grid grid-cols-4 mb-5">
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
            <div class="kpi-value">{{ $stats['selesai'] }}</div>
            <div class="kpi-label">SELESAI</div>
            <div class="kpi-sub">Progres 100%</div>
        </div>
    </div>
    <div class="kpi-card kpi-aktif">
        <div class="kpi-icon"><i class="fa-solid fa-hammer" style="font-size:1rem;"></i></div>
        <div>
            <div class="kpi-value">{{ $stats['berjalan'] }}</div>
            <div class="kpi-label">SEDANG BERJALAN</div>
            <div class="kpi-sub">Progres ≥ 50%</div>
        </div>
    </div>
    <div class="kpi-card kpi-perkolam">
        <div class="kpi-icon"><i class="fa-solid fa-chart-simple" style="font-size:1rem;"></i></div>
        <div>
            <div class="kpi-value">{{ $stats['rata_rata'] }}%</div>
            <div class="kpi-label">RATA-RATA PROGRES</div>
            <div class="kpi-sub">Seluruh lokasi periode ini</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
    <div class="card-body">
        <form method="GET" action="{{ route('progres-fisik.index') }}" class="d-flex gap-3 flex-wrap align-items-end">
            <div class="form-group mb-0" style="min-width:200px;">
                <label class="form-label">Cari KDMP</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Nama, kabupaten, provinsi...">
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
            <a href="{{ route('progres-fisik.index') }}" class="btn btn-outline">Reset</a>
            <a href="{{ route('progres-fisik.pdf', request()->query()) }}" class="btn btn-primary" target="_blank" style="background:#EF4444; border-color:#EF4444;">
                <i class="fa-solid fa-file-pdf mr-1"></i> Export PDF
            </a>
        </form>
    </div>
</div>

{{-- Tabel KDMP --}}
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body">
        {{-- Card Header: Title + Add Button --}}
        <div class="dt-card-header">
            <h4 class="dt-card-title">Data Progres Fisik KDMP</h4>
            <a href="{{ route('progres-fisik.create') }}" class="btn-tambah-data">
                <i class="fa-solid fa-plus"></i>
                Tambah Data Progres
            </a>
        </div>

        <div class="table-responsive">
            <table id="progresFisikTable" class="table table-hover table-sm align-middle w-100 mb-0">
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th style="text-align:center;">KDKMP</th>
                        <th style="text-align:center;">Bangunan</th>
                        <th style="text-align:center;">Kolam</th>
                        <th style="text-align:center;">Listrik</th>
                        <th style="text-align:center;">Air</th>
                        <th style="text-align:center;">Aerasi</th>
                        <th style="text-align:center;">Rata-rata</th>
                        <th style="text-align:center; width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kdmpList as $kdmp)
                        @php
                            $lastRecord = $kdmp->progresFisikRecords->first();
                            $avg = $lastRecord ? $lastRecord->average_progress : 0;
                        @endphp
                        <tr data-kdmp-id="{{ $kdmp->id }}">
                            <td class="text-center fw-bold text-muted">{{ $kdmp->no }}</td>
                            <td>
                                <div class="fw-bold">{{ $kdmp->nama_kdkmp }}</div>
                                <div class="text-muted" style="font-size:0.8em;">{{ $kdmp->kabupaten }}, {{ $kdmp->provinsi }}</div>
                            </td>
                            <td class="text-center">
                                @if($lastRecord)
                                    <div class="d-flex align-items-center gap-1 justify-content-center">
                                        <div style="width:50px;height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden;">
                                            <div style="width:{{ $lastRecord->progres_bangunan }}%;height:100%;background:#0891B2;border-radius:3px;"></div>
                                        </div>
                                        <span style="font-size:0.75rem;font-weight:600;">{{ $lastRecord->progres_bangunan }}%</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lastRecord)
                                    <div class="d-flex align-items-center gap-1 justify-content-center">
                                        <div style="width:50px;height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden;">
                                            <div style="width:{{ $lastRecord->progres_kolam }}%;height:100%;background:#10B981;border-radius:3px;"></div>
                                        </div>
                                        <span style="font-size:0.75rem;font-weight:600;">{{ $lastRecord->progres_kolam }}%</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lastRecord)
                                    <div class="d-flex align-items-center gap-1 justify-content-center">
                                        <div style="width:50px;height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden;">
                                            <div style="width:{{ $lastRecord->progres_listrik }}%;height:100%;background:#F59E0B;border-radius:3px;"></div>
                                        </div>
                                        <span style="font-size:0.75rem;font-weight:600;">{{ $lastRecord->progres_listrik }}%</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lastRecord)
                                    <div class="d-flex align-items-center gap-1 justify-content-center">
                                        <div style="width:50px;height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden;">
                                            <div style="width:{{ $lastRecord->progres_air }}%;height:100%;background:#3B82F6;border-radius:3px;"></div>
                                        </div>
                                        <span style="font-size:0.75rem;font-weight:600;">{{ $lastRecord->progres_air }}%</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lastRecord)
                                    <div class="d-flex align-items-center gap-1 justify-content-center">
                                        <div style="width:50px;height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden;">
                                            <div style="width:{{ $lastRecord->progres_aerasi }}%;height:100%;background:#8B5CF6;border-radius:3px;"></div>
                                        </div>
                                        <span style="font-size:0.75rem;font-weight:600;">{{ $lastRecord->progres_aerasi }}%</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lastRecord)
                                    <span class="badge badge-{{ $avg >= 100 ? 'success' : ($avg >= 50 ? 'primary' : ($avg > 0 ? 'warning' : 'secondary')) }}" style="font-size:0.8rem; padding:0.3rem 0.6rem; border-radius:var(--radius-full); background:{{ $avg >= 100 ? 'rgba(16,185,129,0.1)' : ($avg >= 50 ? 'rgba(59,130,246,0.1)' : ($avg > 0 ? 'rgba(245,158,11,0.1)' : 'var(--gray-100)')) }}; color:{{ $avg >= 100 ? '#059669' : ($avg >= 50 ? '#2563EB' : ($avg > 0 ? '#D97706' : 'var(--gray-500)')) }}; font-weight:700;">
                                        {{ $avg }}%
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('progres-fisik.show', $kdmp->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                    <a href="{{ route('progres-fisik.create', ['kdmp_id' => $kdmp->id]) }}" class="btn btn-sm btn-outline" title="Tambah data">+</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
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
    .highlight-row td {
        background: rgba(8, 145, 178, 0.12) !important;
        transition: background 2s ease;
    }
    [data-theme="dark"] .highlight-row td {
        background: rgba(8, 145, 178, 0.2) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        var table = $('#progresFisikTable').DataTable({
            stateSave: true,
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
            order: [[0, 'asc']],
            columnDefs: [{ orderable: false, targets: [8] }]
        });

        // Highlight and scroll to specific KDMP row
        var urlParams = new URLSearchParams(window.location.search);
        var highlightId = urlParams.get('highlight');
        if (highlightId) {
            // Find the row with the matching kdmp id
            var targetRow = null;
            var targetIndex = -1;
            table.rows().every(function(rowIdx) {
                var node = this.node();
                if ($(node).data('kdmp-id') == highlightId) {
                    targetRow = node;
                    targetIndex = rowIdx;
                    return false;
                }
            });

            if (targetRow && targetIndex >= 0) {
                // Calculate which page this row is on and navigate to it
                var pageInfo = table.page.info();
                var pageLength = pageInfo.length;
                if (pageLength > 0) {
                    var targetPage = Math.floor(targetIndex / pageLength);
                    table.page(targetPage).draw(false);
                }

                // Highlight and scroll
                setTimeout(function() {
                    $(targetRow).addClass('highlight-row');
                    targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Remove highlight after 3 seconds
                    setTimeout(function() {
                        $(targetRow).removeClass('highlight-row');
                    }, 3000);
                }, 300);
            }

            // Clean URL
            var cleanUrl = window.location.pathname + window.location.search.replace(/[?&]highlight=[^&]+/, '').replace(/^&/, '?');
            if (cleanUrl.endsWith('?')) cleanUrl = cleanUrl.slice(0, -1);
            window.history.replaceState({}, '', cleanUrl);
        }
    });
</script>
@endpush
