@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Detail Kuesioner SPPG</h1>
        <p class="page-subtitle">{{ $sppg->nama_sppg ?? 'Data SPPG' }}</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'SPPG', 'url' => route('sppg.index')],
        ['label' => 'Detail', 'url' => '#']
    ]" />
</div>

<!-- Page Actions -->
<div class="page-header">
    <div class="page-header-content">
        <a href="{{ route('sppg.index') }}" class="btn btn-outline">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <a href="{{ route('sppg.edit', $sppg) }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
    </div>
</div>

<!-- Info Cards -->
<div class="grid grid-cols-3 mb-5">
    <div class="stat-card card-gradient-warning">
        <div class="stat-card-content">
            <h3>Jumlah Sekolah</h3>
            <div class="stat-card-value">{{ $sppg->jumlah_sekolah ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card card-gradient-teal">
        <div class="stat-card-content">
            <h3>Jumlah Siswa</h3>
            <div class="stat-card-value">{{ number_format($sppg->jumlah_siswa ?? 0) }}</div>
        </div>
    </div>
    <div class="stat-card card-gradient-success">
        <div class="stat-card-content">
            <h3>Total Kebutuhan</h3>
            <div class="stat-card-value">{{ number_format(($sppg->kebutuhan_lele ?? 0) + ($sppg->kebutuhan_nila ?? 0)) }} Kg</div>
        </div>
    </div>
</div>

<!-- Data Sections -->
<div class="section-card">
    <div class="section-header">
        <div class="section-icon warning">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <h3 class="section-title">Data Responden</h3>
    </div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Verifikator</div><div class="detail-value">{{ $sppg->verifikator ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Nama Responden</div><div class="detail-value">{{ $sppg->responden ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Tanggal</div><div class="detail-value">{{ $sppg->tanggal?->format('d/m/Y') ?? '-' }}</div></div>
        </div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-icon teal">A</div>
        <h3 class="section-title">Data SPPG & Kebutuhan</h3>
    </div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item" style="grid-column: 1 / -1;"><div class="detail-label">Nama SPPG</div><div class="detail-value font-semibold">{{ $sppg->nama_sppg ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Kabupaten</div><div class="detail-value">{{ $sppg->kabupaten ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Provinsi</div><div class="detail-value">{{ $sppg->provinsi ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Jml Sekolah MBG</div><div class="detail-value">{{ $sppg->jumlah_sekolah ?? 0 }}</div></div>
            <div class="detail-item"><div class="detail-label">Jml Siswa MBG</div><div class="detail-value">{{ number_format($sppg->jumlah_siswa ?? 0) }}</div></div>
            <div class="detail-item"><div class="detail-label">Kebutuhan Lele</div><div class="detail-value">{{ number_format($sppg->kebutuhan_lele ?? 0) }} Kg/bulan</div></div>
            <div class="detail-item"><div class="detail-label">Kebutuhan Nila</div><div class="detail-value">{{ number_format($sppg->kebutuhan_nila ?? 0) }} Kg/bulan</div></div>
        </div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-icon success">B</div>
        <h3 class="section-title">Preferensi Ikan</h3>
    </div>
    <div class="section-body">
        @if($sppg->jenis_ikan_prioritas && count($sppg->jenis_ikan_prioritas) > 0)
        <div class="form-group">
            <p class="form-label mb-3">Jenis Ikan Prioritas</p>
            <div class="flex flex-wrap gap-2">
                @foreach($sppg->jenis_ikan_prioritas as $jenis)
                <span class="badge badge-teal">{{ $jenis }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($sppg->standar_kualitas && count($sppg->standar_kualitas) > 0)
        <div class="form-group">
            <p class="form-label mb-3">Standar Kualitas</p>
            <div class="flex flex-wrap gap-2">
                @foreach($sppg->standar_kualitas as $kualitas)
                <span class="badge badge-success">{{ $kualitas }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-icon navy">C</div>
        <h3 class="section-title">Kerjasama</h3>
    </div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item" style="grid-column: 1 / -1;"><div class="detail-label">Minat Kerjasama</div><div class="detail-value">{{ $sppg->minat_kerjasama ?? '-' }}</div></div>
            <div class="detail-item" style="grid-column: 1 / -1;"><div class="detail-label">Alasan</div><div class="detail-value">{{ $sppg->alasan_minat ?? '-' }}</div></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
