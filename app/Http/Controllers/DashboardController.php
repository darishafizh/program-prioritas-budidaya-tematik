<?php

namespace App\Http\Controllers;

use App\Models\Kdmp;
use App\Models\KdmpSurvey;
use App\Models\LocationScore;
use App\Models\MonitoringRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard Monev 3-Layer
     * Layer 1: Executive (KPI + Map)
     * Layer 2: Manajerial (Charts + Analysis)
     * Layer 3: Teknis (Detail Table)
     */
    public function index(Request $request)
    {
        // ============================================================
        // FILTER PARAMETERS
        // ============================================================
        $filterProvinsi  = $request->get('provinsi');
        $filterKomoditas = $request->get('komoditas');
        $filterTahun     = $request->get('tahun', date('Y'));
        $filterBulan     = $request->get('bulan');

        // Filter options untuk dropdown
        $provinsiList  = Kdmp::whereNotNull('provinsi')->distinct()->orderBy('provinsi')->pluck('provinsi');
        $komoditasList = Kdmp::whereNotNull('komoditas')->where('komoditas', '!=', '')->distinct()->orderBy('komoditas')->pluck('komoditas');
        $tahunList     = MonitoringRecord::distinct()->orderByDesc('tahun')->pluck('tahun');
        if ($tahunList->isEmpty()) $tahunList = collect([date('Y')]);
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // ============================================================
        // BASE QUERIES (filtered KDMP IDs)
        // ============================================================
        $kdmpQuery = Kdmp::query();
        if ($filterProvinsi)  $kdmpQuery->where('provinsi', $filterProvinsi);
        if ($filterKomoditas) $kdmpQuery->where('komoditas', $filterKomoditas);
        $filteredKdmpIds = $kdmpQuery->pluck('id');

        // Semua KDMP (terfilter) — dipakai untuk tab Teknis (termasuk yang belum ada data)
        $allKdmp = Kdmp::whereIn('id', $filteredKdmpIds)->orderBy('no')->get();
        $totalLokasi = $allKdmp->count();

        // ============================================================
        // LATEST MONITORING RECORDS — per KDMP sesuai filter periode
        // ============================================================
        // Ambil ID record terbaru per KDMP (sesuai filter tahun/bulan)
        $latestRecordIds = MonitoringRecord::whereIn('kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->when($filterBulan, fn($q) => $q->where('bulan', $filterBulan))
            ->select('kdmp_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('kdmp_id')
            ->pluck('latest_id');

        $latestRecords = MonitoringRecord::with('kdmp')
            ->whereIn('id', $latestRecordIds)
            ->get()
            ->keyBy('kdmp_id'); // indexed by kdmp_id for fast lookup

        // ============================================================
        // LAYER 1: EXECUTIVE — KPI Cards
        // ============================================================

        // KPI Calculations (dari record yang ada)
        $totalProduksi    = $latestRecords->sum('volume_panen_kg');
        $avgSR            = $latestRecords->whereNotNull('survival_rate')->avg('survival_rate');
        $avgBiayaPerKg    = $latestRecords->filter(fn($r) => $r->biaya_per_kg !== null)->avg('biaya_per_kg');

        $totalKolamAktif  = $latestRecords->sum('jumlah_kolam_aktif');
        $totalKolamAll    = $latestRecords->sum('jumlah_kolam_total');
        $utilisasiKolam   = $totalKolamAll > 0 ? round(($totalKolamAktif / $totalKolamAll) * 100, 1) : null;
        $produksiPerKolam = $totalKolamAktif > 0 ? round((float)$totalProduksi / $totalKolamAktif, 1) : null;

        // Unit aktif = KDMP yang sudah lapor (ada record monitoring)
        $unitAktif    = $latestRecords->count();
        $pctUnitAktif = $totalLokasi > 0 ? round(($unitAktif / $totalLokasi) * 100, 1) : 0;

        // Program Health Status berdasarkan rata-rata SR
        $programHealth = 'success';
        if ($avgSR !== null) {
            if ($avgSR < 70) $programHealth = 'danger';
            elseif ($avgSR <= 80) $programHealth = 'warning';
        }

        // Status breakdown berdasarkan status_lokasi field
        $statusBreakdown = [
            'on_track'   => $latestRecords->where('status_lokasi', 'on_track')->count(),
            'bermasalah' => $latestRecords->where('status_lokasi', 'bermasalah')->count(),
            'vakum'      => $latestRecords->where('status_lokasi', 'vakum')->count(),
            'selesai'    => $latestRecords->where('status_lokasi', 'selesai')->count(),
            'belum_lapor' => $totalLokasi - $unitAktif,
        ];
        $totalMonitored = $latestRecords->count();

        // SR distribution
        $srDistribution = [
            'danger'  => $latestRecords->where('survival_rate', '<', 70)->whereNotNull('survival_rate')->count(),
            'warning' => $latestRecords->whereBetween('survival_rate', [70, 80])->count(),
            'success' => $latestRecords->where('survival_rate', '>', 80)->count(),
            'unknown' => $latestRecords->whereNull('survival_rate')->count() + ($totalLokasi - $unitAktif),
        ];

        // ============================================================
        // MAP DATA — markers colored by SR status
        // ============================================================
        $mapLocations = Kdmp::whereNotNull('lat')->whereNotNull('long')
            ->whereIn('id', $filteredKdmpIds)
            ->get()
            ->map(function ($item) use ($latestRecords) {
                $record = $latestRecords->get($item->id);
                $sr = $record?->survival_rate;
                $srColor = '#9CA3AF'; // gray default (belum ada data)
                if ($sr !== null) {
                    if ($sr < 70) $srColor = '#DC2626';
                    elseif ($sr <= 80) $srColor = '#D97706';
                    else $srColor = '#16A34A';
                }
                return [
                    'id'        => $item->id,
                    'name'      => $item->nama_kdkmp ?? 'KDMP',
                    'kabupaten' => $item->kabupaten,
                    'provinsi'  => $item->provinsi,
                    'komoditas' => $item->komoditas,
                    'lat'       => $item->lat,
                    'lng'       => $item->long,
                    'sr'        => $sr,
                    'srColor'   => $srColor,
                    'status'    => $record?->status_lokasi ?? 'belum_lapor',
                    'produksi'  => $record ? (float) $record->volume_panen_kg : 0,
                ];
            })
            ->values();

        // ============================================================
        // LAYER 2: MANAJERIAL — Charts Data
        // ============================================================

        // Produksi per Provinsi (bar chart)
        $produksiPerProvinsi = MonitoringRecord::join('kdmp', 'monitoring_records.kdmp_id', '=', 'kdmp.id')
            ->whereIn('monitoring_records.kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->when($filterBulan, fn($q) => $q->where('bulan', $filterBulan))
            ->select('kdmp.provinsi', DB::raw('SUM(volume_panen_kg) as total_volume'))
            ->groupBy('kdmp.provinsi')
            ->orderByDesc('total_volume')
            ->get();

        // Tren Produksi per Bulan (line chart)
        $trenProduksi = MonitoringRecord::whereIn('kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->select('bulan', 'tahun', DB::raw('SUM(volume_panen_kg) as total_volume'), DB::raw('AVG(survival_rate) as avg_sr'))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')->orderBy('bulan')
            ->get()
            ->map(function ($row) use ($bulanList) {
                return [
                    'label'  => ($bulanList[$row->bulan] ?? $row->bulan) . ' ' . $row->tahun,
                    'volume' => (float) $row->total_volume,
                    'avg_sr' => $row->avg_sr ? round($row->avg_sr, 1) : null,
                ];
            });

        // Distribusi Masalah (donut chart)
        $distribusiMasalah = MonitoringRecord::whereIn('kdmp_id', $filteredKdmpIds)
            ->when($filterTahun, fn($q) => $q->where('tahun', $filterTahun))
            ->when($filterBulan, fn($q) => $q->where('bulan', $filterBulan))
            ->whereNotNull('kendala')
            ->where('kendala', '!=', '')
            ->pluck('kendala')
            ->flatMap(function ($kendala) {
                $categories = [];
                $lower = strtolower($kendala);
                if (str_contains($lower, 'pakan'))       $categories[] = 'Pakan';
                if (str_contains($lower, 'air'))         $categories[] = 'Kualitas Air';
                if (str_contains($lower, 'penyakit') || str_contains($lower, 'hama'))  $categories[] = 'Penyakit/Hama';
                if (str_contains($lower, 'modal') || str_contains($lower, 'biaya'))    $categories[] = 'Modal/Biaya';
                if (str_contains($lower, 'sdm') || str_contains($lower, 'tenaga'))     $categories[] = 'SDM';
                if (str_contains($lower, 'pemasaran') || str_contains($lower, 'jual')) $categories[] = 'Pemasaran';
                if (str_contains($lower, 'cuaca') || str_contains($lower, 'banjir'))   $categories[] = 'Cuaca/Alam';
                if (empty($categories)) $categories[] = 'Lainnya';
                return $categories;
            })
            ->countBy()
            ->sortDesc();

        // Perbandingan Performa antar Wilayah
        $perbandinganWilayah = MonitoringRecord::join('kdmp', 'monitoring_records.kdmp_id', '=', 'kdmp.id')
            ->whereIn('monitoring_records.id', $latestRecordIds)
            ->select(
                'kdmp.provinsi',
                DB::raw('AVG(survival_rate) as avg_sr'),
                DB::raw('AVG(volume_panen_kg) as avg_produksi'),
                DB::raw('COUNT(*) as jumlah_lokasi')
            )
            ->groupBy('kdmp.provinsi')
            ->orderBy('kdmp.provinsi')
            ->get();

        // ============================================================
        // LAYER 3: TEKNIS — Detail Table
        // Tampilkan SEMUA KDMP (termasuk yang belum ada data monitoring)
        // ============================================================
        $detailLokasi = $allKdmp->map(function ($kdmp) use ($latestRecords) {
            $record = $latestRecords->get($kdmp->id);

            if ($record) {
                return [
                    'id'               => $kdmp->id,
                    'nama'             => $kdmp->nama_kdkmp ?? '-',
                    'provinsi'         => $kdmp->provinsi ?? '-',
                    'kabupaten'        => $kdmp->kabupaten ?? '-',
                    'komoditas'        => $kdmp->komoditas ?? '-',
                    'kolam_aktif'      => $record->jumlah_kolam_aktif,
                    'kolam_total'      => $record->jumlah_kolam_total,
                    'utilisasi'        => $record->utilisasi_kolam,
                    'produksi'         => (float) $record->volume_panen_kg,
                    'produksi_per_kolam' => $record->produksi_per_kolam,
                    'sr'               => $record->survival_rate !== null ? (float) $record->survival_rate : null,
                    'sr_status'        => $record->sr_status,
                    'biaya_per_kg'     => $record->biaya_per_kg ? round($record->biaya_per_kg, 0) : null,
                    'status'           => $record->status_label,
                    'status_color'     => $record->status_color,
                    'kendala'          => $record->kendala,
                    'is_prioritas'     => $record->is_prioritas,
                    'periode'          => $record->periode_label,
                    'progres'          => $record->progres_fisik,
                    'has_data'         => true,
                ];
            } else {
                // KDMP belum ada data monitoring pada periode ini
                return [
                    'id'               => $kdmp->id,
                    'nama'             => $kdmp->nama_kdkmp ?? '-',
                    'provinsi'         => $kdmp->provinsi ?? '-',
                    'kabupaten'        => $kdmp->kabupaten ?? '-',
                    'komoditas'        => $kdmp->komoditas ?? '-',
                    'kolam_aktif'      => null,
                    'kolam_total'      => null,
                    'utilisasi'        => null,
                    'produksi'         => 0,
                    'produksi_per_kolam' => null,
                    'sr'               => null,
                    'sr_status'        => 'secondary',
                    'biaya_per_kg'     => null,
                    'status'           => 'Belum Lapor',
                    'status_color'     => 'secondary',
                    'kendala'          => null,
                    'is_prioritas'     => false,
                    'periode'          => '-',
                    'progres'          => null,
                    'has_data'         => false,
                ];
            }
        })
        ->sortByDesc('is_prioritas')
        ->sortByDesc('has_data')
        ->values();

        // Prioritas Intervensi — lokasi yang butuh perhatian segera
        $prioritasIntervensi = $detailLokasi->where('is_prioritas', true)->values();

        // Total Nilai Produksi
        $totalNilai = $latestRecords->sum('nilai_produksi');

        // Komoditas palette
        $sebaranKomoditas = Kdmp::select('komoditas', DB::raw('count(*) as total'))
            ->whereNotNull('komoditas')
            ->where('komoditas', '!=', '')
            ->whereIn('id', $filteredKdmpIds)
            ->groupBy('komoditas')
            ->orderByDesc('total')
            ->get();

        return view('dashboard.index', compact(
            // Filters
            'filterProvinsi', 'filterKomoditas', 'filterTahun', 'filterBulan',
            'provinsiList', 'komoditasList', 'tahunList', 'bulanList',
            // Executive
            'totalLokasi', 'totalProduksi', 'produksiPerKolam', 'utilisasiKolam',
            'avgSR', 'avgBiayaPerKg', 'pctUnitAktif', 'unitAktif',
            'programHealth', 'statusBreakdown', 'totalMonitored', 'srDistribution',
            'mapLocations', 'totalNilai',
            // Manajerial
            'produksiPerProvinsi', 'trenProduksi', 'distribusiMasalah', 'perbandinganWilayah',
            // Teknis
            'detailLokasi', 'prioritasIntervensi',
            // Misc
            'sebaranKomoditas'
        ));
    }

    /**
     * AJAX endpoint for filtered dashboard data
     */
    public function filterData(Request $request)
    {
        // Re-use index logic but return JSON
        $data = $this->getDashboardData($request);
        return response()->json($data);
    }

    /**
     * Get API data for charts (AJAX) — legacy
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type', 'all');
        $data = [];

        if ($type === 'all' || $type === 'komoditas') {
            $data['komoditas'] = Kdmp::select('komoditas', DB::raw('count(*) as total'))
                ->whereNotNull('komoditas')
                ->where('komoditas', '!=', '')
                ->groupBy('komoditas')
                ->orderByDesc('total')
                ->get();
        }

        if ($type === 'all' || $type === 'scoring') {
            $data['scoring'] = [
                'SANGAT LAYAK' => LocationScore::where('status', 'SANGAT LAYAK')->count(),
                'LAYAK'        => LocationScore::where('status', 'LAYAK')->count(),
                'CUKUP LAYAK'  => LocationScore::where('status', 'CUKUP LAYAK')->count(),
                'TIDAK LAYAK'  => LocationScore::where('status', 'TIDAK LAYAK')->count(),
            ];
        }

        if ($type === 'all' || $type === 'produksi') {
            $data['produksi'] = MonitoringRecord::select(
                    'bulan', 'tahun',
                    DB::raw('SUM(volume_panen_kg) as total_volume')
                )
                ->groupBy('tahun', 'bulan')
                ->orderBy('tahun')->orderBy('bulan')
                ->limit(12)
                ->get();
        }

        return response()->json($data);
    }
}
