@extends('layouts.app')

@section('content')

{{-- ============================================================ --}}
{{-- HEADER + FILTER BAR --}}
{{-- ============================================================ --}}
<div class="monev-header animate-fade-in-up">
    <div>
        <h1 class="monev-title">Dashboard Monev</h1>
        <p class="monev-subtitle">Program Budidaya Tematik · Decision-Driven Monitoring</p>
    </div>
    <div class="monev-meta">
        @php
            $txtMasalah = $distribusiMasalah->count() > 0 ? $distribusiMasalah->keys()->first() : 'Tidak ada masalah signifikan';
            $lokasiKritis = $detailLokasi->where('is_prioritas', true)->count();
        @endphp
        <div class="insight-badge pulse">
            <i class="fa-solid fa-lightbulb" style="color: #F59E0B;"></i>
            <strong>Insight:</strong> {{ $lokasiKritis }} lokasi butuh intervensi | Kendala dominan: {{ $txtMasalah }}
        </div>
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

    {{-- ACTIONABLE ALERTS --}}
    @php
        $lokasiNonOptimal = $detailLokasi->where('utilisasi', '<', 50)->whereNotNull('utilisasi')->count();
        $lokasiSRRendah = $srDistribution['danger'] ?? 0;
        $lokasiVakum = $statusBreakdown['vakum'] ?? 0;
    @endphp
    <div class="action-alerts-grid animate-fade-in-up delay-300">
        <div class="action-alert-card bg-red-soft">
            <div class="alert-icon text-red"><i class="fa-solid fa-heart-crack"></i></div>
            <div class="alert-detail">
                <h3>{{ $lokasiSRRendah }} Lokasi</h3>
                <p>Survival Rate Rendah (< 70%)</p>
            </div>
            <button onclick="switchTab('teknis'); document.getElementById('tableSearch').value='merah'; setTimeout(()=>document.getElementById('tableSearch').dispatchEvent(new Event('input')), 100);" class="alert-action text-red btn-link border-0 bg-transparent p-0">Lihat Detail <i class="fa-solid fa-arrow-right"></i></button>
        </div>
        <div class="action-alert-card bg-orange-soft">
            <div class="alert-icon text-orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="alert-detail">
                <h3>{{ $lokasiNonOptimal }} Lokasi</h3>
                <p>Utilisasi Kolam Tidak Optimal (< 50%)</p>
            </div>
            <button onclick="switchTab('teknis')" class="alert-action text-orange btn-link border-0 bg-transparent p-0">Tinjau Utilisasi <i class="fa-solid fa-arrow-right"></i></button>
        </div>
        <div class="action-alert-card bg-gray-soft">
            <div class="alert-icon text-gray"><i class="fa-solid fa-circle-pause"></i></div>
            <div class="alert-detail">
                <h3>{{ $lokasiVakum }} Lokasi</h3>
                <p>Status Vakum / Tidak Aktif</p>
            </div>
            <button onclick="switchTab('teknis'); document.getElementById('tableSearch').value='vakum'; setTimeout(()=>document.getElementById('tableSearch').dispatchEvent(new Event('input')), 100);" class="alert-action text-gray btn-link border-0 bg-transparent p-0">Periksa Status <i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </div>

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

    {{-- Map + Prioritas Nasional --}}
    <div class="dash-grid-70-30 animate-fade-in-up delay-400">
        <div class="dash-card p-0 overflow-hidden">
            <div class="dash-card-header p-3 mb-0 border-bottom">
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
            <div id="monevMap" style="height:460px;"></div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="fa-solid fa-fire" style="color:#DC2626;"></i>
                    Prioritas Nasional (Intervensi)
                </div>
            </div>
            <div class="national-priority-list">
                @if($prioritasIntervensi->count() > 0)
                    @foreach($prioritasIntervensi->take(6) as $idx => $p)
                        <div class="priority-item {{ $idx < 3 ? 'high-priority' : 'medium-priority' }}">
                            <div class="priority-rank">{{ $idx + 1 }}</div>
                            <div class="priority-detail">
                                <h4 class="text-truncate" style="max-width:180px;" title="{{ $p['nama'] }}">{{ $p['nama'] }}</h4>
                                <p class="text-truncate" style="max-width:180px;">{{ $p['kabupaten'] }}, {{ $p['provinsi'] }}</p>
                            </div>
                            <div class="priority-badge">
                                {{ number_format($p['sr'], 1) }}% SR
                            </div>
                        </div>
                    @endforeach
                    @if($prioritasIntervensi->count() > 6)
                        <button onclick="switchTab('teknis')" class="btn btn-sm btn-light w-100 mt-2 text-danger fw-bold">Lihat Semua ({{ $prioritasIntervensi->count() }})</button>
                    @endif
                @else
                    <div class="empty-state-sm py-5">
                        <i class="fa-solid fa-check-circle" style="color:#16A34A; font-size:2.5rem; margin-bottom:1rem;"></i>
                        <h6 class="mb-1 text-success">Kondisi Terkendali</h6>
                        <p class="text-muted text-center" style="font-size:0.8rem">Tidak ada lokasi prioritas mendesak saat ini.</p>
                    </div>
                @endif
            </div>
            
            <div class="mt-3 p-3 bg-gray-soft rounded border">
                <div class="dash-card-title mb-2" style="font-size:0.8rem;">
                    <i class="fa-solid fa-circle-check text-success"></i> Status Keseluruhan
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span style="font-size:0.8rem; color:var(--gray-600);">On Track</span>
                    <strong style="font-size:0.85rem;">{{ $statusBreakdown['on_track'] }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span style="font-size:0.8rem; color:var(--gray-600);">Bermasalah</span>
                    <strong style="font-size:0.85rem; color:#DC2626;">{{ $statusBreakdown['bermasalah'] }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- TAB 2: MANAJERIAL LAYER --}}
{{-- ============================================================ --}}
<div class="tab-content" id="tab-manajerial">

    {{-- Row 1: Gap Analysis & Leaderboard --}}
    <div class="dash-grid-60-40 animate-fade-in-up">
        <div class="dash-card gap-analysis-card">
            <div class="dash-card-header mb-3 border-bottom pb-2">
                <div class="dash-card-title">
                    <i class="fa-solid fa-bullseye" style="color:#0891B2;"></i>
                    Analisis Gap Nasional <span class="badge border bg-light text-muted ms-2" style="font-size:0.6rem">Mockup Visual</span>
                </div>
            </div>
            <div class="gap-container px-2">
                <div class="gap-item mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <strong style="font-size:0.85rem">Realisasi vs Target Produksi</strong>
                        <span class="text-success fw-bold" style="font-size:0.85rem">78% Terpenuhi</span>
                    </div>
                    <div class="progress" style="height: 12px; border-radius:10px;">
                        <div class="progress-bar" role="progressbar" style="width: 78%; background-color:#0891B2;" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1" style="font-size:0.75rem; color:var(--gray-500);">
                        <span>Realisasi: {{ number_format($totalProduksi, 0, ',', '.') }} kg</span>
                        <span>Target: {{ number_format($totalProduksi * 1.28, 0, ',', '.') }} kg</span>
                    </div>
                </div>
                
                <div class="gap-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <strong style="font-size:0.85rem">Supply Aktual vs Estimasi Demand</strong>
                        <span class="text-warning fw-bold" style="font-size:0.85rem">Defisit 35%</span>
                    </div>
                    <div class="progress" style="height: 12px; border-radius:10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1" style="font-size:0.75rem; color:var(--gray-500);">
                        <span>Kapasitas Supply: 65%</span>
                        <span>Demand Pasar Lokal/Ekspor</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-3 rounded" style="background: rgba(8,145,178,0.05); border:1px solid rgba(8,145,178,0.2);">
                <div class="d-flex align-items-start gap-2">
                    <i class="fa-solid fa-lightbulb mt-1" style="color:#0891B2;"></i>
                    <span style="font-size:0.8rem; color:var(--gray-700); line-height:1.4;">
                        <strong>Rekomendasi Manajerial:</strong> Defisit supply dapat ditutup dengan mereaktivasi {{ $statusBreakdown['vakum'] ?? 0 }} lokasi vakum serta menyelesaikan 
                        "<strong class="text-danger">{{ $distribusiMasalah->count() > 0 ? $distribusiMasalah->keys()->first() : 'Operasional' }}</strong>" 
                        sebagai masalah paling dominan di area produksi.
                    </span>
                </div>
            </div>
        </div>

        <div class="dash-card p-0 overflow-hidden d-flex flex-column">
            <div class="dash-card-header p-3 border-bottom mb-0">
                <div class="dash-card-title">
                    <i class="fa-solid fa-trophy" style="color:#D97706;"></i>
                    Leaderboard Kelayakan Wilayah
                </div>
            </div>
            <div class="d-flex flex-grow-1">
                <div class="w-50 border-end" style="background:#f8fafc;">
                    <div class="text-center py-2 border-bottom mb-2" style="font-size:0.7rem; font-weight:700; color:var(--gray-500); letter-spacing:1px; text-transform:uppercase;">Top 5 Terbaik (SR)</div>
                    <div class="px-3 pb-3">
                        @foreach($perbandinganWilayah->sortByDesc('avg_sr')->take(5) as $idx => $wil)
                        <div class="d-flex align-items-center mb-2 {{ $idx == 0 ? 'bg-success text-white rounded p-1 shadow-sm' : '' }}">
                            <div class="fw-bold fs-6 me-2 {{ $idx == 0 ? 'text-white' : 'text-muted' }}" style="width:20px; text-align:center;">{{ $idx+1 }}</div>
                            <div class="flex-grow-1 text-truncate" style="font-size:0.8rem;" title="{{ $wil->provinsi }}">{{ $wil->provinsi }}</div>
                            <div class="fw-bold" style="font-size:0.8rem;">{{ number_format($wil->avg_sr, 0) }}%</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="w-50" style="background:#fffcfc;">
                    <div class="text-center py-2 border-bottom mb-2" style="font-size:0.7rem; font-weight:700; color:#DC2626; letter-spacing:1px; text-transform:uppercase;">Bottom 5 (SR)</div>
                    <div class="px-3 pb-3">
                        @foreach($perbandinganWilayah->sortBy('avg_sr')->take(5) as $idx => $wil)
                        <div class="d-flex align-items-center mb-2">
                            <div class="fw-bold fs-6 me-2 text-danger" style="width:20px; text-align:center;">{{ $idx+1 }}</div>
                            <div class="flex-grow-1 text-truncate" style="font-size:0.8rem;" title="{{ $wil->provinsi }}">{{ $wil->provinsi }}</div>
                            <div class="fw-bold text-danger" style="font-size:0.8rem;">{{ number_format($wil->avg_sr, 0) }}%</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Produksi per Wilayah + Tren Produksi --}}
    <div class="dash-grid-50-50 animate-fade-in-up">
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="fa-solid fa-chart-bar" style="color:#0891B2;"></i>
                    Produksi per Wilayah (kg)
                </div>
            </div>
            @if($produksiPerProvinsi->count() > 0)
            <div style="height:300px;">
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
            <div style="height:300px;">
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

    {{-- Row 3: Distribusi Masalah + Perbandingan --}}
    <div class="dash-grid-40-60 animate-fade-in-up">
        <div class="dash-card">
            <div class="dash-card-header mb-1">
                <div class="dash-card-title">
                    <i class="fa-solid fa-circle-exclamation" style="color:#D97706;"></i>
                    Distribusi Permasalahan
                </div>
            </div>
            @if($distribusiMasalah->count() > 0)
            <div style="height:250px; display:flex; align-items:center;">
                <canvas id="donutMasalah"></canvas>
            </div>
            <div class="donut-legend mt-2">
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
                    Perbandingan Performa Wilayah Historis
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
                        <th class="text-center">Prioritas</th>
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
                        <td class="text-center">
                            @if($loc['is_prioritas'])
                                <span class="badge py-1 px-2" style="background:#DC2626; font-size:0.65rem; font-weight:700; letter-spacing:1px;">TINGGI</span>
                            @elseif($loc['sr'] !== null && $loc['sr'] < 75)
                                <span class="badge py-1 px-2 text-dark" style="background:#FBBF24; font-size:0.65rem; font-weight:700; letter-spacing:1px;">SEDANG</span>
                            @else
                                <span class="badge py-1 px-2" style="background:#10B981; font-size:0.65rem; font-weight:700; letter-spacing:1px;">RENDAH</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding:2rem; color:var(--gray-400);">
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

