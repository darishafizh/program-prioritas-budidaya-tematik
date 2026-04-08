@extends('layouts.app')

@section('content')
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Tambah Lokasi Budidaya</h1>
            <p class="page-subtitle">Tambah data lokasi budidaya baru</p>
        </div>
        <x-breadcrumb :items="[
            ['label' => 'Lokasi Budidaya', 'url' => route('lokasi-budidaya.index')],
            ['label' => 'Tambah', 'url' => '']
        ]" />
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('lokasi-budidaya.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label" for="nama_koperasi">Nama Koperasi <span class="required">*</span></label>
                        <input type="text" name="nama_koperasi" id="nama_koperasi" class="form-control @error('nama_koperasi') is-invalid @enderror" value="{{ old('nama_koperasi') }}" required>
                        @error('nama_koperasi')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="lokasi">Lokasi <span class="required">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi') }}" required>
                        @error('lokasi')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="volume_hasil_panen">Volume Hasil Panen (kg) <span class="required">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" name="volume_hasil_panen" id="volume_hasil_panen" class="form-control @error('volume_hasil_panen') is-invalid @enderror" value="{{ old('volume_hasil_panen', 0) }}" required>
                        </div>
                        @error('volume_hasil_panen')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="nilai_hasil_panen">Nilai Hasil Panen (Rp) <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-icon" style="left:12px;">Rp</span>
                            <input type="number" step="0.01" min="0" name="nilai_hasil_panen" id="nilai_hasil_panen" class="form-control @error('nilai_hasil_panen') is-invalid @enderror" style="padding-left: 36px;" value="{{ old('nilai_hasil_panen', 0) }}" required>
                        </div>
                        @error('nilai_hasil_panen')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="biaya_operasional">Biaya Operasional (Rp) <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-icon" style="left:12px;">Rp</span>
                            <input type="number" step="0.01" min="0" name="biaya_operasional" id="biaya_operasional" class="form-control @error('biaya_operasional') is-invalid @enderror" style="padding-left: 36px;" value="{{ old('biaya_operasional', 0) }}" required>
                        </div>
                        @error('biaya_operasional')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="harga_jual_per_kg">Harga Jual per kg (Rp) <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-icon" style="left:12px;">Rp</span>
                            <input type="number" step="0.01" min="0" name="harga_jual_per_kg" id="harga_jual_per_kg" class="form-control @error('harga_jual_per_kg') is-invalid @enderror" style="padding-left: 36px;" value="{{ old('harga_jual_per_kg', 0) }}" required>
                        </div>
                        @error('harga_jual_per_kg')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions mt-4">
                    <a href="{{ route('lokasi-budidaya.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-success">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
