@extends('layouts.app')

@section('content')

{{-- ============================================================ --}}
{{-- HEADER + FILTER BAR --}}
{{-- ============================================================ --}}
<div class="monev-header animate-fade-in-up">
    <div>
        <h1 class="monev-title">Dashboard Monev</h1>
        <p class="monev-subtitle">Program Budidaya Tematik · Monitoring & Evaluasi</p>
    </div>
    <div class="monev-meta">
        <span class="monev-badge">
            <i class="fa-regular fa-calendar"></i>
            {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}
        </span>
        <span class="monev-badge primary">
            <i class="fa-solid fa-location-dot"></i>
            {{ $totalLokasi }} Lokasi
        </span>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar animate-fade-in-up delay-100" id="filterBar">
    <form method="GET" action="{{ route('dashboard') }}" class="filter-form" id="filterForm">
        <div class="filter-group">
            <label class="filter-label"><i class="fa-solid fa-earth-asia"></i> Provinsi</label>
            <select name="provinsi" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Provinsi</option>
                @foreach($provinsiList as $prov)
                <option value="{{ $prov }}" {{ $filterProvinsi == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label"><i class="fa-solid fa-calendar-days"></i> Tahun</label>
            <select name="tahun" class="filter-select" onchange="this.form.submit()">
                @foreach($tahunList as $t)
                <option value="{{ $t }}" {{ $filterTahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label"><i class="fa-solid fa-clock"></i> Bulan</label>
            <select name="bulan" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Bulan</option>
                @foreach($bulanList as $num => $nama)
                <option value="{{ $num }}" {{ $filterBulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label"><i class="fa-solid fa-fish"></i> Komoditas</label>
            <select name="komoditas" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Komoditas</option>
                @foreach($komoditasList as $kom)
                <option value="{{ $kom }}" {{ $filterKomoditas == $kom ? 'selected' : '' }}>{{ $kom }}</option>
                @endforeach
            </select>
        </div>
        @if($filterProvinsi || $filterKomoditas || $filterBulan)
        <a href="{{ route('dashboard', ['tahun' => $filterTahun]) }}" class="filter-reset" title="Reset Filter">
            <i class="fa-solid fa-xmark"></i> Reset
        </a>
        @endif
    </form>
</div>

{{-- ============================================================ --}}
{{-- TAB NAVIGATION --}}
{{-- ============================================================ --}}
<div class="tab-nav animate-fade-in-up delay-200" id="tabNav">
    <button class="tab-btn active" data-tab="executive" onclick="switchTab('executive')">
        <i class="fa-solid fa-gauge-high"></i> Executive
    </button>
    <button class="tab-btn" data-tab="manajerial" onclick="switchTab('manajerial')">
        <i class="fa-solid fa-chart-column"></i> Manajerial
    </button>
    <button class="tab-btn" data-tab="teknis" onclick="switchTab('teknis')">
        <i class="fa-solid fa-table-list"></i> Teknis
    </button>
</div>

{{-- ============================================================ --}}
{{-- TAB 1: EXECUTIVE LAYER --}}
{{-- ============================================================ --}}
<div class="tab-content active" id="tab-executive">

    {{-- KPI Cards --}}
    <div class="kpi-grid-6 animate-fade-in-up delay-300">
        <div class="kpi-card kpi-produksi">
            <div class="kpi-icon"><i class="fa-solid fa-weight-scale"></i></div>
            <div class="kpi-body">
                <div class="kpi-value">{{ number_format($totalProduksi, 0, ',', '.') }}</div>
                <div class="kpi-label">Total Produksi (kg)</div>
                <div class="kpi-sub">Kumulatif seluruh lokasi</div>
            </div>
        </div>
        <div class="kpi-card kpi-perkolam">
            <div class="kpi-icon"><i class="fa-solid fa-cubes-stacked"></i></div>
            <div class="kpi-body">
                <div class="kpi-value">{{ $produksiPerKolam !== null ? number_format($produksiPerKolam, 1, ',', '.') : '-' }}</div>
                <div class="kpi-label">Produksi/Kolam (kg)</div>
                <div class="kpi-sub">Rata-rata per kolam aktif</div>
            </div>
        </div>
        <div class="kpi-card kpi-utilisasi">
            <div class="kpi-icon"><i class="fa-solid fa-water"></i></div>
            <div class="kpi-body">
                <div class="kpi-value">{{ $utilisasiKolam !== null ? $utilisasiKolam . '%' : '-' }}</div>
                <div class="kpi-label">Utilisasi Kolam</div>
                <div class="kpi-sub">% kolam aktif dari total</div>
            </div>
        </div>
        <div class="kpi-card kpi-sr {{ $programHealth }}">
            <div class="kpi-icon"><i class="fa-solid fa-heart-pulse"></i></div>
            <div class="kpi-body">
                <div class="kpi-value">{{ $avgSR !== null ? number_format($avgSR, 1) . '%' : '-' }}</div>
                <div class="kpi-label">Rata-rata SR</div>
                <div class="kpi-sub">Survival Rate</div>
            </div>
        </div>
        <div class="kpi-card kpi-biaya">
            <div class="kpi-icon"><i class="fa-solid fa-coins"></i></div>
            <div class="kpi-body">
                <div class="kpi-value">{{ $avgBiayaPerKg !== null ? 'Rp ' . number_format($avgBiayaPerKg, 0, ',', '.') : '-' }}</div>
                <div class="kpi-label">Biaya per Kg</div>
                <div class="kpi-sub">Rata-rata biaya produksi</div>
            </div>
        </div>
        <div class="kpi-card kpi-aktif">
            <div class="kpi-icon"><i class="fa-solid fa-check-double"></i></div>
            <div class="kpi-body">
                <div class="kpi-value">{{ $pctUnitAktif }}%</div>
                <div class="kpi-label">Unit Aktif</div>
                <div class="kpi-sub">{{ $unitAktif }} dari {{ $totalLokasi }} lokasi</div>
            </div>
        </div>
    </div>

    {{-- Program Health Bar --}}
    <div class="health-bar animate-fade-in-up delay-300">
        <div class="health-bar-inner {{ $programHealth }}">
            <div class="health-indicator">
                @if($programHealth === 'success')
                    <i class="fa-solid fa-circle-check"></i> Status Program: <strong>BAIK</strong>
                @elseif($programHealth === 'warning')
                    <i class="fa-solid fa-triangle-exclamation"></i> Status Program: <strong>PERLU PERHATIAN</strong>
                @else
                    <i class="fa-solid fa-circle-exclamation"></i> Status Program: <strong>KRITIS</strong>
                @endif
            </div>
            <div class="health-stats">
                <span><strong>{{ $totalMonitored }}</strong> dilaporkan</span>
                <span class="health-dot success"></span><span>On Track: {{ $statusBreakdown['on_track'] }}</span>
                <span class="health-dot warning"></span><span>Vakum: {{ $statusBreakdown['vakum'] }}</span>
                <span class="health-dot danger"></span><span>Bermasalah: {{ $statusBreakdown['bermasalah'] }}</span>
            </div>
        </div>
    </div>

    {{-- Map + SR Summary --}}
    <div class="dash-grid-70-30 animate-fade-in-up delay-400">
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="fa-solid fa-map-location-dot" style="color:#0891B2;"></i>
                    Peta Sebaran Lokasi (Berdasarkan Status SR)
                </div>
                <div class="map-legend-sr">
                    <span class="legend-item"><span class="legend-dot" style="background:#16A34A;"></span> SR > 80%</span>
                    <span class="legend-item"><span class="legend-dot" style="background:#D97706;"></span> SR 70-80%</span>
                    <span class="legend-item"><span class="legend-dot" style="background:#DC2626;"></span> SR < 70%</span>
                    <span class="legend-item"><span class="legend-dot" style="background:#9CA3AF;"></span> Belum ada data</span>
                </div>
            </div>
            <div id="monevMap" style="height:420px; border-radius: var(--radius-lg); overflow:hidden;"></div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="fa-solid fa-signal" style="color:#0891B2;"></i>
                    Ringkasan Status
                </div>
            </div>

            {{-- Status Donut --}}
            <div style="height:200px; display:flex; align-items:center; justify-content:center;">
                <canvas id="donutStatus"></canvas>
            </div>
            <div class="status-summary-list">
                <div class="status-summary-item">
                    <span class="status-dot success"></span>
                    <span>On Track</span>
                    <strong>{{ $statusBreakdown['on_track'] }}</strong>
                </div>
                <div class="status-summary-item">
                    <span class="status-dot danger"></span>
                    <span>Bermasalah</span>
                    <strong>{{ $statusBreakdown['bermasalah'] }}</strong>
                </div>
                <div class="status-summary-item">
                    <span class="status-dot warning"></span>
                    <span>Vakum</span>
                    <strong>{{ $statusBreakdown['vakum'] }}</strong>
                </div>
                <div class="status-summary-item">
                    <span class="status-dot primary"></span>
                    <span>Selesai</span>
                    <strong>{{ $statusBreakdown['selesai'] }}</strong>
                </div>
            </div>

            <div class="sr-distribution">
                <div class="dash-card-title" style="font-size:0.85rem; margin: 1rem 0 0.5rem;">
                    <i class="fa-solid fa-heart-pulse" style="color:#0891B2;"></i>
                    Distribusi Survival Rate
                </div>
                <div class="sr-bars">
                    @php
                        $srTotal = max(array_sum($srDistribution), 1);
                    @endphp
                    <div class="sr-bar-row">
                        <span class="sr-bar-label success">SR > 80%</span>
                        <div class="sr-bar-track"><div class="sr-bar-fill success" style="width:{{ ($srDistribution['success'] / $srTotal) * 100 }}%;"></div></div>
                        <span class="sr-bar-count">{{ $srDistribution['success'] }}</span>
                    </div>
                    <div class="sr-bar-row">
                        <span class="sr-bar-label warning">70-80%</span>
                        <div class="sr-bar-track"><div class="sr-bar-fill warning" style="width:{{ ($srDistribution['warning'] / $srTotal) * 100 }}%;"></div></div>
                        <span class="sr-bar-count">{{ $srDistribution['warning'] }}</span>
                    </div>
                    <div class="sr-bar-row">
                        <span class="sr-bar-label danger">SR < 70%</span>
                        <div class="sr-bar-track"><div class="sr-bar-fill danger" style="width:{{ ($srDistribution['danger'] / $srTotal) * 100 }}%;"></div></div>
                        <span class="sr-bar-count">{{ $srDistribution['danger'] }}</span>
                    </div>
                    <div class="sr-bar-row">
                        <span class="sr-bar-label secondary">N/A</span>
                        <div class="sr-bar-track"><div class="sr-bar-fill secondary" style="width:{{ ($srDistribution['unknown'] / $srTotal) * 100 }}%;"></div></div>
                        <span class="sr-bar-count">{{ $srDistribution['unknown'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- TAB 2: MANAJERIAL LAYER --}}
{{-- ============================================================ --}}
<div class="tab-content" id="tab-manajerial">

    {{-- Row 1: Produksi per Wilayah + Tren Produksi --}}
    <div class="dash-grid-50-50 animate-fade-in-up">
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="fa-solid fa-chart-bar" style="color:#0891B2;"></i>
                    Produksi per Wilayah (kg)
                </div>
            </div>
            @if($produksiPerProvinsi->count() > 0)
            <div style="height:320px;">
                <canvas id="barProduksiWilayah"></canvas>
            </div>
            @else
            <div class="empty-state-sm">
                <i class="fa-solid fa-chart-bar" style="font-size:2rem;color:var(--gray-300);"></i>
                <p>Belum ada data produksi</p>
            </div>
            @endif
        </div>
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="fa-solid fa-chart-line" style="color:#0891B2;"></i>
                    Tren Produksi Bulanan
                </div>
            </div>
            @if($trenProduksi->count() > 0)
            <div style="height:320px;">
                <canvas id="lineTrenProduksi"></canvas>
            </div>
            @else
            <div class="empty-state-sm">
                <i class="fa-solid fa-chart-line" style="font-size:2rem;color:var(--gray-300);"></i>
                <p>Belum ada data tren produksi</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Row 2: Distribusi Masalah + Perbandingan --}}
    <div class="dash-grid-40-60 animate-fade-in-up">
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="fa-solid fa-circle-exclamation" style="color:#D97706;"></i>
                    Distribusi Permasalahan
                </div>
            </div>
            @if($distribusiMasalah->count() > 0)
            <div style="height:280px; display:flex; align-items:center;">
                <canvas id="donutMasalah"></canvas>
            </div>
            <div class="donut-legend" style="margin-top:0.5rem;">
                @php
                    $masalahColors = ['#DC2626','#D97706','#0891B2','#16A34A','#8B5CF6','#EC4899','#6366F1','#F59E0B'];
                @endphp
                @foreach($distribusiMasalah as $kategori => $count)
                <div class="donut-legend-item">
                    <span class="legend-dot" style="background:{{ $masalahColors[$loop->index % count($masalahColors)] }};"></span>
                    <span>{{ $kategori }}</span>
                    <strong>{{ $count }}</strong>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state-sm">
                <i class="fa-solid fa-circle-check" style="font-size:2rem;color:#16A34A;"></i>
                <p>Tidak ada permasalahan tercatat</p>
            </div>
            @endif
        </div>
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="fa-solid fa-ranking-star" style="color:#8B5CF6;"></i>
                    Perbandingan Performa Wilayah
                </div>
            </div>
            @if($perbandinganWilayah->count() > 0)
            <div style="height:320px;">
                <canvas id="barPerbandingan"></canvas>
            </div>
            @else
            <div class="empty-state-sm">
                <i class="fa-solid fa-ranking-star" style="font-size:2rem;color:var(--gray-300);"></i>
                <p>Belum ada data perbandingan</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- TAB 3: TEKNIS LAYER --}}
{{-- ============================================================ --}}
<div class="tab-content" id="tab-teknis">

    {{-- Priority Alert --}}
    @if($prioritasIntervensi->count() > 0)
    <div class="priority-alert animate-fade-in-up">
        <div class="priority-alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="priority-alert-body">
            <strong>{{ $prioritasIntervensi->count() }} lokasi membutuhkan intervensi segera</strong>
            <span>Kombinasi: SR rendah + produksi rendah + ada kendala</span>
        </div>
    </div>
    @endif

    {{-- Search --}}
    <div class="table-toolbar animate-fade-in-up">
        <div class="table-search-box">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="tableSearch" placeholder="Cari lokasi, provinsi, komoditas..." class="table-search-input">
        </div>
        <div class="table-info">
            <span>Menampilkan <strong>{{ $detailLokasi->count() }}</strong> lokasi</span>
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="dash-card animate-fade-in-up" style="padding:0; overflow:hidden;">
        <div class="table-responsive">
            <table class="monev-table" id="monevTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="nama">Lokasi</th>
                        <th class="sortable text-center" data-sort="kolam">Kolam</th>
                        <th class="sortable text-center" data-sort="produksi">Produksi (kg)</th>
                        <th class="sortable text-center" data-sort="sr">SR (%)</th>
                        <th class="sortable text-center" data-sort="biaya">Biaya/kg</th>
                        <th class="sortable text-center" data-sort="progres">Progres</th>
                        <th class="text-center">Status</th>
                        <th>Permasalahan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailLokasi as $loc)
                    <tr class="{{ $loc['is_prioritas'] ? 'row-prioritas' : '' }}" data-search="{{ strtolower($loc['nama'] . ' ' . $loc['provinsi'] . ' ' . $loc['kabupaten'] . ' ' . $loc['komoditas']) }}">
                        <td>
                            <div class="cell-lokasi">
                                @if($loc['is_prioritas'])
                                <span class="prioritas-badge" title="Prioritas Intervensi"><i class="fa-solid fa-exclamation"></i></span>
                                @endif
                                <div>
                                    <div class="cell-nama">{{ $loc['nama'] }}</div>
                                    <div class="cell-meta">{{ $loc['kabupaten'] }}, {{ $loc['provinsi'] }} · {{ $loc['komoditas'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($loc['kolam_aktif'] !== null && $loc['kolam_total'] !== null)
                                <span class="kolam-indicator {{ $loc['utilisasi'] !== null && $loc['utilisasi'] < 50 ? 'low' : '' }}">
                                    {{ $loc['kolam_aktif'] }}/{{ $loc['kolam_total'] }}
                                </span>
                                @if($loc['utilisasi'] !== null)
                                <div class="cell-sub">{{ $loc['utilisasi'] }}%</div>
                                @endif
                            @else
                                <span style="color:var(--gray-400);">-</span>
                            @endif
                        </td>
                        <td class="text-center font-semibold">
                            {{ $loc['produksi'] > 0 ? number_format($loc['produksi'], 0, ',', '.') : '-' }}
                            @if($loc['produksi_per_kolam'] !== null)
                            <div class="cell-sub">{{ number_format($loc['produksi_per_kolam'], 1) }}/kolam</div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($loc['sr'] !== null)
                            <span class="sr-chip {{ $loc['sr_status'] }}">
                                {{ number_format($loc['sr'], 1) }}%
                            </span>
                            @else
                            <span style="color:var(--gray-400);">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($loc['biaya_per_kg'] !== null)
                                Rp {{ number_format($loc['biaya_per_kg'], 0, ',', '.') }}
                            @else
                                <span style="color:var(--gray-400);">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($loc['progres'] !== null)
                            <div class="progress-mini">
                                <div class="progress-mini-bar" style="width:{{ min($loc['progres'], 100) }}%;
                                    background: {{ $loc['progres'] >= 80 ? '#16A34A' : ($loc['progres'] >= 50 ? '#D97706' : '#DC2626') }};"></div>
                            </div>
                            <span class="cell-sub">{{ $loc['progres'] }}%</span>
                            @else
                            <span style="color:var(--gray-400);">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="status-chip {{ $loc['status_color'] }}">{{ $loc['status'] }}</span>
                        </td>
                        <td>
                            @if($loc['kendala'])
                                <div class="cell-kendala" title="{{ $loc['kendala'] }}">{{ Str::limit($loc['kendala'], 50) }}</div>
                            @else
                                <span style="color:var(--gray-400); font-size:0.78rem;">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding:2rem; color:var(--gray-400);">
                            <i class="fa-solid fa-inbox" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                            Belum ada data monitoring. <a href="{{ route('monitoring.create') }}">Mulai Input</a>
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* ============================================================
   MONEV DASHBOARD — REDESIGN STYLES
   ============================================================ */

/* --- Header --- */
.monev-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.monev-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--kkp-navy);
    margin: 0;
    background: linear-gradient(135deg, var(--kkp-navy), var(--kkp-teal));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
[data-theme="dark"] .monev-title {
    background: linear-gradient(135deg, var(--kkp-cyan), #67e8f9);
    -webkit-background-clip: text;
    background-clip: text;
}
.monev-subtitle {
    font-size: 0.85rem;
    color: var(--gray-500);
    margin: 0.25rem 0 0;
}
.monev-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
}
.monev-badge {
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
.monev-badge.primary {
    background: rgba(8,145,178,0.08);
    border-color: rgba(8,145,178,0.2);
    color: #0891B2;
}

/* --- Filter Bar --- */
.filter-bar {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    box-shadow: var(--shadow-sm);
}
.filter-form {
    display: flex;
    align-items: flex-end;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
    min-width: 140px;
}
.filter-label {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 0.3rem;
}
.filter-select {
    padding: 0.45rem 0.65rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 0.8rem;
    background: var(--bg-body);
    color: var(--text-primary);
    cursor: pointer;
    transition: border-color var(--transition-fast);
    font-family: inherit;
}
.filter-select:focus {
    outline: none;
    border-color: var(--kkp-teal);
    box-shadow: 0 0 0 3px rgba(8,145,178,0.1);
}
.filter-reset {
    padding: 0.45rem 0.75rem;
    background: rgba(220,38,38,0.08);
    color: #DC2626;
    border-radius: var(--radius-md);
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    white-space: nowrap;
    transition: background var(--transition-fast);
    align-self: flex-end;
}
.filter-reset:hover { background: rgba(220,38,38,0.15); color: #DC2626; }

/* --- Tab Navigation --- */
.tab-nav {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 1.25rem;
    background: var(--gray-100);
    padding: 0.25rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
}
.tab-btn {
    flex: 1;
    padding: 0.6rem 1rem;
    border: none;
    background: transparent;
    border-radius: var(--radius-md);
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--gray-500);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    transition: all var(--transition-fast);
    font-family: inherit;
}
.tab-btn:hover { color: var(--gray-800); }
.tab-btn.active {
    background: var(--bg-surface);
    color: var(--kkp-teal);
    box-shadow: var(--shadow-sm);
}
.tab-content { display: none; }
.tab-content.active { display: block; }

/* --- KPI Grid — 6 columns --- */
.kpi-grid-6 {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

/* --- KPI Cards --- */
.kpi-card {
    background: var(--bg-surface);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
    padding: 1rem 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    position: relative;
    overflow: hidden;
    transition: transform var(--transition-fast), box-shadow var(--transition-fast);
    box-shadow: var(--shadow-sm);
}
.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.kpi-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.kpi-value {
    font-size: 1.3rem;
    font-weight: 700;
    line-height: 1.1;
    color: var(--gray-900);
    letter-spacing: -0.02em;
}
.kpi-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--gray-500);
    margin-top: 0.15rem;
}
.kpi-sub {
    font-size: 0.65rem;
    color: var(--gray-400);
    margin-top: 0.1rem;
}

/* KPI Colors */
.kpi-produksi .kpi-icon  { background: rgba(8,145,178,0.1); color: #0891B2; }
.kpi-perkolam .kpi-icon  { background: rgba(99,102,241,0.1); color: #6366F1; }
.kpi-utilisasi .kpi-icon { background: rgba(139,92,246,0.1); color: #8B5CF6; }
.kpi-sr .kpi-icon         { background: rgba(22,163,74,0.1); color: #16A34A; }
.kpi-sr.warning .kpi-icon { background: rgba(217,119,6,0.1); color: #D97706; }
.kpi-sr.danger .kpi-icon  { background: rgba(220,38,38,0.1); color: #DC2626; }
.kpi-biaya .kpi-icon      { background: rgba(236,72,153,0.1); color: #EC4899; }
.kpi-aktif .kpi-icon      { background: rgba(16,185,129,0.1); color: #10B981; }

/* SR class on card border */
.kpi-card.kpi-sr.success { border-left: 3px solid #16A34A; }
.kpi-card.kpi-sr.warning { border-left: 3px solid #D97706; }
.kpi-card.kpi-sr.danger  { border-left: 3px solid #DC2626; }

/* --- Health Bar --- */
.health-bar {
    margin-bottom: 1.25rem;
}
.health-bar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1.25rem;
    border-radius: var(--radius-lg);
    gap: 1rem;
    flex-wrap: wrap;
}
.health-bar-inner.success {
    background: rgba(22,163,74,0.06);
    border: 1px solid rgba(22,163,74,0.2);
}
.health-bar-inner.warning {
    background: rgba(217,119,6,0.06);
    border: 1px solid rgba(217,119,6,0.2);
}
.health-bar-inner.danger {
    background: rgba(220,38,38,0.06);
    border: 1px solid rgba(220,38,38,0.2);
}
.health-indicator {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--gray-700);
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.health-bar-inner.success .health-indicator i { color: #16A34A; }
.health-bar-inner.warning .health-indicator i { color: #D97706; }
.health-bar-inner.danger .health-indicator i  { color: #DC2626; }
.health-stats {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.78rem;
    color: var(--gray-600);
    flex-wrap: wrap;
}
.health-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-left: 0.5rem;
}
.health-dot.success { background: #16A34A; }
.health-dot.warning { background: #D97706; }
.health-dot.danger  { background: #DC2626; }

/* --- Dashboard Cards --- */
.dash-card {
    background: var(--bg-surface);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
    padding: 1.25rem;
    margin-bottom: 1.25rem;
    transition: background-color var(--transition-slow), border-color var(--transition-slow);
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
    font-size: 0.92rem;
    font-weight: 600;
    color: var(--gray-800);
}

/* --- Grid Layouts --- */
.dash-grid-70-30 {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
.dash-grid-50-50 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
.dash-grid-40-60 {
    display: grid;
    grid-template-columns: 2fr 3fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

/* --- Map Legend --- */
.map-legend-sr {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    font-size: 0.7rem;
    color: var(--gray-500);
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}
.legend-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* --- Status Summary --- */
.status-summary-list {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    margin-top: 0.75rem;
}
.status-summary-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--gray-600);
    padding: 0.35rem 0.5rem;
    border-radius: var(--radius-sm);
    transition: background var(--transition-fast);
}
.status-summary-item:hover { background: var(--gray-100); }
.status-summary-item strong {
    margin-left: auto;
    color: var(--gray-800);
    font-size: 0.9rem;
}
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}
.status-dot.success { background: #16A34A; }
.status-dot.danger  { background: #DC2626; }
.status-dot.warning { background: #D97706; }
.status-dot.primary { background: #2563EB; }

/* --- SR Distribution Bars --- */
.sr-distribution { margin-top: 0.5rem; }
.sr-bars { display: flex; flex-direction: column; gap: 0.4rem; }
.sr-bar-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.sr-bar-label {
    font-size: 0.7rem;
    font-weight: 600;
    width: 60px;
    text-align: right;
    flex-shrink: 0;
}
.sr-bar-label.success { color: #16A34A; }
.sr-bar-label.warning { color: #D97706; }
.sr-bar-label.danger  { color: #DC2626; }
.sr-bar-label.secondary { color: var(--gray-400); }
.sr-bar-track {
    flex: 1;
    height: 8px;
    background: var(--gray-200);
    border-radius: var(--radius-full);
    overflow: hidden;
}
.sr-bar-fill {
    height: 100%;
    border-radius: var(--radius-full);
    transition: width 0.6s ease;
    min-width: 2px;
}
.sr-bar-fill.success { background: #16A34A; }
.sr-bar-fill.warning { background: #D97706; }
.sr-bar-fill.danger  { background: #DC2626; }
.sr-bar-fill.secondary { background: var(--gray-400); }
.sr-bar-count {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--gray-700);
    width: 20px;
    text-align: right;
}

/* --- Donut Legend --- */
.donut-legend {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
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

/* --- Priority Alert --- */
.priority-alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 1.25rem;
    background: rgba(220,38,38,0.06);
    border: 1px solid rgba(220,38,38,0.2);
    border-left: 4px solid #DC2626;
    border-radius: var(--radius-lg);
    margin-bottom: 1.25rem;
}
.priority-alert-icon {
    font-size: 1.25rem;
    color: #DC2626;
    flex-shrink: 0;
}
.priority-alert-body strong {
    font-size: 0.88rem;
    color: #DC2626;
    display: block;
}
.priority-alert-body span {
    font-size: 0.78rem;
    color: var(--gray-500);
}

/* --- Table Toolbar --- */
.table-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}
.table-search-box {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 0.45rem 0.75rem;
    min-width: 280px;
    transition: border-color var(--transition-fast);
}
.table-search-box:focus-within {
    border-color: var(--kkp-teal);
    box-shadow: 0 0 0 3px rgba(8,145,178,0.1);
}
.table-search-box i { color: var(--gray-400); font-size: 0.85rem; }
.table-search-input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 0.82rem;
    color: var(--text-primary);
    width: 100%;
    font-family: inherit;
}
.table-info {
    font-size: 0.78rem;
    color: var(--gray-500);
}

/* --- Monev Table --- */
.monev-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.82rem;
}
.monev-table thead th {
    background: var(--gray-100);
    padding: 0.65rem 0.75rem;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--gray-500);
    border-bottom: 2px solid var(--border-color);
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 1;
}
.monev-table tbody td {
    padding: 0.65rem 0.75rem;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
    color: var(--text-primary);
}
.monev-table tbody tr:hover {
    background: rgba(8,145,178,0.03);
}
.monev-table tbody tr.row-prioritas {
    background: rgba(220,38,38,0.03);
}
.monev-table tbody tr.row-prioritas:hover {
    background: rgba(220,38,38,0.06);
}

/* Cell styles */
.cell-lokasi {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.cell-nama { font-weight: 600; color: var(--gray-800); }
.cell-meta { font-size: 0.7rem; color: var(--gray-400); margin-top: 0.1rem; }
.cell-sub { font-size: 0.68rem; color: var(--gray-400); margin-top: 0.15rem; }
.cell-kendala { font-size: 0.75rem; color: var(--gray-600); max-width: 180px; }

.prioritas-badge {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(220,38,38,0.1);
    color: #DC2626;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    flex-shrink: 0;
}

.kolam-indicator {
    font-weight: 600;
    font-size: 0.82rem;
}
.kolam-indicator.low { color: #DC2626; }

/* SR Chip */
.sr-chip {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: 0.75rem;
}
.sr-chip.success { background: rgba(22,163,74,0.1); color: #16A34A; }
.sr-chip.warning { background: rgba(217,119,6,0.1); color: #D97706; }
.sr-chip.danger  { background: rgba(220,38,38,0.1); color: #DC2626; }
.sr-chip.secondary { background: var(--gray-100); color: var(--gray-400); }

/* Status Chip */
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

/* Progress Mini Bar */
.progress-mini {
    width: 60px;
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

/* --- Empty State --- */
.empty-state-sm {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--gray-500);
}
.empty-state-sm p { margin: 0.5rem 0 1rem; font-size: 0.85rem; }

/* --- Map Dark Mode --- */
[data-theme="dark"] .leaflet-tile-pane {
    filter: invert(1) hue-rotate(180deg) brightness(90%) contrast(90%);
}

/* --- Animation --- */
.animate-fade-in-up {
    animation: fadeInUp 0.5s ease forwards;
    opacity: 0;
}
.delay-100 { animation-delay: 0.1s; }
.delay-200 { animation-delay: 0.15s; }
.delay-300 { animation-delay: 0.2s; }
.delay-400 { animation-delay: 0.25s; }
.delay-500 { animation-delay: 0.3s; }
.delay-600 { animation-delay: 0.35s; }

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1400px) {
    .kpi-grid-6 { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 1024px) {
    .dash-grid-70-30,
    .dash-grid-50-50,
    .dash-grid-40-60 { grid-template-columns: 1fr; }
    .kpi-grid-6 { grid-template-columns: repeat(2, 1fr); }
    .filter-form { flex-direction: column; }
    .filter-group { min-width: unset; }
}
@media (max-width: 768px) {
    .monev-header { flex-direction: column; }
    .kpi-grid-6 { grid-template-columns: 1fr 1fr; }
    .tab-btn { font-size: 0.75rem; padding: 0.5rem; }
    .table-search-box { min-width: unset; width: 100%; }
}
@media (max-width: 480px) {
    .kpi-grid-6 { grid-template-columns: 1fr; }
    .health-stats { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // TAB SWITCHING
    // ============================================================
    window.switchTab = function(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');
        document.querySelector('.tab-btn[data-tab="' + tabName + '"]').classList.add('active');

        // Trigger chart resize after tab switch
        setTimeout(() => window.dispatchEvent(new Event('resize')), 100);
    };

    // ============================================================
    // TABLE SEARCH
    // ============================================================
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('#monevTable tbody tr').forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                row.style.display = searchData.includes(query) ? '' : 'none';
            });
        });
    }

    // ============================================================
    // MAP — SR-Based Markers
    // ============================================================
    const mapLocations = @json($mapLocations);
    const mapEl = document.getElementById('monevMap');

    if (mapLocations.length > 0 && mapEl) {
        const map = L.map('monevMap').setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        mapLocations.forEach(loc => {
            if (!loc.lat || !loc.lng) return;
            const marker = L.circleMarker([parseFloat(loc.lat), parseFloat(loc.lng)], {
                radius: 8,
                fillColor: loc.srColor,
                color: 'white',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.85,
            });
            const srText = loc.sr !== null ? `SR: ${loc.sr}%` : 'SR: Belum ada data';
            marker.bindPopup(`
                <div style="min-width:200px;font-size:13px;">
                    <b style="color:#0891B2;">${loc.name}</b><br>
                    <span style="color:#6B7280;">${loc.kabupaten}, ${loc.provinsi}</span><br>
                    <div style="margin-top:6px; display:flex; gap:6px; flex-wrap:wrap;">
                        <span style="padding:2px 8px;border-radius:99px;font-size:11px;font-weight:600;background:${loc.srColor}15;color:${loc.srColor};">${srText}</span>
                        <span style="padding:2px 8px;border-radius:99px;font-size:11px;font-weight:600;background:#0891B220;color:#0891B2;">${loc.komoditas || '-'}</span>
                    </div>
                    ${loc.produksi > 0 ? `<div style="margin-top:4px;font-size:11px;color:#6B7280;">Produksi: ${Number(loc.produksi).toLocaleString('id-ID')} kg</div>` : ''}
                </div>
            `);
            marker.addTo(map);
        });

        const bounds = mapLocations.filter(l => l.lat && l.lng).map(l => [parseFloat(l.lat), parseFloat(l.lng)]);
        if (bounds.length > 0) map.fitBounds(bounds, { padding: [30, 30] });
    } else if (mapEl) {
        mapEl.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9CA3AF;">Belum ada data lokasi yang terfilter</div>';
    }

    // ============================================================
    // DONUT — STATUS MONITORING
    // ============================================================
    const donutStatusEl = document.getElementById('donutStatus');
    if (donutStatusEl) {
        new Chart(donutStatusEl, {
            type: 'doughnut',
            data: {
                labels: ['On Track', 'Bermasalah', 'Vakum', 'Selesai'],
                datasets: [{
                    data: [{{ $statusBreakdown['on_track'] }}, {{ $statusBreakdown['bermasalah'] }}, {{ $statusBreakdown['vakum'] }}, {{ $statusBreakdown['selesai'] }}],
                    backgroundColor: ['#16A34A', '#DC2626', '#D97706', '#2563EB'],
                    borderWidth: 2,
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--bg-surface').trim() || '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }

    // ============================================================
    // BAR — PRODUKSI PER WILAYAH
    // ============================================================
    const barWilayahEl = document.getElementById('barProduksiWilayah');
    if (barWilayahEl) {
        const wilData = @json($produksiPerProvinsi);
        new Chart(barWilayahEl, {
            type: 'bar',
            data: {
                labels: wilData.map(d => d.provinsi ? d.provinsi.substring(0, 15) : '-'),
                datasets: [{
                    label: 'Volume (kg)',
                    data: wilData.map(d => d.total_volume),
                    backgroundColor: '#0891B2',
                    borderRadius: 6,
                    maxBarThickness: 40,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${Number(ctx.parsed.x).toLocaleString('id-ID')} kg` } }
                },
                scales: {
                    x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: v => v.toLocaleString('id-ID') } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    // ============================================================
    // LINE — TREN PRODUKSI
    // ============================================================
    const lineTrenEl = document.getElementById('lineTrenProduksi');
    if (lineTrenEl) {
        const trenData = @json($trenProduksi);
        new Chart(lineTrenEl, {
            type: 'line',
            data: {
                labels: trenData.map(d => d.label),
                datasets: [
                    {
                        label: 'Volume Panen (kg)',
                        data: trenData.map(d => d.volume),
                        borderColor: '#0891B2',
                        backgroundColor: 'rgba(8,145,178,0.08)',
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: '#0891B2',
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Rata-rata SR (%)',
                        data: trenData.map(d => d.avg_sr),
                        borderColor: '#16A34A',
                        backgroundColor: 'rgba(22,163,74,0.08)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 3,
                        pointBackgroundColor: '#16A34A',
                        fill: false,
                        tension: 0.4,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', padding: 15, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                if (ctx.datasetIndex === 0) return ` ${Number(ctx.parsed.y).toLocaleString('id-ID')} kg`;
                                return ` SR: ${ctx.parsed.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    y:  { beginAtZero: true, position: 'left', grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: v => v.toLocaleString('id-ID') } },
                    y1: { beginAtZero: true, position: 'right', max: 100, grid: { drawOnChartArea: false }, ticks: { callback: v => v + '%' } },
                    x:  { grid: { display: false } }
                }
            }
        });
    }

    // ============================================================
    // DONUT — DISTRIBUSI MASALAH
    // ============================================================
    const donutMasalahEl = document.getElementById('donutMasalah');
    if (donutMasalahEl) {
        const masalahData = @json($distribusiMasalah);
        const masalahLabels = Object.keys(masalahData);
        const masalahValues = Object.values(masalahData);
        const masalahColors = ['#DC2626','#D97706','#0891B2','#16A34A','#8B5CF6','#EC4899','#6366F1','#F59E0B'];
        new Chart(donutMasalahEl, {
            type: 'doughnut',
            data: {
                labels: masalahLabels,
                datasets: [{
                    data: masalahValues,
                    backgroundColor: masalahColors.slice(0, masalahLabels.length),
                    borderWidth: 2,
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--bg-surface').trim() || '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: { legend: { display: false } }
            }
        });
    }

    // ============================================================
    // GROUPED BAR — PERBANDINGAN WILAYAH
    // ============================================================
    const barPerbEl = document.getElementById('barPerbandingan');
    if (barPerbEl) {
        const perbData = @json($perbandinganWilayah);
        new Chart(barPerbEl, {
            type: 'bar',
            data: {
                labels: perbData.map(d => d.provinsi ? d.provinsi.substring(0, 15) : '-'),
                datasets: [
                    {
                        label: 'Avg SR (%)',
                        data: perbData.map(d => d.avg_sr ? Number(d.avg_sr).toFixed(1) : 0),
                        backgroundColor: 'rgba(22,163,74,0.7)',
                        borderRadius: 4,
                    },
                    {
                        label: 'Avg Produksi (kg)',
                        data: perbData.map(d => d.avg_produksi ? Number(d.avg_produksi).toFixed(0) : 0),
                        backgroundColor: 'rgba(8,145,178,0.7)',
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', padding: 15, font: { size: 11 } } },
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

});
</script>
@endpush
