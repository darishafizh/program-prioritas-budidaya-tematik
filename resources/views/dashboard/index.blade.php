@extends('layouts.app')

@section('content')

{{-- ============================================================ --}}
{{-- HEADER --}}
{{-- ============================================================ --}}
<div class="exec-header animate-fade-in-up">
    <div>
        <h1 class="exec-title">Executive Summary</h1>
        <p class="exec-subtitle">Program Budidaya Tematik Bioflok · Kementerian Kelautan dan Perikanan</p>
    </div>
    <div class="exec-meta">
        <span class="exec-badge">
            <i class="fa-regular fa-calendar"></i>
            Update: {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}
        </span>
        <span class="exec-badge primary">
            <i class="fa-solid fa-location-dot"></i>
            {{ $totalLokasi }} Lokasi KDMP
        </span>
    </div>
</div>

{{-- ============================================================ --}}
{{-- BAGIAN 1: LOKASI BUDIDAYA --}}
{{-- ============================================================ --}}
<div class="section-divider animate-fade-in-up delay-100">
    <div class="section-divider-line"></div>
    <span class="section-divider-label">
        <i class="fa-solid fa-map-location-dot"></i>
        LOKASI BUDIDAYA
    </span>
    <div class="section-divider-line"></div>
</div>

{{-- KPI Lokasi Budidaya --}}
<div class="kpi-grid-4 animate-fade-in-up delay-200">
    <div class="kpi-card kpi-total">
        <div class="kpi-icon"><i class="fa-solid fa-map-pin"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ number_format($totalLokasi) }}</div>
            <div class="kpi-label">Total Lokasi KDMP</div>
            <div class="kpi-sub">Lokasi budidaya terdaftar</div>
        </div>
    </div>
    <div class="kpi-card kpi-layak">
        <div class="kpi-icon"><i class="fa-solid fa-earth-asia"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $sebaranProvinsi->count() }}</div>
            <div class="kpi-label">Provinsi</div>
            <div class="kpi-sub">Sebaran wilayah</div>
        </div>
    </div>
    <div class="kpi-card kpi-volume">
        <div class="kpi-icon"><i class="fa-solid fa-fish"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $sebaranKomoditas->count() }}</div>
            <div class="kpi-label">Jenis Komoditas</div>
            <div class="kpi-sub">{{ $sebaranKomoditas->pluck('komoditas')->implode(', ') }}</div>
        </div>
    </div>
    <div class="kpi-card kpi-ontrack">
        <div class="kpi-icon"><i class="fa-solid fa-location-crosshairs"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $totalBerkoordinat }}</div>
            <div class="kpi-label">Terverifikasi Koordinat</div>
            <div class="kpi-sub">{{ $totalLokasi > 0 ? round($totalBerkoordinat / $totalLokasi * 100) : 0 }}% dari total lokasi</div>
        </div>
    </div>
</div>

