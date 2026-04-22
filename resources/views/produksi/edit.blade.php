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


</script>
@endpush
