@extends('layouts.app')

@section('content')
<div class="page-header-row">
    <div>
        <h1 class="page-title">Monitoring Lokasi Budidaya Tematik</h1>
        <p class="page-subtitle">Pemantauan dan evaluasi perkembangan 100 KDMP Bioflok</p>
    </div>
    <a href="{{ route('monitoring.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Laporan
    </a>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-4 mb-5">
    <div class="stat-card card-gradient-teal">
        <div class="stat-card-content">
            <h3>Total KDMP</h3>
            <div class="stat-card-value">{{ $stats['total_kdmp'] }}</div>
            <div style="font-size:0.75rem;opacity:0.9;margin-top:0.25rem;">Sudah melapor: {{ $stats['sudah_lapor'] }}</div>
        </div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
    </div>
    <div class="stat-card card-gradient-success">
        <div class="stat-card-content">
            <h3>🟢 On Track</h3>
            <div class="stat-card-value">{{ $stats['on_track'] }}</div>
        </div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>
    <div class="stat-card card-gradient-warning">
        <div class="stat-card-content">
            <h3>🔴 Bermasalah</h3>
            <div class="stat-card-value">{{ $stats['bermasalah'] }}</div>
        </div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.963-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
    </div>
    <div class="stat-card card-gradient-navy">
        <div class="stat-card-content">
            <h3>Total Panen</h3>
            <div class="stat-card-value" style="font-size:1.5rem;">{{ number_format($stats['total_panen'], 0, ',', '.') }} kg</div>
        </div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="section-card mb-4">
    <div class="section-body">
        <form method="GET" action="{{ route('monitoring.index') }}" class="flex gap-3 flex-wrap items-end">
            <div class="form-group" style="margin:0;min-width:200px;">
                <label class="form-label">Cari KDMP</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Nama, kabupaten, provinsi...">
            </div>
            <div class="form-group" style="margin:0;">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control form-select">
                    @foreach($bulanList as $num => $nama)
                    <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control form-select">
                    @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('monitoring.index') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>
</div>

{{-- Tabel KDMP --}}
<div class="section-card">
    <div class="section-body" style="padding:0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Nama KDKMP</th>
                        <th>Kabupaten</th>
                        <th>Provinsi</th>
                        <th style="text-align:center">Komoditas</th>
                        <th style="text-align:center">Laporan Terakhir</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Progres Fisik</th>
                        <th style="text-align:center;width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kdmpList as $kdmp)
                    @php
                        $lastRecord = $kdmp->monitoringRecords->first();
                    @endphp
                    <tr>
                        <td class="text-center font-bold text-muted">{{ $kdmp->no }}</td>
                        <td class="font-semibold">{{ $kdmp->nama_kdkmp }}</td>
                        <td>{{ $kdmp->kabupaten }}</td>
                        <td>{{ $kdmp->provinsi }}</td>
                        <td class="text-center">
                            <span class="badge {{ $kdmp->komoditas === 'Lele' ? 'badge-teal' : 'badge-success' }}">
                                {{ $kdmp->komoditas ?? '-' }}
                            </span>
                        </td>
                        <td class="text-center text-muted text-sm">
                            @if($lastRecord)
                                {{ $lastRecord->bulan_label }} {{ $lastRecord->tahun }}
                            @else
                                <span style="color:var(--gray-400)">Belum ada</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($lastRecord)
                                <span class="status-badge {{ $lastRecord->status_color }}">
                                    {{ $lastRecord->status_icon }} {{ $lastRecord->status_label }}
                                </span>
                            @else
                                <span class="status-badge" style="background:var(--gray-100);color:var(--gray-500);">⚪ Belum Lapor</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($lastRecord)
                                <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                                    <div style="flex:1;height:8px;background:var(--gray-200);border-radius:4px;min-width:60px;">
                                        <div style="height:100%;width:{{ $lastRecord->progres_fisik }}%;background:{{ $lastRecord->progres_fisik >= 80 ? '#10B981' : ($lastRecord->progres_fisik >= 50 ? '#F59E0B' : '#EF4444') }};border-radius:4px;"></div>
                                    </div>
                                    <span style="font-size:0.78rem;font-weight:600;">{{ $lastRecord->progres_fisik }}%</span>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex gap-1 justify-center">
                                <a href="{{ route('monitoring.show', $kdmp->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                <a href="{{ route('monitoring.create', ['kdmp_id' => $kdmp->id]) }}" class="btn btn-sm btn-outline" title="Tambah laporan">+</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;padding:2rem;color:var(--gray-400);">Tidak ada KDMP ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $kdmpList->links() }}
        </div>
    </div>
</div>
@endsection
