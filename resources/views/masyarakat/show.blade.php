@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Detail Kuesioner Masyarakat</h1>
        <p class="page-subtitle">{{ $masyarakat->nama_responden ?? 'Data Responden' }}</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'Masyarakat', 'url' => route('masyarakat.index')],
        ['label' => 'Detail', 'url' => '#']
    ]" />
</div>

<!-- Page Actions -->
<div class="page-header">
    <div class="page-header-content">
        <a href="{{ route('masyarakat.index') }}" class="btn btn-outline">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <a href="{{ route('masyarakat.edit', $masyarakat) }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
    </div>
</div>

<!-- Data Sections -->
<div class="section-card">
    <div class="section-header">
        <div class="section-icon teal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <h3 class="section-title">Data Responden</h3>
    </div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Verifikator</div><div class="detail-value">{{ $masyarakat->verifikator ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Nama Responden</div><div class="detail-value">{{ $masyarakat->nama_responden ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Tempat</div><div class="detail-value">{{ $masyarakat->tempat ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Tanggal</div><div class="detail-value">{{ $masyarakat->tanggal?->format('d/m/Y') ?? '-' }}</div></div>
        </div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-icon success">A</div>
        <h3 class="section-title">Tanggapan Masyarakat</h3>
    </div>
    <div class="section-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Sesuai Kebutuhan</div><div class="detail-value">{{ $masyarakat->sesuai_kebutuhan ?? '-' }}</div></div>
            <div class="detail-item"><div class="detail-label">Perasaan</div><div class="detail-value">{{ $masyarakat->perasaan ?? '-' }}</div></div>
            <div class="detail-item" style="grid-column: 1 / -1;"><div class="detail-label">Harapan</div><div class="detail-value">{{ $masyarakat->harapan ?? '-' }}</div></div>
        </div>
    </div>
</div>

@if($masyarakat->likert_q1 || $masyarakat->likert_q2)
<div class="section-card">
    <div class="section-header">
        <div class="section-icon navy">B</div>
        <h3 class="section-title">Tingkat Kebahagiaan (Likert Scale)</h3>
    </div>
    <div class="section-body">
        @php
        $questions = [
            1 => 'Saya merasa aman dengan kegiatan pembangunan',
            2 => 'Pekerjaan fisik tidak mengganggu aktivitas',
            3 => 'Lingkungan sekitar masih nyaman',
            4 => 'Tidak khawatir terhadap dampak lingkungan',
            5 => 'Kegiatan memperhatikan keselamatan',
            6 => 'Berpotensi memberikan manfaat',
            7 => 'Membuka peluang kerja/usaha',
            8 => 'Mendorong kegiatan ekonomi lokal',
            9 => 'Tidak menimbulkan konflik sosial',
            10 => 'Berdampak positif bagi desa',
        ];
        @endphp
        <div class="likert-container">
            @foreach($questions as $num => $question)
            @php $value = $masyarakat->{"likert_q$num"} ?? 0; @endphp
            <div class="likert-item">
                <p class="likert-question">{{ $num }}. {{ $question }}</p>
                <div class="likert-options">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="likert-option">
                        <span style="width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:600;{{ $value == $i ? 'background:var(--kkp-teal);color:white;' : 'background:var(--gray-200);color:var(--gray-500);' }}">{{ $i }}</span>
                    </div>
                    @endfor
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