{{-- Peta + Donut --}}
<div class="dash-grid-70-30 animate-fade-in-up delay-300">
    {{-- Peta --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="dash-card-title">
                <i class="fa-solid fa-map-location-dot" style="color:#0891B2;"></i>
                Peta Sebaran 100 Lokasi KDMP
            </div>
            <div class="map-legend">
                @foreach($sebaranKomoditas as $i => $kom)
                <span class="legend-dot" style="background:{{ $komoditasColors[$kom->komoditas] ?? '#9CA3AF' }};"></span><span>{{ $kom->komoditas }} ({{ $kom->total }})</span>
                @endforeach
            </div>
        </div>
        <div id="kdmpMap" style="height:400px; border-radius: var(--radius-lg); overflow:hidden;"></div>
    </div>

    {{-- Donut Chart Komoditas --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="dash-card-title">
                <i class="fa-solid fa-chart-pie" style="color:#0891B2;"></i>
                Sebaran Komoditas
            </div>
        </div>
        <div style="height:260px; display:flex; align-items:center;">
            <canvas id="donutKomoditas"></canvas>
        </div>
        <div class="donut-legend">
            @foreach($sebaranKomoditas as $kom)
            <div class="donut-legend-item">
                <span class="legend-dot" style="background:{{ $komoditasColors[$kom->komoditas] ?? '#9CA3AF' }};"></span>
                <span>{{ $kom->komoditas }}</span>
                <strong>{{ $kom->total }} lokasi</strong>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Sebaran per Provinsi --}}
@if($sebaranProvinsi->count() > 0)
<div class="dash-card animate-fade-in-up delay-400">
    <div class="dash-card-header">
        <div class="dash-card-title">
            <i class="fa-solid fa-building-columns" style="color:#D97706;"></i>
            Sebaran Lokasi per Provinsi
        </div>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Provinsi</th>
                    <th style="text-align:center">Jumlah Lokasi</th>
                    <th style="text-align:center">Persentase</th>
                    <th>Distribusi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sebaranProvinsi as $i => $prov)
                <tr>
                    <td class="text-center" style="color:var(--gray-400);">{{ $i + 1 }}</td>
                    <td class="font-semibold">{{ $prov->provinsi }}</td>
                    <td class="text-center">
                        <span class="score-pill high">{{ $prov->total }}</span>
                    </td>
                    <td class="text-center text-sm" style="color:var(--gray-600);">
                        {{ $totalLokasi > 0 ? round($prov->total / $totalLokasi * 100, 1) : 0 }}%
                    </td>
                    <td>
                        <div class="progress-mini" style="width:120px;">
                            <div class="progress-mini-bar" style="width:{{ $totalLokasi > 0 ? round($prov->total / $totalLokasi * 100) : 0 }}%; background: #0891B2;"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ============================================================ --}}
{{-- BAGIAN 2: MONITORING EVALUASI PRODUKSI --}}
{{-- ============================================================ --}}
<div class="section-divider animate-fade-in-up delay-500" style="margin-top:2rem;">
    <div class="section-divider-line"></div>
    <span class="section-divider-label">
        <i class="fa-solid fa-chart-line"></i>
        MONITORING EVALUASI PRODUKSI
    </span>
    <div class="section-divider-line"></div>
</div>

{{-- KPI Monitoring --}}
<div class="kpi-grid-4 animate-fade-in-up delay-600">
    <div class="kpi-card kpi-ontrack">
        <div class="kpi-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $monitoringStats['on_track'] }}</div>
            <div class="kpi-label">On Track</div>
            <div class="kpi-sub">Lokasi berjalan lancar</div>
        </div>
    </div>
    <div class="kpi-card kpi-bermasalah">
        <div class="kpi-icon"><i class="fa-solid fa-circle-xmark"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $monitoringStats['bermasalah'] }}</div>
            <div class="kpi-label">Bermasalah</div>
            <div class="kpi-sub">Perlu intervensi segera</div>
        </div>
    </div>
    <div class="kpi-card kpi-volume">
        <div class="kpi-icon"><i class="fa-solid fa-fish"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">{{ number_format($produksiTotal['volume_kg'], 0) }}</div>
            <div class="kpi-label">Total Volume Panen (kg)</div>
            <div class="kpi-sub">Kumulatif seluruh lokasi</div>
        </div>
    </div>
    <div class="kpi-card kpi-nilai">
        <div class="kpi-icon"><i class="fa-solid fa-sack-dollar"></i></div>
        <div class="kpi-body">
            <div class="kpi-value">Rp {{ number_format($produksiTotal['nilai_rupiah'] / 1000000, 1) }}M</div>
            <div class="kpi-label">Total Nilai Produksi</div>
            <div class="kpi-sub">Kumulatif seluruh lokasi</div>
        </div>
    </div>
</div>

