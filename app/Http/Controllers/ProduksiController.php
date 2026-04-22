<?php

namespace App\Http\Controllers;

use App\Models\Kdmp;
use App\Models\MonitoringRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProduksiController extends Controller
{
    /**
     * Dashboard monitoring — tampilkan semua lokasi KDMP beserta status terakhir
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        $status = $request->get('status');
        $search = $request->get('search');

        // Ambil semua KDMP beserta record monitoring terakhir
        $query = Kdmp::with([
            'monitoringRecords' => fn($q) => $q->orderBy('tahun', 'desc')->orderBy('bulan', 'desc'),
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kdkmp', 'like', "%$search%")
                    ->orWhere('kabupaten', 'like', "%$search%")
                    ->orWhere('provinsi', 'like', "%$search%");
            });
        }

        $kdmpList = $query->orderBy('no')->get();

        $recordsPeriode = MonitoringRecord::where('tahun', $tahun)->where('bulan', $bulan);
        $allRecords = (clone $recordsPeriode)->get();
        $targetKeuntungan = 15000000;
        $onTrackCount = 0;
        $underperformCount = 0;

        foreach ($allRecords as $rec) {
            $keuntungan = (float) $rec->nilai_produksi - (float) $rec->biaya_operasional;
            if ($keuntungan >= $targetKeuntungan) {
                $onTrackCount++;
            } else {
                $underperformCount++;
            }
        }

        $stats = [
            'total_kdmp' => Kdmp::count(),
            'sudah_lapor' => $allRecords->count(),
            'on_track' => $onTrackCount,
            'underperforming' => $underperformCount,
            'total_panen' => $allRecords->sum('volume_panen_kg'),
            'total_nilai' => $allRecords->sum('nilai_produksi'),
        ];

        // Hitung rata-rata per lokasi dari seluruh lokasi yang punya record
        $jumlahLokasi = $allRecords->count() ?: 1; // avoid division by zero

        $stats['avg_volume'] = round((float) $allRecords->sum('volume_panen_kg') / $jumlahLokasi, 0);
        $stats['avg_nilai'] = round((float) $allRecords->sum('nilai_produksi') / $jumlahLokasi, 0);

        // avg harga jual = total nilai / total volume (harga per kg)
        $totalVolume = (float) $allRecords->sum('volume_panen_kg');
        $stats['avg_harga_jual'] = $totalVolume > 0
            ? round((float) $allRecords->sum('nilai_produksi') / $totalVolume, 0)
            : 0;

        // Daftar tahun yang tersedia di data
        $tahunList = range(2024, (int) date('Y') + 1);

        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Siapkan Data Grafik Analitik
        // 1. Trend Rata-rata Keseluruhan Lokasi per Bulan (Tahun Ini)
        $trendBulanan = MonitoringRecord::where('tahun', $tahun)
            ->selectRaw('bulan, sum(volume_panen_kg) as total_volume, sum(nilai_produksi) as total_nilai, count(id) as jumlah_lapor')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
            
        $chartTrend = [
            'labels' => [],
            'avg_volume' => [],
            'avg_nilai' => [],
            'avg_harga' => []
        ];
        
        foreach($trendBulanan as $t) {
            $jml = $t->jumlah_lapor > 0 ? $t->jumlah_lapor : 1;
            $avgVol = round($t->total_volume / $jml, 0);
            $avgNilai = round($t->total_nilai / $jml, 0);
            $avgHarga = $t->total_volume > 0 ? round($t->total_nilai / $t->total_volume, 0) : 0;

            $chartTrend['labels'][] = $bulanList[$t->bulan] ?? $t->bulan;
            $chartTrend['avg_volume'][] = $avgVol;
            $chartTrend['avg_nilai'][] = $avgNilai;
            $chartTrend['avg_harga'][] = $avgHarga;
        }

        // 2. Sebaran Performa Seluruh Titik Lokasi (Scatter Plot)
        // Menampilkan titik seluruh KDMP berdasarkan Volume, Nilai Produksi, dan Harga Jual pada bulan terpilih
        $scatterSeries = [];
        foreach($allRecords as $rec) {
            $kdmp = $kdmpList->firstWhere('id', $rec->kdmp_id);
            if ($kdmp && $rec->volume_panen_kg > 0) {
                $hargaJual = $rec->volume_panen_kg > 0 ? round($rec->nilai_produksi / $rec->volume_panen_kg, 0) : 0;
                $keuntungan = $rec->nilai_produksi - $rec->biaya_operasional;
                
                $scatterSeries[] = [
                    'name' => $kdmp->nama_kdkmp,
                    'data' => [
                        [
                            (float) $rec->volume_panen_kg, // X axis: Volume
                            (float) $rec->nilai_produksi,   // Y axis: Nilai Produksi
                            $hargaJual                     // Extra data
                        ]
                    ]
                ];
            }
        }
        
        $chartScatter = json_encode($scatterSeries);

        return view('produksi.index', compact(
            'kdmpList',
            'stats',
            'tahun',
            'bulan',
            'tahunList',
            'bulanList',
            'search',
            'chartTrend',
            'chartScatter'
        ));
    }

    /**
     * Detail monitoring per KDMP — tampilkan riwayat semua periode
     */
    public function show(Kdmp $monitoring)
    {
        $kdmp = $monitoring;
        $records = MonitoringRecord::where('kdmp_id', $kdmp->id)
            ->with('user')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Data chart progres fisik & panen per periode
        $chartData = $records->sortBy(function ($r) {
            return $r->tahun * 100 + $r->bulan;
        })->values()->map(function ($r) use ($bulanList) {
            return [
                'label' => ($bulanList[$r->bulan] ?? $r->bulan) . ' ' . $r->tahun,
                'progres_fisik' => $r->progres_fisik,
                'volume_panen' => (float) $r->volume_panen_kg,
                'nilai_produksi' => (float) $r->nilai_produksi,
                'biaya_operasional' => (float) $r->biaya_operasional,
                'keuntungan' => (float) $r->nilai_produksi - (float) $r->biaya_operasional,
                'status' => $r->status_lokasi,
            ];
        });

        return view('produksi.show', compact('kdmp', 'records', 'chartData', 'bulanList'));
    }

    /**
     * Form tambah laporan monitoring baru untuk KDMP tertentu
     */
    public function create(Request $request)
    {
        $kdmpId = $request->get('kdmp_id');
        $kdmpList = Kdmp::orderBy('no')->get(['id', 'no', 'nama_kdkmp', 'kabupaten', 'provinsi']);
        $kdmpSelected = $kdmpId ? Kdmp::find($kdmpId) : null;

        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $tahunList = range(2024, (int) date('Y') + 1);

        return view('produksi.create', compact('kdmpList', 'kdmpSelected', 'bulanList', 'tahunList'));
    }

    /**
     * Simpan laporan monitoring
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kdmp_id' => 'required|exists:kdmp,id',
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2024',
            'status_lokasi' => 'required|in:on_track,bermasalah,selesai,vakum',
            'progres_fisik' => 'required|integer|between:0,100',
            'volume_panen_kg' => 'nullable|numeric|min:0',
            'nilai_produksi' => 'nullable|numeric|min:0',
            'biaya_pakan' => 'nullable|numeric|min:0',
            'biaya_bibit' => 'nullable|numeric|min:0',
            'biaya_lainnya' => 'nullable|numeric|min:0',
            'jumlah_pembudidaya_aktif' => 'nullable|integer|min:0',
            'survival_rate' => 'nullable|numeric|min:0|max:100',
            'jumlah_kolam_aktif' => 'nullable|integer|min:0',
            'jumlah_kolam_total' => 'nullable|integer|min:0',
            'kendala' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        // Cek duplikasi
        $existing = MonitoringRecord::where('kdmp_id', $validated['kdmp_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['periode' => 'Laporan untuk KDMP ini pada periode ' . $validated['bulan'] . '/' . $validated['tahun'] . ' sudah ada. Gunakan edit untuk memperbarui.']);
        }

        $validated['biaya_operasional'] = (float)($validated['biaya_pakan'] ?? 0) 
                                        + (float)($validated['biaya_bibit'] ?? 0) 
                                        + (float)($validated['biaya_lainnya'] ?? 0);

        MonitoringRecord::create($validated);

        return redirect()->route('produksi.index')
            ->with('success', 'Laporan monitoring berhasil disimpan!');
    }

    /**
     * Form edit laporan monitoring
     */
    public function edit(MonitoringRecord $monitoring)
    {
        $record = $monitoring;
        $record->load('kdmp');

        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $tahunList = range(2024, (int) date('Y') + 1);

        return view('produksi.edit', compact('record', 'bulanList', 'tahunList'));
    }

    /**
     * Update laporan monitoring
     */
    public function update(Request $request, MonitoringRecord $monitoring)
    {
        $validated = $request->validate([
            'status_lokasi' => 'required|in:on_track,bermasalah,selesai,vakum',
            'progres_fisik' => 'required|integer|between:0,100',
            'volume_panen_kg' => 'nullable|numeric|min:0',
            'nilai_produksi' => 'nullable|numeric|min:0',
            'biaya_pakan' => 'nullable|numeric|min:0',
            'biaya_bibit' => 'nullable|numeric|min:0',
            'biaya_lainnya' => 'nullable|numeric|min:0',
            'jumlah_pembudidaya_aktif' => 'nullable|integer|min:0',
            'survival_rate' => 'nullable|numeric|min:0|max:100',
            'jumlah_kolam_aktif' => 'nullable|integer|min:0',
            'jumlah_kolam_total' => 'nullable|integer|min:0',
            'kendala' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $validated['biaya_operasional'] = (float)($validated['biaya_pakan'] ?? 0) 
                                        + (float)($validated['biaya_bibit'] ?? 0) 
                                        + (float)($validated['biaya_lainnya'] ?? 0);

        $monitoring->update($validated);

        return redirect()->route('produksi.show', $monitoring->kdmp_id)
            ->with('success', 'Laporan monitoring berhasil diperbarui!');
    }

    /**
     * Hapus laporan monitoring
     */
    public function destroy(MonitoringRecord $monitoring)
    {
        $kdmpId = $monitoring->kdmp_id;
        $monitoring->delete();

        return redirect()->route('produksi.show', $kdmpId)
            ->with('success', 'Laporan telah dihapus.');
    }
    /**
     * Export PDF detail per lokasi KDMP
     */
    public function exportPdfDetail(Kdmp $kdmp)
    {
        $records = MonitoringRecord::where('kdmp_id', $kdmp->id)
            ->with('user')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('produksi.pdf-detail', compact('kdmp', 'records'))
            ->setPaper('a4', 'landscape');

        $filename = 'Detail_Monitoring_' . str_replace(' ', '_', $kdmp->nama_kdkmp) . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Export data monitoring ke PDF
     */
    public function exportPdf(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        $search = $request->get('search');

        // Ambil semua KDMP beserta record monitoring terakhir
        $query = Kdmp::with([
            'monitoringRecords' => fn($q) => $q->orderBy('tahun', 'desc')->orderBy('bulan', 'desc'),
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kdkmp', 'like', "%$search%")
                    ->orWhere('kabupaten', 'like', "%$search%")
                    ->orWhere('provinsi', 'like', "%$search%");
            });
        }

        $kdmpList = $query->orderBy('no')->get();

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('produksi.pdf', compact('kdmpList', 'tahun', 'bulan', 'bulanList', 'search'))->setPaper('a4', 'landscape');
        return $pdf->stream('Data_Lokasi_Budidaya_' . ($bulanList[$bulan] ?? $bulan) . '_' . $tahun . '.pdf');
    }
}
