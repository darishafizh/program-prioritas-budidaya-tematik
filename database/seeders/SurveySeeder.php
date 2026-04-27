<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kdmp;
use App\Models\KdmpSurvey;
use App\Models\MasyarakatSurvey;
use App\Models\SppgSurvey;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SurveySeeder extends Seeder
{
    public function run()
    {
        $user = User::first() ?? User::factory()->create();
        $kdmps = Kdmp::take(10)->get();

        if ($kdmps->isEmpty()) {
            $this->command->info("Tidak ada data KDMP, buat data KDMP terlebih dahulu.");
            return;
        }

        foreach ($kdmps as $kdmp) {
            // 1. Buat Data KdmpSurvey
            KdmpSurvey::create([
                'user_id' => $user->id,
                'kdmp_id' => $kdmp->id,
                'verifikator' => 'Verifikator ' . fake()->name(),
                'responden' => 'Responden ' . fake()->name(),
                'tempat' => $kdmp->kabupaten ?? 'Tempat Survey',
                'tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'jam' => fake()->time('H:i'),
                'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
                'umur' => rand(25, 60),
                'pendidikan' => fake()->randomElement(['SMA', 'D3', 'S1', 'S2']),
                'pekerjaan' => 'Pembudidaya Ikan',
                'alamat' => fake()->address(),

                // Bagian A
                'nama_koperasi' => 'Koperasi ' . fake()->company(),
                'nomor_badan_hukum' => fake()->numerify('AHU-########.AH.01.02.Tahun 202#'),
                'desa' => fake()->citySuffix(),
                'kecamatan' => fake()->city(),
                'kabupaten' => $kdmp->kabupaten ?? fake()->city(),
                'provinsi' => $kdmp->provinsi ?? fake()->state(),
                'luas_lahan' => rand(1000, 5000),
                'jumlah_paket' => rand(1, 5),
                'komoditas' => $kdmp->komoditas ?? fake()->randomElement(['Nila', 'Lele']),
                'koordinat' => fake()->latitude() . ',' . fake()->longitude(),
                'krit_badan_hukum_kbli' => true,
                'kbli_number' => '03221',
                'krit_ekusuka' => true,
                'krit_jkn_aktif' => true,
                'krit_proposal' => true,
                'krit_kesanggupan' => true,
                'krit_belum_bantuan' => true,
                'krit_pelatihan' => true,
                'pelatihan_hari' => rand(3, 7),
                'krit_kelola_koperasi' => true,
                'hambatan_koperasi' => ['Kurangnya modal', 'Akses pasar terbatas'],
                'catatan_a' => 'Memenuhi syarat administratif',

                // Bagian B
                'jumlah_pembudidaya' => rand(10, 50),
                'jumlah_pokdakan' => rand(1, 5),
                'komoditas_terbanyak' => 'Ikan Nila',
                'produksi_ikan' => rand(500, 2000),
                'konsumsi_perkapita' => rand(20, 40),
                'ikan_pasar' => 'Banyak',
                'catatan_b' => 'Potensi produksi tinggi',

                // Bagian C
                'koordinat_gps' => fake()->latitude() . ',' . fake()->longitude(),
                'lahan_datar' => true,
                'lahan_legalitas' => true,
                'lahan_sumber_air' => true,
                'lahan_listrik' => true,
                'lahan_akses' => true,
                'lahan_jauh_pencemar' => true,
                'lahan_prasarana' => true,
                
                'inst_bak_terpal' => true,
                'inst_lantai' => true,
                'inst_air' => true,
                'inst_listrik' => true,
                'inst_aerasi' => true,
                'inst_atap' => true,
                'inst_peralatan' => true,
                'inst_ipal' => true,
                
                'progres_bangunan' => rand(50, 100),
                'progres_kolam' => rand(50, 100),
                'progres_listrik' => rand(50, 100),
                'progres_air' => rand(50, 100),
                'progres_aerasi' => rand(50, 100),
                
                'tk_laki' => rand(5, 15),
                'tk_perempuan' => rand(2, 8),
                'upah_harian' => rand(100000, 150000),
                'jam_kerja' => 8,
                'tk_lokal' => rand(5, 20),
                'tk_luar' => rand(0, 5),
                'kendala_pembangunan' => ['Cuaca ekstrem'],
                'sop_status' => 'Sudah',
                'sop_kendala' => 'Tidak ada',

                // Bagian D
                'responden_pengurus' => 'Ketua',
                'koperasi_d' => 'Koperasi Maju Bersama',
                'tujuan_penjualan' => ['Pasar Tradisional', 'Rumah Makan'],
                'tujuan_lain' => null,
                'pj_operasional' => fake()->randomElement(['Ketua', 'Pengurus', 'Anggota', 'Tenaga Khusus']),
                'modal_tersedia' => fake()->randomElement(['Ya', 'Belum']),
                'strategi_antisipasi' => fake()->randomElement(['Diversifikasi', 'Dukungan Koperasi', 'Pinjaman', 'Belum Ada']),
                'kendala_pemasaran' => 'Harga tidak stabil',
                'rencana_antisipasi' => 'Menjalin kontrak dengan pihak pembeli',
            ]);

            // 2. Buat Data MasyarakatSurvey
            MasyarakatSurvey::create([
                'user_id' => $user->id,
                'verifikator' => 'Verifikator ' . fake()->name(),
                'responden' => 'Tokoh Masyarakat ' . fake()->name(),
                'tempat' => $kdmp->kabupaten ?? 'Tempat Survey',
                'tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'jam' => fake()->time('H:i'),
                'koordinat' => fake()->latitude() . ',' . fake()->longitude(),
                'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
                'umur' => rand(30, 65),
                'pendidikan' => fake()->randomElement(['SMA', 'D3', 'S1']),
                'pekerjaan' => 'Wiraswasta',
                'alamat' => fake()->address(),

                // Bagian A
                'sesuai_kebutuhan' => fake()->randomElement(['Ya, sesuai', 'Tidak sesuai']),
                'item_tidak_sesuai' => null,
                'perasaan' => fake()->randomElement(['Senang', 'Biasa saja', 'Tidak Senang']),
                'alasan' => 'Meningkatkan perekonomian desa',
                'harapan' => 'Program ini berkelanjutan',
                'saran' => 'Perlu pendampingan berkala',

                // Bagian B - Likert (1-5)
                'likert_q1' => rand(4, 5), 
                'likert_q2' => rand(4, 5),
                'likert_q3' => rand(4, 5),
                'likert_q4' => rand(4, 5),
                'likert_q5' => rand(3, 5),
                'likert_q6' => rand(4, 5), 
                'likert_q7' => rand(4, 5),
                'likert_q8' => rand(4, 5),
                'likert_q9' => rand(3, 5),
                'likert_q10' => rand(4, 5),
                'likert_q11' => rand(4, 5), 
                'likert_q12' => rand(4, 5),
                'likert_q13' => rand(4, 5),
                'likert_q14' => rand(4, 5),
                'likert_q15' => rand(4, 5),

                // Bagian C
                'pendapatan_ikan' => rand(2000000, 5000000),
                'pendapatan_lain' => rand(1000000, 3000000),
                'total_pendapatan' => rand(3000000, 8000000),
                'kontribusi' => fake()->randomElement(['<50%', '50-80%', '>80%', '100%']),
                'jumlah_sumber' => fake()->randomElement(['1 sumber', '2 sumber', '3 sumber', '>3 sumber']),
                'ketergantungan' => fake()->randomElement(['Sangat bergantung', 'Cukup bergantung', 'Sedikit bergantung', 'Tidak bergantung']),
                'stabilitas' => fake()->randomElement(['Stabil', 'Cenderung stabil', 'Tidak stabil', 'Sangat tidak stabil']),
                'peran_perempuan' => fake()->randomElement(['Selalu', 'Sering', 'Jarang', 'Tidak pernah']),
                'kontribusi_perempuan' => fake()->randomElement(['>75%', '51-75%', '25-50%', '<25%', 'Tidak dilibatkan']),

                // Bagian D
                'anggota_kub' => fake()->randomElement(['Ya sangat aktif', 'Ya tidak aktif', 'Tidak pernah', 'Tidak ada KUB']),
                'manfaat_kub' => fake()->randomElement(['Sangat Setuju', 'Setuju', 'Netral', 'Tidak Setuju', 'Sangat Tidak Setuju']),
                'anggota_koperasi' => fake()->randomElement(['Ya sangat aktif', 'Ya tidak aktif', 'Tidak pernah', 'Tidak ada koperasi']),
                'tertarik_koperasi' => fake()->randomElement(['Sangat tertarik', 'Tertarik', 'Tidak tertarik', 'Sangat tidak tertarik', 'Sudah jadi anggota']),
                'manfaat_koperasi' => fake()->randomElement(['Sangat Setuju', 'Setuju', 'Netral', 'Tidak Setuju', 'Sangat Tidak Setuju']),
                'kop_rapat_tahunan' => true,
                'kop_aktif_partisipasi' => true,
                'kop_pengurus_kompeten' => true,
                'kop_transparan' => true,
                'kop_keuangan_sehat' => true,
                'kop_jaringan_luas' => true,
                'kop_percaya_profesional' => true,
            ]);

            // 3. Buat Data SppgSurvey
            SppgSurvey::create([
                'user_id' => $user->id,
                'verifikator' => 'Verifikator ' . fake()->name(),
                'responden' => 'Pihak SPPG ' . fake()->name(),
                'tempat' => $kdmp->kabupaten ?? 'Tempat Survey',
                'tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'jam' => fake()->time('H:i'),
                'koordinat' => fake()->latitude() . ',' . fake()->longitude(),
                'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
                'umur' => rand(30, 55),
                'pendidikan' => fake()->randomElement(['D3', 'S1', 'S2']),
                'pekerjaan' => 'Penyedia SPPG',
                'alamat' => fake()->address(),

                // Bagian A
                'nama_sppg' => 'Dapur Umum SPPG ' . fake()->city(),
                'kabupaten' => $kdmp->kabupaten ?? fake()->city(),
                'jumlah_sekolah' => rand(5, 20),
                'jumlah_siswa' => rand(500, 2000),
                'porsi_harian' => rand(500, 2000),
                'porsi_bulanan' => rand(10000, 40000),
                'kebutuhan_lele' => rand(50, 200),
                'kebutuhan_nila' => rand(50, 200),
                'kebutuhan_lain' => rand(10, 50),
                'frekuensi_menu' => rand(2, 4),
                'anggaran_porsi' => rand(15000, 25000),
                'status_terpenuhi' => fake()->randomElement(['Ya', 'Belum']),
                'kekurangan' => rand(20, 50),

                // Bagian B
                'jenis_ikan_prioritas' => ['Nila', 'Lele'],
                'jenis_ikan_lain' => 'Patin',
                'ukuran_kondisi' => ['Segar', 'Fillet'],
                'ukuran_detail' => 'Ukuran konsumsi (4-6 ekor/kg)',
                'standar_kualitas' => ['Tidak berbau lumpur', 'Segar'],
                'sertifikasi' => ['CBIB', 'Halal'],
                'sumber_prioritas' => ['Pembudidaya lokal', 'Koperasi'],

                // Bagian C
                'penting_jenis' => rand(4, 5),
                'harga_jenis' => rand(25000, 35000),
                'penting_ukuran' => rand(4, 5),
                'harga_ukuran' => rand(25000, 35000),
                'penting_kualitas' => rand(4, 5),
                'harga_kualitas' => rand(25000, 40000),
                'penting_sertifikasi' => rand(3, 5),
                'harga_sertifikasi' => rand(28000, 40000),
                'penting_sumber' => rand(4, 5),
                'harga_sumber' => rand(25000, 35000),

                // Bagian D
                'kendala_pasokan' => ['Pasokan tidak stabil', 'Ukuran tidak seragam'],
                'kendala_lain' => null,
                'minat_kerjasama' => fake()->randomElement(['Ya, sangat berminat', 'Ya, berminat', 'Mungkin', 'Tidak']),
                'alasan_minat' => 'Memastikan pasokan stabil dan berkualitas',
                'volume_kebutuhan' => rand(100, 500),
                'jenis_ikan_dibutuhkan' => 'Nila dan Lele',
                'kontrak_status' => fake()->randomElement(['Ada', 'Tidak Ada', 'Dalam Proses']),
                'target_kontrak' => 'Dalam 3 bulan ke depan',
            ]);

            $this->command->info("Survey untuk KDMP {$kdmp->kabupaten} berhasil dibuat.");
        }
    }
}
