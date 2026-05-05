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

        // ── PRODUKSI ───────────────────────────────────────────────
        $latestProdIds = MonitoringRecord::whereIn('kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->select('kdmp_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('kdmp_id')
            ->pluck('latest_id');
        $prodRecords = MonitoringRecord::whereIn('id', $latestProdIds)->get();

        $totalProduksi      = $prodRecords->sum('volume_panen_kg');
        $totalNilaiProduksi = $prodRecords->sum('nilai_produksi');
        $avgSR              = $prodRecords->whereNotNull('survival_rate')->avg('survival_rate') ?? 0;
        $totalKolamAktif    = $prodRecords->sum('jumlah_kolam_aktif');
        $totalKolamAll      = $prodRecords->sum('jumlah_kolam_total');
        $utilisasi          = $totalKolamAll > 0 ? round(($totalKolamAktif / $totalKolamAll) * 100, 1) : 0;

        // ── EKSEKUTIF DASBOR ───────────────────────────────────────
        $countPanen = $prodRecords->where('volume_panen_kg', '>', 0)->count();
        $countBelumPanen = $totalLokasi - $countPanen;
        $pctPanen = $totalLokasi > 0 ? round(($countPanen / $totalLokasi) * 100) : 0;
        $pctBelumPanen = $totalLokasi > 0 ? 100 - $pctPanen : 0;

        $avgProduksiPanen = $countPanen > 0 ? $totalProduksi / $countPanen : 0;
        $avgNilaiPanen = $countPanen > 0 ? $totalNilaiProduksi / $countPanen : 0;

        $eksekutif = [
            'countPanen' => $countPanen,
            'countBelumPanen' => $countBelumPanen,
            'pctPanen' => $pctPanen,
            'pctBelumPanen' => $pctBelumPanen,
            'totalProduksi' => $totalProduksi,
            'avgProduksi' => $avgProduksiPanen,
            'totalNilai' => $totalNilaiProduksi,
            'avgNilai' => $avgNilaiPanen,
        ];

        // ── TOP & BOTTOM PERFORMANCE ───────────────────────────────
        $kdmps = Kdmp::whereIn('id', $filteredKdmpIds)->get()->keyBy('id');
        $performanceData = $prodRecords->map(function ($prod) use ($kdmps) {
            $kdmp = $kdmps[$prod->kdmp_id] ?? null;
            return [
                'kdmp_name' => $kdmp ? $kdmp->nama_kdkmp : 'Unknown',
                'kabupaten' => $kdmp ? $kdmp->kabupaten : 'Unknown',
                'provinsi' => $kdmp ? $kdmp->provinsi : 'Unknown',
                'komoditas' => $kdmp ? $kdmp->komoditas : 'Unknown',
                'volume' => (float) $prod->volume_panen_kg,
                'nilai' => (float) $prod->nilai_produksi,
            ];
        });

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
        $mapLocations = Kdmp::whereNotNull('lat')->whereNotNull('long')
            ->whereIn('id', $filteredKdmpIds)
            ->get()
            ->map(function ($item) use ($prodRecords) {
                $prod  = $prodRecords->where('kdmp_id', $item->id)->first();

                // Warna berdasarkan status produksi
                $color = '#94A3B8'; // belum ada data
                $statusText = 'Belum Lapor';

                if ($prod) {
                    $hasVolume = (float) $prod->volume_panen_kg > 0;
                    $isBermasalah = in_array($prod->status_lokasi, ['bermasalah', 'vakum']);

                    if ($isBermasalah) {
                        $color = '#EF4444'; // Red — problematic
                        $statusText = 'Underperformed';
                    } elseif ($hasVolume) {
                        $color = '#10B981'; // Green — has harvest
                        $statusText = 'Sudah Panen';
                    } else {
                        $color = '#94A3B8'; // Gray — monitoring exists but no harvest yet
                        $statusText = 'Belum Panen';
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
                    'produksi'    => $prod ? (float) $prod->volume_panen_kg : 0,
                    'nilai'       => $prod ? (float) $prod->nilai_produksi : 0,
                    'biaya'       => $prod ? (float) $prod->biaya_operasional : 0,
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
