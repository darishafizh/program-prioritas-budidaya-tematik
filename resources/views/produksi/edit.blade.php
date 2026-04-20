@extends('layouts.app')

@section('content')
{{-- Page Header --}}
<div class="page-header-row">
    <div>
        <h1 class="page-title">Edit Laporan Monitoring</h1>
        <p class="page-subtitle">{{ $record->kdmp->nama_kdkmp }} — {{ $record->bulan_label }} {{ $record->tahun }}</p>
    </div>
    <a href="{{ route('produksi.show', $record->kdmp_id) }}" class="btn btn-outline">
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

<form action="{{ route('produksi.update', $record->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="status_lokasi" value="{{ $record->status_lokasi }}">
    <input type="hidden" name="progres_fisik" value="{{ $record->progres_fisik }}">

    {{-- Section 1: Informasi Lokasi (Readonly) --}}
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
                <p class="monitoring-form-desc">Data lokasi dan periode laporan</p>
            </div>
        </div>
        <div class="monitoring-form-body">
            <div class="grid grid-cols-3">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">KDKMP</label>
                    <div class="form-control" style="background:var(--gray-50);cursor:default;">{{ $record->kdmp->nama_kdkmp }}</div>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Periode</label>
                    <div class="form-control" style="background:var(--gray-50);cursor:default;">{{ $bulanList[$record->bulan] }} {{ $record->tahun }}</div>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Kabupaten</label>
                    <div class="form-control" style="background:var(--gray-50);cursor:default;">{{ $record->kdmp->kabupaten }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Data Produksi --}}
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
            <div class="grid grid-cols-3">
                <div class="form-group">
                    <label class="form-label">Biaya Pakan (Rp)</label>
                    <input type="text" class="form-control rupiah-input" data-target="biaya_pakan" value="{{ old('biaya_pakan', $record->biaya_pakan) }}" inputmode="numeric">
                    <input type="hidden" name="biaya_pakan" value="{{ old('biaya_pakan', $record->biaya_pakan) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Biaya Bibit (Rp)</label>
                    <input type="text" class="form-control rupiah-input" data-target="biaya_bibit" value="{{ old('biaya_bibit', $record->biaya_bibit) }}" inputmode="numeric">
                    <input type="hidden" name="biaya_bibit" value="{{ old('biaya_bibit', $record->biaya_bibit) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Biaya Lainnya (Rp)</label>
                    <input type="text" class="form-control rupiah-input" data-target="biaya_lainnya" value="{{ old('biaya_lainnya', $record->biaya_lainnya) }}" inputmode="numeric">
                    <input type="hidden" name="biaya_lainnya" value="{{ old('biaya_lainnya', $record->biaya_lainnya) }}">
                </div>
            </div>
            <div class="grid grid-cols-3 mt-2">
                <div class="form-group">
                    <label class="form-label">Volume Panen (kg)</label>
                    <input type="text" class="form-control rupiah-input" data-target="volume_panen_kg" data-decimal="true" value="{{ old('volume_panen_kg', $record->volume_panen_kg) }}" inputmode="decimal">
                    <input type="hidden" name="volume_panen_kg" value="{{ old('volume_panen_kg', $record->volume_panen_kg) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Nilai Produksi (Rp)</label>
                    <input type="text" class="form-control rupiah-input" data-target="nilai_produksi" value="{{ old('nilai_produksi', $record->nilai_produksi) }}" inputmode="numeric">
                    <input type="hidden" name="nilai_produksi" value="{{ old('nilai_produksi', $record->nilai_produksi) }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Jumlah Pembudidaya Aktif</label>
                    <input type="text" class="form-control rupiah-input" data-target="jumlah_pembudidaya_aktif" value="{{ old('jumlah_pembudidaya_aktif', $record->jumlah_pembudidaya_aktif) }}" inputmode="numeric">
                    <input type="hidden" name="jumlah_pembudidaya_aktif" value="{{ old('jumlah_pembudidaya_aktif', $record->jumlah_pembudidaya_aktif) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: KPI Tambahan --}}
    <div class="monitoring-form-card">
        <div class="monitoring-form-header">
            <div class="monitoring-form-header-icon" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="monitoring-form-title">Indikator Kinerja (KPI)</h3>
                <p class="monitoring-form-desc">Data survival rate dan utilisasi kolam (opsional)</p>
            </div>
        </div>
        <div class="monitoring-form-body">
            <div class="grid grid-cols-3">
                <div class="form-group">
                    <label class="form-label">Survival Rate (%)</label>
                    <input type="number" name="survival_rate" min="0" max="100" step="0.01"
                        value="{{ old('survival_rate', $record->survival_rate) }}" class="form-control" placeholder="0-100">
                    <small style="color:var(--gray-400);font-size:0.72rem;">Tingkat kelangsungan hidup ikan</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Kolam Aktif</label>
                    <input type="number" name="jumlah_kolam_aktif" min="0"
                        value="{{ old('jumlah_kolam_aktif', $record->jumlah_kolam_aktif) }}" class="form-control" placeholder="Kolam aktif">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Jumlah Kolam Total</label>
                    <input type="number" name="jumlah_kolam_total" min="0"
                        value="{{ old('jumlah_kolam_total', $record->jumlah_kolam_total) }}" class="form-control" placeholder="Total kolam">
                </div>
            </div>
        </div>
    </div>

    {{-- Section 4: Upload Foto Dokumentasi --}}
    <div class="monitoring-form-card">
        <div class="monitoring-form-header">
            <div class="monitoring-form-header-icon" style="background: rgba(236, 72, 153, 0.1); color: #EC4899;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="monitoring-form-title">Foto Dokumentasi</h3>
                <p class="monitoring-form-desc">Upload foto kegiatan budidaya (JPG/PNG, maks. total 50 MB)</p>
            </div>
        </div>
        <div class="monitoring-form-body">
            {{-- Existing Photos --}}
            @if($record->foto && count($record->foto) > 0)
            <div style="margin-bottom:1rem;">
                <div style="font-size:0.78rem; font-weight:600; color:var(--gray-500); margin-bottom:0.5rem; display:flex; align-items:center; gap:0.35rem;">
                    <i class="fa-solid fa-images"></i> Foto Saat Ini ({{ count($record->foto) }})
                </div>
                <div class="existing-photos-grid">
                    @foreach($record->foto as $idx => $foto)
                    <div class="existing-photo-item" id="existingPhoto{{ $idx }}">
                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto {{ $idx + 1 }}">
                        <label class="existing-photo-keep" title="Hapus foto ini">
                            <input type="checkbox" name="hapus_foto[]" value="{{ $idx }}">
                            <i class="fa-solid fa-trash-can"></i>
                        </label>
                    </div>
                    @endforeach
                </div>
                <small style="color:var(--gray-400);font-size:0.7rem;">Centang <i class="fa-solid fa-trash-can" style="font-size:0.65rem;"></i> untuk menghapus foto</small>
            </div>
            @endif

            {{-- Upload New --}}
            <div class="upload-zone" id="uploadZone">
                <input type="file" name="foto[]" id="fotoInput" multiple accept="image/jpeg,image/png" style="display:none;">
                <div class="upload-zone-content" id="uploadPlaceholder">
                    <div class="upload-zone-icon">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <p class="upload-zone-text">Seret & lepas foto baru di sini, atau <span class="upload-zone-link">pilih file</span></p>
                    <p class="upload-zone-hint">Format: JPG, PNG · Bisa pilih lebih dari 1 file · Maks. total 50 MB</p>
                </div>
            </div>
            <div class="upload-preview-grid" id="previewGrid"></div>
            <div class="upload-info" id="uploadInfo" style="display:none;">
                <span id="uploadCount">0 file</span>
                <span class="upload-info-dot">·</span>
                <span id="uploadSize">0 MB</span>
                <span class="upload-info-dot">·</span>
                <span id="uploadLimit">Sisa: 50 MB</span>
                <button type="button" class="upload-clear-btn" id="clearAllBtn" title="Hapus Semua">
                    <i class="fa-solid fa-trash-can"></i> Hapus Semua
                </button>
            </div>
            <div class="upload-error" id="uploadError" style="display:none;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span id="uploadErrorMsg"></span>
            </div>
        </div>
    </div>

    {{-- Section 5: Catatan & Kendala --}}
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
                <textarea name="kendala" rows="3" class="form-control" placeholder="Tuliskan kendala yang dihadapi...">{{ old('kendala', $record->kendala) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" rows="3" class="form-control" placeholder="Rencana tindak lanjut...">{{ old('tindak_lanjut', $record->tindak_lanjut) }}</textarea>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" class="form-control" placeholder="Catatan lain...">{{ old('catatan', $record->catatan) }}</textarea>
            </div>
        </div>
    </div>

    {{-- Submit Actions --}}
    <div class="monitoring-form-actions">
        <a href="{{ route('produksi.show', $record->kdmp_id) }}" class="btn btn-outline">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Batal
        </a>
        <button type="submit" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Perubahan
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

    /* ===== Grid gap fix ===== */
    .monitoring-form-body .grid {
        gap: 1rem;
    }

    /* ===== Existing Photos Grid ===== */
    .existing-photos-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
    }
    .existing-photo-item {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--gray-200);
    }
    .existing-photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .existing-photo-keep {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(0,0,0,0.5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.65rem;
        transition: background 150ms;
    }
    .existing-photo-keep:hover {
        background: #DC2626;
    }
    .existing-photo-keep input[type="checkbox"] {
        display: none;
    }
    .existing-photo-keep input[type="checkbox"]:checked ~ i {
        color: #FCA5A5;
    }
    .existing-photo-item:has(input:checked) {
        opacity: 0.4;
        border-color: #DC2626;
    }

    /* ===== Upload Zone ===== */
    .upload-zone {
        border: 2px dashed var(--gray-300);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 200ms ease;
        background: var(--gray-50);
    }
    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: #EC4899;
        background: rgba(236, 72, 153, 0.04);
    }
    .upload-zone-icon {
        font-size: 2.25rem;
        color: var(--gray-300);
        margin-bottom: 0.75rem;
        transition: color 200ms;
    }
    .upload-zone:hover .upload-zone-icon,
    .upload-zone.dragover .upload-zone-icon {
        color: #EC4899;
    }
    .upload-zone-text {
        font-size: 0.88rem;
        font-weight: 500;
        color: var(--gray-600);
        margin: 0 0 0.25rem;
    }
    .upload-zone-link {
        color: #EC4899;
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .upload-zone-hint {
        font-size: 0.75rem;
        color: var(--gray-400);
        margin: 0;
    }

    /* Preview Grid */
    .upload-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }
    .upload-preview-item {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        aspect-ratio: 1;
        border: 1px solid var(--gray-200);
        background: var(--gray-100);
    }
    .upload-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .upload-preview-remove {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(0,0,0,0.6);
        color: #fff;
        border: none;
        font-size: 0.65rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 150ms;
        opacity: 0;
    }
    .upload-preview-item:hover .upload-preview-remove {
        opacity: 1;
    }
    .upload-preview-remove:hover {
        background: #DC2626;
    }
    .upload-preview-name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 4px 6px;
        background: linear-gradient(transparent, rgba(0,0,0,0.65));
        color: #fff;
        font-size: 0.62rem;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Upload Info Bar */
    .upload-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
        font-size: 0.78rem;
        color: var(--gray-500);
        font-weight: 500;
    }
    .upload-info-dot {
        color: var(--gray-300);
    }
    .upload-clear-btn {
        margin-left: auto;
        background: rgba(220, 38, 38, 0.08);
        color: #DC2626;
        border: none;
        padding: 0.3rem 0.7rem;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 150ms;
    }
    .upload-clear-btn:hover {
        background: rgba(220, 38, 38, 0.16);
    }

    /* Upload Error */
    .upload-error {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: #FEF2F2;
        border: 1px solid #FECACA;
        border-radius: 8px;
        font-size: 0.78rem;
        color: #DC2626;
        font-weight: 500;
    }

    /* ===== Dark Mode Overrides ===== */
    [data-theme="dark"] .monitoring-form-card {
        background: var(--bg-surface);
        border-color: #374151;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
    }
    [data-theme="dark"] .monitoring-form-header {
        background: #1F2937;
        border-color: #374151;
    }
    [data-theme="dark"] .monitoring-form-title { color: #F9FAFB; }
    [data-theme="dark"] .monitoring-form-desc { color: #9CA3AF; }
    [data-theme="dark"] .monitoring-alert-error {
        background: rgba(220, 38, 38, 0.1);
        border-color: #EF4444;
        color: #FCA5A5;
    }
    [data-theme="dark"] .upload-zone {
        background: rgba(255,255,255,0.02);
        border-color: #374151;
    }
    [data-theme="dark"] .upload-zone:hover,
    [data-theme="dark"] .upload-zone.dragover {
        border-color: #EC4899;
        background: rgba(236, 72, 153, 0.06);
    }
    [data-theme="dark"] .upload-zone-text { color: #D1D5DB; }
    [data-theme="dark"] .upload-zone-hint { color: #6B7280; }
    [data-theme="dark"] .upload-preview-item { border-color: #374151; background: #1F2937; }
    [data-theme="dark"] .upload-error {
        background: rgba(220, 38, 38, 0.12);
        border-color: rgba(239, 68, 68, 0.3);
        color: #FCA5A5;
    }
    [data-theme="dark"] .upload-info { color: #9CA3AF; }
    [data-theme="dark"] .existing-photo-item { border-color: #374151; }
</style>
@endpush

@push('scripts')
<script>
    // ===== RUPIAH AUTO-FORMAT =====
    function formatRupiah(angka) {
        if (angka === '' || angka === null || angka === undefined) return '0';
        const str = String(angka);
        const parts = str.split(',');
        let intPart = parts[0].replace(/\./g, '');
        intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return parts.length > 1 ? intPart + ',' + parts[1] : intPart;
    }

    function parseRupiah(str) {
        if (!str) return 0;
        const cleaned = str.replace(/\./g, '').replace(',', '.');
        const val = parseFloat(cleaned);
        return isNaN(val) ? 0 : val;
    }

    document.querySelectorAll('.rupiah-input').forEach(input => {
        const targetName = input.getAttribute('data-target');
        const hiddenInput = document.querySelector('input[type="hidden"][name="' + targetName + '"]');
        const allowDecimal = input.getAttribute('data-decimal') === 'true';

        const initVal = parseFloat(input.value) || 0;
        if (allowDecimal && initVal % 1 !== 0) {
            input.value = formatRupiah(String(initVal).replace('.', ','));
        } else {
            input.value = formatRupiah(String(Math.round(initVal)));
        }

        input.addEventListener('focus', function() {
            if (this.value === '0') this.select();
        });

        input.addEventListener('input', function() {
            let cursorPos = this.selectionStart;
            let oldLen = this.value.length;
            let raw = this.value;

            if (allowDecimal) {
                raw = raw.replace(/[^\d,]/g, '');
                const commaIdx = raw.indexOf(',');
                if (commaIdx !== -1) {
                    raw = raw.substring(0, commaIdx + 1) + raw.substring(commaIdx + 1).replace(/,/g, '');
                    const decPart = raw.substring(commaIdx + 1);
                    if (decPart.length > 2) raw = raw.substring(0, commaIdx + 3);
                }
                const parts = raw.split(',');
                parts[0] = parts[0].replace(/^0+/, '') || '0';
                raw = parts.join(',');
            } else {
                raw = raw.replace(/[^\d]/g, '');
                raw = raw.replace(/^0+/, '') || '0';
            }

            const formatted = formatRupiah(raw);
            this.value = formatted;
            let newLen = formatted.length;
            cursorPos = cursorPos + (newLen - oldLen);
            if (cursorPos < 0) cursorPos = 0;
            this.setSelectionRange(cursorPos, cursorPos);

            if (hiddenInput) hiddenInput.value = parseRupiah(formatted);
        });

        input.addEventListener('blur', function() {
            if (this.value === '' || this.value === ',') this.value = '0';
            if (hiddenInput) hiddenInput.value = parseRupiah(this.value);
        });

        if (hiddenInput) hiddenInput.value = parseRupiah(input.value);
    });

    // ===== FOTO UPLOAD HANDLER =====
    const MAX_TOTAL_SIZE = 50 * 1024 * 1024;
    const ALLOWED_TYPES = ['image/jpeg', 'image/png'];
    const uploadZone = document.getElementById('uploadZone');
    const fotoInput = document.getElementById('fotoInput');
    const previewGrid = document.getElementById('previewGrid');
    const uploadInfo = document.getElementById('uploadInfo');
    const uploadCount = document.getElementById('uploadCount');
    const uploadSize = document.getElementById('uploadSize');
    const uploadLimit = document.getElementById('uploadLimit');
    const uploadError = document.getElementById('uploadError');
    const uploadErrorMsg = document.getElementById('uploadErrorMsg');
    const clearAllBtn = document.getElementById('clearAllBtn');

    let selectedFiles = [];

    uploadZone.addEventListener('click', () => fotoInput.click());

    ['dragenter', 'dragover'].forEach(evt => {
        uploadZone.addEventListener(evt, e => { e.preventDefault(); uploadZone.classList.add('dragover'); });
    });
    ['dragleave', 'drop'].forEach(evt => {
        uploadZone.addEventListener(evt, e => { e.preventDefault(); uploadZone.classList.remove('dragover'); });
    });
    uploadZone.addEventListener('drop', e => {
        const files = Array.from(e.dataTransfer.files).filter(f => ALLOWED_TYPES.includes(f.type));
        addFiles(files);
    });

    fotoInput.addEventListener('change', () => {
        const files = Array.from(fotoInput.value ? fotoInput.files : []);
        addFiles(files);
    });

    clearAllBtn.addEventListener('click', () => {
        selectedFiles = [];
        syncInputFiles();
        renderPreviews();
    });

    function addFiles(newFiles) {
        hideError();
        const validFiles = newFiles.filter(f => {
            if (!ALLOWED_TYPES.includes(f.type)) {
                showError('Format file "' + f.name + '" tidak didukung. Hanya JPG dan PNG.');
                return false;
            }
            return true;
        });

        const merged = [...selectedFiles, ...validFiles];
        const totalSize = merged.reduce((sum, f) => sum + f.size, 0);

        if (totalSize > MAX_TOTAL_SIZE) {
            showError('Total ukuran file melebihi batas 50 MB.');
            return;
        }

        selectedFiles = merged;
        syncInputFiles();
        renderPreviews();
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        syncInputFiles();
        renderPreviews();
        hideError();
    }

    function syncInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fotoInput.files = dt.files;
    }

    function renderPreviews() {
        previewGrid.innerHTML = '';
        const totalSize = selectedFiles.reduce((sum, f) => sum + f.size, 0);

        if (selectedFiles.length === 0) {
            uploadInfo.style.display = 'none';
            return;
        }

        uploadInfo.style.display = 'flex';
        uploadCount.textContent = selectedFiles.length + ' file baru';
        uploadSize.textContent = formatSize(totalSize);
        uploadLimit.textContent = 'Sisa: ' + formatSize(MAX_TOTAL_SIZE - totalSize);

        selectedFiles.forEach((file, idx) => {
            const item = document.createElement('div');
            item.className = 'upload-preview-item';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.onload = () => URL.revokeObjectURL(img.src);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'upload-preview-remove';
            removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            removeBtn.addEventListener('click', (e) => { e.stopPropagation(); removeFile(idx); });

            const nameLabel = document.createElement('div');
            nameLabel.className = 'upload-preview-name';
            nameLabel.textContent = file.name;

            item.appendChild(img);
            item.appendChild(removeBtn);
            item.appendChild(nameLabel);
            previewGrid.appendChild(item);
        });
    }

    function formatSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function showError(msg) {
        uploadError.style.display = 'flex';
        uploadErrorMsg.textContent = msg;
    }

    function hideError() {
        uploadError.style.display = 'none';
    }
</script>
@endpush
