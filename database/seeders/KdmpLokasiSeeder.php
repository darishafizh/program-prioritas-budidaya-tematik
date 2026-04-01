<?php

namespace Database\Seeders;

use App\Models\Kdmp;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KdmpLokasiSeeder extends Seeder
{
    /**
     * Seed the kdmp table from data_100_lokasi.xlsx
     */
    public function run(): void
    {
        $filePath = public_path('assets/data_100_lokasi.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        // Remove header row
        $header = array_shift($rows);

        $count = 0;

        foreach ($rows as $row) {
            // Columns: A=No, B=WADMPR, C=Kabupaten, D=WADMKD, E=Nama KDKMP,
            //          F=Komoditas, G=Ketua/Anggota KDKMP, H=No HP, I=Nama Penyuluh, J=No HP_1

            $no = intval($row['A'] ?? 0);
            $provinsi = trim($row['B'] ?? '');
            $kabupaten = trim($row['C'] ?? '');
            $desa = trim($row['D'] ?? '');
            $namaKdkmp = trim($row['E'] ?? '');
            $komoditas = trim($row['F'] ?? '');
            $ketuaAnggota = trim($row['G'] ?? '');
            $noHp = trim($row['H'] ?? '');
            $namaPenyuluh = trim($row['I'] ?? '');
            $noHpPenyuluh = trim($row['J'] ?? '');

            // Skip empty rows
            if (empty($namaKdkmp) && empty($provinsi)) {
                continue;
            }

            Kdmp::create([
                'no'              => $no ?: null,
                'provinsi'        => $provinsi ?: null,
                'kabupaten'       => $kabupaten ?: null,
                'desa'            => $desa ?: null,
                'nama_kdkmp'      => $namaKdkmp ?: null,
                'komoditas'       => $komoditas ?: null,
                'ketua_anggota'   => $ketuaAnggota ?: null,
                'no_hp'           => $noHp ?: null,
                'nama_penyuluh'   => $namaPenyuluh ?: null,
                'no_hp_penyuluh'  => $noHpPenyuluh ?: null,
            ]);

            $count++;
        }

        $this->command->info("Successfully imported {$count} KDMP locations.");
    }
}
