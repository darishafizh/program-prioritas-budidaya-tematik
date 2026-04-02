@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Detail Skor: {{ $locationScore->kecamatan }}</h1>
        <p class="page-subtitle">{{ $locationScore->kabupaten }}, {{ $locationScore->provinsi }}</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'Scoring', 'url' => route('scoring.index')],
        ['label' => $locationScore->kecamatan, 'url' => '#']
    ]" />
</div>

<!-- Status Overview -->
<div class="section-card mb-4">
    <div class="section-body">
        <div class="status-overview">
            <div class="status-main">
                <div class="status-circle {{ $locationScore->status_color }}">
                    <span class="status-score">{{ number_format($locationScore->total_score, 1) }}</span>
                </div>
                <div class="status-info">
                    <span class="status-badge-large {{ $locationScore->status_color }}">
                        {{ $locationScore->status_icon }} {{ $locationScore->status }}
                    </span>
                    <p class="status-desc">
                        @if($locationScore->status == 'SANGAT LAYAK')
                        Lokasi ini sangat berpotensi untuk pengembangan budidaya tematik bioflok dan dapat diprioritaskan.
                        @elseif($locationScore->status == 'LAYAK')
                        Lokasi ini layak untuk pengembangan budidaya tematik bioflok dengan beberapa perbaikan minor.
                        @elseif($locationScore->status == 'CUKUP LAYAK')
                        Lokasi ini cukup layak namun perlu perhatian pada beberapa aspek sebelum pengembangan.
                        @else
                        Lokasi ini belum memenuhi kriteria kelayakan. Perlu evaluasi mendalam.
                        @endif
                    </p>
                </div>
            </div>
            <div class="status-breakdown">
                <div class="breakdown-item">
                    <span class="breakdown-label">KDMP (40%)</span>
                    <div class="breakdown-bar">
                        <div class="breakdown-fill" style="width: {{ $locationScore->kdmp_score }}%; background: var(--kkp-teal);"></div>
                    </div>
                    <span class="breakdown-value">{{ number_format($locationScore->kdmp_score, 1) }}</span>
                </div>
                <div class="breakdown-item">
                    <span class="breakdown-label">Masyarakat (30%)</span>
                    <div class="breakdown-bar">
                        <div class="breakdown-fill" style="width: {{ $locationScore->masyarakat_score }}%; background: var(--kkp-navy);"></div>
                    </div>
                    <span class="breakdown-value">{{ number_format($locationScore->masyarakat_score, 1) }}</span>
                </div>
                <div class="breakdown-item">
                    <span class="breakdown-label">SPPG (30%)</span>
                    <div class="breakdown-bar">
                        <div class="breakdown-fill" style="width: {{ $locationScore->sppg_score }}%; background: #F59E0B;"></div>
                    </div>
                    <span class="breakdown-value">{{ number_format($locationScore->sppg_score, 1) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Scores Grid -->
<div class="grid grid-cols-3 gap-4 mb-4">
    <!-- KDMP Detail -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon teal">K</div>
            <h3 class="section-title">Detail Skor KDMP</h3>
        </div>
        <div class="section-body">
            <div class="detail-score-list">
                <div class="detail-score-item">
                    <span>Administrasi</span>
                    <span class="detail-score">{{ number_format($locationScore->kdmp_admin_score, 1) }}%</span>
                </div>
                <div class="detail-score-item">
                    <span>Kriteria Lahan</span>
                    <span class="detail-score">{{ number_format($locationScore->kdmp_lahan_score, 1) }}%</span>
                </div>
                <div class="detail-score-item">
                    <span>Status Instalasi</span>
                    <span class="detail-score">{{ number_format($locationScore->kdmp_instalasi_score, 1) }}%</span>
                </div>
                <div class="detail-score-item">
                    <span>Progres Teknis</span>
                    <span class="detail-score">{{ number_format($locationScore->kdmp_progres_score, 1) }}%</span>
                </div>
            </div>
            @if($locationScore->kdmpSurvey)
            <div class="mt-3 pt-3 border-t">
                <a href="{{ route('kdmp.show', $locationScore->kdmpSurvey) }}" class="btn btn-sm btn-outline w-full">
                    Lihat Survey KDMP
                </a>
            </div>
            @else
            <p class="text-sm text-muted mt-3">Belum ada data survey KDMP</p>
            @endif
        </div>
    </div>

    <!-- Masyarakat Detail -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon navy">M</div>
            <h3 class="section-title">Detail Skor Masyarakat</h3>
        </div>
        <div class="section-body">
            <div class="detail-score-list">
                <div class="detail-score-item">
                    <span>Tanggapan</span>
                    <span class="detail-score">{{ number_format($locationScore->masy_tanggapan_score, 1) }}%</span>
                </div>
                <div class="detail-score-item">
                    <span>Tingkat Kebahagiaan</span>
                    <span class="detail-score">{{ number_format($locationScore->masy_likert_score, 1) }}%</span>
                </div>
                <div class="detail-score-item">
                    <span>Kelembagaan</span>
                    <span class="detail-score">{{ number_format($locationScore->masy_kelembagaan_score, 1) }}%</span>
                </div>
            </div>
            @if($locationScore->masyarakatSurvey)
            <div class="mt-3 pt-3 border-t">
                <a href="{{ route('masyarakat.show', $locationScore->masyarakatSurvey) }}" class="btn btn-sm btn-outline w-full">
                    Lihat Survey Masyarakat
                </a>
            </div>
            @else
            <p class="text-sm text-muted mt-3">Belum ada data survey Masyarakat</p>
            @endif
        </div>
    </div>

    <!-- SPPG Detail -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon warning">S</div>
            <h3 class="section-title">Detail Skor SPPG</h3>
        </div>
        <div class="section-body">
            <div class="detail-score-list">
                <div class="detail-score-item">
                    <span>Supply-Demand</span>
                    <span class="detail-score">{{ number_format($locationScore->sppg_demand_score, 1) }}%</span>
                </div>
                <div class="detail-score-item">
                    <span>Minat Kerjasama</span>
                    <span class="detail-score">{{ number_format($locationScore->sppg_minat_score, 1) }}%</span>
                </div>
                <div class="detail-score-item">
                    <span>Infrastruktur</span>
                    <span class="detail-score">{{ number_format($locationScore->sppg_infra_score, 1) }}%</span>
                </div>
            </div>
            @if($locationScore->sppgSurvey)
            <div class="mt-3 pt-3 border-t">
                <a href="{{ route('sppg.show', $locationScore->sppgSurvey) }}" class="btn btn-sm btn-outline w-full">
                    Lihat Survey SPPG
                </a>
            </div>
            @else
            <p class="text-sm text-muted mt-3">Belum ada data survey SPPG</p>
            @endif
        </div>
    </div>
</div>

<!-- Radar Chart -->
<div class="section-card mb-4">
    <div class="section-header">
        <div class="section-icon success">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
            </svg>
        </div>
        <h3 class="section-title">Visualisasi Skor</h3>
    </div>
    <div class="section-body">
        <div class="chart-container" style="max-width:500px;margin:0 auto;">
            <canvas id="radarChart"></canvas>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="form-actions">
    <a href="{{ route('scoring.index') }}" class="btn btn-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>
</div>
@endsection

@push('styles')
<style>
.status-overview {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.status-main {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex: 0 0 auto;
}

.status-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.status-circle.success { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
.status-circle.primary { background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); }
.status-circle.warning { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
.status-circle.danger { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); }

.status-score {
    font-size: 2rem;
    font-weight: 700;
    color: white;
}

.status-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.status-badge-large {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: 1rem;
}

.status-badge-large.success { background: rgba(16, 185, 129, 0.1); color: #059669; }
.status-badge-large.primary { background: rgba(59, 130, 246, 0.1); color: #2563EB; }
.status-badge-large.warning { background: rgba(245, 158, 11, 0.1); color: #D97706; }
.status-badge-large.danger { background: rgba(239, 68, 68, 0.1); color: #DC2626; }

.status-desc {
    font-size: 0.9rem;
    color: var(--gray-600);
    max-width: 350px;
}

.status-breakdown {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.breakdown-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.breakdown-label {
    flex: 0 0 130px;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.breakdown-bar {
    flex: 1;
    height: 12px;
    background: var(--gray-200);
    border-radius: var(--radius-full);
    overflow: hidden;
}

.breakdown-fill {
    height: 100%;
    border-radius: var(--radius-full);
    transition: width 0.5s ease;
}

.breakdown-value {
    flex: 0 0 50px;
    font-weight: 600;
    text-align: right;
}

.detail-score-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.detail-score-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-100);
}

.detail-score-item:last-child {
    border-bottom: none;
}

.detail-score {
    font-weight: 600;
    color: var(--kkp-navy);
}

.chart-container {
    position: relative;
    height: 350px;
}

@media (max-width: 768px) {
    .status-overview {
        flex-direction: column;
    }
    
    .grid-cols-3 {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('radarChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: [
                'Administrasi', 
                'Kriteria Lahan', 
                'Status Instalasi', 
                'Progres Teknis',
                'Tanggapan Masyarakat',
                'Tingkat Kebahagiaan',
                'Kelembagaan',
                'Supply-Demand',
                'Minat Kerjasama',
                'Infrastruktur SPPG'
            ],
            datasets: [{
                label: 'Skor (%)',
                data: [
                    {{ $locationScore->kdmp_admin_score }},
                    {{ $locationScore->kdmp_lahan_score }},
                    {{ $locationScore->kdmp_instalasi_score }},
                    {{ $locationScore->kdmp_progres_score }},
                    {{ $locationScore->masy_tanggapan_score }},
                    {{ $locationScore->masy_likert_score }},
                    {{ $locationScore->masy_kelembagaan_score }},
                    {{ $locationScore->sppg_demand_score }},
                    {{ $locationScore->sppg_minat_score }},
                    {{ $locationScore->sppg_infra_score }}
                ],
                backgroundColor: 'rgba(0, 122, 138, 0.2)',
                borderColor: 'rgba(0, 122, 138, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(0, 122, 138, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(0, 122, 138, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
