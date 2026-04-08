@extends('layouts.app')

@section('content')
<div class="page-header-row">
    <div>
        <h1 class="page-title">Tambah Laporan Monitoring</h1>
        <p class="page-subtitle">Input laporan perkembangan periodik lokasi KDMP</p>
    </div>
    <a href="{{ route('monitoring.index') }}" class="btn btn-outline">← Kembali</a>
</div>

<div class="section-card" style="max-width:820px;">
    <div class="section-body">
        <form action="{{ route('monitoring.store') }}" method="POST">
            @csrf

            @if ($errors->any())
            <div class="alert" style="background:#FEF2F2;border:1px solid #FECACA;border-radius:var(--radius);padding:1rem;margin-bottom:1.25rem;color:#DC2626;">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Pilih KDMP --}}
            <div class="form-group">
                <label class="form-label">Pilih KDKMP <span style="color:red">*</span></label>
                <select name="kdmp_id" id="kdmp_select" class="form-control form-select" required>
                    <option value="">-- Pilih KDKMP --</option>
                    @foreach($kdmpList as $k)
                    <option value="{{ $k->id }}" {{ (old('kdmp_id', $kdmpSelected?->id) == $k->id) ? 'selected' : '' }}>
                        [{{ $k->no }}] {{ $k->nama_kdkmp }} — {{ $k->kabupaten }}, {{ $k->provinsi }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Periode --}}
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Bulan <span style="color:red">*</span></label>
                    <select name="bulan" class="form-control form-select" required>
                        @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ old('bulan', date('n')) == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun <span style="color:red">*</span></label>
                    <select name="tahun" class="form-control form-select" required>
                        @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ old('tahun', date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Status & Progres --}}
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Status Lokasi <span style="color:red">*</span></label>
                    <select name="status_lokasi" class="form-control form-select" required>
                        <option value="on_track"   {{ old('status_lokasi') === 'on_track'   ? 'selected' : '' }}><i class="fa-solid fa-circle-check text-success mr-1"></i> On Track</option>
                        <option value="bermasalah" {{ old('status_lokasi') === 'bermasalah' ? 'selected' : '' }}><i class="fa-solid fa-circle-xmark text-danger mr-1"></i> Bermasalah</option>
                        <option value="selesai"    {{ old('status_lokasi') === 'selesai'    ? 'selected' : '' }}><i class="fa-solid fa-circle-check text-primary mr-1"></i> Selesai</option>
                        <option value="vakum"      {{ old('status_lokasi') === 'vakum'      ? 'selected' : '' }}><i class="fa-solid fa-circle-exclamation text-warning mr-1"></i> Vakum</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Progres Fisik (%) <span style="color:red">*</span></label>
                    <input type="number" name="progres_fisik" min="0" max="100"
                        value="{{ old('progres_fisik', 0) }}" class="form-control" required>
                </div>
            </div>

            {{-- Data Produksi --}}
            <div style="border-top:1px solid var(--gray-200);padding-top:1.25rem;margin-top:0.5rem;">
                <h4 style="font-weight:600;margin-bottom:1rem;color:var(--gray-700);">Data Produksi (opsional)</h4>
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
                    <div class="form-group">
                        <label class="form-label">Biaya Operasional (Rp)</label>
                        <input type="number" name="biaya_operasional" min="0"
                            value="{{ old('biaya_operasional', 0) }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Pembudidaya Aktif</label>
                        <input type="number" name="jumlah_pembudidaya_aktif" min="0"
                            value="{{ old('jumlah_pembudidaya_aktif', 0) }}" class="form-control">
                    </div>
                </div>
            </div>

            {{-- Catatan --}}
            <div style="border-top:1px solid var(--gray-200);padding-top:1.25rem;margin-top:0.5rem;">
                <div class="form-group">
                    <label class="form-label">Kendala</label>
                    <textarea name="kendala" rows="3" class="form-control" placeholder="Tuliskan kendala yang dihadapi...">{{ old('kendala') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tindak Lanjut</label>
                    <textarea name="tindak_lanjut" rows="3" class="form-control" placeholder="Rencana tindak lanjut...">{{ old('tindak_lanjut') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan Tambahan</label>
                    <textarea name="catatan" rows="2" class="form-control" placeholder="Catatan lain...">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-2">
                <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                <a href="{{ route('monitoring.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
