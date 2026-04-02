@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Dashboard Monitoring</h1>
        <p class="page-subtitle">Visualisasi data kuesioner budidaya ikan tematik</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')]
    ]" />
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-4 mb-5">
    <!-- Total Kuesioner -->
    <div class="stat-card card-gradient-teal">
        <div class="stat-card-content">
            <h3>Total Kuesioner</h3>
            <div class="stat-card-value">{{ number_format($totalKuesioner) }}</div>
            <div class="flex gap-2 mt-3" style="font-size:0.75rem; opacity:0.9;">
                <span style="background:rgba(255,255,255,0.2); padding:2px 8px; border-radius:4px;">KDMP: {{ $totalKdmp }}</span>
                <span style="background:rgba(255,255,255,0.2); padding:2px 8px; border-radius:4px;">Masy: {{ $totalMasyarakat }}</span>
                <span style="background:rgba(255,255,255,0.2); padding:2px 8px; border-radius:4px;">SPPG: {{ $totalSppg }}</span>
            </div>
        </div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
    </div>

    <!-- Koperasi Terdata -->
    <div class="stat-card card-gradient-success">
        <div class="stat-card-content">
            <h3>Koperasi Terdata</h3>
            <div class="stat-card-value">{{ number_format($totalKoperasi) }}</div>
        </div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
    </div>

    <!-- Total Pembudidaya -->
    <div class="stat-card card-gradient-navy">
        <div class="stat-card-content">
            <h3>Total Pembudidaya</h3>
            <div class="stat-card-value">{{ number_format($totalPembudidaya) }}</div>
        </div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Rata-rata Progres -->
    <div class="stat-card card-gradient-warning">
        <div class="stat-card-content">
            <h3>Rata-rata Progres</h3>
            <div class="stat-card-value">{{ number_format($avgProgress, 1) }}%</div>
        </div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-5">
    <div class="card-body">
        <h2 class="card-title mb-4">Aksi Cepat</h2>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('kdmp.create') }}" class="btn btn-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Kuesioner KDMP
            </a>
            <a href="{{ route('masyarakat.create') }}" class="btn btn-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Kuesioner Masyarakat
            </a>
            <a href="{{ route('sppg.create') }}" class="btn btn-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Kuesioner SPPG
            </a>
        </div>
    </div>
</div>

