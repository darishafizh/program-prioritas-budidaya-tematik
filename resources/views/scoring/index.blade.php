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
<div class="stats-grid mb-6">
    <div class="stat-card">
        <div class="stat-icon success">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['sangat_layak'] }}</span>
            <span class="stat-label">Sangat Layak</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['layak'] }}</span>
            <span class="stat-label">Layak</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['cukup_layak'] }}</span>
            <span class="stat-label">Cukup Layak</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['tidak_layak'] }}</span>
            <span class="stat-label">Tidak Layak</span>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="section-card mb-4">
    <div class="section-body">
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
<div class="section-card mb-4">
    <div class="section-body">
        <form action="{{ route('scoring.index') }}" method="GET" class="grid grid-cols-4 gap-4">
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control form-select">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kabupaten</label>
                <select name="kabupaten" class="form-control form-select">
                    <option value="">Semua Kabupaten</option>
                    @foreach($kabupatenOptions as $kab)
                    <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Provinsi</label>
                <select name="provinsi" class="form-control form-select">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinsiOptions as $prov)
                    <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group flex items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('scoring.index') }}" class="btn btn-outline ml-2">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Scores Table -->
<div class="section-card">
    <div class="section-header">
        <div class="section-icon teal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <h3 class="section-title">Ranking Lokasi</h3>
    </div>
    <div class="section-body p-0">
        @if($scores->count() > 0)
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px">Rank</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th>Provinsi</th>
                        <th style="width:100px;text-align:center">KDMP</th>
                        <th style="width:100px;text-align:center">Masyarakat</th>
                        <th style="width:100px;text-align:center">SPPG</th>
                        <th style="width:100px;text-align:center">Total</th>
                        <th style="width:140px;text-align:center">Status</th>
                        <th style="width:100px;text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scores as $index => $score)
                    <tr>
                        <td class="text-center font-bold">{{ ($scores->currentPage() - 1) * $scores->perPage() + $index + 1 }}</td>
                        <td class="font-semibold">{{ $score->kecamatan }}</td>
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
        
        <div class="p-4">
            {{ $scores->links() }}
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
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.stat-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--shadow-sm);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #10B981; }
.stat-icon.primary { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
.stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
.stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray-500);
}

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

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endpush
