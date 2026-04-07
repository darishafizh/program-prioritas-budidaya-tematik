@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Kuesioner KDMP</h1>
        <p class="page-subtitle">Kolam Demonstrasi Mina Pedesaan - Bioflok</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'KDMP', 'url' => route('kdmp.index')],
        ['label' => 'Tambah', 'url' => '#']
    ]" />
</div>

<form action="{{ route('kdmp.store') }}" method="POST">
    @csrf

    {{-- ===== PEMILIHAN KDKMP DARI DATA MASTER ===== --}}
    <div class="section-card mb-4" style="border:2px solid #0891B2; background: linear-gradient(135deg, rgba(8,145,178,0.04), rgba(6,182,212,0.06));">
        <div class="section-header">
            <div class="section-icon teal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="section-title">Pilih Lokasi KDKMP <span style="font-size:0.78rem;font-weight:400;color:#0891B2;">(data otomatis terisi)</span></h3>
        </div>
        <div class="section-body">
            <div class="form-group" style="max-width: 600px;">
                <label class="form-label">Pilih KDKMP dari Data Master</label>
                <select name="kdmp_id" id="kdmp_id_selector" class="form-control form-select">
                    <option value="">-- Pilih nama KDKMP --</option>
                    @foreach($kdmpList as $kdmp)
                    <option value="{{ $kdmp->id }}"
                        data-nama="{{ $kdmp->nama_kdkmp }}"
                        data-desa="{{ $kdmp->desa }}"
                        data-kabupaten="{{ $kdmp->kabupaten }}"
                        data-provinsi="{{ $kdmp->provinsi }}"
                        data-komoditas="{{ $kdmp->komoditas }}"
                        data-lat="{{ $kdmp->lat }}"
                        data-long="{{ $kdmp->long }}">
                        [{{ $kdmp->no }}] {{ $kdmp->nama_kdkmp }} — {{ $kdmp->kabupaten }}, {{ $kdmp->provinsi }}
                    </option>
                    @endforeach
                </select>
                <p class="text-muted text-sm mt-1">Memilih KDKMP akan otomatis mengisi Nama Koperasi, Lokasi, Komoditas, dan Koordinat di bawah.</p>
            </div>
        </div>
    </div>

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
                    <input type="text" name="verifikator" class="form-control" placeholder="Nama verifikator">
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat</label>
                    <input type="text" name="tempat" class="form-control" placeholder="Tempat wawancara">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Jam</label>
                    <input type="time" name="jam" class="form-control">
                </div>
            </div>

            <div class="border-t mt-4 pt-4">
                <label class="form-label font-semibold mb-3">Data Responden</label>
                <div class="grid grid-cols-4">
                    <div class="form-group">
                        <label class="form-label">Nama Responden</label>
                        <input type="text" name="responden" class="form-control" placeholder="Nama responden">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control form-select">
                            <option value="">Pilih</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Umur (tahun)</label>
                        <input type="number" name="umur" min="1" class="form-control" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan" class="form-control form-select">
                            <option value="">Pilih</option>
                            @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan/Jabatan</label>
                        <input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Alamat sesuai KTP</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat lengkap">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Identitas Koperasi KDMP -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon teal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="section-title">Identitas Koperasi KDMP</h3>
        </div>
        <div class="section-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Data</th>
                            <th style="min-width:350px">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Nama Koperasi KDMP<br><small class="text-muted">Nomor Badan Hukum</small></td>
                            <td>
                                <input type="text" name="nama_koperasi" class="form-control mb-2" placeholder="Nama Koperasi">
                                <input type="text" name="nomor_badan_hukum" class="form-control" placeholder="Nomor Badan Hukum">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>Lokasi<br><small class="text-muted">(Desa/Kelurahan, Kecamatan, Kab/Kota, Provinsi)</small></td>
                            <td>
                                <div class="grid grid-cols-2" style="gap:0.5rem;">
                                    <input type="text" name="desa" class="form-control" placeholder="Desa/Kelurahan">
                                    <input type="text" name="kecamatan" class="form-control" placeholder="Kecamatan">
                                    <input type="text" name="kabupaten" class="form-control" placeholder="Kabupaten/Kota">
                                    <input type="text" name="provinsi" class="form-control" placeholder="Provinsi">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Luas lahan per paket bioflok<br><small class="text-muted">(minimal 858 m²/paket)</small></td>
                            <td>
                                <div class="flex" style="align-items:center; gap:0.5rem;">
                                    <input type="number" name="luas_lahan" min="0" class="form-control" placeholder="0">
                                    <span>m²</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">4</td>
                            <td>Jumlah paket bantuan</td>
                            <td>
                                <div class="flex" style="align-items:center; gap:0.5rem;">
                                    <input type="number" name="jumlah_paket" min="0" class="form-control" placeholder="0">
                                    <span>paket</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">5</td>
                            <td>Komoditas</td>
                            <td>
                                <div class="flex gap-4">
                                    <label class="form-check">
                                        <input type="checkbox" name="komoditas[]" value="Ikan Lele" class="form-check-input">
                                        <span class="form-check-label">Ikan Lele</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="komoditas[]" value="Ikan Nila" class="form-check-input">
                                        <span class="form-check-label">Ikan Nila</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">6</td>
                            <td>Lokasi (koordinat)</td>
                            <td>
                                <input type="text" name="koordinat" class="form-control" placeholder="-6.123, 106.456">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bagian A: Administrasi -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon navy">A</div>
            <h3 class="section-title">Administrasi</h3>
        </div>
        <div class="section-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Kriteria (dari Formulir 6, Lampiran Verifikasi)</th>
                            <th style="width:130px">Ya/Tidak</th>
                            <th style="min-width:250px">Keterangan/Kondisi Existing</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Badan hukum KDMP dengan KBLI 03221 Pembesaran Ikan Air Tawar & Anggota Budidaya Ikan Aktif</td>
                            <td>
                                <select name="krit_badan_hukum_kbli" class="form-control form-select">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="kbli_number" class="form-control mb-1" placeholder="No. Badan Hukum">
                                <input type="text" name="kbli_keterangan" class="form-control" placeholder="KBLI:">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>e-Kusuka terdaftar di satudata.kkp.go.id</td>
                            <td>
                                <select name="krit_ekusuka" class="form-control form-select">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </td>
                            <td><input type="text" name="krit_ekusuka_ket" class="form-control" placeholder="Keterangan..."></td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Salah satu pengurus/anggota aktif JKN</td>
                            <td>
                                <select name="krit_jkn_aktif" class="form-control form-select">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </td>
                            <td><input type="text" name="krit_jkn_ket" class="form-control" placeholder="Keterangan..."></td>
                        </tr>
                        <tr>
                            <td class="text-center">4</td>
                            <td>Permohonan, proposal & rencana usaha disampaikan</td>
                            <td>
                                <select name="krit_proposal" class="form-control form-select">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </td>
                            <td><input type="text" name="krit_proposal_ket" class="form-control" placeholder="Keterangan..."></td>
                        </tr>
                        <tr>
                            <td class="text-center">5</td>
                            <td>Pernyataan Kesanggupan operasional & perawatan</td>
                            <td>
                                <select name="krit_kesanggupan" class="form-control form-select">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </td>
                            <td><input type="text" name="krit_kesanggupan_ket" class="form-control" placeholder="Keterangan..."></td>
                        </tr>
                        <tr>
                            <td class="text-center">6</td>
                            <td>Belum pernah menerima bantuan serupa dari KKP selama satu tahun</td>
                            <td>
                                <select name="krit_belum_bantuan" class="form-control form-select">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </td>
                            <td><input type="text" name="krit_belum_bantuan_ket" class="form-control" placeholder="Keterangan..."></td>
                        </tr>
                        <tr>
                            <td class="text-center">7</td>
                            <td>Sudah mendapatkan pelatihan teknis budidaya ikan air tawar</td>
                            <td>
                                <select name="krit_pelatihan" class="form-control form-select">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </td>
                            <td>
                                <div class="flex" style="align-items:center; gap:0.5rem;">
                                    <span>Berapa lama?</span>
                                    <input type="number" name="pelatihan_hari" min="0" class="form-control" style="width:80px" placeholder="0">
                                    <span>hari</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">8</td>
                            <td>Apakah koperasi mengelola pakan, pemasaran dan pencatatan produksi anggota bersama?</td>
                            <td>
                                <select name="krit_kelola_koperasi" class="form-control form-select">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </td>
                            <td><input type="text" name="krit_kelola_ket" class="form-control" placeholder="Keterangan..."></td>
                        </tr>
                        <tr>
                            <td class="text-center">9</td>
                            <td>Hambatan utama koperasi saat ini</td>
                            <td colspan="2">
                                <div class="flex gap-3 flex-wrap">
                                    @foreach(['SDM', 'Modal', 'Kepercayaan anggota', 'Pasar', 'Tata kelola'] as $hambatan)
                                    <label class="form-check">
                                        <input type="checkbox" name="hambatan_koperasi[]" value="{{ $hambatan }}" class="form-check-input">
                                        <span class="form-check-label">{{ $hambatan }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Catatan & Rekomendasi -->
            <div class="grid grid-cols-2 mt-4">
                <div class="form-group">
                    <label class="form-label font-semibold">Catatan:</label>
                    <textarea name="catatan_a" rows="3" class="form-control" placeholder="Catatan..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label font-semibold">Rekomendasi:</label>
                    <textarea name="rekomendasi_a" rows="3" class="form-control" placeholder="Rekomendasi..."></textarea>
                </div>
            </div>
        </div>
    </div>

    @include('kdmp._form_part2')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selector = document.getElementById('kdmp_id_selector');
    if (!selector) return;

    selector.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        if (!selected.value) return;

        const nama      = selected.getAttribute('data-nama') || '';
        const desa      = selected.getAttribute('data-desa') || '';
        const kabupaten = selected.getAttribute('data-kabupaten') || '';
        const provinsi  = selected.getAttribute('data-provinsi') || '';
        const komoditas = selected.getAttribute('data-komoditas') || '';
        const lat       = selected.getAttribute('data-lat') || '';
        const long_     = selected.getAttribute('data-long') || '';

        // Auto-fill form fields
        const setVal = (name, value) => {
            const el = document.querySelector(`[name="${name}"]`);
            if (el) el.value = value;
        };

        setVal('nama_koperasi', nama);
        setVal('desa',          desa);
        setVal('kabupaten',     kabupaten);
        setVal('provinsi',      provinsi);
        setVal('komoditas',     komoditas);

        if (lat && long_) {
            setVal('koordinat', lat + ', ' + long_);
        }
    });
});
</script>
@endpush
