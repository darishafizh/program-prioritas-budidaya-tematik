<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Data KDMP dengan Koordinat ===\n";
$rows = DB::table('kdmp')->select('id','nama_kdkmp','provinsi','kabupaten','lat','long')->get();
foreach ($rows as $r) {
    echo $r->id . ' | ' . $r->nama_kdkmp . ' | ' . $r->provinsi . ' | ' . $r->kabupaten . ' | lat=' . ($r->lat ?? 'NULL') . ' | long=' . ($r->long ?? 'NULL') . "\n";
}
echo "\nTotal semua KDMP: " . count($rows) . "\n";

$withCoords = DB::table('kdmp')->whereNotNull('lat')->whereNotNull('long')->count();
echo "KDMP dengan koordinat: " . $withCoords . "\n";
