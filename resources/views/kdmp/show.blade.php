@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Detail Kuesioner KDMP</h1>
        <p class="page-subtitle">{{ $kdmp->nama_koperasi }}</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'KDMP', 'url' => route('kdmp.index')],
        ['label' => 'Detail', 'url' => '#']
    ]" />
</div>

<!-- Page Actions -->
<div class="page-header">
    <div class="page-header-content">
        <a href="{{ route('kdmp.index') }}" class="btn btn-outline">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <a href="{{ route('kdmp.edit', $kdmp) }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
    </div>
</div>

<!-- Info Cards -->
<div class="grid grid-cols-3 mb-5">
    <div class="stat-card card-gradient-teal">
        <div class="stat-card-content">
            <h3>Komoditas</h3>
            <div class="stat-card-value">{{ $kdmp->komoditas ?? '-' }}</div>
        </div>
    </div>
    <div class="stat-card card-gradient-success">
        <div class="stat-card-content">
            <h3>Rata-rata Progres</h3>
            <div class="stat-card-value">{{ $kdmp->average_progress }}%</div>
        </div>
    </div>
    <div class="stat-card card-gradient-navy">
        <div class="stat-card-content">
            <h3>Pembudidaya</h3>
            <div class="stat-card-value">{{ $kdmp->jumlah_pembudidaya ?? 0 }}</div>
        </div>
    </div>
</div>

<!-- Data Sections -->
<div class="section-card">
    <div class="section-header">
        <div class="section-icon teal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <h3 class="section-title">Data Verifikator & Responden</h3>
    </div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Verifikator</div><div class="detail-value">{{ $kdmp->verifikator ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Responden</div><div class="detail-value">{{ $kdmp->responden ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Tempat</div><div class="detail-value">{{ $kdmp->tempat ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Tanggal</div><div class="detail-value">{{ $kdmp->tanggal?->format('d/m/Y') ?? '-' }}</div></div>
        </div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-icon navy">A</div>
        <h3 class="section-title">Identitas Lembaga</h3>
    </div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item" style="grid-column: 1 / -1;"><div class="detail-label">Nama Koperasi</div><div class="detail-value font-semibold">{{ $kdmp->nama_koperasi ?? '-' }}</div></div>
            <div class="detail-item" style="grid-column: 1 / -1;"><div class="detail-label">No. Badan Hukum</div><div class="detail-value">{{ $kdmp->nomor_badan_hukum ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Desa</div><div class="detail-value">{{ $kdmp->desa ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Kecamatan</div><div class="detail-value">{{ $kdmp->kecamatan ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Kabupaten</div><div class="detail-value">{{ $kdmp->kabupaten ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Provinsi</div><div class="detail-value">{{ $kdmp->provinsi ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Luas Lahan</div><div class="detail-value">{{ number_format($kdmp->luas_lahan ?? 0) }} m²</div></div>
            <div class="detail-item"><div class="detail-label">Koordinat</div><div class="detail-value">{{ $kdmp->koordinat ?? '-' }}</div></div>
        </div>
        @if($kdmp->hambatan_koperasi && count($kdmp->hambatan_koperasi) > 0)
        <div class="border-t mt-4 pt-4">
            <p class="form-label mb-3">Hambatan Koperasi</p>
            <div class="flex flex-wrap gap-2">
                @foreach($kdmp->hambatan_koperasi as $hambatan)
                <span class="badge badge-warning">{{ $hambatan }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-icon warning">C</div>
        <h3 class="section-title">Progres Pembangunan</h3>
    </div>
    <div class="section-body">
        @foreach(['Bangunan' => $kdmp->progres_bangunan, 'Kolam' => $kdmp->progres_kolam, 'Listrik' => $kdmp->progres_listrik, 'Air' => $kdmp->progres_air, 'Aerasi' => $kdmp->progres_aerasi] as $label => $value)
        <div class="form-group p-4 bg-gray rounded-lg">
            <div class="progress-label"><span class="font-medium">{{ $label }}</span><strong class="text-primary">{{ $value ?? 0 }}%</strong></div>
            <div class="progress"><div class="progress-bar" style="width: {{ $value ?? 0 }}%"></div></div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
