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
        // Default: tidak ada filter aktif (tampilkan semua data)
        $tahun = $request->get('tahun');
        $bulan = $request->get('bulan');
        $status = $request->get('status');
        $search = $request->get('search');

        // Ambil semua KDMP beserta record sesuai periode yang dipilih (atau semua jika tidak difilter)
        $query = Kdmp::with([
            'monitoringRecords' => fn($q) => $q
                ->select('id', 'kdmp_id', 'tanggal', 'volume_panen_kg', 'nilai_produksi', 'biaya_operasional', 'biaya_pakan', 'biaya_bibit', 'biaya_lainnya')
                ->when($tahun, fn($q2) => $q2->whereYear('tanggal', $tahun))
                ->when($bulan, fn($q2) => $q2->whereMonth('tanggal', $bulan))
                ->orderByDesc('tanggal'),
        ])->select('id', 'nama_kdkmp', 'kabupaten', 'provinsi');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kdkmp', 'like', "%$search%")
                    ->orWhere('kabupaten', 'like', "%$search%")
                    ->orWhere('provinsi', 'like', "%$search%");
            });
        }

        $kdmpList = $query->orderBy('id')->get();

        // 1 Query cepat untuk mengambil semua record (dengan filter jika ada)
        $allRecords = MonitoringRecord::select('kdmp_id', 'volume_panen_kg', 'nilai_produksi', 'biaya_operasional')
                                      ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
                                      ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
                                      ->get();
        $totalKdmp = Kdmp::count();
        $kdmpIdsPanen = $allRecords->where('volume_panen_kg', '>', 0)->pluck('kdmp_id')->unique();
        $sudahPanenCount = $kdmpIdsPanen->count();
        $belumPanenCount = $totalKdmp - $sudahPanenCount;

        $stats = [
            'total_kdmp' => $totalKdmp,
            'sudah_lapor' => $allRecords->unique('kdmp_id')->count(),
            'sudah_panen' => $sudahPanenCount,
            'belum_panen' => $belumPanenCount,
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
        $trendBulanan = MonitoringRecord::whereYear('tanggal', $tahun)
            ->selectRaw('MONTH(tanggal) as bulan, SUM(volume_panen_kg) as total_volume, SUM(nilai_produksi) as total_nilai, COUNT(id) as jumlah_lapor')
            ->groupByRaw('MONTH(tanggal)')
            ->orderByRaw('MONTH(tanggal)')
            ->get();
            
        $chartTrend = [
            'labels' => array_values($bulanList),
            'avg_volume' => array_fill(0, 12, 0),
            'avg_nilai' => array_fill(0, 12, 0),
            'avg_harga' => array_fill(0, 12, 0)
        ];
        
        foreach($trendBulanan as $t) {
            $idx = $t->bulan - 1; // 0-11
            $jml = $t->jumlah_lapor > 0 ? $t->jumlah_lapor : 1;
            $avgVol = round($t->total_volume / $jml, 0);
            $avgNilai = round($t->total_nilai / $jml, 0);
            $avgHarga = $t->total_volume > 0 ? round($t->total_nilai / $t->total_volume, 0) : 0;

            $chartTrend['avg_volume'][$idx] = (float) $avgVol;
            $chartTrend['avg_nilai'][$idx] = (float) $avgNilai;
            $chartTrend['avg_harga'][$idx] = (float) $avgHarga;
        }

        // 2. Sebaran Performa Seluruh Titik Lokasi (Scatter Plot)
        // Grouped into "On Track" and "Underperform"
        $scatterSeries = [
            ['name' => 'On Track', 'data' => []],
            ['name' => 'Underperform', 'data' => []]
        ];

        foreach($allRecords as $rec) {
            $kdmp = $kdmpList->firstWhere('id', $rec->kdmp_id);
            if ($kdmp && $rec->volume_panen_kg > 0) {
                $hargaJual = $rec->volume_panen_kg > 0 ? round((float)$rec->nilai_produksi / (float)$rec->volume_panen_kg, 0) : 0;
                $keuntungan = (float)$rec->nilai_produksi - (float)$rec->biaya_operasional;
                
                $dataPoint = [
                    'x' => (float) $rec->volume_panen_kg,
                    'y' => (float) $rec->nilai_produksi,
                    'kdmpName' => $kdmp->nama_kdkmp,
                    'hargaJual' => $hargaJual
                ];

                if ($keuntungan >= 15000000) {
                    $scatterSeries[0]['data'][] = $dataPoint; // On Track
                } else {
                    $scatterSeries[1]['data'][] = $dataPoint; // Underperform
                }
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
            ->orderByDesc('tanggal')
            ->get();

        // Data chart per periode
        $chartData = $records->sortBy('tanggal')->values()->map(function ($r) {
            return [
                'label' => $r->tanggal ? $r->tanggal->format('d M Y') : '-',
                'progres_fisik' => $r->progres_fisik,
                'volume_panen' => (float) $r->volume_panen_kg,
                'nilai_produksi' => (float) $r->nilai_produksi,
                'biaya_operasional' => (float) $r->biaya_operasional,
                'keuntungan' => (float) $r->nilai_produksi - (float) $r->biaya_operasional,
                'status' => $r->status_lokasi,
            ];
        });

        return view('produksi.show', compact('kdmp', 'records', 'chartData'));
    }

    /**
     * Form tambah laporan monitoring baru untuk KDMP tertentu
     */
    public function create(Request $request)
    {
        $kdmpId = $request->get('kdmp_id');
        if ($kdmpId && !is_numeric($kdmpId)) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($kdmpId);
            $kdmpId = $decoded[0] ?? null;
        }
        $kdmpList = Kdmp::orderBy('id')->get(['id', 'nama_kdkmp', 'kabupaten', 'provinsi']);
        $kdmpSelected = $kdmpId ? Kdmp::find($kdmpId) : null;

        return view('produksi.create', compact('kdmpList', 'kdmpSelected'));
    }

    /**
     * Simpan laporan monitoring
     */
    public function store(Request $request)
    {
        // Manipulasi format tanggal: jika input berupa dd/mm/yyyy (misal 12/04/2026)
        // secara eksplisit kita anggap 12 = tanggal, 04 = bulan, 2026 = tahun
        if ($request->has('tanggal')) {
            $rawDate = $request->input('tanggal');
            if (str_contains($rawDate, '/')) {
                try {
                    $parsed = \Carbon\Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
                    $request->merge(['tanggal' => $parsed]);
                } catch (\Exception $e) {
                    // Jika gagal parsing, biarkan apa adanya untuk ditangani validator
                }
            }
        }

        $kdmpId = $request->input('kdmp_id');
        if ($kdmpId && !is_numeric($kdmpId)) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($kdmpId);
            $request->merge(['kdmp_id' => $decoded[0] ?? null]);
        }

        $validated = $request->validate([
            'kdmp_id' => 'required|exists:kdmp,id',
            'tanggal' => 'required|date',
            'status_lokasi' => 'required|in:on_track,bermasalah,selesai,vakum',
            'volume_panen_kg' => 'nullable|numeric|min:0',
            'tujuan_pasar' => 'nullable|string|max:255',
            'dokumentasi' => 'nullable|url|max:500',
            'nilai_produksi' => 'nullable|numeric|min:0',
            'biaya_pakan' => 'nullable|numeric|min:0',
            'biaya_bibit' => 'nullable|numeric|min:0',
            'biaya_lainnya' => 'nullable|numeric|min:0',
            'jumlah_pembudidaya_aktif' => 'nullable|integer|min:0',
            'survival_rate' => 'nullable|numeric|min:0|max:100',
            'fcr' => 'nullable|numeric|min:0',
            'jumlah_kolam_aktif' => 'nullable|integer|min:0',
            'jumlah_kolam_total' => 'nullable|integer|min:0',
            'kendala' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        
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
        if (Auth::user()->role !== 'admin' && $monitoring->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat mengubah data milik Anda sendiri.');
        }

        $record = $monitoring;
        $record->load('kdmp');

        return view('produksi.edit', compact('record'));
    }

    /**
     * Update laporan monitoring
     */
    public function update(Request $request, MonitoringRecord $monitoring)
    {
        // Manipulasi format tanggal: jika input berupa dd/mm/yyyy
        // secara eksplisit kita anggap 12 = tanggal, 04 = bulan, 2026 = tahun
        if ($request->has('tanggal')) {
            $rawDate = $request->input('tanggal');
            if (str_contains($rawDate, '/')) {
                try {
                    $parsed = \Carbon\Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
                    $request->merge(['tanggal' => $parsed]);
                } catch (\Exception $e) {
                    // Jika gagal parsing, biarkan apa adanya untuk ditangani validator
                }
            }
        }

        if (Auth::user()->role !== 'admin' && $monitoring->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat mengubah data milik Anda sendiri.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'status_lokasi' => 'required|in:on_track,bermasalah,selesai,vakum',
            'volume_panen_kg' => 'nullable|numeric|min:0',
            'tujuan_pasar' => 'nullable|string|max:255',
            'dokumentasi' => 'nullable|url|max:500',
            'nilai_produksi' => 'nullable|numeric|min:0',
            'biaya_pakan' => 'nullable|numeric|min:0',
            'biaya_bibit' => 'nullable|numeric|min:0',
            'biaya_lainnya' => 'nullable|numeric|min:0',
            'jumlah_pembudidaya_aktif' => 'nullable|integer|min:0',
            'survival_rate' => 'nullable|numeric|min:0|max:100',
            'fcr' => 'nullable|numeric|min:0',
            'jumlah_kolam_aktif' => 'nullable|integer|min:0',
            'jumlah_kolam_total' => 'nullable|integer|min:0',
            'kendala' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:51200',
            'hapus_foto' => 'nullable|array',
            'hapus_foto.*' => 'integer',
        ]);

        $validated['biaya_operasional'] = (float)($validated['biaya_pakan'] ?? 0) 
                                        + (float)($validated['biaya_bibit'] ?? 0) 
                                        + (float)($validated['biaya_lainnya'] ?? 0);

        $monitoring->update($validated);

        $kdmpHash = \Vinkla\Hashids\Facades\Hashids::encode($monitoring->kdmp_id);
        return redirect()->route('produksi.show', $kdmpHash)
            ->with('success', 'Laporan monitoring berhasil diperbarui!');
    }

    /**
     * Hapus laporan monitoring
     */
    public function destroy(MonitoringRecord $monitoring)
    {
        if (Auth::user()->role !== 'admin' && $monitoring->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat menghapus data milik Anda sendiri.');
        }

        $kdmpId = $monitoring->kdmp_id;
        $monitoring->delete();

        $kdmpHash = \Vinkla\Hashids\Facades\Hashids::encode($kdmpId);
        return redirect()->route('produksi.show', $kdmpHash)
            ->with('success', 'Laporan telah dihapus.');
    }
    /**
     * Export PDF detail per lokasi KDMP
     */
    public function exportPdfDetail(Kdmp $kdmp)
    {
        $records = MonitoringRecord::where('kdmp_id', $kdmp->id)
            ->with('user')
            ->orderByDesc('tanggal')
            ->get();

        $progresFisikRecords = \App\Models\ProgresFisikRecord::where('kdmp_id', $kdmp->id)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('produksi.pdf-detail', compact('kdmp', 'records', 'progresFisikRecords'))
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
            'monitoringRecords' => fn($q) => $q->orderByDesc('tanggal'),
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kdkmp', 'like', "%$search%")
                    ->orWhere('kabupaten', 'like', "%$search%")
                    ->orWhere('provinsi', 'like', "%$search%");
            });
        }

        $kdmpList = $query->orderBy('id')->get();

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('produksi.pdf', compact('kdmpList', 'tahun', 'bulan', 'bulanList', 'search'))->setPaper('a4', 'landscape');
        return $pdf->stream('Data_Lokasi_Budidaya_' . ($bulanList[$bulan] ?? $bulan) . '_' . $tahun . '.pdf');
    }

    /**
     * Export data monitoring ke Excel (CSV format, compatible with Excel)
     */
    public function exportExcel(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        $search = $request->get('search');

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Query data
        $query = Kdmp::with([
            'monitoringRecords' => fn($q) => $q->orderByDesc('tanggal'),
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kdkmp', 'like', "%$search%")
                    ->orWhere('kabupaten', 'like', "%$search%")
                    ->orWhere('provinsi', 'like', "%$search%");
            });
        }

        $kdmpList = $query->orderBy('id')->get();

        $namaBulan = $bulanList[$bulan] ?? $bulan;
        $filename = "Data_Monitoring_Produksi_{$namaBulan}_{$tahun}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($kdmpList, $bulanList) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8 so Excel reads it correctly
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($file, [
                'No',
                'Nama KDKMP',
                'Alamat',
                'Komoditas',
                'Ketua / Anggota (Telp)',
                'Penyuluh (Telp)',
                'Periode Laporan Terakhir',
                'Status Lokasi',
                'Volume Panen (kg)',
                'Nilai Produksi (Rp)',
                'Biaya Operasional (Rp)',
                'Keuntungan (Rp)',
                'Survival Rate (%)',
                'Kolam Aktif',
                'Kolam Total',
                'Pembudidaya Aktif',
                'Kendala',
                'Tindak Lanjut',
                'Catatan',
            ]);

            // Data rows
            $no = 1;
            foreach ($kdmpList as $kdmp) {
                $lastRecord = $kdmp->monitoringRecords->first();

                $alamat = implode(', ', array_filter([$kdmp->desa, $kdmp->kabupaten, $kdmp->provinsi])) ?: '-';
                $ketua = $kdmp->ketua_anggota ?? '-';
                if ($kdmp->no_hp) $ketua .= ' (0' . ltrim((string)$kdmp->no_hp, '0') . ')';
                $penyuluh = $kdmp->nama_penyuluh ?? '-';
                if ($kdmp->no_hp_penyuluh) $penyuluh .= ' (0' . ltrim((string)$kdmp->no_hp_penyuluh, '0') . ')';

                $periode = '-';
                $status = '-';
                $volumePanen = '-';
                $nilaiProduksi = '-';
                $biayaOperasional = '-';
                $keuntungan = '-';
                $sr = '-';
                $kolamAktif = '-';
                $kolamTotal = '-';
                $pembudidaya = '-';
                $kendala = '-';
                $tindakLanjut = '-';
                $catatan = '-';

                if ($lastRecord) {
                    $periode = $lastRecord->periode_label ?? '-';
                    $status = $lastRecord->status_label ?? $lastRecord->status_lokasi;
                    $volumePanen = $kdmp->monitoringRecords->sum('volume_panen_kg');
                    $nilaiProduksi = $kdmp->monitoringRecords->sum('nilai_produksi');
                    $biayaOperasional = $kdmp->monitoringRecords->sum('biaya_operasional');
                    $keuntungan = (float)$nilaiProduksi - (float)$biayaOperasional;
                    $sr = $lastRecord->survival_rate !== null ? $lastRecord->survival_rate . '%' : '-';
                    $kolamAktif = $lastRecord->jumlah_kolam_aktif ?? '-';
                    $kolamTotal = $lastRecord->jumlah_kolam_total ?? '-';
                    $pembudidaya = $lastRecord->jumlah_pembudidaya_aktif ?? '-';
                    $kendala = $lastRecord->kendala ?? '-';
                    $tindakLanjut = $lastRecord->tindak_lanjut ?? '-';
                    $catatan = $lastRecord->catatan ?? '-';
                }

                fputcsv($file, [
                    $no++,
                    $kdmp->nama_kdkmp,
                    $alamat,
                    $kdmp->komoditas ?? '-',
                    $ketua,
                    $penyuluh,
                    $periode,
                    $status,
                    $volumePanen,
                    $nilaiProduksi,
                    $biayaOperasional,
                    $keuntungan,
                    $sr,
                    $kolamAktif,
                    $kolamTotal,
                    $pembudidaya,
                    $kendala,
                    $tindakLanjut,
                    $catatan,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
