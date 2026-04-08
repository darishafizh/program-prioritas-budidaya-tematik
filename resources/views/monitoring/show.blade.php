@extends('layouts.app')

@section('content')
<div class="page-header-row">
    <div>
        <h1 class="page-title">{{ $kdmp->nama_kdkmp }}</h1>
        <p class="page-subtitle">{{ $kdmp->desa }}, {{ $kdmp->kabupaten }}, {{ $kdmp->provinsi }} &bull; {{ $kdmp->komoditas }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('monitoring.create', ['kdmp_id' => $kdmp->id]) }}" class="btn btn-primary">+ Tambah Laporan</a>
        <a href="{{ route('monitoring.index') }}" class="btn btn-outline">← Kembali</a>
    </div>
</div>

{{-- Info KDMP --}}
<div class="grid grid-cols-4 mb-5">
    <div class="stat-card card-gradient-teal">
        <div class="stat-card-content">
            <h3>Total Laporan</h3>
            <div class="stat-card-value">{{ $records->count() }}</div>
        </div>
    </div>
    <div class="stat-card card-gradient-success">
        <div class="stat-card-content">
            <h3>Total Panen</h3>
            <div class="stat-card-value" style="font-size:1.4rem;">{{ number_format($records->sum('volume_panen_kg'),0,',','.') }} kg</div>
        </div>
    </div>
    <div class="stat-card card-gradient-navy">
        <div class="stat-card-content">
            <h3>Total Nilai</h3>
            <div class="stat-card-value" style="font-size:1.2rem;">Rp {{ number_format($records->sum('nilai_produksi'),0,',','.') }}</div>
        </div>
    </div>
    <div class="stat-card card-gradient-warning">
        <div class="stat-card-content">
            <h3>Status Terakhir</h3>
            @if($records->count())
            @php $last = $records->first(); @endphp
            <div class="stat-card-value" style="font-size:1.1rem;">{!! $last->status_icon !!} {{ $last->status_label }}</div>
            @else
            <div class="stat-card-value" style="font-size:1rem;">Belum Ada</div>
            @endif
        </div>
    </div>
</div>

{{-- Chart Progres --}}
@if($chartData->count() > 0)
<div class="card mb-5">
    <div class="card-body">
        <h3 class="card-title mb-4">Grafik Perkembangan</h3>
        <div class="grid grid-cols-2">
            <div>
                <h4 style="font-size:0.9rem;font-weight:600;color:var(--gray-600);margin-bottom:0.75rem;">Progres Fisik (%)</h4>
                <div style="height:220px;"><canvas id="chartProgres"></canvas></div>
            </div>
            <div>
                <h4 style="font-size:0.9rem;font-weight:600;color:var(--gray-600);margin-bottom:0.75rem;">Volume Panen (kg)</h4>
                <div style="height:220px;"><canvas id="chartPanen"></canvas></div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Riwayat Laporan --}}
<div class="section-card">
    <div class="section-header">
        <div class="section-icon teal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="section-title">Riwayat Laporan Monitoring</h3>
    </div>
    <div class="section-body" style="padding:0;">
        @forelse($records as $record)
        <div class="monitoring-record-item" style="border-bottom:1px solid var(--gray-200);padding:1.25rem 1.5rem;">
            <div class="monitoring-record-header">
                <div class="monitoring-record-main">
                    <div style="text-align:center;min-width:70px;">
                        <div style="font-size:1rem;font-weight:700;color:var(--kkp-teal);">{{ $bulanList[$record->bulan] }}</div>
                        <div style="font-size:0.8rem;color:var(--gray-500);">{{ $record->tahun }}</div>
                    </div>
                    <div class="monitoring-record-divider" style="height:48px;width:1px;background:var(--gray-200);"></div>
                    <div>
                        <span class="status-badge {{ $record->status_color }}">{!! $record->status_icon !!} {{ $record->status_label }}</span>
                        <div class="monitoring-record-stats">
                            <span><i class="fa-solid fa-chart-line"></i> Progres: <strong>{{ $record->progres_fisik }}%</strong></span>
                            <span><i class="fa-solid fa-fish"></i> Panen: <strong>{{ number_format($record->volume_panen_kg,0,',','.') }} kg</strong></span>
                            <span><i class="fa-solid fa-sack-dollar"></i> Nilai: <strong>Rp {{ number_format($record->nilai_produksi,0,',','.') }}</strong></span>
                            <span><i class="fa-solid fa-users"></i> Pembudidaya: <strong>{{ $record->jumlah_pembudidaya_aktif }} orang</strong></span>
                        </div>
                    </div>
                </div>
                <div class="monitoring-record-actions">
                    <a href="{{ route('monitoring.edit', $record->id) }}" class="btn btn-sm btn-outline">Edit</a>
                    <form action="{{ route('monitoring.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin hapus laporan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:#FEE2E2;color:#DC2626;border:none;">Hapus</button>
                    </form>
                </div>
            </div>
            @if($record->kendala)
            <div style="margin-top:0.75rem;padding:0.6rem 0.8rem;background:var(--gray-50);border-radius:var(--radius-md);border-left:3px solid #EF4444;font-size:0.82rem;">
                <strong>Kendala:</strong> {{ $record->kendala }}
            </div>
            @endif
            @if($record->tindak_lanjut)
            <div style="margin-top:0.5rem;padding:0.6rem 0.8rem;background:var(--gray-50);border-radius:var(--radius-md);border-left:3px solid #10B981;font-size:0.82rem;">
                <strong>Tindak Lanjut:</strong> {{ $record->tindak_lanjut }}
            </div>
            @endif
        </div>
        @empty
        <div style="text-align:center;padding:3rem;color:var(--gray-400);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:40px;height:40px;margin:0 auto 1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>Belum ada laporan monitoring untuk KDMP ini.</p>
            <a href="{{ route('monitoring.create', ['kdmp_id' => $kdmp->id]) }}" class="btn btn-primary mt-3">Tambah Laporan Pertama</a>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($chartData);
    if (!chartData.length) return;

    const labels = chartData.map(d => d.label);
    const colors = { teal:'#0891B2', green:'#10B981', amber:'#F59E0B' };

    new Chart(document.getElementById('chartProgres'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Progres Fisik (%)',
                data: chartData.map(d => d.progres_fisik),
                borderColor: colors.teal, backgroundColor: 'rgba(8,145,178,0.1)',
                fill: true, tension: 0.3, pointRadius: 5
            }]
        },
        options: { responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true, max:100 } } }
    });

    new Chart(document.getElementById('chartPanen'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Volume Panen (kg)',
                data: chartData.map(d => d.volume_panen),
                backgroundColor: colors.green, borderRadius: 6
            }]
        },
        options: { responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } } }
    });
});
</script>
@endpush
