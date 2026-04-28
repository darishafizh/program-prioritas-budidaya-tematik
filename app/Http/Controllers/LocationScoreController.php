<?php

namespace App\Http\Controllers;

use App\Models\LocationScore;
use App\Models\KdmpSurvey;
use App\Services\ScoringService;
use Illuminate\Http\Request;

class LocationScoreController extends Controller
{
    protected $scoringService;

    public function __construct(ScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    /**
     * Display scoring dashboard
     */
    public function index(Request $request)
    {
        $query = LocationScore::query();

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('kabupaten')) {
            $query->byKabupaten($request->kabupaten);
        }

        if ($request->filled('provinsi')) {
            $query->byProvinsi($request->provinsi);
        }

        // Get ranked results
        $scores = $query->ranked()->get();

        // Get filter options
        $statusOptions = ['POTENSIAL', 'TIDAK POTENSIAL'];
        $kabupatenOptions = LocationScore::distinct()->pluck('kabupaten')->filter();
        $provinsiOptions = LocationScore::distinct()->pluck('provinsi')->filter();

        // Get statistics
        $stats = [
            'total' => LocationScore::count(),
            'potensial' => LocationScore::byStatus('POTENSIAL')->count(),
            'tidak_potensial' => LocationScore::byStatus('TIDAK POTENSIAL')->count(),
            'avg_score' => LocationScore::avg('total_score') ?? 0,
        ];

        return view('scoring.index', compact(
            'scores',
            'statusOptions',
            'kabupatenOptions',
            'provinsiOptions',
            'stats'
        ));
    }

    /**
     * Show detail score for a location
     */
    public function show(LocationScore $locationScore)
    {
        $locationScore->load(['kdmpSurvey', 'masyarakatSurvey', 'sppgSurvey']);

        return view('scoring.show', compact('locationScore'));
    }

    /**
     * Recalculate score for a location
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string',
            'kabupaten' => 'required|string',
            'provinsi' => 'required|string',
        ]);

        $locationScore = $this->scoringService->calculateForKecamatan(
            $request->kecamatan,
            $request->kabupaten,
            $request->provinsi
        );

        return redirect()->route('scoring.show', $locationScore)
            ->with('success', 'Skor berhasil dihitung!');
    }

    /**
     * Recalculate all location scores
     */
    public function recalculateAll()
    {
        $count = $this->scoringService->recalculateAll();

        return redirect()->route('scoring.index')
            ->with('success', "Berhasil menghitung ulang {$count} lokasi!");
    }

    /**
     * Export scores to Excel
     */
    public function export(Request $request)
    {
        $scores = LocationScore::ranked()->get();

        // Simple CSV export
        $filename = 'skor_kelayakan_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($scores) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'No', 'Kecamatan', 'Kabupaten', 'Provinsi',
                'Skor KDMP', 'Skor Masyarakat', 'Skor SPPG',
                'Total Skor', 'Status'
            ]);

            // Data
            foreach ($scores as $index => $score) {
                fputcsv($file, [
                    $index + 1,
                    $score->kecamatan,
                    $score->kabupaten,
                    $score->provinsi,
                    $score->kdmp_score,
                    $score->masyarakat_score,
                    $score->sppg_score,
                    $score->total_score,
                    $score->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Auto-generate scores from existing KDMP surveys
     */
    public function generateFromSurveys()
    {
        $kdmpSurveys = KdmpSurvey::whereNotNull('kecamatan')
            ->whereNotNull('kabupaten')
            ->get();

        $count = 0;
        foreach ($kdmpSurveys as $survey) {
            $this->scoringService->calculateForKecamatan(
                $survey->kecamatan,
                $survey->kabupaten,
                $survey->provinsi ?? '-'
            );
            $count++;
        }

        return redirect()->route('scoring.index')
            ->with('success', "Berhasil generate skor dari {$count} survey KDMP!");
    }
}
