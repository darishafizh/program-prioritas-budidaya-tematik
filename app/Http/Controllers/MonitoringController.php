<?php

namespace App\Http\Controllers;

use App\Models\Kdmp;
use App\Models\MonitoringRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
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

        // Statistik ringkasan berdasarkan record periode terpilih
        $recordsPeriode = MonitoringRecord::where('tahun', $tahun)->where('bulan', $bulan);

        $stats = [
            'total_kdmp' => Kdmp::count(),
            'sudah_lapor' => MonitoringRecord::where('tahun', $tahun)->where('bulan', $bulan)->count(),
            'on_track' => (clone $recordsPeriode)->where('status_lokasi', 'on_track')->count(),
            'bermasalah' => (clone $recordsPeriode)->where('status_lokasi', 'bermasalah')->count(),
            'selesai' => (clone $recordsPeriode)->where('status_lokasi', 'selesai')->count(),
            'vakum' => (clone $recordsPeriode)->where('status_lokasi', 'vakum')->count(),
            'total_panen' => (clone $recordsPeriode)->sum('volume_panen_kg'),
            'total_nilai' => (clone $recordsPeriode)->sum('nilai_produksi'),
            'total_biaya' => (clone $recordsPeriode)->sum('biaya_operasional'),
            'keuntungan' => (clone $recordsPeriode)->sum('nilai_produksi') - (clone $recordsPeriode)->sum('biaya_operasional'),
        ];

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

        return view('monitoring.index', compact(
            'kdmpList',
            'stats',
            'tahun',
            'bulan',
            'tahunList',
            'bulanList',
            'search'
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
                'status' => $r->status_lokasi,
            ];
        });

        return view('monitoring.show', compact('kdmp', 'records', 'chartData', 'bulanList'));
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

        return view('monitoring.create', compact('kdmpList', 'kdmpSelected', 'bulanList', 'tahunList'));
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
            'biaya_operasional' => 'nullable|numeric|min:0',
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

        MonitoringRecord::create($validated);

        return redirect()->route('monitoring.index')
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

        return view('monitoring.edit', compact('record', 'bulanList', 'tahunList'));
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
            'biaya_operasional' => 'nullable|numeric|min:0',
            'jumlah_pembudidaya_aktif' => 'nullable|integer|min:0',
            'survival_rate' => 'nullable|numeric|min:0|max:100',
            'jumlah_kolam_aktif' => 'nullable|integer|min:0',
            'jumlah_kolam_total' => 'nullable|integer|min:0',
            'kendala' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $monitoring->update($validated);

        return redirect()->route('monitoring.show', $monitoring->kdmp_id)
            ->with('success', 'Laporan monitoring berhasil diperbarui!');
    }

    /**
     * Hapus laporan monitoring
     */
    public function destroy(MonitoringRecord $monitoring)
    {
        $kdmpId = $monitoring->kdmp_id;
        $monitoring->delete();

        return redirect()->route('monitoring.show', $kdmpId)
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('monitoring.pdf-detail', compact('kdmp', 'records'))
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('monitoring.pdf', compact('kdmpList', 'tahun', 'bulan', 'bulanList', 'search'))->setPaper('a4', 'landscape');
        return $pdf->stream('Data_Lokasi_Budidaya_' . ($bulanList[$bulan] ?? $bulan) . '_' . $tahun . '.pdf');
    }
}
