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
        <div class="section-card mb-4"
            style="border:2px solid #0891B2; background: linear-gradient(135deg, rgba(8,145,178,0.04), rgba(6,182,212,0.06));">
            <div class="section-header">
                <div class="section-icon teal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="section-title">Pilih Lokasi KDKMP <span
                        style="font-size:0.78rem;font-weight:400;color:#0891B2;">(data otomatis terisi)</span></h3>
            </div>
            <div class="section-body">
                <div class="form-group" style="max-width: 600px;">
                    <label class="form-label">Pilih KDKMP dari Data Master</label>
                    <select name="kdmp_id" id="kdmp_id_selector" class="form-control form-select">
                        <option value="">-- Pilih nama KDKMP --</option>
                        @foreach($kdmpList as $kdmp)
                            <option value="{{ $kdmp->id }}" data-nama="{{ $kdmp->nama_kdkmp }}" data-desa="{{ $kdmp->desa }}"
                                data-kabupaten="{{ $kdmp->kabupaten }}" data-provinsi="{{ $kdmp->provinsi }}"
                                data-komoditas="{{ $kdmp->komoditas }}" data-lat="{{ $kdmp->lat }}"
                                data-long="{{ $kdmp->long }}">
                                [{{ $kdmp->no }}] {{ $kdmp->nama_kdkmp }} — {{ $kdmp->kabupaten }}, {{ $kdmp->provinsi }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-muted text-sm mt-1">Memilih KDKMP akan otomatis mengisi Nama Koperasi, Lokasi, Komoditas,
                        dan Koordinat di bawah.</p>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-icon success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
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
                                    <input type="text" name="nama_koperasi" class="form-control mb-2"
                                        placeholder="Nama Koperasi">
                                    <input type="text" name="nomor_badan_hukum" class="form-control"
                                        placeholder="Nomor Badan Hukum">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td>Lokasi<br><small class="text-muted">(Desa/Kelurahan, Kecamatan, Kab/Kota,
                                        Provinsi)</small></td>
                                <td>
                                    <div class="grid grid-cols-2" style="gap:0.5rem;" x-data="wilayahApi()">
                                        <select name="provinsi" x-model="selectedProvinsi" @change="fetchKabupaten"
                                            class="form-control form-select form-select-wilayah">
                                            <option value="">-- Pilih Provinsi --</option>
                                            <template x-for="prov in listProvinsi" :key="prov.id">
                                                <option :value="prov.nama" x-text="prov.nama" :data-id="prov.id"></option>
                                            </template>
                                        </select>

                                        <select name="kabupaten" x-model="selectedKabupaten" @change="fetchKecamatan"
                                            class="form-control form-select form-select-wilayah"
                                            :disabled="listKabupaten.length === 0">
                                            <option value="">-- Pilih Kab/Kota --</option>
                                            <template x-for="kab in listKabupaten" :key="kab.id">
                                                <option :value="kab.nama" x-text="kab.nama" :data-id="kab.id"></option>
                                            </template>
                                        </select>

                                        <select name="kecamatan" x-model="selectedKecamatan" @change="fetchDesa"
                                            class="form-control form-select form-select-wilayah"
                                            :disabled="listKecamatan.length === 0">
                                            <option value="">-- Pilih Kecamatan --</option>
                                            <template x-for="kec in listKecamatan" :key="kec.id">
                                                <option :value="kec.nama" x-text="kec.nama" :data-id="kec.id"></option>
                                            </template>
                                        </select>

                                        <select name="desa" x-model="selectedDesa"
                                            class="form-control form-select form-select-wilayah"
                                            :disabled="listDesa.length === 0">
                                            <option value="">-- Pilih Desa/Kel --</option>
                                            <template x-for="desa in listDesa" :key="desa.id">
                                                <option :value="desa.nama" x-text="desa.nama" :data-id="desa.id"></option>
                                            </template>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td>Luas lahan per paket bioflok<br><small class="text-muted">(minimal 858 m²/paket)</small>
                                </td>
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
                                        <input type="number" name="jumlah_paket" min="0" class="form-control"
                                            placeholder="0">
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
                                            <input type="checkbox" name="komoditas[]" value="Ikan Lele"
                                                class="form-check-input">
                                            <span class="form-check-label">Ikan Lele</span>
                                        </label>
                                        <label class="form-check">
                                            <input type="checkbox" name="komoditas[]" value="Ikan Nila"
                                                class="form-check-input">
                                            <span class="form-check-label">Ikan Nila</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">6</td>
                                <td>Lokasi (koordinat)</td>
                                <td>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <input type="text" name="lat" id="input-lat" class="form-control" placeholder="Lat (-6.123)">
                                        </div>
                                        <div>
                                            <input type="text" name="long" id="input-long" class="form-control"
                                                placeholder="Long (106.456)">
                                        </div>
                                    </div>
                                    <button type="button" id="btn-get-location" onclick="getCurrentLocation()" class="btn-get-location mt-2">
                                        <svg id="loc-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <svg id="loc-spinner" style="display:none;" class="animate-spin" fill="none" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity:0.25;"></circle>
                                            <path fill="currentColor" style="opacity:0.75;" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                        <span id="loc-text">Ambil Lokasi Saat Ini</span>
                                    </button>
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
                                <td>Badan hukum KDMP dengan KBLI 03221 Pembesaran Ikan Air Tawar & Anggota Budidaya Ikan
                                    Aktif</td>
                                <td>
                                    <select name="krit_badan_hukum_kbli" class="form-control form-select">
                                        <option value="">Pilih</option>
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="kbli_number" class="form-control mb-1"
                                        placeholder="No. Badan Hukum">
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
                                <td><input type="text" name="krit_ekusuka_ket" class="form-control"
                                        placeholder="Keterangan..."></td>
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
                                <td><input type="text" name="krit_jkn_ket" class="form-control" placeholder="Keterangan...">
                                </td>
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
                                <td><input type="text" name="krit_proposal_ket" class="form-control"
                                        placeholder="Keterangan..."></td>
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
                                <td><input type="text" name="krit_kesanggupan_ket" class="form-control"
                                        placeholder="Keterangan..."></td>
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
                                <td><input type="text" name="krit_belum_bantuan_ket" class="form-control"
                                        placeholder="Keterangan..."></td>
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
                                        <input type="number" name="pelatihan_hari" min="0" class="form-control"
                                            style="width:80px" placeholder="0">
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
                                <td><input type="text" name="krit_kelola_ket" class="form-control"
                                        placeholder="Keterangan..."></td>
                            </tr>
                            <tr>
                                <td class="text-center">9</td>
                                <td>Hambatan utama koperasi saat ini</td>
                                <td colspan="2">
                                    <div class="flex gap-3 flex-wrap">
                                        @foreach(['SDM', 'Modal', 'Kepercayaan anggota', 'Pasar', 'Tata kelola'] as $hambatan)
                                            <label class="form-check">
                                                <input type="checkbox" name="hambatan_koperasi[]" value="{{ $hambatan }}"
                                                    class="form-check-input">
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
                        <textarea name="rekomendasi_a" rows="3" class="form-control"
                            placeholder="Rekomendasi..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        @include('kdmp._form_part2')
    </form>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Geolocation button */
        .btn-get-location {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #0891B2;
            background: rgba(8, 145, 178, 0.08);
            border: 1.5px dashed #0891B2;
            border-radius: var(--radius-md, 8px);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-get-location:hover {
            background: rgba(8, 145, 178, 0.15);
            border-style: solid;
            transform: translateY(-1px);
        }
        .btn-get-location:active {
            transform: translateY(0);
        }
        .btn-get-location.loading {
            opacity: 0.7;
            pointer-events: none;
        }
        .btn-get-location svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        [data-theme="dark"] .btn-get-location {
            color: #22D3EE;
            background: rgba(34, 211, 238, 0.08);
            border-color: #22D3EE;
        }
        [data-theme="dark"] .btn-get-location:hover {
            background: rgba(34, 211, 238, 0.15);
        }

        /* Select2 theme override to match app design */
        .select2-container--default .select2-selection--single {
            height: 44px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background: var(--bg-surface);
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px;
            color: var(--gray-700);
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        .select2-dropdown {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 8px 12px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0891B2;
        }

        .select2-results__option {
            padding: 8px 12px;
            font-size: 0.875rem;
        }

        /* Dark mode support */
        [data-theme="dark"] .select2-container--default .select2-selection--single {
            background: var(--bg-surface);
            border-color: #374151;
        }

        [data-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #E5E7EB;
        }

        [data-theme="dark"] .select2-dropdown {
            background: var(--bg-surface);
            border-color: #374151;
        }

        [data-theme="dark"] .select2-container--default .select2-search--dropdown .select2-search__field {
            background: #1F2937;
            border-color: #374151;
            color: #E5E7EB;
        }

        [data-theme="dark"] .select2-results__option {
            color: #E5E7EB;
        }

        [data-theme="dark"] .select2-container--default .select2-results__option[aria-selected=true] {
            background: #374151;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2 on the KDKMP selector
            $('#kdmp_id_selector').select2({
                placeholder: '-- Cari dan pilih nama KDKMP --',
                allowClear: true,
                width: '100%'
            });

            // Auto-fill fields when KDKMP is selected
            $('#kdmp_id_selector').on('change', function () {
                var selected = $(this).find(':selected');
                if (!selected.val()) return;

                var fields = {
                    'nama_koperasi': selected.data('nama') || '',
                    'komoditas': selected.data('komoditas') || ''
                };

                $.each(fields, function (name, value) {
                    $('[name="' + name + '"]').val(value);
                });

                var lat = selected.data('lat') || '';
                var long = selected.data('long') || '';

                $('[name="lat"]').val(lat);
                $('[name="long"]').val(long);

                // Trigger wilayah autofill via custom event to Alpine
                window.dispatchEvent(new CustomEvent('autofill-wilayah', {
                    detail: {
                        provinsi: selected.data('provinsi') || '',
                        kabupaten: selected.data('kabupaten') || '',
                        desa: selected.data('desa') || ''
                    }
                }));
            });
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('wilayahApi', () => ({
                baseUrl: 'https://ibnux.github.io/data-indonesia',

                listProvinsi: [],
                listKabupaten: [],
                listKecamatan: [],
                listDesa: [],

                selectedProvinsi: '',
                selectedKabupaten: '',
                selectedKecamatan: '',
                selectedDesa: '',

                init() {
                    // Load Provinsi on init
                    fetch(`${this.baseUrl}/provinsi.json`)
                        .then(res => res.json())
                        .then(data => {
                            this.listProvinsi = data.sort((a, b) => a.nama.localeCompare(b.nama));
                            this.updateSelect2();
                        })
                        .catch(err => console.error("Error fetching provinsi:", err));

                    // Listen to jQuery autofill
                    window.addEventListener('autofill-wilayah', (e) => this.handleAutofill(e.detail));
                },

                async handleAutofill(detail) {
                    if (!detail.provinsi) return;

                    // Wait for listProvinsi to load if empty
                    if (this.listProvinsi.length === 0) {
                        try {
                            let res = await fetch(`${this.baseUrl}/provinsi.json`);
                            this.listProvinsi = (await res.json()).sort((a, b) => a.nama.localeCompare(b.nama));
                            this.updateSelect2();
                        } catch (e) { return; }
                    }

                    // 1. MATCH PROVINSI
                    let provMatch = this.findBestMatch(this.listProvinsi, detail.provinsi);
                    if (provMatch) {
                        this.selectedProvinsi = provMatch.nama;

                        await this.fetchKabupaten(provMatch.id);

                        // 2. MATCH KABUPATEN
                        let kabName = detail.kabupaten;
                        let kabMatch = this.findBestMatch(this.listKabupaten, kabName);
                        if (kabMatch) {
                            this.selectedKabupaten = kabMatch.nama;
                            await this.fetchKecamatan(kabMatch.id);
                        }
                    }
                },

                findBestMatch(list, queryStr) {
                    if (!queryStr) return null;
                    let q = queryStr.toUpperCase().replace(/(KABUPATEN|KAB\.|KOTA)\s+/i, '').trim();
                    for (let item of list) {
                        let nameStr = item.nama.toUpperCase().replace(/(KABUPATEN|KAB\.|KOTA)\s+/i, '').trim();
                        if (nameStr === q || nameStr.includes(q) || q.includes(nameStr)) {
                            return item;
                        }
                    }
                    return null;
                },

                getProvinsiId() {
                    const p = this.listProvinsi.find(x => x.nama === this.selectedProvinsi);
                    return p ? p.id : null;
                },

                getKabupatenId() {
                    const k = this.listKabupaten.find(x => x.nama === this.selectedKabupaten);
                    return k ? k.id : null;
                },

                getKecamatanId() {
                    const kec = this.listKecamatan.find(x => x.nama === this.selectedKecamatan);
                    return kec ? kec.id : null;
                },

                async fetchKabupaten(overrideProvId = null) {
                    let pid = overrideProvId;
                    if (typeof overrideProvId !== 'string' && typeof overrideProvId !== 'number') {
                        pid = this.getProvinsiId();
                        this.selectedKabupaten = '';
                        this.selectedKecamatan = '';
                        this.selectedDesa = '';
                        this.listKabupaten = [];
                        this.listKecamatan = [];
                        this.listDesa = [];
                    }
                    if (!pid) return;

                    return fetch(`${this.baseUrl}/kabupaten/${pid}.json`)
                        .then(res => res.json())
                        .then(data => {
                            this.listKabupaten = data.sort((a, b) => a.nama.localeCompare(b.nama));
                            this.updateSelect2();
                        })
                        .catch(err => console.error(err));
                },

                async fetchKecamatan(overrideKabId = null) {
                    let kid = overrideKabId;
                    if (typeof overrideKabId !== 'string' && typeof overrideKabId !== 'number') {
                        kid = this.getKabupatenId();
                        this.selectedKecamatan = '';
                        this.selectedDesa = '';
                        this.listKecamatan = [];
                        this.listDesa = [];
                    }
                    if (!kid) return;

                    return fetch(`${this.baseUrl}/kecamatan/${kid}.json`)
                        .then(res => res.json())
                        .then(data => {
                            this.listKecamatan = data.sort((a, b) => a.nama.localeCompare(b.nama));
                            this.updateSelect2();
                        })
                        .catch(err => console.error(err));
                },

                async fetchDesa() {
                    let kecId = this.getKecamatanId();
                    this.selectedDesa = '';
                    this.listDesa = [];
                    if (!kecId) return;

                    return fetch(`${this.baseUrl}/kelurahan/${kecId}.json`)
                        .then(res => res.json())
                        .then(data => {
                            this.listDesa = data.sort((a, b) => a.nama.localeCompare(b.nama));
                            this.updateSelect2();
                        })
                        .catch(err => console.error(err));
                },

                updateSelect2() {
                    this.$nextTick(() => {
                        $('.form-select-wilayah').each(function () {
                            // Check if initialized
                            if ($(this).hasClass("select2-hidden-accessible")) {
                                $(this).select2('destroy');
                            }
                            $(this).select2({ width: '100%' })
                                .off('select2:select')
                                .on('select2:select', function (e) {
                                    this.dispatchEvent(new Event('change', { bubbles: true }));
                                });
                        });
                    });
                }
            }));
        });

        // ──── Geolocation: Ambil Lokasi Saat Ini ────
        function getCurrentLocation() {
            const btn = document.getElementById('btn-get-location');
            const icon = document.getElementById('loc-icon');
            const spinner = document.getElementById('loc-spinner');
            const text = document.getElementById('loc-text');
            const latInput = document.getElementById('input-lat') || document.querySelector('[name="lat"]');
            const longInput = document.getElementById('input-long') || document.querySelector('[name="long"]');

            if (!navigator.geolocation) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Didukung',
                    text: 'Browser Anda tidak mendukung fitur Geolocation.',
                    confirmButtonColor: '#0891B2'
                });
                return;
            }

            // Set loading state
            btn.classList.add('loading');
            icon.style.display = 'none';
            spinner.style.display = 'inline';
            text.textContent = 'Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);

                    latInput.value = lat;
                    longInput.value = lng;

                    // Reset button
                    btn.classList.remove('loading');
                    icon.style.display = 'inline';
                    spinner.style.display = 'none';
                    text.textContent = 'Ambil Lokasi Saat Ini';

                    Swal.fire({
                        icon: 'success',
                        title: 'Lokasi Ditemukan',
                        html: `<b>Latitude:</b> ${lat}<br><b>Longitude:</b> ${lng}`,
                        confirmButtonColor: '#0891B2',
                        timer: 3000,
                        timerProgressBar: true
                    });
                },
                function(error) {
                    // Reset button
                    btn.classList.remove('loading');
                    icon.style.display = 'inline';
                    spinner.style.display = 'none';
                    text.textContent = 'Ambil Lokasi Saat Ini';

                    let msg = 'Gagal mengambil lokasi.';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            msg = 'Izin lokasi ditolak. Silakan aktifkan izin lokasi di pengaturan browser Anda.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            msg = 'Informasi lokasi tidak tersedia. Pastikan GPS aktif.';
                            break;
                        case error.TIMEOUT:
                            msg = 'Waktu permintaan lokasi habis. Silakan coba lagi.';
                            break;
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal Mengambil Lokasi',
                        text: msg,
                        confirmButtonColor: '#0891B2'
                    });
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }
    </script>
@endpush