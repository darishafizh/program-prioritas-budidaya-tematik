<?php

namespace App\Http\Controllers;

use App\Models\Kdmp;
use App\Models\MonitoringRecord;
use App\Models\ProgresFisikRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filterProvinsi  = $request->get('provinsi');
        $filterKomoditas = $request->get('komoditas');
        $filterTahun     = $request->get('tahun', date('Y'));

        $provinsiList  = Kdmp::whereNotNull('provinsi')->distinct()->orderBy('provinsi')->pluck('provinsi');
        $komoditasList = Kdmp::whereNotNull('komoditas')->where('komoditas', '!=', '')->distinct()->orderBy('komoditas')->pluck('komoditas');
        $tahunList     = collect([date('Y'), date('Y') - 1, date('Y') - 2]);

        // ── KDMP base ──────────────────────────────────────────────
        $kdmpQuery = Kdmp::query();
        if ($filterProvinsi)  $kdmpQuery->where('provinsi', $filterProvinsi);
        if ($filterKomoditas) $kdmpQuery->where('komoditas', $filterKomoditas);
        $filteredKdmpIds = $kdmpQuery->pluck('id');
        $totalLokasi = $filteredKdmpIds->count();

        // ── PRODUKSI (semua record dalam tahun = kumulatif) ─────────
        $allProdRecords = MonitoringRecord::whereIn('kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->get();

        // Kumulatif: total dari SEMUA record di tahun ini
        $totalProduksi      = $allProdRecords->sum('volume_panen_kg');
        $totalNilaiProduksi = $allProdRecords->sum('nilai_produksi');

        // Record terakhir per KDMP (untuk status, SR, kolam, map, performa)
        $latestProdIds = MonitoringRecord::whereIn('kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->select('kdmp_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('kdmp_id')
            ->pluck('latest_id');
        $prodRecords = MonitoringRecord::whereIn('id', $latestProdIds)->get();

        $avgSR              = $prodRecords->whereNotNull('survival_rate')->avg('survival_rate') ?? 0;
        $totalKolamAktif    = $prodRecords->sum('jumlah_kolam_aktif');
        $totalKolamAll      = $prodRecords->sum('jumlah_kolam_total');
        $utilisasi          = $totalKolamAll > 0 ? round(($totalKolamAktif / $totalKolamAll) * 100, 1) : 0;

        // ── EKSEKUTIF DASBOR (kumulatif) ──────────────────────────
        // Lokasi yang pernah panen = punya minimal 1 record dengan volume > 0
        $kdmpIdsPanen = $allProdRecords->where('volume_panen_kg', '>', 0)
            ->pluck('kdmp_id')->unique();
        $countPanen = $kdmpIdsPanen->count();
        $countBelumPanen = $totalLokasi - $countPanen;

        // Hitung On Track vs Underperform dari lokasi yang sudah panen
        $targetKeuntunganExec = 15000000;
        $kumulatifPanenPerKdmp = $allProdRecords->whereIn('kdmp_id', $kdmpIdsPanen)
            ->groupBy('kdmp_id')
            ->map(function ($records) {
                return [
                    'nilai' => (float) $records->sum('nilai_produksi'),
                    'biaya' => (float) $records->sum('biaya_operasional'),
                ];
            });

        $countOnTrack = 0;
        $countUnderperform = 0;
        foreach ($kumulatifPanenPerKdmp as $kumul) {
            $keuntungan = $kumul['nilai'] - $kumul['biaya'];
            if ($keuntungan >= $targetKeuntunganExec) {
                $countOnTrack++;
            } else {
                $countUnderperform++;
            }
        }

        $eksekutif = [
            'countBelumPanen' => $countBelumPanen,
            'countPanen' => $countPanen,
            'countOnTrack' => $countOnTrack,
            'countUnderperform' => $countUnderperform,
            'totalProduksi' => $totalProduksi,
            'totalNilai' => $totalNilaiProduksi,
        ];

        // ── TOP & BOTTOM PERFORMANCE (kumulatif per KDMP) ──────────
        $kdmps = Kdmp::whereIn('id', $filteredKdmpIds)->get()->keyBy('id');
        $performanceData = $allProdRecords->groupBy('kdmp_id')->map(function ($records, $kdmpId) use ($kdmps) {
            $kdmp = $kdmps[$kdmpId] ?? null;
            return [
                'kdmp_name' => $kdmp ? $kdmp->nama_kdkmp : 'Unknown',
                'kabupaten' => $kdmp ? $kdmp->kabupaten : 'Unknown',
                'provinsi' => $kdmp ? $kdmp->provinsi : 'Unknown',
                'komoditas' => $kdmp ? $kdmp->komoditas : 'Unknown',
                'volume' => (float) $records->sum('volume_panen_kg'),
                'nilai' => (float) $records->sum('nilai_produksi'),
            ];
        })->values();

        // Sort by volume descending, then nilai descending
        $top5 = $performanceData->sortByDesc(function ($item) {
            return $item['volume'] * 1000000000 + $item['nilai'];
        })->take(5)->values();

        // Sort by volume ascending, then nilai ascending
        $bottom5 = $performanceData->sortBy(function ($item) {
            return $item['volume'] * 1000000000 + $item['nilai'];
        })->take(5)->values();

        $perfRegionName = $filterProvinsi ? "Region " . $filterProvinsi : 'Skala Nasional';
        
        $topCommodities = $top5->pluck('komoditas')->unique();
        $topCommodity = $topCommodities->count() > 0 ? $topCommodities->first() : 'Budidaya';
        $perfIsAbsolute = $topCommodities->count() === 1 ? 'secara absolut dikuasai oleh' : 'didominasi oleh';

        $performanceSummary = [
            'top5' => $top5,
            'bottom5' => $bottom5,
            'regionName' => $perfRegionName,
            'komoditas' => $topCommodity,
            'isAbsolute' => $perfIsAbsolute,
        ];

        // ── CHART: Produksi per Provinsi ───────────────────────────
        $prodPerProvinsi = MonitoringRecord::join('kdmp', 'monitoring_produksi.kdmp_id', '=', 'kdmp.id')
            ->whereIn('monitoring_produksi.kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->select('kdmp.provinsi', DB::raw('SUM(volume_panen_kg) as total'))
            ->groupBy('kdmp.provinsi')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── CHART: Trend bulanan ───────────────────────────────────
        $prodBulananRaw = MonitoringRecord::whereIn('kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->select('bulan', DB::raw('SUM(volume_panen_kg) as vol'), DB::raw('SUM(nilai_produksi) as val'))
            ->groupBy('bulan')->orderBy('bulan')
            ->get()->keyBy('bulan');
        $prodBulanan = $nilaiBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $prodBulanan[]  = $prodBulananRaw->has($i) ? (float) $prodBulananRaw[$i]->vol : 0;
            $nilaiBulanan[] = $prodBulananRaw->has($i) ? (float) $prodBulananRaw[$i]->val : 0;
        }

        // ── CHART: Sebaran Komoditas ───────────────────────────────
        $sebaranKomoditas = Kdmp::whereIn('id', $filteredKdmpIds)
            ->whereNotNull('komoditas')->where('komoditas', '!=', '')
            ->select('komoditas', DB::raw('count(*) as total'))
            ->groupBy('komoditas')->orderByDesc('total')->get();

        // ── MAP DATA ───────────────────────────────────────────────
        $targetKeuntungan = 15000000;
        // Kumulatif per KDMP untuk map
        $kumulatifPerKdmp = $allProdRecords->groupBy('kdmp_id')->map(function ($records) {
            return [
                'volume' => (float) $records->sum('volume_panen_kg'),
                'nilai'  => (float) $records->sum('nilai_produksi'),
                'biaya'  => (float) $records->sum('biaya_operasional'),
            ];
        });

        $mapLocations = Kdmp::whereNotNull('lat')->whereNotNull('long')
            ->whereIn('id', $filteredKdmpIds)
            ->get()
            ->map(function ($item) use ($prodRecords, $kumulatifPerKdmp, $targetKeuntungan) {
                $prod  = $prodRecords->where('kdmp_id', $item->id)->first();
                $kumul = $kumulatifPerKdmp[$item->id] ?? null;

                // Status berdasarkan data kumulatif
                $color = '#94A3B8'; // belum ada data
                $statusText = 'Belum Panen';

                if ($kumul && $kumul['volume'] > 0) {
                    $keuntungan = $kumul['nilai'] - $kumul['biaya'];
                    if ($keuntungan >= $targetKeuntungan) {
                        $color = '#10B981'; // Green — On Track
                        $statusText = 'On Track';
                    } else {
                        $color = '#EF4444'; // Red — Underperform
                        $statusText = 'Underperform';
                    }
                }

                return [
                    'id'          => $item->id,
                    'no'          => $item->no,
                    'name'        => $item->nama_kdkmp ?? 'KDMP',
                    'kabupaten'   => $item->kabupaten,
                    'provinsi'    => $item->provinsi,
                    'desa'        => $item->desa,
                    'komoditas'   => $item->komoditas,
                    'ketua'       => $item->ketua_anggota,
                    'penyuluh'    => $item->nama_penyuluh,
                    'lat'         => $item->lat,
                    'lng'         => $item->long,
                    'color'       => $color,
                    'status'      => $statusText,
                    'produksi'    => $kumul ? $kumul['volume'] : 0,
                    'nilai'       => $kumul ? $kumul['nilai'] : 0,
                    'biaya'       => $kumul ? $kumul['biaya'] : 0,
                    'sr'          => $prod ? $prod->survival_rate : null,
                    'kolam_aktif' => $prod ? $prod->jumlah_kolam_aktif : null,
                    'kolam_total' => $prod ? $prod->jumlah_kolam_total : null,
                    'kendala'     => $prod->kendala ?? null,
                ];
            })
            ->values();

        return view('dashboard.index', compact(
            'filterProvinsi', 'filterKomoditas', 'filterTahun',
            'provinsiList', 'komoditasList', 'tahunList',
            'totalLokasi',
            'eksekutif',
            'performanceSummary',
            'totalProduksi', 'totalNilaiProduksi', 'avgSR', 'utilisasi',
            'prodPerProvinsi', 'prodBulanan', 'nilaiBulanan',
            'sebaranKomoditas',
            'mapLocations'
        ));
    }
}