<!-- Scoring Section -->
<div class="section-card mb-5">
    <div class="section-header">
        <div class="section-icon teal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <h3 class="section-title">Skor Kelayakan Lokasi</h3>
    </div>
    <div class="section-body">
        <!-- Scoring Stats -->
        <div class="grid grid-cols-5 gap-4 mb-4">
            <div class="scoring-stat-card">
                <span class="scoring-stat-number">{{ $scoringStats['total'] }}</span>
                <span class="scoring-stat-label">Total Lokasi</span>
            </div>
            <div class="scoring-stat-card success">
                <span class="scoring-stat-number">{{ $scoringStats['sangat_layak'] }}</span>
                <span class="scoring-stat-label">🟢 Sangat Layak</span>
            </div>
            <div class="scoring-stat-card primary">
                <span class="scoring-stat-number">{{ $scoringStats['layak'] }}</span>
                <span class="scoring-stat-label">🔵 Layak</span>
            </div>
            <div class="scoring-stat-card warning">
                <span class="scoring-stat-number">{{ $scoringStats['cukup_layak'] }}</span>
                <span class="scoring-stat-label">🟡 Cukup Layak</span>
            </div>
            <div class="scoring-stat-card danger">
                <span class="scoring-stat-number">{{ $scoringStats['tidak_layak'] }}</span>
                <span class="scoring-stat-label">🔴 Tidak Layak</span>
            </div>
        </div>

        @if($topLocations->count() > 0)
        <!-- Top Locations Table -->
        <h4 style="font-weight:600; margin-bottom:1rem; color:var(--gray-700);">Top 5 Lokasi Terbaik</h4>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px">Rank</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th style="width:80px;text-align:center">KDMP</th>
                        <th style="width:90px;text-align:center">Masyarakat</th>
                        <th style="width:80px;text-align:center">SPPG</th>
                        <th style="width:80px;text-align:center">Total</th>
                        <th style="width:130px;text-align:center">Status</th>
                        <th style="width:80px;text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topLocations as $index => $score)
                    <tr>
                        <td class="text-center font-bold">{{ $index + 1 }}</td>
                        <td class="font-semibold">{{ $score->kecamatan }}</td>
                        <td>{{ $score->kabupaten }}</td>
                        <td class="text-center">
                            <span class="score-badge {{ $score->kdmp_score >= 70 ? 'high' : ($score->kdmp_score >= 50 ? 'medium' : 'low') }}">
                                {{ number_format($score->kdmp_score, 0) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="score-badge {{ $score->masyarakat_score >= 70 ? 'high' : ($score->masyarakat_score >= 50 ? 'medium' : 'low') }}">
                                {{ number_format($score->masyarakat_score, 0) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="score-badge {{ $score->sppg_score >= 70 ? 'high' : ($score->sppg_score >= 50 ? 'medium' : 'low') }}">
                                {{ number_format($score->sppg_score, 0) }}
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
                            <a href="{{ route('scoring.show', $score) }}" class="btn btn-sm btn-primary">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-between items-center">
            <form action="{{ route('scoring.generate') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Generate/Update Skor
                </button>
            </form>
            <a href="{{ route('scoring.index') }}" class="btn btn-primary btn-sm">Lihat Semua Lokasi →</a>
        </div>
        @else
        <div class="empty-state-mini">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:40px;height:40px;color:var(--gray-400);margin-bottom:0.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <p style="color:var(--gray-600); margin-bottom:1rem;">Belum ada data skor. Klik tombol di bawah untuk generate dari survey KDMP.</p>
            <form action="{{ route('scoring.generate') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Generate Skor dari Survey
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-2 mb-5">
    <!-- Komoditas Chart -->
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-4">Sebaran Komoditas</h3>
            <div style="height: 250px;">
                <canvas id="komoditasChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Progress Chart -->
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-4">Progres Pembangunan</h3>
            <div style="height: 250px;">
                <canvas id="progresChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Hambatan Chart -->
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-4">Hambatan Koperasi</h3>
            <div style="height: 250px;">
                <canvas id="hambatanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Instalasi Chart -->
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-4">Status Instalasi</h3>
            <div style="height: 250px;">
                <canvas id="instalasiChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="card mb-5">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="card-title flex items-center gap-2">
                    <svg style="width:20px;height:20px;color:#0891B2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Peta Lokasi KDMP
                </h3>
                <p class="text-muted text-sm">Sebaran titik lokasi Koperasi Desa Merah Putih</p>
            </div>
            <span class="badge badge-teal">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                </svg>
                {{ $mapLocations->count() }} lokasi
            </span>
        </div>
        <div id="kdmpMap" style="height: 400px; border-radius: var(--radius-lg); overflow: hidden;"></div>
    </div>
</div>

<!-- Data Tables Links -->
<div class="grid grid-cols-3">
    <a href="{{ route('kdmp.index') }}" class="card" style="text-decoration:none;">
        <div class="card-body flex items-center gap-4">
            <div style="width:56px;height:56px;background:linear-gradient(135deg,#0891B2,#06B6D4);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;">
                <svg style="width:28px;height:28px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 style="margin:0;color:var(--gray-800);">Kuesioner KDMP</h3>
                <p class="text-muted text-sm" style="margin:0;">{{ $totalKdmp }} data tersimpan</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('masyarakat.index') }}" class="card" style="text-decoration:none;">
        <div class="card-body flex items-center gap-4">
            <div style="width:56px;height:56px;background:linear-gradient(135deg,#10B981,#059669);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;">
                <svg style="width:28px;height:28px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 style="margin:0;color:var(--gray-800);">Kuesioner Masyarakat</h3>
                <p class="text-muted text-sm" style="margin:0;">{{ $totalMasyarakat }} data tersimpan</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('sppg.index') }}" class="card" style="text-decoration:none;">
        <div class="card-body flex items-center gap-4">
            <div style="width:56px;height:56px;background:linear-gradient(135deg,#F59E0B,#D97706);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;">
                <svg style="width:28px;height:28px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h3 style="margin:0;color:var(--gray-800);">Kuesioner SPPG</h3>
                <p class="text-muted text-sm" style="margin:0;">{{ $totalSppg }} data tersimpan</p>
            </div>
        </div>
    </a>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.scoring-stat-card {
    background: var(--gray-100);
    border-radius: var(--radius-lg);
    padding: 1rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.scoring-stat-card.success { background: rgba(16, 185, 129, 0.1); }
.scoring-stat-card.primary { background: rgba(59, 130, 246, 0.1); }
.scoring-stat-card.warning { background: rgba(245, 158, 11, 0.1); }
.scoring-stat-card.danger { background: rgba(239, 68, 68, 0.1); }

.scoring-stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-800);
}