{{-- Tren Produksi + Donut Status --}}
<div class="dash-grid-70-30 animate-fade-in-up delay-600">
    {{-- Line Chart Tren Produksi --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="dash-card-title">
                <i class="fa-solid fa-chart-area" style="color:#0891B2;"></i>
                Tren Volume Produksi per Bulan
            </div>
        </div>
        @if($trenProduksi->count() > 0)
        <div style="height:280px;">
            <canvas id="trenProduksiChart"></canvas>
        </div>
        @else
        <div class="empty-state-sm">
            <i class="fa-solid fa-chart-area" style="font-size:2rem;color:var(--gray-300);"></i>
            <p>Belum ada data produksi tercatat</p>
            <a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-primary">Input Monitoring</a>
        </div>
        @endif
    </div>

    {{-- Donut Status Monitoring --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="dash-card-title">
                <i class="fa-solid fa-gauge-high" style="color:#0891B2;"></i>
                Status Monitoring
            </div>
        </div>
        @if($monitoringStats['total'] > 0)
        <div style="height:220px; display:flex; align-items:center;">
            <canvas id="donutMonitoring"></canvas>
        </div>
        <div class="donut-legend">
            <div class="donut-legend-item">
                <span class="legend-dot" style="background:#16A34A;"></span>
                <span>On Track</span>
                <strong>{{ $monitoringStats['on_track'] }}</strong>
            </div>
            <div class="donut-legend-item">
                <span class="legend-dot" style="background:#DC2626;"></span>
                <span>Bermasalah</span>
                <strong>{{ $monitoringStats['bermasalah'] }}</strong>
            </div>
            <div class="donut-legend-item">
                <span class="legend-dot" style="background:#D97706;"></span>
                <span>Vakum</span>
                <strong>{{ $monitoringStats['vakum'] }}</strong>
            </div>
            <div class="donut-legend-item">
                <span class="legend-dot" style="background:#2563EB;"></span>
                <span>Selesai</span>
                <strong>{{ $monitoringStats['selesai'] }}</strong>
            </div>
        </div>
        @else
        <div class="empty-state-sm">
            <i class="fa-solid fa-gauge-high" style="font-size:2rem;color:var(--gray-300);"></i>
            <p>Belum ada data monitoring</p>
        </div>
        @endif
    </div>
</div>

{{-- Tabel Rekap Status Monitoring Terkini --}}
<div class="dash-card animate-fade-in-up delay-600">
    <div class="dash-card-header">
        <div class="dash-card-title">
            <i class="fa-solid fa-table-list" style="color:#0891B2;"></i>
            Rekap Status Monitoring Terkini (Per Lokasi)
        </div>
        <a href="{{ route('monitoring.index') }}" class="dash-link">Lihat Semua →</a>
    </div>
    @if($monitoringTerkini->count() > 0)
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama KDMP</th>
                    <th>Kabupaten</th>
                    <th>Komoditas</th>
                    <th style="text-align:center">Periode</th>
                    <th style="text-align:center">Progres Fisik</th>
                    <th style="text-align:center">Volume Panen</th>
                    <th style="text-align:center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monitoringTerkini as $record)
                <tr>
                    <td class="font-semibold">{{ $record->kdmp->nama_kdkmp ?? '-' }}</td>
                    <td>{{ $record->kdmp->kabupaten ?? '-' }}</td>
                    <td>{{ $record->kdmp->komoditas ?? '-' }}</td>
                    <td class="text-center text-sm" style="color:var(--gray-500);">{{ $record->periode_label }}</td>
                    <td class="text-center">
                        @if($record->progres_fisik !== null)
                        <div class="progress-mini">
                            <div class="progress-mini-bar" style="width:{{ min($record->progres_fisik, 100) }}%;
                                background: {{ $record->progres_fisik >= 80 ? '#16A34A' : ($record->progres_fisik >= 50 ? '#D97706' : '#DC2626') }};">
                            </div>
                        </div>
                        <span style="font-size:0.75rem;color:var(--gray-600);">{{ $record->progres_fisik }}%</span>
                        @else
                        <span style="color:var(--gray-300);">-</span>
                        @endif
                    </td>
                    <td class="text-center text-sm">
                        {{ $record->volume_panen_kg ? number_format($record->volume_panen_kg, 0) . ' kg' : '-' }}
                    </td>
                    <td class="text-center">
                        <span class="status-chip {{ $record->status_color }}">
                            {!! $record->status_icon !!} {{ $record->status_label }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state-sm">
        <i class="fa-solid fa-table-list" style="font-size:2rem;color:var(--gray-300);"></i>
        <p style="color:var(--gray-500);">Belum ada data monitoring yang tercatat.</p>
        <a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-primary">Mulai Input Monitoring</a>
    </div>
    @endif
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* ============================================================
   EXECUTIVE HEADER
   ============================================================ */
.exec-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.exec-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--kkp-navy);
    margin: 0;
}
.exec-subtitle {
    font-size: 0.85rem;
    color: var(--gray-500);
    margin: 0.25rem 0 0;
}
.exec-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
}
.exec-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.75rem;
    background: var(--gray-100);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-full);
    font-size: 0.78rem;
    color: var(--gray-600);
    font-weight: 500;
}
.exec-badge.primary {
    background: rgba(8,145,178,0.08);
    border-color: rgba(8,145,178,0.2);
    color: #0891B2;
}

