<?php
/**
 * Import Produksi dari file "2.Monev Tematik 2025.xlsx"
 * 
 * Kolom Excel:
 *  0: No
 *  1: Nama KDMP
 *  2: Kabupaten
 *  3: Provinsi
 *  4: Komoditas
 *  5-8: Panen 1 (Tanggal, Volume, Nilai, Tujuan Pasar)
 *  9-12: Panen 2
 *  13-16: Panen 3
 *  17-20: Panen 4
 *  21-24: Panen 5
 *  25-28: Panen 6
 *  29: Total Volume Panen (Kg)
 *  30: Total Nilai Panen (Rp)
 *  31: SR rata-rata
 *  32: FCR rata-rata
 *  33: Kendala
 *  34: Solusi atau Saran
 *  35: Link Dokumentasi (TIDAK ADA di sistem, skip)
 *  36: Keterangan Tambahan
 * 
 * Mapping ke monitoring_produksi:
 *  - volume_panen_kg  ← Volume Produksi
 *  - nilai_produksi   ← Nilai Produksi
 *  - survival_rate    ← SR rata-rata
 *  - kendala          ← Kendala
 *  - tindak_lanjut    ← Solusi atau Saran
 *  - catatan          ← Keterangan Tambahan
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Kdmp;
use App\Models\MonitoringRecord;

// ── FUNGSI UTILITAS ─────────────────────────────────────────────────

/**
 * Mapping nama bulan Indonesia → angka
 */
function parseBulanIndonesia(string $text): ?int
{
    $bulanMap = [
        'januari' => 1,
        'februari' => 2,
        'maret' => 3,
        'april' => 4,
        'mei' => 5,
        'juni' => 6,
        'juli' => 7,
        'agustus' => 8,
        'september' => 9,
        'oktober' => 10,
        'november' => 11,
        'desember' => 12,
        // Singkatan
        'jan' => 1,
        'feb' => 2,
        'mar' => 3,
        'apr' => 4,
        'jun' => 6,
        'jul' => 7,
        'ags' => 8,
        'agt' => 8,
        'sep' => 9,
        'okt' => 10,
        'nov' => 11,
        'des' => 12,
    ];

    $text = strtolower(trim($text));
    foreach ($bulanMap as $name => $num) {
        if (strpos($text, $name) !== false) {
            return $num;
        }
    }
    return null;
}

/**
 * Coba parse tanggal Indonesia → [bulan, tahun, tanggal_str]
 * Contoh: "10 April 2026", "30 Maret sd 8 April 2026", "Maret 2026"
 */
function parseTanggalIndonesia(string $text): ?array
{
    $text = trim($text);
    if (empty($text))
        return null;

    // Cek apakah ini teks "belum panen", "masih pemeliharaan" dll
    $skipWords = ['belum', 'masih', 'rencana', 'panen perkiraan', 'estimasi'];
    $lower = strtolower($text);
    foreach ($skipWords as $skip) {
        if (strpos($lower, $skip) !== false) {
            return null;
        }
    }

    $bulan = parseBulanIndonesia($text);
    if ($bulan === null)
        return null;

    // Coba ambil tahun (4 digit)
    preg_match('/(\d{4})/', $text, $matchYear);
    $tahun = isset($matchYear[1]) ? (int) $matchYear[1] : 2026;

    // Coba ambil tanggal (1-2 digit di awal atau setelah spasi)
    // Untuk "30 Maret sd 8 April 2026" → ambil angka terakhir sebelum nama bulan terakhir
    preg_match_all('/(\d{1,2})\s/', $text, $matchDays);
    $hari = null;
    if (!empty($matchDays[1])) {
        $hari = (int) end($matchDays[1]); // ambil yang terakhir
    }

    // Build tanggal string Y-m-d jika bisa
    $tanggalStr = null;
    if ($hari && $bulan && $tahun) {
        $tanggalStr = sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);
    }

    return [
        'bulan' => $bulan,
        'tahun' => $tahun,
        'tanggal' => $tanggalStr,
    ];
}

/**
 * Bersihkan angka dari format string: "16,200,000" → 16200000, "  900 " → 900
 */
function cleanNumber($val): float
{
    if ($val === null || $val === '')
        return 0;
    $val = trim((string) $val);
    if ($val === '-' || $val === '  -   ')
        return 0;
    $val = str_replace([',', ' '], '', $val);
    return is_numeric($val) ? (float) $val : 0;
}

/**
 * Parse SR rata-rata: "67 %", "67", "0,06" dll
 */
function parseSR($val): ?float
{
    if ($val === null || trim((string) $val) === '')
        return null;
    $val = trim((string) $val);
    $val = str_replace(['%', ' '], '', $val);
    $val = str_replace(',', '.', $val); // koma desimal → titik
    return is_numeric($val) ? (float) $val : null;
}

// ── MULAI IMPORT ────────────────────────────────────────────────────

echo "=== Import Produksi dari 2.Monev Tematik 2025.xlsx ===\n\n";

$spreadsheet = IOFactory::load('public/2.Monev Tematik 2025.xlsx');
$worksheet = $spreadsheet->getSheet(0);
$rows = $worksheet->toArray();

