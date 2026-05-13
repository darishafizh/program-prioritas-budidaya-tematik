<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Kdmp;
use App\Models\MonitoringRecord;

$spreadsheet = IOFactory::load('Produksi.xlsx');
$worksheet = $spreadsheet->getActiveSheet();
$rows = $worksheet->toArray();

$imported = 0;
$notFound = [];

foreach ($rows as $index => $row) {
    if ($index === 0)
        continue; // skip header

    $no = $row[0];
    $nama = trim($row[1] ?? '');
    $kabupaten = $row[2];
    $komoditas = $row[3];
    $bantuan = $row[4];
    $keterangan = trim($row[5] ?? ''); // "Panen" atau "Belum Panen" dsb
    $volume = $row[6];
    $nilai = $row[7];

    if (empty($nama))
        continue;

    // Clean numbers
    $volume = str_replace(',', '', (string) $volume);
    $nilai = str_replace(',', '', (string) $nilai);

    $volume = is_numeric($volume) ? (float) $volume : 0;
    $nilai = is_numeric($nilai) ? (float) $nilai : 0;

    $kdmp = Kdmp::where('nama_kdkmp', $nama)->first();

    if ($kdmp) {
        $status_lokasi = 'on_track';
        // If it's something bad
        if (stripos($keterangan, 'bermasalah') !== false) {
            $status_lokasi = 'bermasalah';
        }

        MonitoringRecord::updateOrCreate([
            'kdmp_id' => $kdmp->id,
            'bulan' => date('n'),
            'tahun' => date('Y'),
            'tanggal' => date('Y-m-d'),
        ], [
            'user_id' => 1, // assume admin
            'status_lokasi' => $status_lokasi,
            'progres_fisik' => 100,
            'volume_panen_kg' => $volume,
            'nilai_produksi' => $nilai,
            'biaya_operasional' => 0,
            'catatan' => $keterangan ? "Keterangan: $keterangan" : null
        ]);
        $imported++;
    } else {
        $notFound[] = $nama;
    }
}

echo "Imported $imported records.\n";
if (!empty($notFound)) {
    echo "Not found in KDMP table:\n" . implode("\n", $notFound) . "\n";
}
