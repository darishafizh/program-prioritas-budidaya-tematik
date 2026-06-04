@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Edit Kuesioner Masyarakat</h1>
        <p class="page-subtitle">Survei persepsi masyarakat terhadap pembangunan budi daya tematik bioflok</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'Masyarakat', 'url' => route('masyarakat.index')],
        ['label' => 'Edit', 'url' => '#']
    ]" />
</div>

<form action="{{ route('masyarakat.update', $masyarakat) }}" method="POST">
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
                    <input type="text" name="verifikator" class="form-control" placeholder="Nama verifikator" value="{{ old('verifikator', $masyarakat->verifikator) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat</label>
                    <input type="text" name="tempat" class="form-control" placeholder="Tempat wawancara" value="{{ old('tempat', $masyarakat->tempat) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $masyarakat->tanggal) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jam</label>
                    <input type="time" name="jam" class="form-control" value="{{ old('jam', $masyarakat->jam) }}">
                </div>
            </div>

            <div class="border-t mt-4 pt-4">
                <label class="form-label font-semibold mb-3">Data Responden</label>
                <div class="grid grid-cols-4">
                    <div class="form-group">
                        <label class="form-label">Nama Responden</label>
                        <input type="text" name="responden" class="form-control" placeholder="Nama responden" value="{{ old('responden', $masyarakat->responden) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control form-select">
                            <option value="">Pilih</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $masyarakat->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $masyarakat->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Umur (tahun)</label>
                        <input type="number" name="umur" min="1" class="form-control" placeholder="0" value="{{ old('umur', $masyarakat->umur) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan" class="form-control form-select">
                            <option value="">Pilih</option>
                            @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $p)
                            <option value="{{ $p }}" {{ old('pendidikan', $masyarakat->pendidikan) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan/Jabatan</label>
                        <input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan" value="{{ old('pekerjaan', $masyarakat->pekerjaan) }}">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Alamat sesuai KTP</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat lengkap" value="{{ old('alamat', $masyarakat->alamat) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Koordinat GPS</label>
                        <input type="text" name="koordinat" class="form-control" placeholder="-6.123, 106.456" value="{{ old('koordinat', $masyarakat->koordinat) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian A: Tanggapan Masyarakat -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon teal">A</div>
            <h3 class="section-title">Tanggapan Masyarakat Terkait Pembangunan Budi Daya Tematik Bioflok</h3>
        </div>
        <div class="section-body">
            <p class="text-sm text-muted mb-4">(3 Responden)</p>
            
            <!-- Question 1 -->
            <div class="form-group">
                <label class="form-label font-semibold">1. Apakah item rencana pembangunan telah sesuai dengan kebutuhan masyarakat?</label>
                <div class="flex gap-4 mt-2">
                    <label class="form-check">
                        <input type="radio" name="sesuai_kebutuhan" value="Ya, sesuai" class="form-check-input" {{ old('sesuai_kebutuhan', $masyarakat->sesuai_kebutuhan) == 'Ya, sesuai' ? 'checked' : '' }}>
                        <span class="form-check-label">a. Ya, sesuai</span>
                    </label>
                    <label class="form-check">
                        <input type="radio" name="sesuai_kebutuhan" value="Tidak sesuai" class="form-check-input" {{ old('sesuai_kebutuhan', $masyarakat->sesuai_kebutuhan) == 'Tidak sesuai' ? 'checked' : '' }}>
                        <span class="form-check-label">b. Tidak sesuai</span>
                    </label>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="form-group">
                <label class="form-label font-semibold">2. Apabila tidak sesuai, item apa yang menurut Anda tidak sesuai dengan kebutuhan masyarakat?</label>
                <textarea name="item_tidak_sesuai" rows="2" class="form-control" placeholder="Sebutkan item yang tidak sesuai...">{{ old('item_tidak_sesuai', $masyarakat->item_tidak_sesuai) }}</textarea>
            </div>

            <!-- Question 3 -->
            <div class="form-group">
                <label class="form-label font-semibold">3. Apakah anda senang terhadap rencana Pembangunan Budi daya Bioflok?</label>
                <div class="flex gap-4 mt-2">
                    <label class="form-check">
                        <input type="radio" name="perasaan" value="Senang" class="form-check-input" {{ old('perasaan', $masyarakat->perasaan) == 'Senang' ? 'checked' : '' }}>
                        <span class="form-check-label">a. Senang</span>
                    </label>
                    <label class="form-check">
                        <input type="radio" name="perasaan" value="Biasa saja" class="form-check-input" {{ old('perasaan', $masyarakat->perasaan) == 'Biasa saja' ? 'checked' : '' }}>
                        <span class="form-check-label">b. Biasa saja</span>
                    </label>
                    <label class="form-check">
                        <input type="radio" name="perasaan" value="Tidak Senang" class="form-check-input" {{ old('perasaan', $masyarakat->perasaan) == 'Tidak Senang' ? 'checked' : '' }}>
                        <span class="form-check-label">c. Tidak Senang</span>
                    </label>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="form-group">
                <label class="form-label font-semibold">4. Apabila anda senang/biasa saja/tidak senang dengan rencana Pembangunan Budi daya Bioflok tersebut, apa alasan anda?</label>
                <textarea name="alasan" rows="2" class="form-control" placeholder="Jelaskan alasan...">{{ old('alasan', $masyarakat->alasan) }}</textarea>
            </div>

            <!-- Question 5 -->
            <div class="form-group">
                <label class="form-label font-semibold">5. Harapan masyarakat setelah pembangunan Pembangunan Budi daya Bioflok?</label>
                <textarea name="harapan" rows="2" class="form-control" placeholder="Tuliskan harapan...">{{ old('harapan', $masyarakat->harapan) }}</textarea>
            </div>

            <!-- Question 6 -->
            <div class="form-group">
                <label class="form-label font-semibold">6. Masukan/saran perbaikan dari masyarakat terhadap Pembangunan Budi daya Bioflok?</label>
                <textarea name="saran" rows="2" class="form-control" placeholder="Tuliskan saran...">{{ old('saran', $masyarakat->saran) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Bagian B: Tingkat Kebahagiaan Masyarakat -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon navy">B</div>
            <h3 class="section-title">Tingkat Kebahagiaan Masyarakat</h3>
        </div>
        <div class="section-body">
            <div class="alert alert-info mb-4" style="background:#E0F2FE; border:1px solid #0EA5E9; color:#0369A1; padding:1rem; border-radius:8px;">
                <strong>Petunjuk Pengisian:</strong><br>
                1) Kuesioner ini mengukur kebahagiaan masyarakat terkait Pembangunan Budi daya Bioflok<br>
                2) Pilih jawaban untuk setiap pernyataan: a= Sangat Tidak Setuju, b= Tidak Setuju, c= Netral, d= Setuju, e= Sangat Setuju<br>
                3) Periode rujukan: 1 bulan terakhir untuk Kepuasan Hidup & Makna Hidup; 2 minggu terakhir untuk Perasaan<br>
                4) Tidak ada jawaban benar/salah. Jawablah sesuai kondisi Anda
            </div>
            
            <!-- A. RASA AMAN & KENYAMANAN LINGKUNGAN -->
            <div class="mb-5">
                <div class="subsection-header">
                    <span class="subsection-icon">A</span>
                    <span class="subsection-title">Rasa Aman & Kenyamanan Lingkungan</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Pernyataan</th>
                                <th style="width:400px">Pilihan Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $likertA = [
                                1 => 'Saya merasa aman dengan adanya kegiatan pembangunan kolam bioflok di lingkungan saya',
                                2 => 'Pekerjaan fisik kolam bioflok tidak mengganggu aktivitas sehari-hari warga',
                                3 => 'Lingkungan sekitar lokasi pembangunan masih nyaman untuk ditinggali',
                                4 => 'Saya tidak merasa khawatir terhadap potensi dampak lingkungan dari pembangunan kolam bioflok',
                                5 => 'Saya percaya bahwa kegiatan ini dilakukan dengan memperhatikan keselamatan dan ketertiban lingkungan',
                            ];
                            @endphp
                            @foreach($likertA as $num => $question)
                            <tr>
                                <td class="text-center">{{ $num }}</td>
                                <td>{{ $question }}</td>
                                <td>
                                    <div class="likert-inline">
                                        @foreach(['a' => 'Sangat tidak setuju', 'b' => 'Tidak Setuju', 'c' => 'Netral', 'd' => 'Setuju', 'e' => 'Sangat setuju'] as $val => $label)
                                        <label class="likert-radio">
                                            <input type="radio" name="likert_q{{ $num }}" value="{{ $val }}" {{ old('likert_q'.$num, $masyarakat->{'likert_q'.$num}) == $val ? 'checked' : '' }}>
                                            <span>{{ $val }}. {{ $label }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- B. PERSEPSI DAMPAK SOSIAL & EKONOMI AWAL -->
            <div class="mb-5">
                <div class="subsection-header">
                    <span class="subsection-icon">B</span>
                    <span class="subsection-title">Persepsi Dampak Sosial & Ekonomi Awal</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Pernyataan</th>
                                <th style="width:400px">Pilihan Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $likertB = [
                                6 => 'Pembangunan kolam bioflok berpotensi memberikan manfaat bagi masyarakat sekitar',
                                7 => 'Kegiatan pembangunan ini membuka peluang kerja atau usaha bagi warga sekitar',
                                8 => 'Saya melihat program ini dapat mendorong kegiatan ekonomi lokal di masa depan',
                                9 => 'Keberadaan kolam bioflok tidak menimbulkan konflik sosial di lingkungan saya',
                                10 => 'Secara umum, saya menilai pembangunan kolam bioflok ini berdampak positif bagi desa/kelurahan',
                            ];
                            @endphp
                            @foreach($likertB as $num => $question)
                            <tr>
                                <td class="text-center">{{ $num }}</td>
                                <td>{{ $question }}</td>
                                <td>
                                    <div class="likert-inline">
                                        @foreach(['a' => 'Sangat tidak setuju', 'b' => 'Tidak Setuju', 'c' => 'Netral', 'd' => 'Setuju', 'e' => 'Sangat setuju'] as $val => $label)
                                        <label class="likert-radio">
                                            <input type="radio" name="likert_q{{ $num }}" value="{{ $val }}" {{ old('likert_q'.$num, $masyarakat->{'likert_q'.$num}) == $val ? 'checked' : '' }}>
                                            <span>{{ $val }}. {{ $label }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- C. PENERIMAAN SOSIAL & HARAPAN -->
            <div class="mb-3">
                <div class="subsection-header">
                    <span class="subsection-icon">C</span>
                    <span class="subsection-title">Penerimaan Sosial & Harapan</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Pernyataan</th>
                                <th style="width:400px">Pilihan Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $likertC = [
                                11 => 'Saya mendukung pembangunan kolam bioflok di wilayah saya',
                                12 => 'Saya berharap kolam bioflok ini dikelola dengan baik dan berkelanjutan',
                                13 => 'Saya berharap masyarakat sekitar juga dapat merasakan manfaat dari program ini',
                                14 => 'Saya percaya pengelola/koperasi akan bertanggung jawab terhadap dampak lingkungan dan sosial',
                                15 => 'Saya bersedia menjaga hubungan baik dan mendukung keberlanjutan program ini',
                            ];
                            @endphp
                            @foreach($likertC as $num => $question)
                            <tr>
                                <td class="text-center">{{ $num }}</td>
                                <td>{{ $question }}</td>
                                <td>
                                    <div class="likert-inline">
                                        @foreach(['a' => 'Sangat tidak setuju', 'b' => 'Tidak Setuju', 'c' => 'Netral', 'd' => 'Setuju', 'e' => 'Sangat setuju'] as $val => $label)
                                        <label class="likert-radio">
                                            <input type="radio" name="likert_q{{ $num }}" value="{{ $val }}" {{ old('likert_q'.$num, $masyarakat->{'likert_q'.$num}) == $val ? 'checked' : '' }}>
                                            <span>{{ $val }}. {{ $label }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian C: Informasi Pendapatan Rumah Tangga -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon warning">C</div>
            <h3 class="section-title">Informasi Pendapatan Rumah Tangga (Kondisi Eksisting)</h3>
        </div>
        <div class="section-body">
            <!-- Question 1: Pendapatan Table -->
            <div class="form-group">
                <label class="form-label font-semibold mb-3">1. Jelaskan pendapatan rata-rata seluruh anggota rumah tangga anda dalam sebulan (Rp/bulan)</label>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Jenis Pendapatan</th>
                                <th style="min-width:200px">Rata-rata dalam sebulan (Rp/bulan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>Pendapatan Perikanan (Rp/bulan)</td>
                                <td><input type="number" name="pendapatan_ikan" min="0" class="form-control" placeholder="0" value="{{ old('pendapatan_ikan', $masyarakat->pendapatan_ikan) }}"></td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td>Pendapatan di luar Perikanan (Rp/bulan)</td>
                                <td><input type="number" name="pendapatan_lain" min="0" class="form-control" placeholder="0" value="{{ old('pendapatan_lain', $masyarakat->pendapatan_lain) }}"></td>
                            </tr>
                            <tr class="bg-light">
                                <td class="text-center">3</td>
                                <td><strong>Total pendapatan rumah tangga (Rp/bulan)</strong></td>
                                <td><input type="number" name="total_pendapatan" min="0" class="form-control" placeholder="0" value="{{ old('total_pendapatan', $masyarakat->total_pendapatan) }}"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="form-group">
                <label class="form-label font-semibold">2. Berapa persentase kontribusi dari pendapatan sebagai pembudidaya ikan terhadap total pendapatan keluarga?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Kurang dari 50%',
                        'b' => '50-80%',
                        'c' => 'Lebih dari 80%',
                        'd' => '100% (satu-satunya sumber pendapatan)'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="kontribusi" value="{{ $label }}" class="form-check-input" {{ old('kontribusi', $masyarakat->kontribusi) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 3 -->
            <div class="form-group">
                <label class="form-label font-semibold">3. Berapakah jumlah sumber penghasilan yang dimiliki oleh rumah tangga Anda?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => '1 (hanya satu sumber) dari budi daya',
                        'b' => '2 sumber',
                        'c' => '3 sumber',
                        'd' => 'Lebih dari 3 sumber'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="jumlah_sumber" value="{{ $label }}" class="form-check-input" {{ old('jumlah_sumber', $masyarakat->jumlah_sumber) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 4 -->
            <div class="form-group">
                <label class="form-label font-semibold">4. Seberapa besar keluarga Anda bergantung pada penghasilan dari kegiatan perikanan?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Sangat bergantung',
                        'b' => 'Cukup bergantung',
                        'c' => 'Sedikit bergantung',
                        'd' => 'Tidak bergantung'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="ketergantungan" value="{{ $label }}" class="form-check-input" {{ old('ketergantungan', $masyarakat->ketergantungan) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 5 -->
            <div class="form-group">
                <label class="form-label font-semibold">5. Bagaimana Anda menilai tingkat stabilitas pendapatan pembudidaya ikan?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Stabil sepanjang tahun',
                        'b' => 'Cenderung stabil',
                        'c' => 'Tidak stabil',
                        'd' => 'Sangat tidak stabil'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="stabilitas" value="{{ $label }}" class="form-check-input" {{ old('stabilitas', $masyarakat->stabilitas) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 6 -->
            <div class="form-group">
                <label class="form-label font-semibold">6. Menurut Anda, apakah perempuan/istri pembudi daya diikutsertakan dalam kegiatan ekonomi rumah tangga?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Selalu',
                        'b' => 'Sering',
                        'c' => 'Jarang',
                        'd' => 'Tidak pernah'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="peran_perempuan" value="{{ $label }}" class="form-check-input" {{ old('peran_perempuan', $masyarakat->peran_perempuan) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 7 -->
            <div class="form-group">
                <label class="form-label font-semibold">7. Berapa rata-rata kontribusi perempuan dalam pendapatan rumah tangga?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Lebih dari 75%',
                        'b' => '51%–75%',
                        'c' => '25%–50%',
                        'd' => 'Kurang dari 25%',
                        'e' => 'Perempuan tidak dilibatkan dalam kegiatan ekonomi rumah tangga'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="kontribusi_perempuan" value="{{ $label }}" class="form-check-input" {{ old('kontribusi_perempuan', $masyarakat->kontribusi_perempuan) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian D: Sosial dan Kelembagaan -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon success">D</div>
            <h3 class="section-title">Sosial dan Kelembagaan (Kondisi Eksisting)</h3>
        </div>
        <div class="section-body">
            <!-- Question 1 -->
            <div class="form-group">
                <label class="form-label font-semibold">1. Apakah Anda ikut serta sebagai anggota kelompok pembudidaya atau KUB?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Ya, sangat aktif',
                        'b' => 'Ya, tidak aktif',
                        'c' => 'Tidak pernah bergabung',
                        'd' => 'Tidak ada kelompok pembudidaya/KUB di lokasi saya'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="anggota_kub" value="{{ $label }}" class="form-check-input" {{ old('anggota_kub', $masyarakat->anggota_kub) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 2 -->
            <div class="form-group">
                <label class="form-label font-semibold">2. Apakah Anda merasa mendapatkan manfaat dari keanggotaan dalam kelompok pembudidaya atau KUB?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Sangat Setuju',
                        'b' => 'Setuju',
                        'c' => 'Cukup Setuju',
                        'd' => 'Kurang Setuju',
                        'e' => 'Tidak Setuju'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="manfaat_kub" value="{{ $label }}" class="form-check-input" {{ old('manfaat_kub', $masyarakat->manfaat_kub) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 3 -->
            <div class="form-group">
                <label class="form-label font-semibold">3. Apakah Anda ikut serta sebagai anggota koperasi perikanan di wilayah anda?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Ya, sangat aktif',
                        'b' => 'Ya, tidak aktif',
                        'c' => 'Tidak pernah bergabung',
                        'd' => 'Tidak ada koperasi perikanan di lokasi saya'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="anggota_koperasi" value="{{ $label }}" class="form-check-input" {{ old('anggota_koperasi', $masyarakat->anggota_koperasi) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 4 -->
            <div class="form-group">
                <label class="form-label font-semibold">4. Jika anda belum menjadi anggota koperasi, apakah anda tertarik untuk menjadi Anggota Koperasi Perikanan?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Sangat tidak tertarik',
                        'b' => 'Tidak tertarik',
                        'c' => 'Tertarik',
                        'd' => 'Sudah menjadi anggota'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="tertarik_koperasi" value="{{ $label }}" class="form-check-input" {{ old('tertarik_koperasi', $masyarakat->tertarik_koperasi) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 5 -->
            <div class="form-group">
                <label class="form-label font-semibold">5. Apakah Anda merasa mendapatkan manfaat dari keanggotaan dari koperasi perikanan?</label>
                <div class="flex gap-3 flex-wrap mt-2">
                    @foreach([
                        'a' => 'Sangat Setuju',
                        'b' => 'Setuju',
                        'c' => 'Cukup Setuju',
                        'd' => 'Kurang Setuju',
                        'e' => 'Tidak Setuju'
                    ] as $val => $label)
                    <label class="form-check">
                        <input type="radio" name="manfaat_koperasi" value="{{ $label }}" class="form-check-input" {{ old('manfaat_koperasi', $masyarakat->manfaat_koperasi) == $label ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $val }}. {{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Question 6: Table -->
            <div class="form-group border-t mt-4 pt-4">
                <label class="form-label font-semibold mb-3">6. Bagaimanakah pendapat anda secara umum tentang koperasi perikanan yang terdapat di wilayah Anda?</label>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Aktivitas</th>
                                <th style="width:200px">Pilihan Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $koperasiQuestions = [
                                ['name' => 'kop_rapat_tahunan', 'label' => 'Apakah koperasi Rutin melakukan rapat anggota tahunan'],
                                ['name' => 'kop_aktif_partisipasi', 'label' => 'Apakah anda saat ini sebagai anggota aktif berpartisipasi'],
                                ['name' => 'kop_pengurus_kompeten', 'label' => 'Apakah Pengurus koperasi saat ini kompeten (mampu mengelola koperasi)'],
                                ['name' => 'kop_transparan', 'label' => 'Apakah pengurus koperasi telah menjalankan koperasi dengan transparan dan akuntabel'],
                                ['name' => 'kop_keuangan_sehat', 'label' => 'Apakah Keuangan koperasi sehat'],
                                ['name' => 'kop_jaringan_luas', 'label' => 'Apakah koperasi memiliki jaringan pasar yang luas'],
                                ['name' => 'kop_percaya_profesional', 'label' => 'Apakah anda dan Pelaku usaha lainnya percaya dengan profesionalisme koperasi'],
                            ];
                            @endphp
                            @foreach($koperasiQuestions as $index => $q)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $q['label'] }}</td>
                                <td>
                                    <div class="flex gap-4">
                                        <label class="form-check">
                                            <input type="radio" name="{{ $q['name'] }}" value="Ya" class="form-check-input" {{ old($q['name'], $masyarakat->{$q['name']}) == 'Ya' ? 'checked' : '' }}>
                                            <span class="form-check-label">Ya</span>
                                        </label>
                                        <label class="form-check">
                                            <input type="radio" name="{{ $q['name'] }}" value="Tidak" class="form-check-input" {{ old($q['name'], $masyarakat->{$q['name']}) == 'Tidak' ? 'checked' : '' }}>
                                            <span class="form-check-label">Tidak</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="form-actions">
        <a href="{{ route('masyarakat.show', $masyarakat) }}" class="btn btn-outline">Batal</a>
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
