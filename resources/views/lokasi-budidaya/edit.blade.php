@extends('layouts.app')

@section('content')
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Edit Lokasi Budidaya</h1>
            <p class="page-subtitle">Ubah data lokasi budidaya</p>
        </div>
        <x-breadcrumb :items="[
            ['label' => 'Lokasi Budidaya', 'url' => route('lokasi-budidaya.index')],
            ['label' => 'Edit', 'url' => '']
        ]" />
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('lokasi-budidaya.update', $lokasiBudidaya) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2">
                    {{-- Nama Koperasi --}}
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label" for="nama_koperasi">Nama Koperasi <span class="required">*</span></label>
                        <input type="text" name="nama_koperasi" id="nama_koperasi" class="form-control @error('nama_koperasi') is-invalid @enderror" value="{{ old('nama_koperasi', $lokasiBudidaya->nama_koperasi) }}" required>
                        @error('nama_koperasi')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lokasi: Provinsi --}}
                    <div class="form-group">
                        <label class="form-label" for="provinsi">Provinsi <span class="required">*</span></label>
                        <input type="text" name="provinsi" id="provinsi" class="form-control @error('provinsi') is-invalid @enderror" value="{{ old('provinsi', $lokasiBudidaya->provinsi) }}" required>
                        @error('provinsi')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kabupaten / Kota --}}
                    <div class="form-group">
                        <label class="form-label" for="kabupaten_kota">Kabupaten / Kota</label>
                        <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="form-control @error('kabupaten_kota') is-invalid @enderror" value="{{ old('kabupaten_kota', $lokasiBudidaya->kabupaten_kota) }}">
                        @error('kabupaten_kota')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kecamatan --}}
                    <div class="form-group">
                        <label class="form-label" for="kecamatan">Kecamatan</label>
                        <input type="text" name="kecamatan" id="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror" value="{{ old('kecamatan', $lokasiBudidaya->kecamatan) }}">
                        @error('kecamatan')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Desa --}}
                    <div class="form-group">
                        <label class="form-label" for="desa">Desa</label>
                        <input type="text" name="desa" id="desa" class="form-control @error('desa') is-invalid @enderror" value="{{ old('desa', $lokasiBudidaya->desa) }}">
                        @error('desa')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Volume --}}
                    <div class="form-group">
                        <label class="form-label" for="volume">Volume <span class="required">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" name="volume" id="volume" class="form-control @error('volume') is-invalid @enderror" value="{{ old('volume', $lokasiBudidaya->volume) }}" required>
                        </div>
                        @error('volume')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Hasil Panen (kg) --}}
                    <div class="form-group">
                        <label class="form-label" for="hasil_panen_kg">Hasil Panen (kg) <span class="required">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" name="hasil_panen_kg" id="hasil_panen_kg" class="form-control @error('hasil_panen_kg') is-invalid @enderror" value="{{ old('hasil_panen_kg', $lokasiBudidaya->hasil_panen_kg) }}" required>
                        </div>
                        @error('hasil_panen_kg')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nilai Hasil Panen (Rp) --}}
                    <div class="form-group">
                        <label class="form-label" for="nilai_hasil_panen">Nilai Hasil Panen (Rp) <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-icon" style="left:12px;">Rp</span>
                            <input type="number" step="0.01" min="0" name="nilai_hasil_panen" id="nilai_hasil_panen" class="form-control @error('nilai_hasil_panen') is-invalid @enderror" style="padding-left: 36px;" value="{{ old('nilai_hasil_panen', $lokasiBudidaya->nilai_hasil_panen) }}" required>
                        </div>
                        @error('nilai_hasil_panen')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Biaya Operasional (Rp) --}}
                    <div class="form-group">
                        <label class="form-label" for="biaya_operasional">Biaya Operasional (Rp) <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-icon" style="left:12px;">Rp</span>
                            <input type="number" step="0.01" min="0" name="biaya_operasional" id="biaya_operasional" class="form-control @error('biaya_operasional') is-invalid @enderror" style="padding-left: 36px;" value="{{ old('biaya_operasional', $lokasiBudidaya->biaya_operasional) }}" required>
                        </div>
                        @error('biaya_operasional')
                            <div class="text-danger text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Harga Jual per kg (Rp) --}}
                    <div class="form-group">
                        <label class="form-label" for="harga_jual_per_kg">Harga Jual per kg (Rp) <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-icon" style="left:12px;">Rp</span>
                            <input type="number" step="0.01" min="0" name="harga_jual_per_kg" id="harga_jual_per_kg" class="form-control @error('harga_jual_per_kg') is-invalid @enderror" style="padding-left: 36px;" value="{{ old('harga_jual_per_kg', $lokasiBudidaya->harga_jual_per_kg) }}" required>
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
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
