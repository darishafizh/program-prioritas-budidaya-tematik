@extends('layouts.app')

@section('content')
{{-- Page Header --}}
<div class="page-header-row">
    <div>
        <h1 class="page-title">Tambah Laporan Monitoring</h1>
        <p class="page-subtitle">Input laporan perkembangan periodik lokasi KDMP</p>
    </div>
    <a href="{{ route('monitoring.index') }}" class="btn btn-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>

{{-- Error Alert --}}
@if ($errors->any())
<div class="monitoring-alert-error">
    <div class="monitoring-alert-icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.963-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
    </div>
    <div>
        <strong>Terjadi kesalahan:</strong>
        <ul style="margin:0.25rem 0 0;padding-left:1.25rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('monitoring.store') }}" method="POST">
    @csrf

    {{-- Section 1: Informasi Lokasi --}}
    <div class="monitoring-form-card">
        <div class="monitoring-form-header">
            <div class="monitoring-form-header-icon" style="background: rgba(8, 145, 178, 0.1); color: var(--kkp-teal);">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="monitoring-form-title">Informasi Lokasi</h3>
                <p class="monitoring-form-desc">Pilih lokasi KDKMP yang akan dilaporkan</p>
            </div>
        </div>
        <div class="monitoring-form-body">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Pilih KDKMP <span class="required">*</span></label>
                <select name="kdmp_id" id="kdmp_select" class="form-control form-select" required>
                    <option value="">-- Pilih KDKMP --</option>
                    @foreach($kdmpList as $k)
                    <option value="{{ $k->id }}" {{ (old('kdmp_id', $kdmpSelected?->id) == $k->id) ? 'selected' : '' }}>
                        [{{ $k->no }}] {{ $k->nama_kdkmp }} — {{ $k->kabupaten }}, {{ $k->provinsi }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Section 2: Periode & Status --}}
    <div class="monitoring-form-card">
        <div class="monitoring-form-header">
            <div class="monitoring-form-header-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366F1;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="monitoring-form-title">Periode & Status</h3>
                <p class="monitoring-form-desc">Tentukan periode laporan dan status lokasi saat ini</p>
            </div>
        </div>
        <div class="monitoring-form-body">
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Bulan <span class="required">*</span></label>
                    <select name="bulan" class="form-control form-select" required>
                        @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ old('bulan', date('n')) == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun <span class="required">*</span></label>
                    <select name="tahun" class="form-control form-select" required>
                        @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ old('tahun', date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Status Lokasi <span class="required">*</span></label>
                    <select name="status_lokasi" class="form-control form-select" required>
                        <option value="on_track"   {{ old('status_lokasi') === 'on_track'   ? 'selected' : '' }}>✅ On Track</option>
                        <option value="bermasalah" {{ old('status_lokasi') === 'bermasalah' ? 'selected' : '' }}>❌ Bermasalah</option>
                        <option value="selesai"    {{ old('status_lokasi') === 'selesai'    ? 'selected' : '' }}>✔️ Selesai</option>
                        <option value="vakum"      {{ old('status_lokasi') === 'vakum'      ? 'selected' : '' }}>⚠️ Vakum</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Progres Fisik (%) <span class="required">*</span></label>
                    <input type="number" name="progres_fisik" min="0" max="100"
                        value="{{ old('progres_fisik', 0) }}" class="form-control" required>
                    <div class="monitoring-progress-bar" style="margin-top:8px;">
                        <div class="monitoring-progress-fill" id="progresBar" style="width:0%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Data Produksi --}}
    <div class="monitoring-form-card">
        <div class="monitoring-form-header">
            <div class="monitoring-form-header-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="monitoring-form-title">Data Produksi</h3>
                <p class="monitoring-form-desc">Catat data hasil produksi dan biaya operasional (opsional)</p>
            </div>
        </div>
        <div class="monitoring-form-body">
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Volume Panen (kg)</label>
                    <input type="number" name="volume_panen_kg" min="0" step="0.01"
                        value="{{ old('volume_panen_kg', 0) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Nilai Produksi (Rp)</label>
                    <input type="number" name="nilai_produksi" min="0"
                        value="{{ old('nilai_produksi', 0) }}" class="form-control">
                </div>
            </div>
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Biaya Operasional (Rp)</label>
                    <input type="number" name="biaya_operasional" min="0"
                        value="{{ old('biaya_operasional', 0) }}" class="form-control">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Jumlah Pembudidaya Aktif</label>
                    <input type="number" name="jumlah_pembudidaya_aktif" min="0"
                        value="{{ old('jumlah_pembudidaya_aktif', 0) }}" class="form-control">
                </div>
            </div>
        </div>
    </div>

    {{-- Section 4: Catatan & Kendala --}}
    <div class="monitoring-form-card">
        <div class="monitoring-form-header">
            <div class="monitoring-form-header-icon" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h3 class="monitoring-form-title">Catatan & Kendala</h3>
                <p class="monitoring-form-desc">Dokumentasikan kendala dan rencana tindak lanjut</p>
            </div>
        </div>
        <div class="monitoring-form-body">
            <div class="form-group">
                <label class="form-label">Kendala</label>
                <textarea name="kendala" rows="3" class="form-control" placeholder="Tuliskan kendala yang dihadapi...">{{ old('kendala') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" rows="3" class="form-control" placeholder="Rencana tindak lanjut...">{{ old('tindak_lanjut') }}</textarea>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" class="form-control" placeholder="Catatan lain...">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Submit Actions --}}
    <div class="monitoring-form-actions">
        <a href="{{ route('monitoring.index') }}" class="btn btn-outline">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Batal
        </a>
        <button type="submit" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Laporan
        </button>
    </div>
</form>
@endsection

@push('styles')
<style>
    /* ===== Monitoring Form Cards ===== */
    .monitoring-form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        margin-bottom: 1.25rem;
        overflow: hidden;
        border: 1px solid var(--gray-100);
        transition: box-shadow 0.2s ease;
    }
    .monitoring-form-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .monitoring-form-header {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-100);
        background: linear-gradient(135deg, #FAFBFC 0%, #F8F9FA 100%);
    }
    .monitoring-form-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .monitoring-form-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
        line-height: 1.3;
    }
    .monitoring-form-desc {
        font-size: 0.8rem;
        color: var(--gray-500);
        margin: 0;
        line-height: 1.4;
    }
    .monitoring-form-body {
        padding: 1.5rem;
    }

    /* ===== Progress Bar ===== */
    .monitoring-progress-bar {
        height: 6px;
        background: var(--gray-200);
        border-radius: 3px;
        overflow: hidden;
    }
    .monitoring-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #10B981, #059669);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    /* ===== Error Alert ===== */
    .monitoring-alert-error {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        background: #FEF2F2;
        border: 1px solid #FECACA;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        color: #DC2626;
        font-size: 0.875rem;
    }
    .monitoring-alert-icon {
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* ===== Form Actions ===== */
    .monitoring-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding: 1.5rem 0 0.5rem;
    }

    /* ===== Required asterisk ===== */
    .required {
        color: var(--kkp-danger);
        margin-left: 2px;
    }

    /* ===== Grid gap fix ===== */
    .monitoring-form-body .grid {
        gap: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Live progress bar update
    const progresInput = document.querySelector('input[name="progres_fisik"]');
    const progresBar = document.getElementById('progresBar');
    if (progresInput && progresBar) {
        function updateBar() {
            const val = Math.max(0, Math.min(100, parseInt(progresInput.value) || 0));
            progresBar.style.width = val + '%';
            if (val >= 80) {
                progresBar.style.background = 'linear-gradient(90deg, #10B981, #059669)';
            } else if (val >= 50) {
                progresBar.style.background = 'linear-gradient(90deg, #F59E0B, #D97706)';
            } else {
                progresBar.style.background = 'linear-gradient(90deg, #EF4444, #DC2626)';
            }
        }
        progresInput.addEventListener('input', updateBar);
        updateBar();
    }
</script>
@endpush