.scoring-stat-label {
    font-size: 0.8rem;
    color: var(--gray-600);
}

.score-badge {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.75rem;
}

.score-badge.high { background: rgba(16, 185, 129, 0.1); color: #10B981; }
.score-badge.medium { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
.score-badge.low { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

.total-score {
    font-size: 1rem;
    font-weight: 700;
    color: var(--kkp-navy);
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: 0.7rem;
    white-space: nowrap;
}

.status-badge.success { background: rgba(16, 185, 129, 0.1); color: #059669; }
.status-badge.primary { background: rgba(59, 130, 246, 0.1); color: #2563EB; }
.status-badge.warning { background: rgba(245, 158, 11, 0.1); color: #D97706; }
.status-badge.danger { background: rgba(239, 68, 68, 0.1); color: #DC2626; }

.empty-state-mini {
    text-align: center;
    padding: 2rem;
}

@media (max-width: 768px) {
    .grid-cols-5 { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush


@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colors = {
        teal: '#0891B2',
        cyan: '#06B6D4',
        navy: '#0D2137',
        green: '#10B981',
        amber: '#F59E0B',
        red: '#EF4444'
    };

    const komoditasData = @json($komoditasData);
    new Chart(document.getElementById('komoditasChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(komoditasData),
            datasets: [{ data: Object.values(komoditasData), backgroundColor: [colors.teal, colors.green, colors.amber, colors.red] }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });

    const progresData = @json($progresData);
    new Chart(document.getElementById('progresChart'), {
        type: 'bar',
        data: { labels: Object.keys(progresData), datasets: [{ label: 'Progres (%)', data: Object.values(progresData), backgroundColor: colors.teal, borderRadius: 8 }] },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    const hambatanData = @json($hambatanCounts);
    new Chart(document.getElementById('hambatanChart'), {
        type: 'bar',
        data: { labels: Object.keys(hambatanData), datasets: [{ label: 'Jumlah', data: Object.values(hambatanData), backgroundColor: colors.amber, borderRadius: 8 }] },
        options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y' }
    });

    const instalasiData = @json($instalasiData);
    new Chart(document.getElementById('instalasiChart'), {
        type: 'radar',
        data: { labels: Object.keys(instalasiData), datasets: [{ label: 'Terpasang', data: Object.values(instalasiData), backgroundColor: 'rgba(8, 145, 178, 0.2)', borderColor: colors.teal, pointBackgroundColor: colors.teal }] },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const mapLocations = @json($mapLocations);
    if (mapLocations.length > 0) {
        const map = L.map('kdmpMap').setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
        mapLocations.forEach(loc => {
            L.marker([parseFloat(loc.lat), parseFloat(loc.lng)])
                .bindPopup(`<b style="color: #0891B2;">${loc.name}</b><br>${loc.location}<br>Komoditas: ${loc.commodity || '-'}`)
                .addTo(map);
        });
    } else {
        document.getElementById('kdmpMap').innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--gray-400);">Belum ada data lokasi</div>';
    }
});
</script>
@endpush
