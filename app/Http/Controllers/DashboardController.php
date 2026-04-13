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
     * Executive Summary Dashboard
     * Fokus: (1) Indikasi Kelayakan Lokasi, (2) Monitoring Evaluasi Produksi
     */
    public function index()
    {
        // ============================================================
        // BAGIAN 1: INDIKASI KELAYAKAN LOKASI (dari LocationScore)
        // ============================================================

        $totalLokasi = Kdmp::count();

        $scoringStats = [
            'total'       => LocationScore::count(),
            'sangat_layak'=> LocationScore::where('status', 'SANGAT LAYAK')->count(),
            'layak'       => LocationScore::where('status', 'LAYAK')->count(),
            'cukup_layak' => LocationScore::where('status', 'CUKUP LAYAK')->count(),
            'tidak_layak' => LocationScore::where('status', 'TIDAK LAYAK')->count(),
            'avg_score'   => round(LocationScore::avg('total_score') ?? 0, 1),
        ];

        // Belum dinilai = lokasi di master yang belum ada di location_scores
        $scoringStats['belum_dinilai'] = $totalLokasi - $scoringStats['total'];

        // Top 5 lokasi terbaik
        $topLocations = LocationScore::orderByDesc('total_score')->limit(5)->get();

        // Peta: semua 100 lokasi KDMP dengan status scoring (preload untuk hindari N+1)
        $allScores = LocationScore::all()->keyBy('kdmp_id');

        $mapLocations = Kdmp::whereNotNull('lat')->whereNotNull('long')
            ->get()
            ->map(function ($item) use ($allScores) {
                $score = $allScores->get($item->id);
                return [
                    'id'        => $item->id,
                    'name'      => $item->nama_kdkmp ?? 'KDMP Tanpa Nama',
                    'kabupaten' => $item->kabupaten,
                    'provinsi'  => $item->provinsi,
                    'komoditas' => $item->komoditas,
                    'lat'       => $item->lat,
                    'lng'       => $item->long,
                    'status'    => $score ? $score->status : 'BELUM DINILAI',
                    'score'     => $score ? $score->total_score : null,
                ];
            })
            ->values();

        // Distribusi per Provinsi (untuk referensi)
        $sebaranProvinsi = Kdmp::select('provinsi', DB::raw('count(*) as total'))
            ->whereNotNull('provinsi')
            ->groupBy('provinsi')
            ->orderByDesc('total')
            ->get();

        // ============================================================
        // BAGIAN 2: MONITORING EVALUASI PRODUKSI
        // ============================================================

        $monitoringStats = [
            'total'       => MonitoringRecord::count(),
            'on_track'    => MonitoringRecord::where('status_lokasi', 'on_track')->count(),
            'bermasalah'  => MonitoringRecord::where('status_lokasi', 'bermasalah')->count(),
            'vakum'       => MonitoringRecord::where('status_lokasi', 'vakum')->count(),
            'selesai'     => MonitoringRecord::where('status_lokasi', 'selesai')->count(),
        ];

        // Total produksi kumulatif
        $produksiTotal = [
            'volume_kg'    => MonitoringRecord::sum('volume_panen_kg') ?? 0,
            'nilai_rupiah'  => MonitoringRecord::sum('nilai_produksi') ?? 0,
        ];

        // Tren produksi per bulan (12 bulan terakhir)
        $trenProduksi = MonitoringRecord::select(
                'bulan', 'tahun',
                DB::raw('SUM(volume_panen_kg) as total_volume'),
                DB::raw('SUM(nilai_produksi) as total_nilai')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->limit(12)
            ->get()
            ->map(function ($row) {
                $bulanNames = [
                    1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
                    7=>'Jul',8=>'Ags',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'
                ];
                return [
                    'label'       => ($bulanNames[$row->bulan] ?? $row->bulan) . ' ' . $row->tahun,
                    'volume'      => (float) $row->total_volume,
                    'nilai'       => (float) $row->total_nilai,
                ];
            });

        // Rekap status monitoring terkini per lokasi (ambil record terbaru tiap kdmp)
        $monitoringTerkini = MonitoringRecord::select(
                'kdmp_id',
                DB::raw('MAX(id) as latest_id')
            )
            ->groupBy('kdmp_id')
            ->with(['kdmp'])
            ->get()
            ->map(function ($row) {
                return MonitoringRecord::with('kdmp')->find($row->latest_id);
            })
            ->filter()
            ->sortBy(function ($r) {
                return match ($r->status_lokasi) {
                    'bermasalah' => 0,
                    'vakum'      => 1,
                    'on_track'   => 2,
                    'selesai'    => 3,
                    default      => 4,
                };
            })
            ->take(10)
            ->values();

        return view('dashboard.index', compact(
            // Bagian 1: Scoring
            'totalLokasi',
            'scoringStats',
            'topLocations',
            'mapLocations',
            'sebaranProvinsi',
            // Bagian 2: Monitoring
            'monitoringStats',
            'produksiTotal',
            'trenProduksi',
            'monitoringTerkini'
        ));
    }

    /**
     * Get API data for charts (AJAX)
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type', 'all');
        $data = [];

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
