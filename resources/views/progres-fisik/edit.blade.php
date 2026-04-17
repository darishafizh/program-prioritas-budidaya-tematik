@extends('layouts.app')

@section('content')
<div class="page-header-row">
    <div>
        <h1 class="page-title">Edit Data Progres Fisik</h1>
        <p class="page-subtitle">{{ $record->kdmp->nama_kdkmp }} — {{ $record->periode_label }}</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'Progres Fisik', 'url' => route('progres-fisik.index')],
        ['label' => $record->kdmp->nama_kdkmp, 'url' => route('progres-fisik.show', $record->kdmp_id)],
        ['label' => 'Edit', 'url' => '#']
    ]" />
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-icon teal"><i class="fa-solid fa-pen-to-square" style="font-size:0.85rem;"></i></div>
        <h3 class="section-title">Edit Progres Fisik — {{ $record->periode_label }}</h3>
    </div>
    <div class="section-body">
        @if($errors->any())
        <div class="alert alert-danger mb-4" style="border-radius:var(--radius-md);padding:0.75rem 1rem;font-size:0.85rem;">
            <ul style="margin:0;padding-left:1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('progres-fisik.update', $record->id) }}">
            @csrf @method('PUT')

            {{-- Info Lokasi (read-only) --}}
            <div class="detail-grid" style="margin-bottom:1.5rem;">
                <div class="form-group">
                    <label class="form-label">Lokasi KDMP</label>
                    <input type="text" class="form-control" value="{{ $record->kdmp->nama_kdkmp }} — {{ $record->kdmp->kabupaten }}, {{ $record->kdmp->provinsi }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Periode</label>
                    <input type="text" class="form-control" value="{{ $record->periode_label }}" disabled>
                </div>
            </div>

            {{-- Progres per Komponen --}}
            <h4 style="font-size:0.9rem;font-weight:600;color:var(--gray-700);margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:1px solid var(--border-color);">
                <i class="fa-solid fa-chart-simple" style="color:var(--kkp-teal);margin-right:0.3rem;"></i> Progres per Komponen (0-100%)
            </h4>

            @foreach([
                ['name' => 'progres_bangunan', 'label' => 'Bangunan', 'color' => '#0891B2', 'icon' => 'fa-building'],
                ['name' => 'progres_kolam', 'label' => 'Kolam', 'color' => '#10B981', 'icon' => 'fa-water'],
                ['name' => 'progres_listrik', 'label' => 'Listrik', 'color' => '#F59E0B', 'icon' => 'fa-bolt'],
                ['name' => 'progres_air', 'label' => 'Sistem Air', 'color' => '#3B82F6', 'icon' => 'fa-droplet'],
                ['name' => 'progres_aerasi', 'label' => 'Aerasi', 'color' => '#8B5CF6', 'icon' => 'fa-wind'],
            ] as $comp)
            <div class="form-group" style="background:var(--gray-50);padding:1rem;border-radius:var(--radius-md);border-left:3px solid {{ $comp['color'] }};">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
                    <label class="form-label" style="margin:0;font-weight:600;">
                        <i class="fa-solid {{ $comp['icon'] }}" style="color:{{ $comp['color'] }};margin-right:0.3rem;"></i>
                        {{ $comp['label'] }}
                    </label>
                    <span class="progres-display" data-target="{{ $comp['name'] }}" style="font-size:0.85rem;font-weight:700;color:{{ $comp['color'] }};">{{ old($comp['name'], $record->{$comp['name']}) }}%</span>
                </div>
                <input type="range" name="{{ $comp['name'] }}" value="{{ old($comp['name'], $record->{$comp['name']}) }}" min="0" max="100" step="5" class="form-range progres-slider" data-name="{{ $comp['name'] }}"
                    style="width:100%;accent-color:{{ $comp['color'] }};">
                <div style="display:flex;justify-content:space-between;font-size:0.65rem;color:var(--gray-400);margin-top:0.15rem;">
                    <span>0%</span><span>25%</span><span>50%</span><span>75%</span><span>100%</span>
                </div>
            </div>
            @endforeach

            {{-- Catatan --}}
            <h4 style="font-size:0.9rem;font-weight:600;color:var(--gray-700);margin:1.5rem 0 1rem;padding-bottom:0.5rem;border-bottom:1px solid var(--border-color);">
                <i class="fa-solid fa-note-sticky" style="color:var(--kkp-teal);margin-right:0.3rem;"></i> Catatan Lapangan
            </h4>
            <div class="form-group">
                <label class="form-label">Kendala</label>
                <textarea name="kendala" class="form-control" rows="3">{{ old('kendala', $record->kendala) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" class="form-control" rows="3">{{ old('tindak_lanjut', $record->tindak_lanjut) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $record->catatan) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="form-actions" style="display:flex;gap:0.75rem;padding-top:1rem;border-top:1px solid var(--border-color);margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check" style="font-size:0.75rem;"></i> Simpan Perubahan</button>
                <a href="{{ route('progres-fisik.show', $record->kdmp_id) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.progres-slider').forEach(slider => {
        const name = slider.dataset.name;
        const display = document.querySelector(`.progres-display[data-target="${name}"]`);
        slider.addEventListener('input', () => { display.textContent = slider.value + '%'; });
    });
});
</script>
@endpush
