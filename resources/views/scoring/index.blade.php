@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Skor Kelayakan Lokasi</h1>
        <p class="page-subtitle">Penilaian kelayakan lokasi untuk Budidaya Tematik Bioflok</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'Scoring', 'url' => '#']
    ]" />
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-4 mb-5">
    <div class="kpi-card kpi-aktif">
        <div class="kpi-icon">
            <svg fill="none" class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <div class="kpi-value">{{ $stats['sangat_layak'] }}</div>
            <div class="kpi-label">SANGAT LAYAK</div>
        </div>
    </div>
    <div class="kpi-card kpi-perkolam">
        <div class="kpi-icon">
            <svg fill="none" class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <div class="kpi-value">{{ $stats['layak'] }}</div>
            <div class="kpi-label">LAYAK</div>
        </div>
    </div>
    <div class="kpi-card kpi-sr warning">
        <div class="kpi-icon">
            <svg fill="none" class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <div class="kpi-value">{{ $stats['cukup_layak'] }}</div>
            <div class="kpi-label">CUKUP LAYAK</div>
        </div>
    </div>
    <div class="kpi-card kpi-sr danger">
        <div class="kpi-icon">
            <svg fill="none" class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <div class="kpi-value">{{ $stats['tidak_layak'] }}</div>
            <div class="kpi-label">TIDAK LAYAK</div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
    <div class="card-body">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <div class="flex gap-2">
                <form action="{{ route('scoring.generate') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Generate dari Survey
                    </button>
                </form>
                <form action="{{ route('scoring.recalculate-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-outline">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Hitung Ulang Semua
                    </button>
                </form>
            </div>
            <a href="{{ route('scoring.export') }}" class="btn btn-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
    <div class="card-body">
        <form action="{{ route('scoring.index') }}" method="GET" class="d-flex gap-3 flex-wrap align-items-end">
            <div class="form-group mb-0" style="min-width:200px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-control form-select">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-0" style="min-width:200px;">
                <label class="form-label">Kabupaten</label>
                <select name="kabupaten" class="form-control form-select">
                    <option value="">Semua Kabupaten</option>
                    @foreach($kabupatenOptions as $kab)
                    <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-0" style="min-width:200px;">
                <label class="form-label">Provinsi</label>
                <select name="provinsi" class="form-control form-select">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinsiOptions as $prov)
                    <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('scoring.index') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>
</div>

<!-- Scores Table -->
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-header border-bottom-0 pb-0 pt-4">
        <div class="d-flex align-items-center gap-2">
            <div class="section-icon teal" style="background: rgba(13, 148, 136, 0.1); color: #0D9488; padding: 8px; border-radius: 8px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="mb-0 fw-bold" style="font-size: 1.2rem;">Ranking Lokasi</h3>
        </div>
    </div>
    <div class="card-body p-0 mt-3">
        @if($scores->count() > 0)
        <div class="table-responsive">
            <table id="scoringTable" class="table table-hover w-100 table-sm align-middle">
                <thead>
                    <tr>
                        <th class="text-center" width="40">Rank</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th>Provinsi</th>
                        <th class="text-center">KDMP</th>
                        <th class="text-center">Masyarakat</th>
                        <th class="text-center">SPPG</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scores as $index => $score)
                    <tr>
                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $score->kecamatan }}</td>
                        <td>{{ $score->kabupaten }}</td>
                        <td>{{ $score->provinsi }}</td>
                        <td class="text-center">
                            <span class="score-badge {{ $score->kdmp_score >= 70 ? 'high' : ($score->kdmp_score >= 50 ? 'medium' : 'low') }}">
                                {{ number_format($score->kdmp_score, 1) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="score-badge {{ $score->masyarakat_score >= 70 ? 'high' : ($score->masyarakat_score >= 50 ? 'medium' : 'low') }}">
                                {{ number_format($score->masyarakat_score, 1) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="score-badge {{ $score->sppg_score >= 70 ? 'high' : ($score->sppg_score >= 50 ? 'medium' : 'low') }}">
                                {{ number_format($score->sppg_score, 1) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="total-score">{{ number_format($score->total_score, 1) }}</span>
                        </td>
                        <td class="text-center">
                            <span class="status-badge {{ $score->status_color }}">
                                {{ $score->status_icon }} {{ $score->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('scoring.show', $score) }}" class="btn btn-sm btn-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;color:var(--gray-400);margin-bottom:1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3>Belum Ada Data Skor</h3>
            <p>Klik tombol "Generate dari Survey" untuk menghitung skor dari data survey yang sudah ada.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>

.score-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.8rem;
}

.score-badge.high { background: rgba(16, 185, 129, 0.1); color: #10B981; }
.score-badge.medium { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
.score-badge.low { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

.total-score {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--kkp-navy);
}

.status-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: 0.75rem;
    white-space: nowrap;
}

.status-badge.success { background: rgba(16, 185, 129, 0.1); color: #059669; }
.status-badge.primary { background: rgba(59, 130, 246, 0.1); color: #2563EB; }
.status-badge.warning { background: rgba(245, 158, 11, 0.1); color: #D97706; }
.status-badge.danger { background: rgba(239, 68, 68, 0.1); color: #DC2626; }

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--gray-500);
}

.empty-state h3 {
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

/* Table modifications */
.table-sm td, .table-sm th {
    padding: 0.75rem 0.75rem;
    vertical-align: middle;
    font-size: 0.875rem;
}

/* Dark mode support */
[data-theme="dark"] table.dataTable thead th,
[data-theme="dark"] table.dataTable thead td,
[data-theme="dark"] .table thead th,
[data-theme="dark"] .table thead td {
    background: #164E63 !important;
    color: #E0F2FE !important;
    border-bottom: none !important;
}

[data-theme="dark"] table.dataTable tbody td {
    background: var(--bg-surface) !important;
    color: #D1D5DB !important;
    border-color: #374151 !important;
}

[data-theme="dark"] table.dataTable tbody tr:hover td {
    background: #1F2937 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#scoringTable').DataTable({
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
        order: [[0, 'asc']],
        columnDefs: [
            { className: "text-center", targets: [0] },
            { orderable: false, targets: [9] }
        ]
    });
});
</script>
@endpush
