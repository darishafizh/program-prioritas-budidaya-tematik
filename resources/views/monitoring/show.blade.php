@extends('layouts.app')

@push('styles')
<style>
    /* ============================================
       DETAIL LOKASI KDMP — MODERN PROFESSIONAL UI
       ============================================ */

    /* Hero Header */
    .detail-hero {
        background: linear-gradient(135deg, #0B1929 0%, #164E63 60%, #0891B2 100%);
        border-radius: var(--radius-xl);
        padding: 2rem 2.25rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.75rem;
    }

    .detail-hero::before {
        content: '';
        position: absolute;
        top: -60%;
        right: -10%;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(6,182,212,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .detail-hero::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .detail-hero-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        position: relative;
        z-index: 1;
    }

    .detail-hero-info {
        flex: 1;
    }

    .detail-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 0.3rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.7rem;
        font-weight: 500;
        color: rgba(255,255,255,0.85);
        margin-bottom: 0.75rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .detail-hero-badge i {
        font-size: 0.6rem;
        color: var(--kkp-cyan);
    }

    .detail-hero h1 {
        font-size: 1.65rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.35rem;
        letter-spacing: -0.02em;
        line-height: 1.25;
    }

    .detail-hero-subtitle {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.65);
        font-weight: 400;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .detail-hero-subtitle .divider {
        width: 3px;
        height: 3px;
        background: rgba(255,255,255,0.35);
        border-radius: 50%;
    }

    .detail-hero-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        font-size: 0.78rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 200ms ease;
        border: none;
        cursor: pointer;
    }

    .hero-btn svg { width: 15px; height: 15px; }

    .hero-btn-outline {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.18);
        color: #fff;
    }

    .hero-btn-outline:hover {
        background: rgba(255,255,255,0.18);
        color: #fff;
    }

    .hero-btn-primary {
        background: var(--kkp-cyan);
        color: #0B1929;
        font-weight: 600;
    }

    .hero-btn-primary:hover {
        background: #22d3ee;
        color: #0B1929;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(6,182,212,0.3);
    }

    .hero-btn-danger {
        background: rgba(239,68,68,0.15);
        border: 1px solid rgba(239,68,68,0.3);
        color: #FCA5A5;
    }

    .hero-btn-danger:hover {
        background: rgba(239,68,68,0.25);
        color: #fff;
    }

    /* KPI Metrics Row */
    .kpi-metrics-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.875rem;
        margin-bottom: 1.75rem;
    }

    .kpi-metric {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.125rem 1.25rem;
        position: relative;
        overflow: hidden;
        transition: all 250ms ease;
    }

    .kpi-metric:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .kpi-metric::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }

    .kpi-metric.metric-teal::before { background: linear-gradient(90deg, #0891B2, #06B6D4); }
    .kpi-metric.metric-green::before { background: linear-gradient(90deg, #059669, #10B981); }
    .kpi-metric.metric-navy::before { background: linear-gradient(90deg, #1E3A5F, #3B82F6); }
    .kpi-metric.metric-amber::before { background: linear-gradient(90deg, #D97706, #F59E0B); }
    .kpi-metric.metric-emerald::before { background: linear-gradient(90deg, #059669, #34D399); }
    .kpi-metric.metric-rose::before { background: linear-gradient(90deg, #DC2626, #EF4444); }

    .kpi-metric-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }

    .metric-teal .kpi-metric-icon { background: rgba(8,145,178,0.1); color: #0891B2; }
    .metric-green .kpi-metric-icon { background: rgba(16,185,129,0.1); color: #10B981; }
    .metric-navy .kpi-metric-icon { background: rgba(59,130,246,0.1); color: #3B82F6; }
    .metric-amber .kpi-metric-icon { background: rgba(245,158,11,0.1); color: #F59E0B; }
    .metric-emerald .kpi-metric-icon { background: rgba(5,150,105,0.1); color: #059669; }
    .metric-rose .kpi-metric-icon { background: rgba(239,68,68,0.1); color: #EF4444; }

    .kpi-metric-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.25rem;
    }

    .kpi-metric-value {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.15;
        letter-spacing: -0.02em;
    }

    .kpi-metric-sub {
        font-size: 0.68rem;
        color: var(--gray-400);
        margin-top: 0.2rem;
    }

    /* Chart Section */
    .chart-section {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        margin-bottom: 1.75rem;
        overflow: hidden;
        transition: background-color var(--transition-slow), border-color var(--transition-slow);
    }

    .chart-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .chart-section-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .chart-section-title-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .chart-section-title h3 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
        letter-spacing: -0.01em;
    }

    .chart-section-title p {
        font-size: 0.72rem;
        color: var(--gray-500);
        margin: 0;
    }

    .chart-section-body {
        padding: 1.5rem;
    }

    .charts-dual-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .chart-panel {
        position: relative;
    }

    .chart-panel-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .chart-panel-title .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .chart-panel-title .dot.teal { background: #0891B2; }
    .chart-panel-title .dot.green { background: #10B981; }

    .chart-canvas-wrapper {
        position: relative;
        height: 220px;
    }

    /* Financial chart - full width */
    .chart-financial {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    .chart-financial-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .chart-financial-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--gray-700);
    }

    .chart-legend-inline {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .chart-legend-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.7rem;
        font-weight: 500;
        color: var(--gray-500);
    }

    .chart-legend-item .line-swatch {
        width: 20px;
        height: 3px;
        border-radius: 2px;
    }

    .chart-legend-item .line-swatch.biaya { background: #EF4444; }
    .chart-legend-item .line-swatch.nilai { background: #3B82F6; }
    .chart-legend-item .line-swatch.keuntungan { background: #10B981; }

    .chart-financial-canvas {
        position: relative;
        height: 280px;
    }

    /* Records Timeline */
    .records-section {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: background-color var(--transition-slow), border-color var(--transition-slow);
    }

    .records-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .records-header-left {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .records-header-icon {
        width: 32px;
        height: 32px;
        background: rgba(8,145,178,0.1);
        color: #0891B2;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .records-header h3 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
    }

    .records-count {
        background: var(--gray-100);
        color: var(--gray-600);
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: var(--radius-full);
    }

    /* Individual Record Card */
    .record-item {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        transition: background 200ms ease;
    }

    .record-item:last-child {
        border-bottom: none;
    }

    .record-item:hover {
        background: var(--gray-50);
    }

    .record-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .record-main {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        flex: 1;
    }

    .record-period {
        text-align: center;
        min-width: 60px;
        flex-shrink: 0;
    }

    .record-period-month {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--kkp-teal);
        line-height: 1.2;
    }

    .record-period-year {
        font-size: 0.72rem;
        color: var(--gray-400);
        font-weight: 500;
    }

    .record-divider {
        width: 1px;
        height: 40px;
        background: var(--border-color);
        flex-shrink: 0;
        align-self: center;
    }

    .record-content {
        flex: 1;
    }

    .record-status-row {
        margin-bottom: 0.5rem;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: var(--radius-full);
        font-size: 0.72rem;
        font-weight: 600;
    }

    .status-pill.success {
        background: rgba(16,185,129,0.1);
        color: #059669;
    }

    .status-pill.danger {
        background: rgba(239,68,68,0.1);
        color: #DC2626;
    }

    .status-pill.primary {
        background: rgba(59,130,246,0.1);
        color: #2563EB;
    }

    .status-pill.warning {
        background: rgba(245,158,11,0.1);
        color: #D97706;
    }

    .status-pill.secondary {
        background: var(--gray-100);
        color: var(--gray-600);
    }

    .record-stats-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1.25rem;
    }

    .record-stat {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.78rem;
        color: var(--gray-600);
    }

    .record-stat i {
        font-size: 0.7rem;
        color: var(--gray-400);
        width: 14px;
        text-align: center;
    }

    .record-stat strong {
        font-weight: 600;
        color: var(--gray-800);
    }

    .record-actions {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        flex-shrink: 0;
    }

    .record-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.35rem 0.7rem;
        border-radius: var(--radius-md);
        font-size: 0.72rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid var(--border-color);
        background: var(--bg-surface);
        color: var(--gray-600);
        cursor: pointer;
        transition: all 180ms ease;
    }

    .record-action-btn:hover {
        border-color: var(--kkp-teal);
        color: var(--kkp-teal);
        background: rgba(8,145,178,0.04);
    }

    .record-action-btn.danger {
        border: none;
        background: rgba(239,68,68,0.06);
        color: #DC2626;
    }

    .record-action-btn.danger:hover {
        background: rgba(239,68,68,0.12);
    }

    /* Record notes */
    .record-notes {
        margin-top: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .record-note {
        padding: 0.55rem 0.75rem;
        border-radius: var(--radius-md);
        font-size: 0.78rem;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .record-note.kendala {
        background: rgba(239,68,68,0.04);
        border-left: 3px solid #EF4444;
        color: var(--gray-700);
    }

    .record-note.tindak-lanjut {
        background: rgba(16,185,129,0.04);
        border-left: 3px solid #10B981;
        color: var(--gray-700);
    }

    .record-note strong {
        font-weight: 600;
        white-space: nowrap;
        color: var(--gray-700);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
    }

    .empty-state-icon {
        width: 56px;
        height: 56px;
        background: var(--gray-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .empty-state-icon i {
        font-size: 1.25rem;
        color: var(--gray-400);
    }

    .empty-state h4 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.35rem;
    }

    .empty-state p {
        font-size: 0.82rem;
        color: var(--gray-500);
        margin-bottom: 1.25rem;
    }

    /* Dark Mode Overrides */
    [data-theme="dark"] .detail-hero {
        background: linear-gradient(135deg, #0f172a 0%, #164E63 60%, #0e7490 100%);
    }

    [data-theme="dark"] .kpi-metric { 
        background: #111827; 
        border-color: #1F2937; 
    }

    [data-theme="dark"] .kpi-metric-value { color: #E5E7EB; }

    [data-theme="dark"] .chart-section,
    [data-theme="dark"] .records-section { 
        background: #111827; 
        border-color: #1F2937; 
    }

    [data-theme="dark"] .chart-section-header,
    [data-theme="dark"] .records-header,
    [data-theme="dark"] .record-item,
    [data-theme="dark"] .chart-financial { 
        border-color: #1F2937; 
    }

    [data-theme="dark"] .record-item:hover { background: rgba(255,255,255,0.02); }
    [data-theme="dark"] .records-count { background: #1F2937; color: #9CA3AF; }
    [data-theme="dark"] .record-action-btn { background: #1F2937; border-color: #374151; color: #9CA3AF; }
    [data-theme="dark"] .record-note.kendala { background: rgba(239,68,68,0.08); }
    [data-theme="dark"] .record-note.tindak-lanjut { background: rgba(16,185,129,0.08); }
    [data-theme="dark"] .record-note strong { color: #D1D5DB; }
    [data-theme="dark"] .record-stat strong { color: #D1D5DB; }

    /* Responsive */
    @media (max-width: 1024px) {
        .kpi-metrics-row { grid-template-columns: repeat(3, 1fr); }
        .charts-dual-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .kpi-metrics-row { grid-template-columns: repeat(2, 1fr); }
        .detail-hero { padding: 1.5rem; }
        .detail-hero h1 { font-size: 1.25rem; }
        .detail-hero-top { flex-direction: column; }
        .detail-hero-actions { width: 100%; }
        .record-top { flex-direction: column; }
        .record-actions { width: 100%; justify-content: flex-end; }
        .chart-legend-inline { flex-wrap: wrap; gap: 0.6rem; }
        .chart-financial-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
    }

    @media (max-width: 480px) {
        .kpi-metrics-row { grid-template-columns: 1fr; }
        .hero-btn span { display: none; }
        .record-stats-grid { flex-direction: column; gap: 0.3rem; }
    }
</style>
@endpush

@section('content')
@php
    $totalNilai = $records->sum('nilai_produksi');
    $totalBiaya = $records->sum('biaya_operasional');
    $keuntungan = $totalNilai - $totalBiaya;
    $lastRecord = $records->first();
@endphp

{{-- Hero Header --}}
<div class="detail-hero" id="detail-hero">
    <div class="detail-hero-top">
        <div class="detail-hero-info">
            <div class="detail-hero-badge">
                <i class="fa-solid fa-location-dot"></i>
                Detail Lokasi KDMP
            </div>
            <h1>{{ $kdmp->nama_kdkmp }}</h1>
            <div class="detail-hero-subtitle">
                <span><i class="fa-solid fa-map-marker-alt" style="margin-right:2px;"></i> {{ $kdmp->desa }}, {{ $kdmp->kabupaten }}</span>
                <span class="divider"></span>
                <span>{{ $kdmp->provinsi }}</span>
                <span class="divider"></span>
                <span><i class="fa-solid fa-fish" style="margin-right:2px;"></i> {{ $kdmp->komoditas }}</span>
            </div>
        </div>
        <div class="detail-hero-actions">
            <a href="{{ route('monitoring.pdf-detail', $kdmp->id) }}" class="hero-btn hero-btn-danger" target="_blank" title="Export PDF">
                <i class="fa-solid fa-file-pdf"></i>
                <span>PDF</span>
            </a>
            <a href="{{ route('monitoring.create', ['kdmp_id' => $kdmp->id]) }}" class="hero-btn hero-btn-primary">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Laporan</span>
            </a>
            <a href="{{ route('monitoring.index') }}" class="hero-btn hero-btn-outline">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</div>

{{-- KPI Metrics Row --}}
<div class="kpi-metrics-row" id="kpi-metrics">
    <div class="kpi-metric metric-teal">
        <div class="kpi-metric-icon"><i class="fa-solid fa-clipboard-list"></i></div>
        <div class="kpi-metric-label">Total Laporan</div>
        <div class="kpi-metric-value">{{ $records->count() }}</div>
        <div class="kpi-metric-sub">periode tercatat</div>
    </div>
    <div class="kpi-metric metric-green">
        <div class="kpi-metric-icon"><i class="fa-solid fa-fish"></i></div>
        <div class="kpi-metric-label">Total Panen</div>
        <div class="kpi-metric-value">{{ number_format($records->sum('volume_panen_kg'),0,',','.') }}</div>
        <div class="kpi-metric-sub">kilogram</div>
    </div>
    <div class="kpi-metric metric-navy">
        <div class="kpi-metric-icon"><i class="fa-solid fa-coins"></i></div>
        <div class="kpi-metric-label">Total Nilai</div>
        <div class="kpi-metric-value" style="font-size:1.1rem;">Rp {{ number_format($totalNilai,0,',','.') }}</div>
        <div class="kpi-metric-sub">nilai produksi</div>
    </div>
    <div class="kpi-metric metric-amber">
        <div class="kpi-metric-icon"><i class="fa-solid fa-signal"></i></div>
        <div class="kpi-metric-label">Status Terakhir</div>
        @if($lastRecord)
        <div class="kpi-metric-value" style="font-size:1rem;">
            <span class="status-pill {{ $lastRecord->status_color }}" style="font-size:0.78rem;">
                {!! $lastRecord->status_icon !!} {{ $lastRecord->status_label }}
            </span>
        </div>
        @else
        <div class="kpi-metric-value" style="font-size:0.9rem; color: var(--gray-400);">Belum Ada</div>
        @endif
        <div class="kpi-metric-sub">kondisi saat ini</div>
    </div>
    <div class="kpi-metric {{ $keuntungan >= 0 ? 'metric-emerald' : 'metric-rose' }}">
        <div class="kpi-metric-icon">
            <i class="fa-solid {{ $keuntungan >= 0 ? 'fa-trending-up' : 'fa-trending-down' }}"></i>
        </div>
        <div class="kpi-metric-label">Keuntungan</div>
        <div class="kpi-metric-value" style="font-size:1.1rem; color: {{ $keuntungan >= 0 ? '#059669' : '#DC2626' }};">
            Rp {{ number_format($keuntungan,0,',','.') }}
        </div>
        <div class="kpi-metric-sub">nilai - biaya operasional</div>
    </div>
</div>

{{-- Charts Section --}}
@if($chartData->count() > 0)
<div class="chart-section" id="chart-section">
    <div class="chart-section-header">
        <div class="chart-section-title">
            <div class="chart-section-title-icon" style="background:rgba(8,145,178,0.1); color:#0891B2;">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
                <h3>Grafik Perkembangan</h3>
                <p>Tren progres fisik, volume panen, dan keuangan per periode</p>
            </div>
        </div>
    </div>
    <div class="chart-section-body">
        {{-- Dual charts: Progres Fisik & Volume Panen --}}
        <div class="charts-dual-grid">
            <div class="chart-panel">
                <div class="chart-panel-title">
                    <span class="dot teal"></span>
                    Progres Fisik (%)
                </div>
                <div class="chart-canvas-wrapper">
                    <canvas id="chartProgres"></canvas>
                </div>
            </div>
            <div class="chart-panel">
                <div class="chart-panel-title">
                    <span class="dot green"></span>
                    Volume Panen (kg)
                </div>
                <div class="chart-canvas-wrapper">
                    <canvas id="chartPanen"></canvas>
                </div>
            </div>
        </div>

        {{-- Financial Line Chart (full width below) --}}
        <div class="chart-financial">
            <div class="chart-financial-header">
                <div class="chart-financial-title">
                    <i class="fa-solid fa-chart-area" style="color:var(--gray-400); margin-right:0.3rem;"></i>
                    Analisis Keuangan per Periode
                </div>
                <div class="chart-legend-inline">
                    <div class="chart-legend-item">
                        <span class="line-swatch biaya"></span>
                        Biaya Operasional
                    </div>
                    <div class="chart-legend-item">
                        <span class="line-swatch nilai"></span>
                        Total Nilai
                    </div>
                    <div class="chart-legend-item">
                        <span class="line-swatch keuntungan"></span>
                        Keuntungan
                    </div>
                </div>
            </div>
            <div class="chart-financial-canvas">
                <canvas id="chartFinancial"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Records Timeline --}}
<div class="records-section" id="records-section">
    <div class="records-header">
        <div class="records-header-left">
            <div class="records-header-icon">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <h3>Riwayat Laporan Monitoring</h3>
        </div>
        <span class="records-count">{{ $records->count() }} laporan</span>
    </div>

    @forelse($records as $record)
    <div class="record-item">
        <div class="record-top">
            <div class="record-main">
                <div class="record-period">
                    <div class="record-period-month">{{ $bulanList[$record->bulan] ?? '-' }}</div>
                    <div class="record-period-year">{{ $record->tahun }}</div>
                </div>
                <div class="record-divider"></div>
                <div class="record-content">
                    <div class="record-status-row">
                        <span class="status-pill {{ $record->status_color }}">
                            {!! $record->status_icon !!} {{ $record->status_label }}
                        </span>
                    </div>
                    <div class="record-stats-grid">
                        <div class="record-stat">
                            <i class="fa-solid fa-chart-line"></i>
                            Progres: <strong>{{ $record->progres_fisik }}%</strong>
                        </div>
                        <div class="record-stat">
                            <i class="fa-solid fa-fish"></i>
                            Panen: <strong>{{ number_format($record->volume_panen_kg,0,',','.') }} kg</strong>
                        </div>
                        <div class="record-stat">
                            <i class="fa-solid fa-sack-dollar"></i>
                            Nilai: <strong>Rp {{ number_format($record->nilai_produksi,0,',','.') }}</strong>
                        </div>
                        <div class="record-stat">
                            <i class="fa-solid fa-users"></i>
                            Pembudidaya: <strong>{{ $record->jumlah_pembudidaya_aktif }} orang</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="record-actions">
                <a href="{{ route('monitoring.edit', $record->id) }}" class="record-action-btn">
                    <i class="fa-solid fa-pen-to-square" style="font-size:0.68rem;"></i> Edit
                </a>
                <form action="{{ route('monitoring.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin hapus laporan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="record-action-btn danger">
                        <i class="fa-solid fa-trash-can" style="font-size:0.68rem;"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @if($record->kendala || $record->tindak_lanjut)
        <div class="record-notes">
            @if($record->kendala)
            <div class="record-note kendala">
                <strong>Kendala:</strong>
                <span>{{ $record->kendala }}</span>
            </div>
            @endif
            @if($record->tindak_lanjut)
            <div class="record-note tindak-lanjut">
                <strong>Tindak Lanjut:</strong>
                <span>{{ $record->tindak_lanjut }}</span>
            </div>
            @endif
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <h4>Belum Ada Laporan</h4>
        <p>Belum ada laporan monitoring untuk KDMP ini.</p>
        <a href="{{ route('monitoring.create', ['kdmp_id' => $kdmp->id]) }}" class="btn btn-primary">
            <i class="fa-solid fa-plus" style="font-size:0.75rem;"></i>
            Tambah Laporan Pertama
        </a>
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($chartData);
    if (!chartData.length) return;

    const labels = chartData.map(d => d.label);

    // Detect dark theme for chart colors
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
    const tickColor = isDark ? '#9CA3AF' : '#6B7280';

    const baseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: isDark ? '#1F2937' : '#fff',
                titleColor: isDark ? '#F3F4F6' : '#111827',
                bodyColor: isDark ? '#D1D5DB' : '#4B5563',
                borderColor: isDark ? '#374151' : '#E5E7EB',
                borderWidth: 1,
                cornerRadius: 8,
                padding: 10,
                titleFont: { family: 'Poppins', weight: '600', size: 12 },
                bodyFont: { family: 'Poppins', size: 11 },
                displayColors: true,
                boxPadding: 4,
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: tickColor, font: { family: 'Poppins', size: 10, weight: '500' } }
            },
            y: {
                grid: { color: gridColor },
                ticks: { color: tickColor, font: { family: 'Poppins', size: 10 } },
                beginAtZero: true,
            }
        },
        interaction: { intersect: false, mode: 'index' },
    };

    // === Progres Fisik Chart ===
    new Chart(document.getElementById('chartProgres'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Progres Fisik (%)',
                data: chartData.map(d => d.progres_fisik),
                borderColor: '#0891B2',
                backgroundColor: 'rgba(8,145,178,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0891B2',
                pointBorderWidth: 2,
                pointHoverRadius: 7,
                borderWidth: 2.5,
            }]
        },
        options: { ...baseOptions, scales: { ...baseOptions.scales, y: { ...baseOptions.scales.y, max: 100 } } }
    });

    // === Volume Panen Chart ===
    new Chart(document.getElementById('chartPanen'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Volume Panen (kg)',
                data: chartData.map(d => d.volume_panen),
                backgroundColor: (ctx) => {
                    const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                    gradient.addColorStop(0, 'rgba(16,185,129,0.7)');
                    gradient.addColorStop(1, 'rgba(16,185,129,0.15)');
                    return gradient;
                },
                borderColor: '#10B981',
                borderWidth: 1,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: baseOptions
    });

    // === Financial Line Chart (3 indicators) ===
    new Chart(document.getElementById('chartFinancial'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Biaya Operasional',
                    data: chartData.map(d => d.biaya_operasional),
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239,68,68,0.05)',
                    fill: false,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: 5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#EF4444',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    borderDash: [5, 5],
                },
                {
                    label: 'Total Nilai',
                    data: chartData.map(d => d.nilai_produksi),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59,130,246,0.06)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: 5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3B82F6',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                },
                {
                    label: 'Keuntungan',
                    data: chartData.map(d => d.keuntungan),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10B981',
                    pointBorderWidth: 2.5,
                    pointHoverRadius: 8,
                }
            ]
        },
        options: {
            ...baseOptions,
            plugins: {
                ...baseOptions.plugins,
                legend: { display: false },
                tooltip: {
                    ...baseOptions.plugins.tooltip,
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y);
                        }
                    }
                }
            },
            scales: {
                ...baseOptions.scales,
                y: {
                    ...baseOptions.scales.y,
                    ticks: {
                        ...baseOptions.scales.y.ticks,
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                            if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
