@extends('layouts.app')

@section('content')
<div class="page-header-row">
    <div>
        <h1 class="page-title">Tambah Data Progres Fisik</h1>
        <p class="page-subtitle">Input progres pembangunan infrastruktur per lokasi KDMP</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'Progres Fisik', 'url' => route('progres-fisik.index')],
        ['label' => 'Tambah Data', 'url' => '#']
    ]" />
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-icon teal"><i class="fa-solid fa-hammer" style="font-size:0.85rem;"></i></div>
        <h3 class="section-title">Form Input Progres Fisik</h3>
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

        <form method="POST" action="{{ route('progres-fisik.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Lokasi & Periode --}}
            <div class="detail-grid" style="margin-bottom:1.5rem;">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Lokasi KDMP <span class="text-danger">*</span></label>
                    <select name="kdmp_id" class="form-control form-select" required>
                        <option value="">— Pilih Lokasi KDMP —</option>
                        @foreach($kdmpList as $k)
                            <option value="{{ $k->id }}" {{ (old('kdmp_id', $kdmpSelected?->id) == $k->id) ? 'selected' : '' }}>
                                {{ $k->no }}. {{ $k->nama_kdkmp }} — {{ $k->kabupaten }}, {{ $k->provinsi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select name="bulan" class="form-control form-select" required>
                        @foreach($bulanList as $num => $nama)
                            <option value="{{ $num }}" {{ old('bulan', date('n')) == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <select name="tahun" class="form-control form-select" required>
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ old('tahun', date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
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
                    <span class="progres-display" data-target="{{ $comp['name'] }}" style="font-size:0.85rem;font-weight:700;color:{{ $comp['color'] }};">{{ old($comp['name'], 0) }}%</span>
                </div>
                <input type="range" name="{{ $comp['name'] }}" value="{{ old($comp['name'], 0) }}" min="0" max="100" step="5" class="form-range progres-slider" data-name="{{ $comp['name'] }}"
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
                <textarea name="kendala" class="form-control" rows="3" placeholder="Jelaskan kendala pembangunan (jika ada)...">{{ old('kendala') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" class="form-control" rows="3" placeholder="Rencana tindak lanjut (jika ada)...">{{ old('tindak_lanjut') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan lainnya...">{{ old('catatan') }}</textarea>
            </div>

            {{-- Dokumentasi Foto --}}
            <h4 style="font-size:0.9rem;font-weight:600;color:var(--gray-700);margin:1.5rem 0 1rem;padding-bottom:0.5rem;border-bottom:1px solid var(--border-color);">
                <i class="fa-solid fa-camera" style="color:var(--kkp-teal);margin-right:0.3rem;"></i> Dokumentasi Foto
            </h4>

            <div class="detail-grid" style="margin-bottom:1.5rem;">
                {{-- Foto Sebelum --}}
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-image" style="color:#F59E0B;margin-right:0.3rem;"></i> Foto Sebelum Pembangunan</label>
                    <div class="foto-upload-area" id="dropSebelum">
                        <input type="file" name="foto_sebelum[]" id="inputFotoSebelum" multiple accept="image/jpeg,image/png" class="foto-upload-input">
                        <div class="foto-upload-placeholder">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.5rem;color:var(--kkp-teal);margin-bottom:0.3rem;"></i>
                            <p style="margin:0;font-size:0.8rem;color:var(--gray-500);">Klik atau seret foto di sini</p>
                            <span style="font-size:0.7rem;color:var(--gray-400);">JPG, PNG — Maks 2MB total semua file</span>
                        </div>
                    </div>
                    <div class="foto-preview-grid" id="previewSebelum"></div>
                </div>
                {{-- Foto Sesudah --}}
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-image" style="color:#10B981;margin-right:0.3rem;"></i> Foto Sesudah Pembangunan</label>
                    <div class="foto-upload-area" id="dropSesudah">
                        <input type="file" name="foto_sesudah[]" id="inputFotoSesudah" multiple accept="image/jpeg,image/png" class="foto-upload-input">
                        <div class="foto-upload-placeholder">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.5rem;color:var(--kkp-teal);margin-bottom:0.3rem;"></i>
                            <p style="margin:0;font-size:0.8rem;color:var(--gray-500);">Klik atau seret foto di sini</p>
                            <span style="font-size:0.7rem;color:var(--gray-400);">JPG, PNG — Maks 2MB total semua file</span>
                        </div>
                    </div>
                    <div class="foto-preview-grid" id="previewSesudah"></div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="form-actions" style="display:flex;gap:0.75rem;padding-top:1rem;border-top:1px solid var(--border-color);margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check" style="font-size:0.75rem;"></i> Simpan Data</button>
                <a href="{{ route('progres-fisik.index', $kdmpSelected ? ['highlight' => $kdmpSelected->id] : []) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.foto-upload-area {
    position: relative;
    border: 2px dashed var(--border-color);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.25s, background 0.25s;
}
.foto-upload-area:hover,
.foto-upload-area.drag-over {
    border-color: var(--kkp-teal);
    background: rgba(8, 145, 178, 0.04);
}
.foto-upload-input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}
.foto-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 0.5rem;
    margin-top: 0.5rem;
}
.foto-preview-item {
    position: relative;
    border-radius: var(--radius-sm);
    overflow: hidden;
    aspect-ratio: 1;
    border: 1px solid var(--border-color);
}
.foto-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.foto-preview-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    background: rgba(220,38,38,0.85);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.65rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Slider display
    document.querySelectorAll('.progres-slider').forEach(slider => {
        const name = slider.dataset.name;
        const display = document.querySelector(`.progres-display[data-target="${name}"]`);
        slider.addEventListener('input', () => { display.textContent = slider.value + '%'; });
    });

    // Foto preview with file accumulation
    function setupFotoPreview(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        if (!input || !preview) return;

        // Store accumulated files
        let accumulatedFiles = new DataTransfer();

        function renderPreviews() {
            preview.innerHTML = '';
            Array.from(accumulatedFiles.files).forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'foto-preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="foto-preview-remove" data-index="${i}" title="Hapus foto">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    `;
                    // Handle remove
                    div.querySelector('.foto-preview-remove').addEventListener('click', function() {
                        const idx = parseInt(this.dataset.index);
                        const newDt = new DataTransfer();
                        Array.from(accumulatedFiles.files).forEach((f, j) => {
                            if (j !== idx) newDt.items.add(f);
                        });
                        accumulatedFiles = newDt;
                        input.files = accumulatedFiles.files;
                        renderPreviews();
                    });
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function getTotalSize() {
            let total = 0;
            Array.from(accumulatedFiles.files).forEach(f => total += f.size);
            return total;
        }

        input.addEventListener('change', function() {
            // Calculate size of new files
            const maxTotal = 2 * 1024 * 1024; // 2MB total
            let newSize = 0;
            Array.from(this.files).forEach(f => newSize += f.size);
            if (getTotalSize() + newSize > maxTotal) {
                const currentMB = ((getTotalSize() + newSize) / 1024 / 1024).toFixed(2);
                alert('Total ukuran semua file tidak boleh melebihi 2MB.\nTotal saat ini: ' + currentMB + 'MB. File tidak ditambahkan.');
            } else {
                Array.from(this.files).forEach(file => {
                    accumulatedFiles.items.add(file);
                });
            }
            // Update input with all accumulated files
            input.files = accumulatedFiles.files;
            renderPreviews();
        });

        // Drag & drop
        const area = input.closest('.foto-upload-area');
        ['dragenter','dragover'].forEach(e => area.addEventListener(e, ev => { ev.preventDefault(); area.classList.add('drag-over'); }));
        ['dragleave','drop'].forEach(e => area.addEventListener(e, ev => { ev.preventDefault(); area.classList.remove('drag-over'); }));
        area.addEventListener('drop', ev => {
            const maxTotal = 2 * 1024 * 1024;
            let newSize = 0;
            Array.from(ev.dataTransfer.files).forEach(f => newSize += f.size);
            if (getTotalSize() + newSize > maxTotal) {
                const currentMB = ((getTotalSize() + newSize) / 1024 / 1024).toFixed(2);
                alert('Total ukuran semua file tidak boleh melebihi 2MB.\nTotal saat ini: ' + currentMB + 'MB. File tidak ditambahkan.');
            } else {
                Array.from(ev.dataTransfer.files).forEach(file => {
                    accumulatedFiles.items.add(file);
                });
            }
            input.files = accumulatedFiles.files;
            renderPreviews();
        });
    }

    setupFotoPreview('inputFotoSebelum', 'previewSebelum');
    setupFotoPreview('inputFotoSesudah', 'previewSesudah');
});
</script>
@endpush