echo "Total baris di Excel: " . count($rows) . "\n";

$imported = 0;
$skipped = 0;
$notFound = [];
$errors = [];

// Ambil semua KDMP dari database
$allKdmp = Kdmp::all();
echo "Total KDMP di database: " . $allKdmp->count() . "\n\n";

// Panen column offsets: [tanggal, volume, nilai, pasar]
$panenOffsets = [
    1 => [5, 6, 7, 8],
    2 => [9, 10, 11, 12],
    3 => [13, 14, 15, 16],
    4 => [17, 18, 19, 20],
    5 => [21, 22, 23, 24],
    6 => [25, 26, 27, 28],
];

foreach ($rows as $rowIdx => $row) {
    // Skip header rows (0, 1, 2)
    if ($rowIdx < 3)
        continue;

    $nama = trim($row[1] ?? '');
    if (empty($nama))
        continue; // skip empty rows

    $kabupaten = trim($row[2] ?? '');
    $provinsi = trim($row[3] ?? '');
    $komoditas = trim($row[4] ?? '');
    $totalVolume = cleanNumber($row[29] ?? 0);
    $totalNilai = cleanNumber($row[30] ?? 0);
    $sr = parseSR($row[31] ?? null);
    $fcr = $row[32] ?? null; // FCR tidak ada di model, tapi bisa masuk catatan
    $kendala = trim($row[33] ?? '');
    $solusi = trim($row[34] ?? '');
    // $linkDok = $row[35]; // TIDAK ADA di sistem → skip
    $keterangan = trim($row[36] ?? '');

    echo "Row $rowIdx | No: {$row[0]} | $nama";

    // ── Cari KDMP di database ──
    // Fungsi normalisasi kabupaten (untuk handle perbedaan nama)
    $normalizeKab = function ($kab) {
        $kab = mb_strtolower(trim($kab));
        // Normalize: "Gunung Kidul" → "gunungkidul"
        $kab = str_replace(' ', '', $kab);
        // Normalize: "kota" prefix
        $kab = preg_replace('/^kota/', '', $kab);
        // Fix typo: "wonoogiri" → "wonogiri"
        $kab = str_replace('wonoogiri', 'wonogiri', $kab);
        return $kab;
    };

    // Fungsi untuk extract nama pendek dari KDMP
    $extractShortName = function ($name) {
        $name = trim($name);
        // Hapus prefix: "Koperasi Desa/Kelurahan Merah Putih", "KDMP", "KKMP", "KDMP Kalurahan"
        $name = preg_replace('/^Koperasi\s+(Desa|Kelurahan)\s+Merah\s+Putih\s+/i', '', $name);
        $name = preg_replace('/^(KDMP|KKMP)\s+(Kalurahan\s+)?/i', '', $name);
        return mb_strtolower(trim($name));
    };

    // 1. Coba cari exact match dulu
    $kdmp = $allKdmp->first(function ($k) use ($nama) {
        return mb_strtolower(trim($k->nama_kdkmp)) === mb_strtolower($nama);
    });

    // 2. Coba short name match (strip prefix)
    if (!$kdmp) {
        $shortName = $extractShortName($nama);
        $kdmp = $allKdmp->first(function ($k) use ($shortName, $extractShortName) {
            return $extractShortName($k->nama_kdkmp) === $shortName;
        });
    }

    // 3. Coba fuzzy: kabupaten (normalized) + kata terakhir nama
    if (!$kdmp && !empty($kabupaten)) {
        $lastWord = trim(preg_replace('/^.*\s/', '', $nama));
        $normKab = $normalizeKab($kabupaten);
        $kdmp = $allKdmp->first(function ($k) use ($lastWord, $normKab, $normalizeKab) {
            return $normalizeKab($k->kabupaten) === $normKab
                && stripos($k->nama_kdkmp, $lastWord) !== false;
        });
    }

    // 4. Coba short name contains match + kabupaten normalized
    if (!$kdmp && !empty($kabupaten)) {
        $shortName = $extractShortName($nama);
        // Hapus spasi untuk handle "Tegal Sari" vs "Tegalsari", "Kauman Kidul" etc
        $shortNameNoSpace = str_replace(' ', '', $shortName);
        $normKab = $normalizeKab($kabupaten);
        $kdmp = $allKdmp->first(function ($k) use ($shortNameNoSpace, $normKab, $extractShortName, $normalizeKab) {
            $kShort = str_replace(' ', '', $extractShortName($k->nama_kdkmp));
            return $normalizeKab($k->kabupaten) === $normKab
                && ($kShort === $shortNameNoSpace || strpos($kShort, $shortNameNoSpace) !== false || strpos($shortNameNoSpace, $kShort) !== false);
        });
    }

    if (!$kdmp) {
        $notFound[] = "Row $rowIdx: $nama ($kabupaten, $provinsi)";
        echo " → ❌ TIDAK DITEMUKAN\n";
        continue;
    }

    echo " → ✅ KDMP #{$kdmp->id}\n";

    // ── Parse setiap panen ──
    $panenRecords = []; // [bulan => [volume, nilai, tanggal]]
    $hasPanenData = false;

    foreach ($panenOffsets as $pNum => $offsets) {
        $tglStr = trim($row[$offsets[0]] ?? '');
        $vol = cleanNumber($row[$offsets[1]] ?? 0);
        $val = cleanNumber($row[$offsets[2]] ?? 0);
        $pasar = trim($row[$offsets[3]] ?? '');

        // Skip kalau tidak ada data
        if (empty($tglStr) && $vol == 0 && $val == 0)
            continue;

        // Parse tanggal
        $parsedDate = parseTanggalIndonesia($tglStr);

        // Kalau ada volume/nilai tapi tanggal tidak bisa diparsing, gunakan fallback
        if ($vol > 0 || $val > 0) {
            $hasPanenData = true;

            if ($parsedDate) {
                $bulan = $parsedDate['bulan'];
                $tahun = $parsedDate['tahun'];
                $tanggal = $parsedDate['tanggal'];
            } else {
                // Fallback: gunakan bulan Mei 2026 (periode pelaporan)
                $bulan = 5;
                $tahun = 2026;
                $tanggal = null;
            }

            $key = "{$tahun}-{$bulan}";
            if (!isset($panenRecords[$key])) {
                $panenRecords[$key] = [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tanggal' => $tanggal,
                    'volume' => 0,
                    'nilai' => 0,
                ];
            }
            $panenRecords[$key]['volume'] += $vol;
            $panenRecords[$key]['nilai'] += $val;
            // Simpan tanggal terakhir
            if ($tanggal) {
                $panenRecords[$key]['tanggal'] = $tanggal;
            }
        }
    }

    // ── Simpan ke database ──
    if (!empty($panenRecords)) {
        // Simpan per bulan
        foreach ($panenRecords as $key => $panen) {
            try {
                MonitoringRecord::updateOrCreate([
                    'kdmp_id' => $kdmp->id,
                    'bulan' => $panen['bulan'],
                    'tahun' => $panen['tahun'],
                ], [
                    'user_id' => 1,
                    'tanggal' => $panen['tanggal'],
                    'status_lokasi' => 'on_track',
                    'progres_fisik' => 100,
                    'volume_panen_kg' => $panen['volume'],
                    'nilai_produksi' => $panen['nilai'],
                    'biaya_operasional' => 0,
                    'survival_rate' => $sr,
                    'kendala' => !empty($kendala) ? $kendala : null,
                    'tindak_lanjut' => !empty($solusi) ? $solusi : null,
                    'catatan' => !empty($keterangan) ? $keterangan : null,
                ]);
                $imported++;
                echo "  → Disimpan: Bulan {$panen['bulan']}/{$panen['tahun']} | Vol: {$panen['volume']} kg | Nilai: Rp " . number_format($panen['nilai'], 0, ',', '.') . "\n";
            } catch (\Exception $e) {
                $errors[] = "Row $rowIdx ($nama): " . $e->getMessage();
                echo "  → ⚠️ ERROR: " . $e->getMessage() . "\n";
            }
        }
    } else {
        // Tidak ada panen individual → simpan record dengan total (mungkin 0)
        try {
            MonitoringRecord::updateOrCreate([
                'kdmp_id' => $kdmp->id,
                'bulan' => 5,     // Mei 2026 (periode laporan)
                'tahun' => 2026,
            ], [
                'user_id' => 1,
                'tanggal' => '2026-05-13',
                'status_lokasi' => 'on_track',
                'progres_fisik' => ($totalVolume > 0) ? 100 : 0,
                'volume_panen_kg' => $totalVolume,
                'nilai_produksi' => $totalNilai,
                'biaya_operasional' => 0,
                'survival_rate' => $sr,
                'kendala' => !empty($kendala) ? $kendala : null,
                'tindak_lanjut' => !empty($solusi) ? $solusi : null,
                'catatan' => !empty($keterangan) ? $keterangan : null,
            ]);
            $imported++;
            $statusLabel = ($totalVolume > 0) ? "Sudah Panen" : "Belum Panen";
            echo "  → Disimpan ($statusLabel): Vol: {$totalVolume} kg | Nilai: Rp " . number_format($totalNilai, 0, ',', '.') . "\n";
        } catch (\Exception $e) {
            $errors[] = "Row $rowIdx ($nama): " . $e->getMessage();
            echo "  → ⚠️ ERROR: " . $e->getMessage() . "\n";
        }
    }
}

// ── LAPORAN AKHIR ────────────────────────────────────────────────────

echo "\n" . str_repeat('=', 60) . "\n";
echo "HASIL IMPORT\n";
echo str_repeat('=', 60) . "\n";
echo "✅ Records berhasil disimpan: $imported\n";

if (!empty($notFound)) {
    echo "\n❌ KDMP tidak ditemukan di database (" . count($notFound) . "):\n";
    foreach ($notFound as $nf) {
        echo "  - $nf\n";
    }
}

if (!empty($errors)) {
    echo "\n⚠️ Errors (" . count($errors) . "):\n";
    foreach ($errors as $err) {
        echo "  - $err\n";
    }
}

echo "\nSelesai.\n";
