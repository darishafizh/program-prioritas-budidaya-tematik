@extends('layouts.app')

@section('content')
<div class="page-header-row">
    <div>
        <h1 class="page-title">Edit Laporan Monitoring</h1>
        <p class="page-subtitle">{{ $record->kdmp->nama_kdkmp }} — {{ $record->bulan_label }} {{ $record->tahun }}</p>
    </div>
    <a href="{{ route('produksi.show', $record->kdmp_id) }}" class="btn btn-outline">← Kembali</a>
</div>

<div class="section-card" style="max-width:820px;">
    <div class="section-body">
        <form action="{{ route('produksi.update', $record->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
            <div class="alert" style="background:#FEF2F2;border:1px solid #FECACA;border-radius:var(--radius);padding:1rem;margin-bottom:1.25rem;color:#DC2626;">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            {{-- Info Readonly --}}
            <div class="grid grid-cols-3 mb-4" style="background:var(--gray-50);border-radius:var(--radius);padding:1rem;">
                <div><span class="text-muted text-sm">KDKMP</span><div class="font-semibold">{{ $record->kdmp->nama_kdkmp }}</div></div>
                <div><span class="text-muted text-sm">Periode</span><div class="font-semibold">{{ $bulanList[$record->bulan] }} {{ $record->tahun }}</div></div>
                <div><span class="text-muted text-sm">Kabupaten</span><div class="font-semibold">{{ $record->kdmp->kabupaten }}</div></div>
            </div>

            <input type="hidden" name="status_lokasi" value="{{ $record->status_lokasi }}">
            <input type="hidden" name="progres_fisik" value="{{ $record->progres_fisik }}">

            <div class="grid grid-cols-3">
                <div class="form-group">
                    <label class="form-label">Biaya Pakan (Rp)</label>
                    <input type="number" name="biaya_pakan" min="0"
                        value="{{ old('biaya_pakan', $record->biaya_pakan) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Biaya Bibit (Rp)</label>
                    <input type="number" name="biaya_bibit" min="0"
                        value="{{ old('biaya_bibit', $record->biaya_bibit) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Biaya Lainnya (Rp)</label>
                    <input type="number" name="biaya_lainnya" min="0"
                        value="{{ old('biaya_lainnya', $record->biaya_lainnya) }}" class="form-control">
                </div>
            </div>

            <div class="grid grid-cols-3">
                <div class="form-group">
                    <label class="form-label">Volume Panen (kg)</label>
                    <input type="number" name="volume_panen_kg" min="0" step="0.01"
                        value="{{ old('volume_panen_kg', $record->volume_panen_kg) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Nilai Produksi (Rp)</label>
                    <input type="number" name="nilai_produksi" min="0"
                        value="{{ old('nilai_produksi', $record->nilai_produksi) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Pembudidaya Aktif</label>
                    <input type="number" name="jumlah_pembudidaya_aktif" min="0"
                        value="{{ old('jumlah_pembudidaya_aktif', $record->jumlah_pembudidaya_aktif) }}" class="form-control">
                </div>
            </div>

            {{-- KPI Tambahan --}}
            <div class="grid grid-cols-3">
                <div class="form-group">
                    <label class="form-label">Survival Rate (%)</label>
                    <input type="number" name="survival_rate" min="0" max="100" step="0.01"
                        value="{{ old('survival_rate', $record->survival_rate) }}" class="form-control" placeholder="0-100">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Kolam Aktif</label>
                    <input type="number" name="jumlah_kolam_aktif" min="0"
                        value="{{ old('jumlah_kolam_aktif', $record->jumlah_kolam_aktif) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Kolam Total</label>
                    <input type="number" name="jumlah_kolam_total" min="0"
                        value="{{ old('jumlah_kolam_total', $record->jumlah_kolam_total) }}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kendala</label>
                <textarea name="kendala" rows="3" class="form-control">{{ old('kendala', $record->kendala) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" rows="3" class="form-control">{{ old('tindak_lanjut', $record->tindak_lanjut) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" rows="2" class="form-control">{{ old('catatan', $record->catatan) }}</textarea>
            </div>

            <div class="flex gap-3 mt-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('produksi.show', $record->kdmp_id) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
