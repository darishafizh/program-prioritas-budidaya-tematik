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

        // ── PROGRES FISIK rata-rata ────────────────────────────────
        $latestFisikIds = ProgresFisikRecord::whereIn('kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->select('kdmp_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('kdmp_id')
            ->pluck('latest_id');
        $fisikRecords = ProgresFisikRecord::whereIn('id', $latestFisikIds)->get();

        $fisikKomponen = [
            'Bangunan' => round($fisikRecords->avg('progres_bangunan') ?? 0, 1),
            'Kolam'    => round($fisikRecords->avg('progres_kolam') ?? 0, 1),
            'Listrik'  => round($fisikRecords->avg('progres_listrik') ?? 0, 1),
            'Air'      => round($fisikRecords->avg('progres_air') ?? 0, 1),
            'Aerasi'   => round($fisikRecords->avg('progres_aerasi') ?? 0, 1),
        ];
        $avgProgresFisik = round(array_sum($fisikKomponen) / count($fisikKomponen), 1);

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
            ->map(function ($item) use ($prodRecords, $fisikRecords) {
                $prod  = $prodRecords->where('kdmp_id', $item->id)->first();
                $fisik = $fisikRecords->where('kdmp_id', $item->id)->first();

                $avgFisik = $fisik
                    ? round(($fisik->progres_bangunan + $fisik->progres_kolam + $fisik->progres_listrik + $fisik->progres_air + $fisik->progres_aerasi) / 5, 1)
                    : null;

                // Warna berdasarkan status produksi
                $color = '#94A3B8'; // belum ada data
                $statusText = 'Belum Lapor';

                if ($prod) {
                    if (in_array($prod->status_lokasi, ['on_track', 'selesai'])) {
                        $color = '#10B981'; // Green
                        $statusText = 'On Track';
                    } elseif (in_array($prod->status_lokasi, ['bermasalah', 'vakum'])) {
                        $color = '#EF4444'; // Red
                        $statusText = 'Underperformed';
                    } else {
                        $color = '#10B981';
                        $statusText = 'On Track';
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
                    'fisik'       => $avgFisik,
                    'fisik_detail' => $fisik ? [
                        'bangunan' => $fisik->progres_bangunan,
                        'kolam'    => $fisik->progres_kolam,
                        'listrik'  => $fisik->progres_listrik,
                        'air'      => $fisik->progres_air,
                        'aerasi'   => $fisik->progres_aerasi,
                    ] : null,
                    'kendala'     => $prod->kendala ?? null,
                ];
            })
            ->values();

        return view('dashboard.index', compact(
            'filterProvinsi', 'filterKomoditas', 'filterTahun',
            'provinsiList', 'komoditasList', 'tahunList',
            'totalLokasi',
            'fisikKomponen', 'avgProgresFisik',
            'totalProduksi', 'totalNilaiProduksi', 'avgSR', 'utilisasi',
            'prodPerProvinsi', 'prodBulanan', 'nilaiBulanan',
            'sebaranKomoditas',
            'mapLocations'
        ));
    }
}
