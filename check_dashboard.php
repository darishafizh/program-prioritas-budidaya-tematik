<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CEK DASHBOARD DATA ===\n\n";

// Bagian 1: Scoring
echo "--- SCORING ---\n";
$total = \App\Models\Kdmp::count();
$scored = \App\Models\LocationScore::count();
$sangat = \App\Models\LocationScore::where('status', 'SANGAT LAYAK')->count();
$layak  = \App\Models\LocationScore::where('status', 'LAYAK')->count();
$cukup  = \App\Models\LocationScore::where('status', 'CUKUP LAYAK')->count();
$tidak  = \App\Models\LocationScore::where('status', 'TIDAK LAYAK')->count();
echo "Total KDMP   : $total\n";
echo "Sudah dinilai: $scored\n";
echo "Sangat Layak : $sangat\n";
echo "Layak        : $layak\n";
echo "Cukup Layak  : $cukup\n";
echo "Tidak Layak  : $tidak\n";
echo "Belum Dinilai: " . ($total - $scored) . "\n";

// Top 5
echo "\nTop 5 Lokasi:\n";
$top = \App\Models\LocationScore::orderByDesc('total_score')->limit(5)->with('kdmp')->get();
foreach ($top as $i => $s) {
    echo "  " . ($i+1) . ". " . ($s->kdmp->nama_kdkmp ?? '-') . " — {$s->kabupaten} — Skor: {$s->total_score} — {$s->status}\n";
}

// Peta
echo "\nLokasi dengan koordinat: " . \App\Models\Kdmp::whereNotNull('lat')->whereNotNull('long')->count() . "\n";

// Bagian 2: Monitoring
echo "\n--- MONITORING ---\n";
$mon = \App\Models\MonitoringRecord::count();
$ontrack    = \App\Models\MonitoringRecord::where('status_lokasi', 'on_track')->count();
$bermasalah = \App\Models\MonitoringRecord::where('status_lokasi', 'bermasalah')->count();
$vakum      = \App\Models\MonitoringRecord::where('status_lokasi', 'vakum')->count();
$selesai    = \App\Models\MonitoringRecord::where('status_lokasi', 'selesai')->count();
$volPanen   = \App\Models\MonitoringRecord::sum('volume_panen_kg');
$nilaiProd  = \App\Models\MonitoringRecord::sum('nilai_produksi');
echo "Total record : $mon\n";
echo "On Track     : $ontrack\n";
echo "Bermasalah   : $bermasalah\n";
echo "Vakum        : $vakum\n";
echo "Selesai      : $selesai\n";
echo "Total Volume : " . number_format($volPanen, 2) . " kg\n";
echo "Total Nilai  : Rp " . number_format($nilaiProd, 0, ',', '.') . "\n";

echo "\n=== SEMUA OK ===\n";
