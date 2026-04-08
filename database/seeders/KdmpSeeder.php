<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KdmpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = base_path('kdmp 100 lokasi.csv.xls');
        
        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan di: " . $filePath);
            return;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file, 1000, "\t");

        $data = [];
        while (($row = fgetcsv($file, 1000, "\t")) !== false) {
            if (count($row) < 13) continue;

            $data[] = [
                'no' => $row[1] === 'NULL' || trim($row[1]) === '' ? null : $row[1],
                'provinsi' => $row[2] === 'NULL' || trim($row[2]) === '' ? null : $row[2],
                'kabupaten' => $row[3] === 'NULL' || trim($row[3]) === '' ? null : $row[3],
                'desa' => $row[4] === 'NULL' || trim($row[4]) === '' ? null : $row[4],
                'nama_kdkmp' => $row[5] === 'NULL' || trim($row[5]) === '' ? null : $row[5],
                'komoditas' => $row[6] === 'NULL' || trim($row[6]) === '' ? null : $row[6],
                'ketua_anggota' => $row[7] === 'NULL' || trim($row[7]) === '' ? null : $row[7],
                'no_hp' => $row[8] === 'NULL' || trim($row[8]) === '' ? null : $row[8],
                'nama_penyuluh' => $row[9] === 'NULL' || trim($row[9]) === '' ? null : $row[9],
                'no_hp_penyuluh' => $row[10] === 'NULL' || trim($row[10]) === '' ? null : $row[10],
                'long' => $row[11] === 'NULL' || trim($row[11]) === '' ? null : str_replace(',', '.', $row[11]),
                'lat' => $row[12] === 'NULL' || trim($row[12]) === '' ? null : str_replace(',', '.', $row[12]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        fclose($file);

        // Menghapus data lama jika diinginkan:
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::table('kdmp')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        
        // Insert data per baris karena DB::table()->insert() memiliki limit jumlah parameter di MySQL/SQLite
        $chunks = array_chunk($data, 50);
        foreach ($chunks as $chunk) {
            \Illuminate\Support\Facades\DB::table('kdmp')->insert($chunk);
        }

        $this->command->info('Data KDMP berhasil di-seed dari file.');
    }
}