/* ============================================================
   SECTION DIVIDER
   ============================================================ */
.section-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.section-divider-line {
    flex: 1;
    height: 1px;
    background: var(--gray-200);
}
.section-divider-label {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--gray-400);
    white-space: nowrap;
}

/* ============================================================
   KPI GRID — 6 kolom (scoring)
   ============================================================ */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}
.kpi-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}
.kpi-card {
    border-radius: var(--radius-lg);
    padding: 1rem 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: white;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}
.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}
.kpi-card::after {
    content: '';
    position: absolute;
    right: -12px;
    bottom: -12px;
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}
.kpi-icon {
    font-size: 1.4rem;
    opacity: 0.85;
    flex-shrink: 0;
}
.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}
.kpi-label {
    font-size: 0.75rem;
    font-weight: 600;
    opacity: 0.9;
    margin-top: 0.15rem;
}
.kpi-sub {
    font-size: 0.68rem;
    opacity: 0.7;
    margin-top: 0.1rem;
}

/* KPI Colors */
.kpi-total        { background: linear-gradient(135deg, #0D2137, #1e3a5a); }
.kpi-sangat-layak { background: linear-gradient(135deg, #15803D, #16A34A); }
.kpi-layak        { background: linear-gradient(135deg, #1D4ED8, #2563EB); }
.kpi-cukup        { background: linear-gradient(135deg, #B45309, #D97706); }
.kpi-tidak        { background: linear-gradient(135deg, #B91C1C, #DC2626); }
.kpi-belum        { background: linear-gradient(135deg, #4B5563, #6B7280); }
.kpi-ontrack      { background: linear-gradient(135deg, #15803D, #16A34A); }
.kpi-bermasalah   { background: linear-gradient(135deg, #B91C1C, #DC2626); }
.kpi-volume       { background: linear-gradient(135deg, #0891B2, #06B6D4); }
.kpi-nilai        { background: linear-gradient(135deg, #7C3AED, #8B5CF6); }

/* ============================================================
   DASHBOARD CARDS
   ============================================================ */
.dash-card {
    background: white;
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-200);
    padding: 1.25rem;
    margin-bottom: 1.25rem;
}
.dash-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.dash-card-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
}
.dash-link {
    font-size: 0.8rem;
    color: #0891B2;
    text-decoration: none;
    font-weight: 500;
}
.dash-link:hover { text-decoration: underline; }

/* ============================================================
   GRID LAYOUTS
   ============================================================ */
.dash-grid-70-30 {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

/* ============================================================
   MAP LEGEND
   ============================================================ */
.map-legend {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    font-size: 0.72rem;
    color: var(--gray-500);
}
.legend-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ============================================================
   DONUT LEGEND
   ============================================================ */
.donut-legend {
    margin-top: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.donut-legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.78rem;
    color: var(--gray-600);
}
.donut-legend-item strong {
    margin-left: auto;
    color: var(--gray-800);
}

/* ============================================================
   SCORE & STATUS CHIPS
   ============================================================ */
.score-pill {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: var(--radius-sm);
    font-weight: 700;
    font-size: 0.78rem;
}
.score-pill.high { background: rgba(22,163,74,0.1); color: #16A34A; }
.score-pill.mid  { background: rgba(217,119,6,0.1); color: #D97706; }
.score-pill.low  { background: rgba(220,38,38,0.1); color: #DC2626; }

.status-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.55rem;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: 0.7rem;
    white-space: nowrap;
}
.status-chip.success { background: rgba(22,163,74,0.1); color: #16A34A; }
.status-chip.primary { background: rgba(37,99,235,0.1); color: #2563EB; }
.status-chip.warning { background: rgba(217,119,6,0.1); color: #D97706; }
.status-chip.danger  { background: rgba(220,38,38,0.1); color: #DC2626; }
.status-chip.secondary { background: var(--gray-100); color: var(--gray-500); }

/* ============================================================
   PROGRESS MINI BAR
   ============================================================ */
.progress-mini {
    width: 70px;
    height: 5px;
    background: var(--gray-200);
    border-radius: 99px;
    overflow: hidden;
    margin: 0 auto 3px;
}
.progress-mini-bar {
    height: 100%;
    border-radius: 99px;
    transition: width 0.6s ease;
}

/* ============================================================
   EMPTY STATE
   ============================================================ */
.empty-state-sm {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--gray-500);
}
.empty-state-sm p {
    margin: 0.5rem 0 1rem;
    font-size: 0.85rem;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1280px) {
    .kpi-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 1024px) {
    .dash-grid-70-30 { grid-template-columns: 1fr; }
    .kpi-grid-4 { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .exec-header { flex-direction: column; }
    .kpi-value { font-size: 1.25rem; }
}
@media (max-width: 480px) {
    .kpi-grid { grid-template-columns: 1fr 1fr; }
    .kpi-grid-4 { grid-template-columns: 1fr 1fr; }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // PETA SEBARAN LOKASI (warna per komoditas)
    // ============================================================
    const mapLocations = @json($mapLocations);
    const komoditasColors = @json($komoditasColors);

    if (mapLocations.length > 0) {
        const map = L.map('kdmpMap').setView([-7.5, 112], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        mapLocations.forEach(loc => {
            if (!loc.lat || !loc.lng) return;
            const color = komoditasColors[loc.komoditas] ?? '#9CA3AF';
            const marker = L.circleMarker([parseFloat(loc.lat), parseFloat(loc.lng)], {
                radius: 7,
                fillColor: color,
                color: 'white',
                weight: 1.5,
                opacity: 1,
                fillOpacity: 0.9,
            });
            marker.bindPopup(`
                <div style="min-width:180px;font-size:13px;">
                    <b style="color:#0891B2;">${loc.name}</b><br>
                    <span style="color:#6B7280;">${loc.kabupaten}, ${loc.provinsi}</span><br>
                    <span style="display:inline-block;margin-top:4px;padding:2px 8px;border-radius:99px;font-size:11px;font-weight:600;background:${color}20;color:${color};">${loc.komoditas || '-'}</span>
                </div>
            `);
            marker.addTo(map);
        });

        // Auto-fit bounds to all markers
        const bounds = mapLocations
            .filter(l => l.lat && l.lng)
            .map(l => [parseFloat(l.lat), parseFloat(l.lng)]);
        if (bounds.length > 0) map.fitBounds(bounds, { padding: [30, 30] });
    } else {
        document.getElementById('kdmpMap').innerHTML =
            '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9CA3AF;">Belum ada data lokasi</div>';
    }

    // ============================================================
    // DONUT — SEBARAN KOMODITAS
    // ============================================================
    const donutKomEl = document.getElementById('donutKomoditas');
    if (donutKomEl) {
        const komData = @json($sebaranKomoditas);
        new Chart(donutKomEl, {
            type: 'doughnut',
            data: {
                labels: komData.map(k => k.komoditas),
                datasets: [{
                    data: komData.map(k => k.total),
                    backgroundColor: komData.map(k => komoditasColors[k.komoditas] ?? '#9CA3AF'),
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: { legend: { display: false } }
            }
        });
    }

    // ============================================================
    // DONUT — STATUS MONITORING
    // ============================================================
    const donutMonEl = document.getElementById('donutMonitoring');
    if (donutMonEl) {
        new Chart(donutMonEl, {
            type: 'doughnut',
            data: {
                labels: ['On Track', 'Bermasalah', 'Vakum', 'Selesai'],
                datasets: [{
                    data: [
                        {{ $monitoringStats['on_track'] }},
                        {{ $monitoringStats['bermasalah'] }},
                        {{ $monitoringStats['vakum'] }},
                        {{ $monitoringStats['selesai'] }},
                    ],
                    backgroundColor: ['#16A34A', '#DC2626', '#D97706', '#2563EB'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: { legend: { display: false } }
            }
        });
    }

    // ============================================================
    // LINE CHART — TREN PRODUKSI
    // ============================================================
    const trenEl = document.getElementById('trenProduksiChart');
    if (trenEl) {
        const trenData = @json($trenProduksi);
        new Chart(trenEl, {
            type: 'line',
            data: {
                labels: trenData.map(d => d.label),
                datasets: [{
                    label: 'Volume Panen (kg)',
                    data: trenData.map(d => d.volume),
                    borderColor: '#0891B2',
                    backgroundColor: 'rgba(8,145,178,0.08)',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#0891B2',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y.toLocaleString('id-ID')} kg`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { callback: v => v.toLocaleString('id-ID') }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

});
</script>
@endpush
