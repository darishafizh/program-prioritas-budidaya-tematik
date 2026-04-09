<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = \Illuminate\Support\Facades\DB::table('kdmp')
    ->whereBetween('id', [67, 100])
    ->get(['id', 'long', 'lat']);

echo str_pad('ID', 6) . str_pad('LONG', 18) . str_pad('LAT', 18) . "\n";
echo str_repeat('-', 42) . "\n";
foreach ($rows as $r) {
    echo str_pad($r->id, 6) . str_pad($r->long ?? 'NULL', 18) . str_pad($r->lat ?? 'NULL', 18) . "\n";
}
echo "\nTotal: " . count($rows) . " baris\n";
