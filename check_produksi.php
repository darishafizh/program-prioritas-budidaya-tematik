<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MonitoringRecord;
use App\Models\Kdmp;

echo "=== Monitoring Records Summary ===\n";
echo "Total records: " . MonitoringRecord::count() . "\n\n";

echo "Records by period:\n";
$periods = MonitoringRecord::selectRaw('tahun, bulan, count(*) as total')
    ->groupBy('tahun', 'bulan')
    ->orderBy('tahun', 'desc')
    ->orderBy('bulan', 'desc')
    ->get();

foreach ($periods as $p) {
    echo "  {$p->tahun}-" . str_pad($p->bulan, 2, '0', STR_PAD_LEFT) . ": {$p->total} records\n";
}

echo "\nCurrent month/year default: " . date('n') . "/" . date('Y') . "\n";

// Check a sample record for Mei 2026
echo "\nRecords for bulan=5, tahun=2026: " . MonitoringRecord::where('bulan', 5)->where('tahun', 2026)->count() . "\n";
echo "Records for bulan=" . date('n') . ", tahun=" . date('Y') . ": " . MonitoringRecord::where('bulan', date('n'))->where('tahun', date('Y'))->count() . "\n";

// Show sample record data
echo "\nSample records (first 3):\n";
$samples = MonitoringRecord::take(3)->get();
foreach ($samples as $s) {
    echo "  ID={$s->id}, kdmp_id={$s->kdmp_id}, bulan={$s->bulan}, tahun={$s->tahun}, volume={$s->volume_panen_kg}, nilai={$s->nilai_produksi}, biaya={$s->biaya_operasional}\n";
}
