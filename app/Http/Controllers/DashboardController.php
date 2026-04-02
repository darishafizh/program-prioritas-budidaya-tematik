<?php

namespace App\Http\Controllers;

use App\Models\KdmpSurvey;
use App\Models\MasyarakatSurvey;
use App\Models\SppgSurvey;
use App\Models\LocationScore;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $scoringService;

    public function __construct(ScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    /**
     * Display dashboard with statistics
     */
    public function index()
    {
        // Get survey counts
        $totalKdmp = KdmpSurvey::count();
        $totalMasyarakat = MasyarakatSurvey::count();
        $totalSppg = SppgSurvey::count();
        $totalKuesioner = $totalKdmp + $totalMasyarakat + $totalSppg;
        
        // Get unique cooperatives
        $totalKoperasi = KdmpSurvey::whereNotNull('nama_koperasi')
            ->distinct('nama_koperasi')
            ->count('nama_koperasi');
        
        // Get total fish farmers
        $totalPembudidaya = KdmpSurvey::sum('jumlah_pembudidaya') ?? 0;
        
        // Get average progress
        $avgProgress = KdmpSurvey::selectRaw('
            AVG((progres_bangunan + progres_kolam + progres_listrik + progres_air + progres_aerasi) / 5) as avg_progress
        ')->value('avg_progress') ?? 0;
        
        // Commodity chart data
        $komoditasData = KdmpSurvey::select('komoditas', DB::raw('count(*) as total'))
            ->whereNotNull('komoditas')
            ->groupBy('komoditas')
            ->pluck('total', 'komoditas')
            ->toArray();
        
        // Progress chart data (by categories)
        $progresData = [
            'Bangunan' => round(KdmpSurvey::avg('progres_bangunan') ?? 0, 1),
            'Kolam' => round(KdmpSurvey::avg('progres_kolam') ?? 0, 1),
            'Listrik' => round(KdmpSurvey::avg('progres_listrik') ?? 0, 1),
            'Air' => round(KdmpSurvey::avg('progres_air') ?? 0, 1),
            'Aerasi' => round(KdmpSurvey::avg('progres_aerasi') ?? 0, 1),
        ];
        
        // Hambatan chart data
        $hambatanCounts = [
            'SDM' => 0,
            'Modal' => 0,
            'Kepercayaan' => 0,
            'Pasar' => 0,
            'Tata Kelola' => 0,
        ];
        
        KdmpSurvey::whereNotNull('hambatan_koperasi')
            ->pluck('hambatan_koperasi')
            ->each(function ($hambatan) use (&$hambatanCounts) {
                if (is_array($hambatan)) {
                    foreach ($hambatan as $h) {
                        if (isset($hambatanCounts[$h])) {
                            $hambatanCounts[$h]++;
                        }
                    }
                }
            });
        
        // Installation status chart
        $instalasiData = [
            'Bak Terpal' => KdmpSurvey::where('inst_bak_terpal', true)->count(),
            'Lantai' => KdmpSurvey::where('inst_lantai', true)->count(),
            'Air' => KdmpSurvey::where('inst_air', true)->count(),
            'Listrik' => KdmpSurvey::where('inst_listrik', true)->count(),
            'Aerasi' => KdmpSurvey::where('inst_aerasi', true)->count(),
            'Atap' => KdmpSurvey::where('inst_atap', true)->count(),
            'Peralatan' => KdmpSurvey::where('inst_peralatan', true)->count(),
            'IPAL' => KdmpSurvey::where('inst_ipal', true)->count(),
        ];
        
        // Map locations
        $mapLocations = KdmpSurvey::whereNotNull('koordinat')
            ->select('id', 'nama_koperasi', 'kabupaten', 'provinsi', 'koordinat', 'komoditas')
            ->get()
            ->map(function ($item) {
                $coords = $item->coordinates_array;
                if ($coords) {
                    return [
                        'id' => $item->id,
                        'name' => $item->nama_koperasi,
                        'location' => $item->kabupaten . ', ' . $item->provinsi,
                        'commodity' => $item->komoditas,
                        'lat' => $coords['lat'],
                        'lng' => $coords['lng'],
                    ];
                }
                return null;
            })
            ->filter()
            ->values();
        
        // Location Scoring Data
        $scoringStats = [
            'total' => LocationScore::count(),
            'sangat_layak' => LocationScore::where('status', 'SANGAT LAYAK')->count(),
            'layak' => LocationScore::where('status', 'LAYAK')->count(),
            'cukup_layak' => LocationScore::where('status', 'CUKUP LAYAK')->count(),
            'tidak_layak' => LocationScore::where('status', 'TIDAK LAYAK')->count(),
            'avg_score' => round(LocationScore::avg('total_score') ?? 0, 1),
        ];
        
        // Top 5 locations by score
        $topLocations = LocationScore::orderByDesc('total_score')
            ->limit(5)
            ->get();
        
        return view('dashboard.index', compact(
            'totalKuesioner',
            'totalKoperasi',
            'totalPembudidaya',
            'avgProgress',
            'totalKdmp',
            'totalMasyarakat',
            'totalSppg',
            'komoditasData',
            'progresData',
            'hambatanCounts',
            'instalasiData',
            'mapLocations',
            'scoringStats',
            'topLocations'
        ));
    }


    /**
     * Get API data for charts (AJAX)
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type', 'all');
        
        $data = [];
        
        if ($type === 'all' || $type === 'komoditas') {
            $data['komoditas'] = KdmpSurvey::select('komoditas', DB::raw('count(*) as total'))
                ->whereNotNull('komoditas')
                ->groupBy('komoditas')
                ->pluck('total', 'komoditas');
        }
        
        if ($type === 'all' || $type === 'progress') {
            $data['progress'] = [
                'Bangunan' => round(KdmpSurvey::avg('progres_bangunan') ?? 0, 1),
                'Kolam' => round(KdmpSurvey::avg('progres_kolam') ?? 0, 1),
                'Listrik' => round(KdmpSurvey::avg('progres_listrik') ?? 0, 1),
                'Air' => round(KdmpSurvey::avg('progres_air') ?? 0, 1),
                'Aerasi' => round(KdmpSurvey::avg('progres_aerasi') ?? 0, 1),
            ];
        }
        
        return response()->json($data);
    }
}
