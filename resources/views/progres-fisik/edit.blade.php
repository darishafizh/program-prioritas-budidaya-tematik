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

        <form method="POST" action="{{ route('progres-fisik.update', $record->id) }}" enctype="multipart/form-data">
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

            {{-- Dokumentasi Foto --}}
            <h4 style="font-size:0.9rem;font-weight:600;color:var(--gray-700);margin:1.5rem 0 1rem;padding-bottom:0.5rem;border-bottom:1px solid var(--border-color);">
                <i class="fa-solid fa-camera" style="color:var(--kkp-teal);margin-right:0.3rem;"></i> Dokumentasi Foto
            </h4>

            <div class="detail-grid" style="margin-bottom:1.5rem;">
                {{-- Foto Sebelum --}}
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-image" style="color:#F59E0B;margin-right:0.3rem;"></i> Foto Sebelum Pembangunan</label>
                    
                    {{-- Existing Foto Sebelum --}}
                    @if(!empty($record->foto_sebelum) && is_array($record->foto_sebelum))
                    <div style="margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:0.5rem;">Foto Tersimpan <span style="font-weight:normal;">(centang untuk hapus)</span>:</span>
                        <div class="foto-preview-grid">
                            @foreach($record->foto_sebelum as $index => $path)
                            <div class="existing-foto-item">
                                <img src="{{ asset('storage/'.$path) }}" alt="Foto Sebelum">
                                <label class="foto-delete-checkbox" title="Hapus foto ini">
                                    <input type="checkbox" name="hapus_foto_sebelum[]" value="{{ $index }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="foto-upload-area" id="dropSebelum">
                        <input type="file" name="foto_sebelum[]" id="inputFotoSebelum" multiple accept="image/jpeg,image/png" class="foto-upload-input">
                        <div class="foto-upload-placeholder">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.5rem;color:var(--kkp-teal);margin-bottom:0.3rem;"></i>
                            <p style="margin:0;font-size:0.8rem;color:var(--gray-500);">Klik atau seret foto baru di sini</p>
                        </div>
                    </div>
                    <div class="foto-preview-grid" id="previewSebelum"></div>
                </div>

                {{-- Foto Sesudah --}}
                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-image" style="color:#10B981;margin-right:0.3rem;"></i> Foto Sesudah Pembangunan</label>
                    
                    {{-- Existing Foto Sesudah --}}
                    @if(!empty($record->foto_sesudah) && is_array($record->foto_sesudah))
                    <div style="margin-bottom:0.75rem;">
                        <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:0.5rem;">Foto Tersimpan <span style="font-weight:normal;">(centang untuk hapus)</span>:</span>
                        <div class="foto-preview-grid">
                            @foreach($record->foto_sesudah as $index => $path)
                            <div class="existing-foto-item">
                                <img src="{{ asset('storage/'.$path) }}" alt="Foto Sesudah">
                                <label class="foto-delete-checkbox" title="Hapus foto ini">
                                    <input type="checkbox" name="hapus_foto_sesudah[]" value="{{ $index }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="foto-upload-area" id="dropSesudah">
                        <input type="file" name="foto_sesudah[]" id="inputFotoSesudah" multiple accept="image/jpeg,image/png" class="foto-upload-input">
                        <div class="foto-upload-placeholder">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.5rem;color:var(--kkp-teal);margin-bottom:0.3rem;"></i>
                            <p style="margin:0;font-size:0.8rem;color:var(--gray-500);">Klik atau seret foto baru di sini</p>
                        </div>
                    </div>
                    <div class="foto-preview-grid" id="previewSesudah"></div>
                </div>
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
.existing-foto-item {
    position: relative;
    border-radius: var(--radius-sm);
    overflow: hidden;
    aspect-ratio: 1;
    border: 1px solid var(--border-color);
}
.existing-foto-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.foto-delete-checkbox {
    position: absolute;
    top: 4px;
    right: 4px;
    background: rgba(255,255,255,0.9);
    border-radius: var(--radius-sm);
    padding: 0.15rem 0.35rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.7rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    color: var(--gray-600);
}
.foto-delete-checkbox:has(input:checked) {
    background: #DC2626;
    color: white;
}
.foto-delete-checkbox input {
    margin: 0;
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
