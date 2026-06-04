@extends('layouts.app')

@section('content')
    {{-- ═══ BARIS 1: Judul | Filter + Export ═══ --}}
    <div class="produksi-row1 mb-3">
        <div>
            <h1 class="page-title">Progres Fisik Pembangunan</h1>
            <p class="page-subtitle">Monitoring progres pembangunan infrastruktur 100 KDMP Bioflok</p>
        </div>
        <form method="GET" action="{{ route('progres-fisik.index') }}"
              class="produksi-filter-form">
            <div class="filter-inline-group">
                <label class="filter-label">Bulan</label>
                <select name="bulan" class="filter-select" onchange="this.form.submit()">
                    <option value="">--</option>
                    @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-inline-group">
                <label class="filter-label">Tahun</label>
                <select name="tahun" class="filter-select" onchange="this.form.submit()">
                    <option value="">--</option>
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="search" value="{{ $search }}">
            <a href="{{ route('progres-fisik.index') }}" class="filter-icon-btn filter-reset-btn" title="Reset Filter">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
            <a href="{{ route('progres-fisik.pdf', request()->query()) }}"
               class="filter-icon-btn filter-pdf-btn" target="_blank" title="Export PDF">
                <i class="fa-solid fa-file-pdf"></i> <span class="ms-1 d-none d-sm-inline" style="font-size:0.8rem;font-weight:600;">PDF</span>
            </a>
        </form>
    </div>

    {{-- ═══ BARIS 2: KPI Cards ═══ --}}
    <div class="grid grid-cols-4 mb-3">
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

    {{-- ═══ BARIS 4: Tabel ═══ --}}
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body">
            {{-- Card Header: Title + Add Button --}}
            <div class="dt-card-header">
                <h4 class="dt-card-title">
                    Data Progres Fisik KDMP
                </h4>
                <a href="{{ route('progres-fisik.create') }}" class="btn-tambah-data">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
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
                        <tr data-kdmp-id="{{ $kdmp->hashid }}">
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
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
                                    <a href="{{ route('progres-fisik.show', $kdmp) }}" class="btn btn-sm btn-primary">Detail</a>
                                    <a href="{{ route('progres-fisik.create', ['kdmp_id' => $kdmp->hashid]) }}" class="btn btn-sm btn-outline" title="Tambah data">+</a>
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
    [data-theme="dark"] .filter-reset-btn { background:#1F2937; border-color:#374151; color:#9CA3AF; }

    /* ── Table Card Title ───────────────────────────────────────────────── */
    .table-card-title { color:var(--kkp-navy,#1e3a5f); font-size:1rem; }
    [data-theme="dark"] .table-card-title { color:#E5E7EB !important; }
    .table-period-badge {
        font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:20px;
        background:rgba(16,185,129,0.1); color:#10B981;
        border:1px solid rgba(16,185,129,0.25); margin-left:6px; vertical-align:middle;
    }
    .table thead, .table thead th, .table thead td, .table th {
        color: #ffffff !important; background: var(--kkp-teal, #0891B2) !important;
    }
    .table tbody td {
        color: #111827 !important;
    }
    .table { border: 1px solid #ccc !important; border-collapse: collapse !important; }
    .table th, .table td { border: 1px solid #ccc !important; }

    /* Dark Mode overrides */
    [data-theme="dark"] .table thead, [data-theme="dark"] .table thead th,
    [data-theme="dark"] .table thead td, [data-theme="dark"] .table th {
        color: #E5E7EB !important; background: #1F2937 !important; border-color: #374151 !important;
    }
    [data-theme="dark"] .table tbody td {
        background: var(--bg-surface) !important; color: #D1D5DB !important; border-color: #374151 !important;
    }
    [data-theme="dark"] .table tbody tr:hover td { background: #1F2937 !important; }
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