/* --- Decision Elements CSS --- */
.insight-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.85rem;
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    color: #B45309;
}
[data-theme="dark"] .insight-badge { color: #FBBF24; }
.pulse {
    animation: slow-pulse 2s infinite;
}
@keyframes slow-pulse {
    0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
    70% { box-shadow: 0 0 0 5px rgba(245, 158, 11, 0); }
    100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
}

.action-alerts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.action-alert-card {
    border-radius: var(--radius-lg);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    border: 1px solid transparent;
}
.bg-red-soft { background: rgba(220, 38, 38, 0.05); border-color: rgba(220, 38, 38, 0.15); }
.bg-orange-soft { background: rgba(217, 119, 6, 0.05); border-color: rgba(217, 119, 6, 0.15); }
.bg-gray-soft { background: rgba(107, 114, 128, 0.05); border-color: rgba(107, 114, 128, 0.15); }
.text-red { color: #DC2626; }
.text-orange { color: #D97706; }
.text-gray { color: #4B5563; }

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    box-shadow: var(--shadow-sm);
    flex-shrink: 0;
}
[data-theme="dark"] .alert-icon { background: var(--gray-800); }
.alert-detail h3 { margin: 0; font-size: 1.1rem; font-weight: 800; line-height: 1.2; }
.alert-detail p { margin: 0; font-size: 0.75rem; font-weight: 500; opacity: 0.8; }
.alert-action {
    margin-left: auto;
    font-size: 0.75rem;
    font-weight: 700;
    text-decoration: none;
    padding: 0.3rem 0.6rem;
    border-radius: var(--radius-md);
    background: #fff;
    box-shadow: var(--shadow-sm);
    transition: transform 0.2s;
    cursor: pointer;
}
.alert-action:hover { transform: translateX(3px); }
[data-theme="dark"] .alert-action { background: var(--gray-800); }

/* Dark mode alert cards */
[data-theme="dark"] .bg-red-soft { background: rgba(220, 38, 38, 0.12); border-color: rgba(220, 38, 38, 0.25); }
[data-theme="dark"] .bg-orange-soft { background: rgba(217, 119, 6, 0.12); border-color: rgba(217, 119, 6, 0.25); }
[data-theme="dark"] .bg-gray-soft { background: rgba(107, 114, 128, 0.12); border-color: rgba(107, 114, 128, 0.25); }
[data-theme="dark"] .alert-detail h3 { color: #F3F4F6; }
[data-theme="dark"] .alert-detail p { color: #D1D5DB; }
[data-theme="dark"] .text-red { color: #F87171; }
[data-theme="dark"] .text-orange { color: #FBBF24; }
[data-theme="dark"] .text-gray { color: #9CA3AF; }
.action-alert-card { user-select: none; -webkit-user-select: none; }

.national-priority-list {
    padding: 0.5rem 1rem 1rem;
}
.priority-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px dashed var(--border-color);
}
.priority-item:last-child { border-bottom: none; }
.priority-rank {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    background: var(--gray-200);
    color: var(--gray-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    margin-right: 0.75rem;
}
.priority-item.high-priority .priority-rank { background: #FEE2E2; color: #DC2626; }
.priority-item.medium-priority .priority-rank { background: #FEF3C7; color: #D97706; }
.priority-detail h4 { margin: 0; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
.priority-detail p { margin: 0; font-size: 0.7rem; color: var(--gray-500); }
.priority-badge {
    margin-left: auto;
    font-size: 0.7rem;
    font-weight: 700;
    background: #FEE2E2;
    color: #DC2626;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
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
.dash-grid-60-40 {
    display: grid;
    grid-template-columns: 3fr 2fr;
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
    .dash-grid-40-60,
    .dash-grid-60-40 { grid-template-columns: 1fr; }
    .kpi-grid-6 { grid-template-columns: repeat(2, 1fr); }
    .filter-form { flex-direction: column; }
    .filter-group { min-width: unset; }
    .action-alerts-grid { grid-template-columns: 1fr; }
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
