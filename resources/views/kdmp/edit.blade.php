@extends('layouts.app')

@section('content')
<div class="page-header-row mb-4">
    <div>
        <h1 class="page-title">Edit Data KDMP</h1>
        <p class="page-subtitle">Pembaruan data lokasi Koperasi Desa Merah Putih (KDMP)</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'KDMP', 'url' => route('kdmp.index')],
        ['label' => 'Edit Data']
    ]" />
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body p-4">
                <form action="{{ route('kdmp.update', $kdmp) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="mb-3"><i class="fa-solid fa-map-location-dot"></i> Data Wilayah</h5>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <select name="provinsi" class="form-control" required>
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->name }}" {{ old('provinsi', $kdmp->provinsi) == $prov->name ? 'selected' : '' }}>{{ $prov->name }}</option>
                                @endforeach
                            </select>
                            @error('provinsi')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                            <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten', $kdmp->kabupaten) }}" placeholder="Contoh: Kab. Bogor" required>
                            @error('kabupaten')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Desa/Kelurahan</label>
                            <input type="text" name="desa" class="form-control" value="{{ old('desa', $kdmp->desa) }}" placeholder="Nama desa/kelurahan">
                            @error('desa')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <h5 class="mb-3"><i class="fa-solid fa-building"></i> Informasi KDMP</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama KDKMP <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kdkmp" class="form-control" value="{{ old('nama_kdkmp', $kdmp->nama_kdkmp) }}" placeholder="Nama Koperasi" required>
                            @error('nama_kdkmp')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Komoditas</label>
                            <select name="komoditas" class="form-control">
                                <option value="">-- Pilih Komoditas --</option>
                                <option value="Lele" {{ old('komoditas', $kdmp->komoditas) == 'Lele' ? 'selected' : '' }}>Lele</option>
                                <option value="Nila" {{ old('komoditas', $kdmp->komoditas) == 'Nila' ? 'selected' : '' }}>Nila</option>
                            </select>
                            @error('komoditas')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ketua/Anggota</label>
                            <input type="text" name="ketua_anggota" class="form-control" value="{{ old('ketua_anggota', $kdmp->ketua_anggota) }}" placeholder="Nama ketua atau perwakilan">
                            @error('ketua_anggota')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. HP (Ketua/Anggota)</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $kdmp->no_hp) }}" placeholder="Contoh: 08123456789">
                            @error('no_hp')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <h5 class="mb-3"><i class="fa-solid fa-user-tie"></i> Pendampingan</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Penyuluh</label>
                            <input type="text" name="nama_penyuluh" class="form-control" value="{{ old('nama_penyuluh', $kdmp->nama_penyuluh) }}" placeholder="Nama penyuluh pendamping">
                            @error('nama_penyuluh')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. HP Penyuluh</label>
                            <input type="text" name="no_hp_penyuluh" class="form-control" value="{{ old('no_hp_penyuluh', $kdmp->no_hp_penyuluh) }}" placeholder="Contoh: 08123456789">
                            @error('no_hp_penyuluh')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <h5 class="mb-3"><i class="fa-solid fa-location-crosshairs"></i> Koordinat Lokasi</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="lat" class="form-control" value="{{ old('lat', $kdmp->lat) }}" placeholder="Contoh: -6.123456">
                            @error('lat')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="long" class="form-control" value="{{ old('long', $kdmp->long) }}" placeholder="Contoh: 106.123456">
                            @error('long')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('kdmp.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
