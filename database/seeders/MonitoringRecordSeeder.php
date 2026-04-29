<?php

namespace Database\Seeders;

use App\Models\Kdmp;
use App\Models\MonitoringRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MonitoringRecordSeeder extends Seeder
{
    /**
     * Hasilkan data monitoring yang realistis dan bervariasi
     * untuk setiap KDMP selama beberapa periode.
     */
    public function run(): void
    {
        // Kosongkan tabel monitoring_records terlebih dahulu
        Schema::disableForeignKeyConstraints();
        MonitoringRecord::truncate();
        Schema::enableForeignKeyConstraints();

        $userId = User::first()?->id ?? 1;
        $kdmpList = Kdmp::orderBy('no')->get();

        if ($kdmpList->isEmpty()) {
            $this->command->error('Tidak ada data KDMP. Jalankan KdmpSeeder terlebih dahulu.');
            return;
        }

        $this->command->info("Menyiapkan data monitoring untuk {$kdmpList->count()} KDMP...");

        // Periode yang akan di-seed: Jan - Apr 2026
        $periodeList = [
            ['bulan' => 1, 'tahun' => 2026],
            ['bulan' => 2, 'tahun' => 2026],
            ['bulan' => 3, 'tahun' => 2026],
            ['bulan' => 4, 'tahun' => 2026],
        ];

        // Profil performa tiap KDMP: variasi realistis
        // Kelompok: on_track (high performer), medium, underperform, vakum
        $records = [];

        foreach ($kdmpList as $idx => $kdmp) {
            // Tentukan profil performa berdasarkan distribusi
            $profileGroup = $idx % 5; // 0,1: on_track; 2,3: medium; 4: underperform/vakum

            foreach ($periodeList as $pIdx => $periode) {
                // Beberapa KDMP tidak melapor setiap bulan
                if ($profileGroup === 4 && $pIdx === 2)
                    continue; // skip 1 bulan
                if ($profileGroup === 3 && $pIdx === 3)
                    continue; // skip bulan terakhir

                [$biayaBibit, $biayaPakan, $biayaLainnya, $volume, $nilaiProduksi, $sr, $kolamAktif, $kolamTotal, $pembudidaya, $status, $kendala, $tindakLanjut, $catatan]
                    = $this->generateRecord($profileGroup, $periode['bulan']);

                $biayaOperasional = $biayaBibit + $biayaPakan + $biayaLainnya;

                $records[] = [
                    'kdmp_id' => $kdmp->id,
                    'user_id' => $userId,
                    'bulan' => $periode['bulan'],
                    'tahun' => $periode['tahun'],
                    'status_lokasi' => $status,
                    'volume_panen_kg' => $volume,
                    'nilai_produksi' => $nilaiProduksi,
                    'biaya_pakan' => $biayaPakan,
                    'biaya_bibit' => $biayaBibit,
                    'biaya_lainnya' => $biayaLainnya,
                    'biaya_operasional' => $biayaOperasional,
                    'jumlah_pembudidaya_aktif' => $pembudidaya,
                    'survival_rate' => $sr,
                    'jumlah_kolam_aktif' => $kolamAktif,
                    'jumlah_kolam_total' => $kolamTotal,
                    'kendala' => $kendala,
                    'tindak_lanjut' => $tindakLanjut,
                    'catatan' => $catatan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert dalam chunk agar tidak overload
        $chunks = array_chunk($records, 50);
        foreach ($chunks as $chunk) {
            MonitoringRecord::insert($chunk);
        }

        $this->command->info("✅ Berhasil menyimpan " . count($records) . " data monitoring produksi.");
    }

    /**
     * Generate nilai record berdasarkan profil performa KDMP.
     * Strategi: hitung biaya dulu → nilai_produksi = biaya + target_profit
     * agar status On Track / Underperform sesuai dengan profil yang diinginkan.
     */
    private function generateRecord(int $profile, int $bulan): array
    {
        $kendalaOptions = [
            'Cuaca buruk menyebabkan penurunan nafsu makan ikan.',
            'Harga pakan meningkat sehingga biaya operasional membengkak.',
            'Beberapa kolam mengalami kebocoran dan perlu perbaikan.',
            'Ketersediaan bibit terbatas di wilayah setempat.',
            'Serangan penyakit white spot pada sebagian kolam.',
            'Keterbatasan SDM dalam mengelola seluruh kolam secara optimal.',
        ];
        $tindakLanjutOptions = [
            'Koordinasi dengan penyuluh untuk penanganan penyakit.',
            'Pengajuan bantuan pakan darurat ke dinas terkait.',
            'Perbaikan infrastruktur kolam dijadwalkan bulan berikutnya.',
            'Pelatihan manajemen kualitas air untuk pembudidaya.',
            'Negosiasi harga bibit dengan supplier lokal.',
            'Penambahan aerator untuk meningkatkan kadar oksigen kolam.',
        ];
        $catatanOptions = [
            'Pembudidaya antusias mengikuti program bioflok.',
            'Perlu pendampingan intensif dari penyuluh perikanan.',
            'Lokasi strategis, akses pasar tersedia di dekat lokasi KDKMP.',
            'Koordinasi antar pembudidaya berjalan baik.',
            'Kualitas air stabil, kondisi lingkungan mendukung.',
            null,
            null,
        ];

        // Tren musiman: Maret-April lebih tinggi 15%
        $musimFaktor = in_array($bulan, [3, 4]) ? 1.15 : 1.0;

        // Strategi: biaya kecil dan realistis, profit dikontrol per profil
        // Target profit >= 15.000.000 = On Track (sesuai model getStatusLabelAttribute)

        switch ($profile) {
            case 0: // High performer — profit selalu >= 18 juta (On Track pasti)
                $biayaBibit = mt_rand(2000000, 4000000);
                $biayaPakan = mt_rand(5000000, 8000000);
                $biayaLainnya = mt_rand(1000000, 2000000);
                $biayaTotal = $biayaBibit + $biayaPakan + $biayaLainnya;
                $targetProfit = mt_rand(18000000, 30000000);
                $nilaiProduksi = round(($biayaTotal + $targetProfit) * $musimFaktor);
                $hargaJual = mt_rand(30000, 40000);
                $volume = round($nilaiProduksi / $hargaJual);
                $sr = round(mt_rand(85, 96) + (mt_rand(0, 99) / 100), 1);
                $kolamTotal = mt_rand(6, 10);
                $kolamAktif = $kolamTotal;
                $pembudidaya = mt_rand(8, 15);
                $status = 'on_track';
                $kendala = null;
                $tindakLanjut = null;
                $catatan = $catatanOptions[array_rand(array_slice($catatanOptions, 0, 5))];
                break;

            case 1: // Good performer — profit 15-22 juta (On Track)
                $biayaBibit = mt_rand(2000000, 3500000);
                $biayaPakan = mt_rand(4000000, 7000000);
                $biayaLainnya = mt_rand(800000, 1500000);
                $biayaTotal = $biayaBibit + $biayaPakan + $biayaLainnya;
                $targetProfit = mt_rand(15000000, 22000000);
                $nilaiProduksi = round(($biayaTotal + $targetProfit) * $musimFaktor);
                $hargaJual = mt_rand(27000, 36000);
                $volume = round($nilaiProduksi / $hargaJual);
                $sr = round(mt_rand(76, 88) + (mt_rand(0, 99) / 100), 1);
                $kolamTotal = mt_rand(5, 8);
                $kolamAktif = $kolamTotal - mt_rand(0, 1);
                $pembudidaya = mt_rand(6, 12);
                $status = 'on_track';
                $kendala = mt_rand(0, 3) === 0 ? $kendalaOptions[array_rand($kendalaOptions)] : null;
                $tindakLanjut = $kendala ? $tindakLanjutOptions[array_rand($tindakLanjutOptions)] : null;
                $catatan = $catatanOptions[array_rand($catatanOptions)];
                break;

            case 2: // Medium — profit acak 5-18 juta (borderline, campuran)
                $biayaBibit = mt_rand(3000000, 6000000);
                $biayaPakan = mt_rand(5000000, 9000000);
                $biayaLainnya = mt_rand(1000000, 2500000);
                $biayaTotal = $biayaBibit + $biayaPakan + $biayaLainnya;
                $targetProfit = mt_rand(5000000, 18000000);
                $nilaiProduksi = round(($biayaTotal + $targetProfit) * $musimFaktor);
                $hargaJual = mt_rand(25000, 32000);
                $volume = round($nilaiProduksi / $hargaJual);
                $sr = round(mt_rand(62, 78) + (mt_rand(0, 99) / 100), 1);
                $kolamTotal = mt_rand(4, 7);
                $kolamAktif = $kolamTotal - mt_rand(0, 2);
                $pembudidaya = mt_rand(4, 9);
                // Status ditentukan otomatis dari profit nyata
                $status = ($nilaiProduksi - $biayaTotal) >= 15000000 ? 'on_track' : 'bermasalah';
                $kendala = $kendalaOptions[array_rand($kendalaOptions)];
                $tindakLanjut = $tindakLanjutOptions[array_rand($tindakLanjutOptions)];
                $catatan = $catatanOptions[array_rand($catatanOptions)];
                break;

            case 3: // Underperformer — profit -5 juta s/d 8 juta (selalu Underperform)
                $biayaBibit = mt_rand(4000000, 7000000);
                $biayaPakan = mt_rand(6000000, 10000000);
                $biayaLainnya = mt_rand(1500000, 3000000);
                $biayaTotal = $biayaBibit + $biayaPakan + $biayaLainnya;
                $targetProfit = mt_rand(-5000000, 8000000);
                $nilaiProduksi = max(0, round(($biayaTotal + $targetProfit) * $musimFaktor));
                $hargaJual = mt_rand(22000, 28000);
                $volume = $nilaiProduksi > 0 ? round($nilaiProduksi / $hargaJual) : 0;
                $sr = round(mt_rand(42, 65) + (mt_rand(0, 99) / 100), 1);
                $kolamTotal = mt_rand(4, 6);
                $kolamAktif = mt_rand(1, max(1, $kolamTotal - 1));
                $pembudidaya = mt_rand(2, 6);
                $status = 'bermasalah';
                $kendala = $kendalaOptions[array_rand($kendalaOptions)];
                $tindakLanjut = $tindakLanjutOptions[array_rand($tindakLanjutOptions)];
                $catatan = $catatanOptions[array_rand($catatanOptions)];
                break;

            case 4: // Vakum — produksi sangat rendah / nihil
            default:
                $biayaBibit = mt_rand(1000000, 3000000);
                $biayaPakan = mt_rand(2000000, 5000000);
                $biayaLainnya = mt_rand(500000, 1500000);
                $biayaTotal = $biayaBibit + $biayaPakan + $biayaLainnya;
                $nilaiProduksi = max(0, round(mt_rand(0, 4000000) * $musimFaktor));
                $hargaJual = mt_rand(20000, 25000);
                $volume = $nilaiProduksi > 0 ? round($nilaiProduksi / $hargaJual) : 0;
                $sr = round(mt_rand(18, 45) + (mt_rand(0, 99) / 100), 1);
                $kolamTotal = mt_rand(3, 5);
                $kolamAktif = mt_rand(0, 2);
                $pembudidaya = mt_rand(1, 4);
                $status = 'vakum';
                $kendala = $kendalaOptions[array_rand($kendalaOptions)];
                $tindakLanjut = $tindakLanjutOptions[array_rand($tindakLanjutOptions)];
                $catatan = 'Lokasi perlu evaluasi dan pendampingan intensif.';
                break;
        }

        return [
            $biayaBibit,
            $biayaPakan,
            $biayaLainnya,
            $volume,
            $nilaiProduksi,
            $sr,
            $kolamAktif,
            $kolamTotal,
            $pembudidaya,
            $status,
            $kendala,
            $tindakLanjut,
            $catatan,
        ];
    }

    /**
     * Progres fisik meningkat setiap periode (simulasi pertumbuhan)
     */
    private function generateProgresFisik(int $profile, int $pIdx): int
    {
        $base = match ($profile) {
            0 => 75,
            1 => 60,
            2 => 45,
            3 => 30,
            4 => 15,
            default => 20,
        };
        // Trend naik per periode + variasi kecil
        return min(100, $base + ($pIdx * mt_rand(5, 10)) + mt_rand(-5, 5));
    }
}
