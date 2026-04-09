<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KdmpUpdateKoordinatSeeder extends Seeder
{
    /**
     * Update kolom long dan lat untuk beberapa data pada tabel kdmp.
     * Gunakan 'id' atau 'no' sebagai kunci pencarian.
     */
    public function run(): void
    {
        // Update koordinat long & lat untuk ID 67 sampai 100
        $updates = [
            ['id' => 67,  'long' => '113.5931611', 'lat' => '-8.262847723'],
            ['id' => 68,  'long' => '112.5431265', 'lat' => '-7.836062529'],
            ['id' => 69,  'long' => '112.1557746', 'lat' => '-8.120951222'],
            ['id' => 70,  'long' => '112.041224',  'lat' => '-7.847852649'],
            ['id' => 71,  'long' => '112.0622771', 'lat' => '-7.856325304'],
            ['id' => 72,  'long' => '113.2155335', 'lat' => '-7.809150058'],
            ['id' => 73,  'long' => '112.311752',  'lat' => '-7.089615835'],
            ['id' => 74,  'long' => '112.392903',  'lat' => '-7.09240071'],
            ['id' => 75,  'long' => '112.2640932', 'lat' => '-7.152256621'],
            ['id' => 76,  'long' => '113.1476988', 'lat' => '-8.214423206'],
            ['id' => 77,  'long' => '111.6556307', 'lat' => '-7.445729821'],
            ['id' => 78,  'long' => '111.6412869', 'lat' => '-7.539513884'],
            ['id' => 79,  'long' => '111.4221432', 'lat' => '-7.553066693'],
            ['id' => 80,  'long' => '111.3512984', 'lat' => '-7.770491529'],
            ['id' => 81,  'long' => '111.4569133', 'lat' => '-7.561858589'],
            ['id' => 82,  'long' => '111.4062739', 'lat' => '-7.707967106'],
            ['id' => 83,  'long' => '112.4957503', 'lat' => '-7.5174193'],
            ['id' => 84,  'long' => '112.3863864', 'lat' => '-7.450857069'],
            ['id' => 85,  'long' => '112.5506694', 'lat' => '-7.479659161'],
            ['id' => 86,  'long' => '112.0575879', 'lat' => '-7.653482611'],
            ['id' => 87,  'long' => '111.866895',  'lat' => '-7.509341864'],
            ['id' => 88,  'long' => '113.3948208', 'lat' => '-7.458087588'],
            ['id' => 89,  'long' => '112.4957393', 'lat' => '-7.871591021'],
            ['id' => 90,  'long' => '111.2046602', 'lat' => '-7.401585877'],
            ['id' => 91,  'long' => '111.0140017', 'lat' => '-8.158520633'],
            ['id' => 92,  'long' => '111.4142413', 'lat' => '-7.994276117'],
            ['id' => 93,  'long' => '111.4161357', 'lat' => '-7.986386997'],
            ['id' => 94,  'long' => '113.4434046', 'lat' => '-7.736889696'],
            ['id' => 95,  'long' => '114.0566063', 'lat' => '-7.668443276'],
            ['id' => 96,  'long' => '113.9904063', 'lat' => '-7.729549915'],
            ['id' => 97,  'long' => '113.9926177', 'lat' => '-7.688383715'],
            ['id' => 98,  'long' => '111.8556449', 'lat' => '-8.054142123'],
            ['id' => 99,  'long' => '111.8868332', 'lat' => '-8.105961679'],
            ['id' => 100, 'long' => '111.9633593', 'lat' => '-8.07799973'],
        ];

        $updated = 0;
        $notFound = 0;

        foreach ($updates as $item) {
            $affected = DB::table('kdmp')
                ->where('id', $item['id'])
                ->update([
                    'long'       => $item['long'],
                    'lat'        => $item['lat'],
                    'updated_at' => now(),
                ]);

            if ($affected > 0) {
                $updated++;
                $this->command->line("  ✓ ID {$item['id']} → long: {$item['long']}, lat: {$item['lat']}");
            } else {
                $notFound++;
                $this->command->warn("  ✗ ID {$item['id']} tidak ditemukan.");
            }
        }

        $this->command->info("Selesai: {$updated} data berhasil diupdate, {$notFound} tidak ditemukan.");
    }
}
