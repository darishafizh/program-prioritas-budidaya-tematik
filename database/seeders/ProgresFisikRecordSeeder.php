<?php

namespace Database\Seeders;

use App\Models\Kdmp;
use App\Models\ProgresFisikRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProgresFisikRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ProgresFisikRecord::truncate();
        Schema::enableForeignKeyConstraints();

        $userId = User::first()?->id ?? 1;
        $kdmpList = Kdmp::orderBy('no')->get();

        if ($kdmpList->isEmpty()) {
            $this->command->error('Tidak ada data KDMP.');
            return;
        }

        $this->command->info("Menyiapkan data progres fisik untuk {$kdmpList->count()} KDMP...");

        $periodeList = [
            ['bulan' => 1, 'tahun' => 2026],
            ['bulan' => 2, 'tahun' => 2026],
            ['bulan' => 3, 'tahun' => 2026],
            ['bulan' => 4, 'tahun' => 2026],
        ];

        $records = [];

        foreach ($kdmpList as $idx => $kdmp) {
            $profileGroup = $idx % 5;

            foreach ($periodeList as $pIdx => $periode) {
                if ($profileGroup === 4 && $pIdx === 2) continue;
                if ($profileGroup === 3 && $pIdx === 3) continue;

                $base = match ($profileGroup) {
                    0 => 60,
                    1 => 50,
                    2 => 30,
                    3 => 15,
                    4 => 5,
                    default => 20,
                };

                // Add gradual progress based on period index
                $baseProgress = min(100, $base + ($pIdx * mt_rand(5, 15)));

                $records[] = [
                    'kdmp_id' => $kdmp->id,
                    'user_id' => $userId,
                    'bulan' => $periode['bulan'],
                    'tahun' => $periode['tahun'],
                    'progres_bangunan' => min(100, $baseProgress + mt_rand(-10, 10)),
                    'progres_kolam'    => min(100, $baseProgress + mt_rand(-5, 15)),
                    'progres_listrik'  => min(100, $baseProgress + mt_rand(-20, 20)),
                    'progres_air'      => min(100, $baseProgress + mt_rand(-10, 15)),
                    'progres_aerasi'   => min(100, $baseProgress + mt_rand(-25, 10)),
                    'kendala' => mt_rand(0, 3) === 0 ? 'Beberapa material bangunan terlambat datang.' : null,
                    'tindak_lanjut' => null,
                    'catatan' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $chunks = array_chunk($records, 50);
        foreach ($chunks as $chunk) {
            ProgresFisikRecord::insert($chunk);
        }

        $this->command->info("✅ Berhasil menyimpan " . count($records) . " data progres fisik.");
    }
}
