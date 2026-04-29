@extends('layouts.app')

@push('styles')
<style>
    .progres-hero {
        background: linear-gradient(135deg, #0B1929 0%, #164E63 60%, #0891B2 100%);
        border-radius: var(--radius-xl); padding: 2rem 2.25rem; color: #fff;
        position: relative; overflow: hidden; margin-bottom: 1.75rem;
    }
    .progres-hero::before {
        content: ''; position: absolute; top: -60%; right: -10%; width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(6,182,212,0.15) 0%, transparent 70%); border-radius: 50%;
    }
    .progres-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; position: relative; z-index: 1; }
    .progres-hero-info { flex: 1; }
    .progres-hero-badge {
        display: inline-flex; align-items: center; gap: 0.375rem; background: rgba(255,255,255,0.12);
        backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); padding: 0.3rem 0.75rem;
        border-radius: var(--radius-full); font-size: 0.7rem; font-weight: 500; color: rgba(255,255,255,0.85);
        margin-bottom: 0.75rem; letter-spacing: 0.03em; text-transform: uppercase;
    }
    .progres-hero h1 { font-size: 1.65rem; font-weight: 700; color: #fff; margin-bottom: 0.35rem; }
    .progres-hero-subtitle { font-size: 0.85rem; color: rgba(255,255,255,0.65); display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
    .progres-hero-subtitle .dot { width: 3px; height: 3px; background: rgba(255,255,255,0.35); border-radius: 50%; }
    .progres-hero-actions { display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; position: relative; z-index: 1; }
    .hero-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: var(--radius-md); font-size: 0.78rem; font-weight: 500; text-decoration: none; transition: all 200ms ease; border: none; cursor: pointer; }
    .hero-btn-outline { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); color: #fff; }
    .hero-btn-outline:hover { background: rgba(255,255,255,0.18); color: #fff; }
    .hero-btn-primary { background: var(--kkp-cyan); color: #0B1929; font-weight: 600; }
    .hero-btn-primary:hover { background: #22d3ee; color: #0B1929; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(6,182,212,0.3); }
    .hero-btn-danger { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #FCA5A5; }
    .hero-btn-danger:hover { background: rgba(239,68,68,0.25); color: #fff; }

    .progres-summary-bar-fill { height: 100%; border-radius: 3px; transition: width 600ms ease; }

    .chart-card { background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-xl); margin-bottom: 1.75rem; overflow: hidden; }
    .chart-card-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); }
    .chart-card-header h3 { font-size: 0.95rem; font-weight: 600; margin: 0; }
    .chart-card-body { padding: 1.5rem; }

    .records-card { background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-xl); overflow: hidden; }
    .records-card-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); }
    .records-card-header h3 { font-size: 0.95rem; font-weight: 600; margin: 0; }

    .pf-record { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); transition: background 200ms ease; }
    .pf-record:last-child { border-bottom: none; }
    .pf-record:hover { background: var(--gray-50); }
    .pf-record-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 0.75rem; }
    .pf-record-period { text-align: center; min-width: 60px; }
    .pf-record-month { font-size: 0.82rem; font-weight: 700; color: var(--kkp-teal); }
    .pf-record-year { font-size: 0.72rem; color: var(--gray-400); }
    .pf-bars { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.75rem; }
    .pf-bar-item label { font-size: 0.68rem; font-weight: 600; color: var(--gray-500); margin-bottom: 0.25rem; display: block; }
    .pf-bar-track { height: 8px; background: var(--gray-200); border-radius: 4px; overflow: hidden; }
    .pf-bar-fill { height: 100%; border-radius: 4px; transition: width 500ms ease; }
    .pf-bar-value { font-size: 0.72rem; font-weight: 700; color: var(--gray-700); margin-top: 0.15rem; }

    .record-note { padding: 0.55rem 0.75rem; border-radius: var(--radius-md); font-size: 0.78rem; line-height: 1.5; display: flex; align-items: flex-start; gap: 0.5rem; margin-top: 0.4rem; }
    .record-note.kendala { background: rgba(239,68,68,0.04); border-left: 3px solid #EF4444; }
    .record-note.tindak-lanjut { background: rgba(16,185,129,0.04); border-left: 3px solid #10B981; }
    .record-note strong { font-weight: 600; white-space: nowrap; }

    [data-theme="dark"] .chart-card, [data-theme="dark"] .records-card { background: #111827; border-color: #1F2937; }
    [data-theme="dark"] .chart-card-header, [data-theme="dark"] .records-card-header, [data-theme="dark"] .pf-record { border-color: #1F2937; }
    [data-theme="dark"] .pf-record:hover { background: rgba(255,255,255,0.02); }

    @media (max-width: 1024px) { .pf-bars { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .pf-bars { grid-template-columns: repeat(2, 1fr); } .progres-hero-top { flex-direction: column; } .pf-record-top { flex-direction: column; } }
    @media (max-width: 480px) { .pf-bars { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
@php
    $lastRecord = $records->first();
    $latestBangunan = $lastRecord ? $lastRecord->progres_bangunan : 0;
    $latestKolam = $lastRecord ? $lastRecord->progres_kolam : 0;
    $latestListrik = $lastRecord ? $lastRecord->progres_listrik : 0;
    $latestAir = $lastRecord ? $lastRecord->progres_air : 0;
    $latestAerasi = $lastRecord ? $lastRecord->progres_aerasi : 0;
@endphp

{{-- Hero --}}
<div class="progres-hero">
    <div class="progres-hero-top">
        <div class="progres-hero-info">
            <div class="progres-hero-badge"><i class="fa-solid fa-hammer"></i> Progres Fisik</div>
            <h1>{{ $kdmp->nama_kdkmp }}</h1>
            <div class="progres-hero-subtitle">
                <span><i class="fa-solid fa-map-marker-alt" style="margin-right:2px;"></i> {{ $kdmp->desa }}, {{ $kdmp->kabupaten }}</span>
                <span class="dot"></span>
                <span>{{ $kdmp->provinsi }}</span>
                <span class="dot"></span>
                <span><i class="fa-solid fa-fish" style="margin-right:2px;"></i> {{ $kdmp->komoditas }}</span>
            </div>
        </div>
        <div class="progres-hero-actions">
            <a href="{{ route('progres-fisik.pdf-detail', $kdmp->id) }}" class="hero-btn hero-btn-danger" target="_blank"><i class="fa-solid fa-file-pdf"></i> PDF</a>
            <a href="{{ route('progres-fisik.create', ['kdmp_id' => $kdmp->id]) }}" class="hero-btn hero-btn-primary"><i class="fa-solid fa-plus"></i> Tambah Data</a>
            <a href="{{ route('progres-fisik.index', ['highlight' => $kdmp->id]) }}" class="hero-btn hero-btn-outline"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>

{{-- Latest Progress Summary Cards --}}
<div class="grid grid-cols-5 mb-5">
    @foreach([
        ['label' => 'BANGUNAN', 'value' => $latestBangunan, 'color' => '#0891B2', 'icon' => 'fa-building', 'class' => 'kpi-produksi'],
        ['label' => 'KOLAM', 'value' => $latestKolam, 'color' => '#10B981', 'icon' => 'fa-water', 'class' => 'kpi-aktif'],
        ['label' => 'LISTRIK', 'value' => $latestListrik, 'color' => '#F59E0B', 'icon' => 'fa-bolt', 'class' => 'kpi-sr warning'],
        ['label' => 'AIR', 'value' => $latestAir, 'color' => '#3B82F6', 'icon' => 'fa-droplet', 'class' => 'kpi-perkolam'],
        ['label' => 'AERASI', 'value' => $latestAerasi, 'color' => '#8B5CF6', 'icon' => 'fa-wind', 'class' => 'kpi-utilisasi'],
    ] as $comp)
    <div class="kpi-card {{ $comp['class'] }}">
        <div class="kpi-icon"><i class="fa-solid {{ $comp['icon'] }}" style="font-size:1rem;"></i></div>
        <div>
            <div class="kpi-value">{{ $comp['value'] }}%</div>
            <div class="kpi-label">{{ $comp['label'] }}</div>
            <div class="kpi-sub">Progres terakhir</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Chart --}}
@if($chartData->count() > 0)
<div class="chart-card">
    <div class="chart-card-header">
        <h3><i class="fa-solid fa-chart-line" style="color:var(--kkp-teal); margin-right:0.4rem;"></i> Tren Progres per Komponen</h3>
    </div>
    <div class="chart-card-body">
        <div style="height:320px;"><canvas id="chartProgresKomponen"></canvas></div>
    </div>
</div>
@endif

{{-- Records --}}
<div class="records-card">
    <div class="records-card-header">
        <h3><i class="fa-solid fa-clock-rotate-left" style="color:var(--kkp-teal); margin-right:0.4rem;"></i> Riwayat Data Progres Fisik</h3>
        <span style="background:var(--gray-100);color:var(--gray-600);font-size:0.7rem;font-weight:600;padding:0.2rem 0.6rem;border-radius:var(--radius-full);">{{ $records->count() }} record</span>
    </div>

    @forelse($records as $record)
    <div class="pf-record">
        <div class="pf-record-top">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div class="pf-record-period">
                    <div class="pf-record-month">{{ $bulanList[$record->bulan] ?? '-' }}</div>
                    <div class="pf-record-year">{{ $record->tahun }}</div>
                </div>
                <span style="font-size:0.75rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:var(--radius-full);background:{{ $record->average_progress >= 100 ? 'rgba(16,185,129,0.1)' : ($record->average_progress >= 50 ? 'rgba(59,130,246,0.1)' : 'rgba(245,158,11,0.1)') }};color:{{ $record->average_progress >= 100 ? '#059669' : ($record->average_progress >= 50 ? '#2563EB' : '#D97706') }};">
                    Rata-rata: {{ $record->average_progress }}%
                </span>
            </div>
            <div style="display:flex;gap:0.35rem;">
                <a href="{{ route('progres-fisik.edit', $record->id) }}" class="btn btn-sm btn-outline" style="font-size:0.72rem;"><i class="fa-solid fa-pen-to-square" style="font-size:0.65rem;"></i> Edit</a>
                <form action="{{ route('progres-fisik.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.06);color:#DC2626;border:none;font-size:0.72rem;"><i class="fa-solid fa-trash-can" style="font-size:0.65rem;"></i> Hapus</button>
                </form>
            </div>
        </div>
        <div class="pf-bars">
            @foreach([
                ['label' => 'Bangunan', 'value' => $record->progres_bangunan, 'color' => '#0891B2'],
                ['label' => 'Kolam', 'value' => $record->progres_kolam, 'color' => '#10B981'],
                ['label' => 'Listrik', 'value' => $record->progres_listrik, 'color' => '#F59E0B'],
                ['label' => 'Air', 'value' => $record->progres_air, 'color' => '#3B82F6'],
                ['label' => 'Aerasi', 'value' => $record->progres_aerasi, 'color' => '#8B5CF6'],
            ] as $bar)
            <div class="pf-bar-item">
                <label>{{ $bar['label'] }}</label>
                <div class="pf-bar-track"><div class="pf-bar-fill" style="width:{{ $bar['value'] }}%;background:{{ $bar['color'] }};"></div></div>
                <div class="pf-bar-value">{{ $bar['value'] }}%</div>
            </div>
            @endforeach
        </div>
        @if($record->kendala)
        <div class="record-note kendala"><strong>Kendala:</strong> <span>{{ $record->kendala }}</span></div>
        @endif
        @if($record->tindak_lanjut)
        <div class="record-note tindak-lanjut"><strong>Tindak Lanjut:</strong> <span>{{ $record->tindak_lanjut }}</span></div>
        @endif

        {{-- Foto Dokumentasi --}}
        @if(!empty($record->foto_sebelum) || !empty($record->foto_sesudah))
        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border-color);">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                
                {{-- Foto Sebelum --}}
                @if(!empty($record->foto_sebelum) && is_array($record->foto_sebelum))
                <div>
                    <h5 style="font-size:0.75rem; font-weight:600; color:var(--gray-600); margin-bottom:0.5rem;">
                        <i class="fa-solid fa-image" style="color:#F59E0B; margin-right:0.3rem;"></i> Foto Sebelum
                    </h5>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem;">
                        @foreach($record->foto_sebelum as $path)
                            <a href="{{ asset('storage/'.$path) }}" target="_blank" style="display:block; aspect-ratio:1; border-radius:var(--radius-sm); overflow:hidden; border:1px solid var(--border-color);">
                                <img src="{{ asset('storage/'.$path) }}" alt="Foto Sebelum" style="width:100%; height:100%; object-fit:cover; transition: transform 0.25s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                {{-- Foto Sesudah --}}
                @if(!empty($record->foto_sesudah) && is_array($record->foto_sesudah))
                <div>
                    <h5 style="font-size:0.75rem; font-weight:600; color:var(--gray-600); margin-bottom:0.5rem;">
                        <i class="fa-solid fa-image" style="color:#10B981; margin-right:0.3rem;"></i> Foto Sesudah
                    </h5>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem;">
                        @foreach($record->foto_sesudah as $path)
                            <a href="{{ asset('storage/'.$path) }}" target="_blank" style="display:block; aspect-ratio:1; border-radius:var(--radius-sm); overflow:hidden; border:1px solid var(--border-color);">
                                <img src="{{ asset('storage/'.$path) }}" alt="Foto Sesudah" style="width:100%; height:100%; object-fit:cover; transition: transform 0.25s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
            </div>
        </div>
        @endif
    </div>
    @empty
    <div style="text-align:center;padding:3.5rem 2rem;">
        <div style="width:56px;height:56px;background:var(--gray-100);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <i class="fa-solid fa-hammer" style="font-size:1.25rem;color:var(--gray-400);"></i>
        </div>
        <h4 style="font-size:0.95rem;font-weight:600;color:var(--gray-700);margin-bottom:0.35rem;">Belum Ada Data</h4>
        <p style="font-size:0.82rem;color:var(--gray-500);margin-bottom:1.25rem;">Belum ada data progres fisik untuk KDMP ini.</p>
        <a href="{{ route('progres-fisik.create', ['kdmp_id' => $kdmp->id]) }}" class="btn btn-primary"><i class="fa-solid fa-plus" style="font-size:0.75rem;"></i> Tambah Data Pertama</a>
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
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

    const datasets = [
        { label: 'Bangunan', key: 'bangunan', color: '#0891B2' },
        { label: 'Kolam', key: 'kolam', color: '#10B981' },
        { label: 'Listrik', key: 'listrik', color: '#F59E0B' },
        { label: 'Air', key: 'air', color: '#3B82F6' },
        { label: 'Aerasi', key: 'aerasi', color: '#8B5CF6' },
        { label: 'Rata-rata', key: 'rata_rata', color: '#EF4444' },
    ];

    new Chart(document.getElementById('chartProgresKomponen'), {
        type: 'line',
        data: {
            labels,
            datasets: datasets.map(ds => ({
                label: ds.label,
                data: chartData.map(d => d[ds.key]),
                borderColor: ds.color,
                backgroundColor: 'transparent',
                tension: 0.4,
                borderWidth: ds.key === 'rata_rata' ? 3 : 2,
                pointRadius: ds.key === 'rata_rata' ? 6 : 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: ds.color,
                pointBorderWidth: 2,
                borderDash: ds.key === 'rata_rata' ? [6, 3] : [],
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: isDark ? '#9CA3AF' : '#6B7280', font: { family: 'Poppins', size: 11 }, usePointStyle: true, pointStyle: 'circle', padding: 16 }
                },
                tooltip: {
                    backgroundColor: isDark ? '#1F2937' : '#fff',
                    titleColor: isDark ? '#F3F4F6' : '#111827',
                    bodyColor: isDark ? '#D1D5DB' : '#4B5563',
                    borderColor: isDark ? '#374151' : '#E5E7EB',
                    borderWidth: 1, cornerRadius: 8, padding: 10,
                    titleFont: { family: 'Poppins', weight: '600', size: 12 },
                    bodyFont: { family: 'Poppins', size: 11 },
                    callbacks: { label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y + '%' }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: isDark ? '#9CA3AF' : '#6B7280', font: { family: 'Poppins', size: 10 } } },
                y: { grid: { color: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)' }, ticks: { color: isDark ? '#9CA3AF' : '#6B7280', font: { family: 'Poppins', size: 10 }, callback: v => v + '%' }, min: 0, max: 100 }
            },
            interaction: { intersect: false, mode: 'index' },
        }
    });
});
</script>
@endpush
