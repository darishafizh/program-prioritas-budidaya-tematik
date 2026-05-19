@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Edit Kuesioner SPPG</h1>
        <p class="page-subtitle">Survei kebutuhan ikan untuk MBG dan sinergi dengan proyek bioflok KDMP</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'SPPG', 'url' => route('sppg.index')],
        ['label' => 'Edit', 'url' => '#']
    ]" />
</div>

<form action="{{ route('sppg.update', $sppg) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Data Verifikator & Responden -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h3 class="section-title">Data Verifikator & Responden</h3>
        </div>
        <div class="section-body">
            <div class="grid grid-cols-3">
                <div class="form-group">
                    <label class="form-label">Nama Verifikator/Interviewer/Pewawancara</label>
                    <input type="text" name="verifikator" class="form-control" placeholder="Nama verifikator" value="{{ old('verifikator', $sppg->verifikator) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat</label>
                    <input type="text" name="tempat" class="form-control" placeholder="Tempat wawancara" value="{{ old('tempat', $sppg->tempat) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $sppg->tanggal) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jam</label>
                    <input type="time" name="jam" class="form-control" value="{{ old('jam', $sppg->jam) }}">
                </div>
            </div>

            <div class="border-t mt-4 pt-4">
                <label class="form-label font-semibold mb-3">Data Responden</label>
                <div class="grid grid-cols-4">
                    <div class="form-group">
                        <label class="form-label">Nama Responden</label>
                        <input type="text" name="responden" class="form-control" placeholder="Nama responden" value="{{ old('responden', $sppg->responden) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control form-select">
                            <option value="">Pilih</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $sppg->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $sppg->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Umur (tahun)</label>
                        <input type="number" name="umur" min="1" class="form-control" placeholder="0" value="{{ old('umur', $sppg->umur) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan" class="form-control form-select">
                            <option value="">Pilih</option>
                            @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $p)
                            <option value="{{ $p }}" {{ old('pendidikan', $sppg->pendidikan) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan/Jabatan</label>
                        <input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan" value="{{ old('pekerjaan', $sppg->pekerjaan) }}">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Alamat sesuai KTP</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat lengkap" value="{{ old('alamat', $sppg->alamat) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Koordinat GPS</label>
                        <input type="text" name="koordinat" class="form-control" placeholder="-6.123, 106.456" value="{{ old('koordinat', $sppg->koordinat) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="alert alert-info mb-4" style="background:#E0F2FE; border:1px solid #0EA5E9; color:#0369A1; padding:1rem; border-radius:8px;">
        <strong>Catatan:</strong> SPPG memerlukan data kebutuhan ikan untuk MBG agar sinergi dengan proyek bioflok KDMP lebih optimal, termasuk volume, jenis, kualitas, dan logistik.
    </div>

    <!-- Bagian A: Data SPPG dan Kebutuhan Bulanan -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon teal">A</div>
            <h3 class="section-title">Data SPPG dan Kebutuhan Bulanan</h3>
        </div>
        <div class="section-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Pertanyaan</th>
                            <th style="min-width:300px">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Nama SPPG, lokasi (kab/kota), dan jumlah sekolah/penerima MBG</td>
                            <td>
                                <div class="grid grid-cols-1" style="gap:0.5rem;">
                                    <input type="text" name="nama_sppg" class="form-control" placeholder="Nama SPPG" value="{{ old('nama_sppg', $sppg->nama_sppg) }}">
                                    <input type="text" name="lokasi_sppg" class="form-control" placeholder="Lokasi (Kab/Kota)" value="{{ old('lokasi_sppg', $sppg->lokasi_sppg) }}">
                                    <input type="number" name="jumlah_sekolah" min="0" class="form-control" placeholder="Jumlah sekolah/penerima" value="{{ old('jumlah_sekolah', $sppg->jumlah_sekolah) }}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>Jumlah porsi MBG harian/bulanan</td>
                            <td>
                                <div class="grid grid-cols-2" style="gap:0.5rem;">
                                    <input type="number" name="porsi_harian" min="0" class="form-control" placeholder="Harian" value="{{ old('porsi_harian', $sppg->porsi_harian) }}">
                                    <input type="number" name="porsi_bulanan" min="0" class="form-control" placeholder="Bulanan" value="{{ old('porsi_bulanan', $sppg->porsi_bulanan) }}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Kebutuhan ikan bulanan (kg)</td>
                            <td>
                                <div class="grid grid-cols-3" style="gap:0.5rem;">
                                    <div>
                                        <label class="form-label text-sm">Lele (kg)</label>
                                        <input type="number" name="kebutuhan_lele" min="0" class="form-control" placeholder="0" value="{{ old('kebutuhan_lele', $sppg->kebutuhan_lele) }}">
                                    </div>
                                    <div>
                                        <label class="form-label text-sm">Nila (kg)</label>
                                        <input type="number" name="kebutuhan_nila" min="0" class="form-control" placeholder="0" value="{{ old('kebutuhan_nila', $sppg->kebutuhan_nila) }}">
                                    </div>
                                    <div>
                                        <label class="form-label text-sm">Lainnya (kg)</label>
                                        <input type="number" name="kebutuhan_lainnya" min="0" class="form-control" placeholder="0" value="{{ old('kebutuhan_lainnya', $sppg->kebutuhan_lainnya) }}">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">4</td>
                            <td>Frekuensi menu ikan per minggu (hari)</td>
                            <td>
                                <input type="number" name="frekuensi_menu" min="0" max="7" class="form-control" placeholder="0" value="{{ old('frekuensi_menu', $sppg->frekuensi_menu) }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">5</td>
                            <td>Berapa anggaran yang disiapkan per porsi/siswa?</td>
                            <td>
                                <input type="number" name="anggaran_porsi" min="0" class="form-control" placeholder="Rp 0" value="{{ old('anggaran_porsi', $sppg->anggaran_porsi) }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">6</td>
                            <td>Apakah kebutuhan SPPG untuk protein ikan sudah terpenuhi? Jika belum, berapa banyak yang dibutuhkan?</td>
                            <td>
                                <div class="flex gap-3 mb-2">
                                    <label class="form-check">
                                        <input type="radio" name="kebutuhan_terpenuhi" value="Sudah" class="form-check-input" onclick="document.getElementById('kebutuhan_tambahan_wrapper').style.display='none'" {{ old('kebutuhan_terpenuhi', $sppg->kebutuhan_terpenuhi) == 'Sudah' ? 'checked' : '' }}>
                                        <span class="form-check-label">Sudah terpenuhi</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="radio" name="kebutuhan_terpenuhi" value="Belum" class="form-check-input" onclick="document.getElementById('kebutuhan_tambahan_wrapper').style.display='block'" {{ old('kebutuhan_terpenuhi', $sppg->kebutuhan_terpenuhi) == 'Belum' ? 'checked' : '' }}>
                                        <span class="form-check-label">Belum terpenuhi</span>
                                    </label>
                                </div>
                                <div id="kebutuhan_tambahan_wrapper" style="display:{{ old('kebutuhan_terpenuhi', $sppg->kebutuhan_terpenuhi) == 'Belum' ? 'block' : 'none' }};">
                                    <input type="number" name="kebutuhan_tambahan" min="0" class="form-control" placeholder="Kebutuhan tambahan (kg)" value="{{ old('kebutuhan_tambahan', $sppg->kebutuhan_tambahan) }}">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bagian B: Preferensi Jenis & Spesifikasi Ikan -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon navy">B</div>
            <h3 class="section-title">Preferensi Jenis & Spesifikasi Ikan</h3>
        </div>
        <div class="section-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Pertanyaan</th>
                            <th style="min-width:350px">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Jenis ikan prioritas</td>
                            <td>
                                @php
                                    $val = $sppg->jenis_prioritas;
                                    $savedJenisPrioritas = is_string($val) ? json_decode($val, true) : $val;
                                    if(!is_array($savedJenisPrioritas)) $savedJenisPrioritas = [$val];
                                    $oldJenisPrioritas = old('jenis_prioritas', $savedJenisPrioritas ?? []);
                                @endphp
                                <div class="flex gap-3 flex-wrap">
                                    @foreach(['Lele', 'Nila', 'Patin', 'Lainnya'] as $ikan)
                                    <label class="form-check">
                                        <input type="checkbox" name="jenis_prioritas[]" value="{{ $ikan }}" class="form-check-input" {{ in_array($ikan, $oldJenisPrioritas) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ $ikan }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                <input type="text" name="jenis_prioritas_lainnya" class="form-control mt-2" placeholder="Jika lainnya, sebutkan..." value="{{ old('jenis_prioritas_lainnya', $sppg->jenis_prioritas_lainnya) }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>Ukuran/kondisi</td>
                            <td>
                                @php
                                    $val = $sppg->ukuran_kondisi;
                                    $savedUkuranKondisi = is_string($val) ? json_decode($val, true) : $val;
                                    if(!is_array($savedUkuranKondisi)) $savedUkuranKondisi = [$val];
                                    $oldUkuranKondisi = old('ukuran_kondisi', $savedUkuranKondisi ?? []);
                                @endphp
                                <div class="flex gap-3 flex-wrap mb-2">
                                    @foreach(['Fillet', 'Utuh'] as $kondisi)
                                    <label class="form-check">
                                        <input type="checkbox" name="ukuran_kondisi[]" value="{{ $kondisi }}" class="form-check-input" {{ in_array($kondisi, $oldUkuranKondisi) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ $kondisi }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                <input type="text" name="ukuran_spesifik" class="form-control" placeholder="Ukuran spesifik (cm/kg)" value="{{ old('ukuran_spesifik', $sppg->ukuran_spesifik) }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Standar kualitas</td>
                            <td>
                                @php
                                    $valKualitas = $sppg->standar_kualitas;
                                    if (is_array($valKualitas)) $valKualitas = $valKualitas[0] ?? '';
                                @endphp
                                <div class="flex gap-3 flex-wrap">
                                    @foreach(['Segar (1 hari dari panen)', 'Beku', 'Olahan'] as $kualitas)
                                    <label class="form-check">
                                        <input type="radio" name="standar_kualitas" value="{{ $kualitas }}" class="form-check-input" {{ old('standar_kualitas', $valKualitas) == $kualitas ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ $kualitas }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">4</td>
                            <td>Sertifikasi</td>
                            <td>
                                @php
                                    $val = $sppg->sertifikasi;
                                    $savedSertifikasi = is_string($val) ? json_decode($val, true) : $val;
                                    if(!is_array($savedSertifikasi)) $savedSertifikasi = [$val];
                                    $oldSertifikasi = old('sertifikasi', $savedSertifikasi ?? []);
                                @endphp
                                <div class="flex gap-3 flex-wrap">
                                    @foreach(['Bebas formalin', 'Terdaftar KKP', 'Lainnya'] as $sertifikasi)
                                    <label class="form-check">
                                        <input type="checkbox" name="sertifikasi[]" value="{{ $sertifikasi }}" class="form-check-input" {{ in_array($sertifikasi, $oldSertifikasi) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ $sertifikasi }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                <input type="text" name="sertifikasi_lainnya" class="form-control mt-2" placeholder="Jika lainnya, sebutkan..." value="{{ old('sertifikasi_lainnya', $sppg->sertifikasi_lainnya) }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">5</td>
                            <td>Sumber prioritas</td>
                            <td>
                                @php
                                    $val = $sppg->sumber_prioritas;
                                    $savedSumberPrioritas = is_string($val) ? json_decode($val, true) : $val;
                                    if(!is_array($savedSumberPrioritas)) $savedSumberPrioritas = [$val];
                                    $oldSumberPrioritas = old('sumber_prioritas', $savedSumberPrioritas ?? []);
                                @endphp
                                <div class="flex gap-3 flex-wrap">
                                    @foreach(['Lokal bioflok KDMP', 'Tambak ikan MBG', 'Pasar'] as $sumber)
                                    <label class="form-check">
                                        <input type="checkbox" name="sumber_prioritas[]" value="{{ $sumber }}" class="form-check-input" {{ in_array($sumber, $oldSumberPrioritas) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ $sumber }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Rekapitulasi: Logistik & Harga -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="section-title">Rekapitulasi - Logistik & Harga</h3>
        </div>
        <div class="section-body">
            <div class="alert alert-info mb-4" style="background:#FEF3C7; border:1px solid #F59E0B; color:#92400E; padding:0.75rem; border-radius:8px;">
                <strong>Petunjuk:</strong> Skala 1-5; 1 = Tidak Penting, 5 = Sangat Penting
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Pertanyaan</th>
                            <th style="width:200px">Skala Kepentingan (1-5)</th>
                            <th style="width:180px">Harga Toleransi (Rp/kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $rekapItems = [
                            ['name' => 'rekap_jenis', 'label' => 'Jenis ikan prioritas'],
                            ['name' => 'rekap_ukuran', 'label' => 'Ukuran/kondisi'],
                            ['name' => 'rekap_kualitas', 'label' => 'Standar kualitas'],
                            ['name' => 'rekap_sertifikasi', 'label' => 'Sertifikasi'],
                            ['name' => 'rekap_sumber', 'label' => 'Sumber prioritas'],
                        ];
                        @endphp
                        @foreach($rekapItems as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item['label'] }}</td>
                            <td>
                                <div class="flex gap-2 justify-center">
                                    @for($i = 1; $i <= 5; $i++)
                                    <label class="likert-scale-option">
                                        <input type="radio" name="{{ $item['name'] }}_skala" value="{{ $i }}" {{ old($item['name'].'_skala', $sppg->{$item['name'].'_skala'}) == $i ? 'checked' : '' }}>
                                        <span>{{ $i }}</span>
                                    </label>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <input type="number" name="{{ $item['name'] }}_harga" min="0" class="form-control" placeholder="Rp 0" value="{{ old($item['name'].'_harga', $sppg->{$item['name'].'_harga'}) }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kendala dan Kerjasama -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="section-title">Kendala dan Kerjasama</h3>
        </div>
        <div class="section-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Pertanyaan</th>
                            <th style="min-width:350px">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Kendala utama pasokan ikan saat ini</td>
                            <td>
                                <textarea name="kendala_pasokan" rows="2" class="form-control" placeholder="Jelaskan kendala...">{{ old('kendala_pasokan', is_array($sppg->kendala_pasokan) ? implode("\n", $sppg->kendala_pasokan) : $sppg->kendala_pasokan) }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>Minat kerjasama dengan KDMP bioflok MBG</td>
                            <td>
                                <div class="flex gap-4">
                                    <label class="form-check">
                                        <input type="radio" name="minat_kerjasama" value="Ya" class="form-check-input" {{ old('minat_kerjasama', $sppg->minat_kerjasama) == 'Ya' ? 'checked' : '' }}>
                                        <span class="form-check-label">Ya</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="radio" name="minat_kerjasama" value="Tidak" class="form-check-input" {{ old('minat_kerjasama', $sppg->minat_kerjasama) == 'Tidak' ? 'checked' : '' }}>
                                        <span class="form-check-label">Tidak</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Sarana untuk proyek bioflok (volume, jenis)</td>
                            <td>
                                <textarea name="sarana_bioflok" rows="2" class="form-control" placeholder="Jelaskan sarana yang tersedia...">{{ old('sarana_bioflok', $sppg->sarana_bioflok) }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">4</td>
                            <td>Kontrak pengadaan & target kontrak 2026</td>
                            <td>
                                <textarea name="kontrak_pengadaan" rows="2" class="form-control" placeholder="Jelaskan kontrak dan target...">{{ old('kontrak_pengadaan', $sppg->kontrak_pengadaan) }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="form-actions">
        <a href="{{ route('sppg.show', $sppg) }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;margin-right:6px;display:inline-block;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Simpan Perubahan
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
