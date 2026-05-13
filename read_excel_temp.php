<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('Produksi.xlsx');
$worksheet = $spreadsheet->getActiveSheet();
$rows = $worksheet->toArray();

echo json_encode(array_slice($rows, 0, 10), JSON_PRETTY_PRINT);
