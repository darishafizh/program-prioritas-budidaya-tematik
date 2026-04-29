<?php

namespace App\Http\Controllers;

use App\Models\Kdmp;
use App\Models\ProgresFisikRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProgresFisikController extends Controller
{
    /**
     * Dashboard progres fisik — semua lokasi KDMP
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        $search = $request->get('search');

        // Ambil semua KDMP beserta record progres fisik terakhir
        $query = Kdmp::with([
            'progresFisikRecords' => fn($q) => $q->orderBy('tahun', 'desc')->orderBy('bulan', 'desc'),
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kdkmp', 'like', "%$search%")
                    ->orWhere('kabupaten', 'like', "%$search%")
                    ->orWhere('provinsi', 'like', "%$search%");
            });
        }

        $kdmpList = $query->orderBy('no')->get();

        // Statistik ringkasan
        $allRecords = ProgresFisikRecord::where('tahun', $tahun)->where('bulan', $bulan)->get();

        $stats = [
            'total_kdmp' => Kdmp::count(),
            'sudah_lapor' => $allRecords->count(),
            'selesai' => $allRecords->filter(fn($r) => $r->average_progress >= 100)->count(),
            'berjalan' => $allRecords->filter(fn($r) => $r->average_progress >= 50 && $r->average_progress < 100)->count(),
            'awal' => $allRecords->filter(fn($r) => $r->average_progress > 0 && $r->average_progress < 50)->count(),
            'rata_rata' => $allRecords->count() > 0 ? round($allRecords->avg(fn($r) => $r->average_progress), 1) : 0,
        ];

        $tahunList = range(2024, (int) date('Y') + 1);
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('progres-fisik.index', compact(
            'kdmpList', 'stats', 'tahun', 'bulan', 'tahunList', 'bulanList', 'search'
        ));
    }

    /**
     * Detail progres fisik per KDMP — riwayat semua periode
     */
    public function show(Kdmp $kdmp)
    {
        $records = ProgresFisikRecord::where('kdmp_id', $kdmp->id)
            ->with('user')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Data chart progres per komponen per periode
        $chartData = $records->sortBy(function ($r) {
            return $r->tahun * 100 + $r->bulan;
        })->values()->map(function ($r) use ($bulanList) {
            return [
                'label' => ($bulanList[$r->bulan] ?? $r->bulan) . ' ' . $r->tahun,
                'bangunan' => $r->progres_bangunan,
                'kolam' => $r->progres_kolam,
                'listrik' => $r->progres_listrik,
                'air' => $r->progres_air,
                'aerasi' => $r->progres_aerasi,
                'rata_rata' => $r->average_progress,
            ];
        });

        return view('progres-fisik.show', compact('kdmp', 'records', 'chartData', 'bulanList'));
    }

    /**
     * Form input progres fisik baru
     */
    public function create(Request $request)
    {
        $kdmpId = $request->get('kdmp_id');
        $kdmpList = Kdmp::orderBy('no')->get(['id', 'no', 'nama_kdkmp', 'kabupaten', 'provinsi']);
        $kdmpSelected = $kdmpId ? Kdmp::find($kdmpId) : null;

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $tahunList = range(2024, (int) date('Y') + 1);

        return view('progres-fisik.create', compact('kdmpList', 'kdmpSelected', 'bulanList', 'tahunList'));
    }

    /**
     * Simpan record progres fisik
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kdmp_id' => 'required|exists:kdmp,id',
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2024',
            'progres_bangunan' => 'required|integer|between:0,100',
            'progres_kolam' => 'required|integer|between:0,100',
            'progres_listrik' => 'required|integer|between:0,100',
            'progres_air' => 'required|integer|between:0,100',
            'progres_aerasi' => 'required|integer|between:0,100',
            'kendala' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto_sebelum' => 'nullable|array',
            'foto_sebelum.*' => 'image|mimes:jpg,jpeg,png',
            'foto_sesudah' => 'nullable|array',
            'foto_sesudah.*' => 'image|mimes:jpg,jpeg,png',
        ]);

        // Validasi total ukuran semua file upload maks 2MB
        $totalSize = 0;
        foreach (['foto_sebelum', 'foto_sesudah'] as $field) {
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $totalSize += $file->getSize();
                }
            }
        }
        if ($totalSize > 2 * 1024 * 1024) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['foto' => 'Total ukuran semua file foto tidak boleh melebihi 2MB. Ukuran saat ini: ' . round($totalSize / 1024 / 1024, 2) . 'MB.']);
        }

        $validated['user_id'] = Auth::id();

        // Cek duplikasi
        $existing = ProgresFisikRecord::where('kdmp_id', $validated['kdmp_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['periode' => 'Data progres fisik untuk KDMP ini pada periode tersebut sudah ada. Gunakan edit untuk memperbarui.']);
        }

        // Handle foto upload
        $validated['foto_sebelum'] = $this->uploadFotos($request, 'foto_sebelum');
        $validated['foto_sesudah'] = $this->uploadFotos($request, 'foto_sesudah');

        ProgresFisikRecord::create($validated);

        return redirect()->route('progres-fisik.index', ['highlight' => $validated['kdmp_id']])
            ->with('success', 'Data progres fisik berhasil disimpan!');
    }

    /**
     * Form edit record progres fisik
     */
    public function edit(ProgresFisikRecord $record)
    {
        $record->load('kdmp');

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $tahunList = range(2024, (int) date('Y') + 1);

        return view('progres-fisik.edit', compact('record', 'bulanList', 'tahunList'));
    }

    /**
     * Update record progres fisik
     */
    public function update(Request $request, ProgresFisikRecord $record)
    {
        $validated = $request->validate([
            'progres_bangunan' => 'required|integer|between:0,100',
            'progres_kolam' => 'required|integer|between:0,100',
            'progres_listrik' => 'required|integer|between:0,100',
            'progres_air' => 'required|integer|between:0,100',
            'progres_aerasi' => 'required|integer|between:0,100',
            'kendala' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto_sebelum' => 'nullable|array',
            'foto_sebelum.*' => 'image|mimes:jpg,jpeg,png',
            'foto_sesudah' => 'nullable|array',
            'foto_sesudah.*' => 'image|mimes:jpg,jpeg,png',
            'hapus_foto_sebelum' => 'nullable|array',
            'hapus_foto_sebelum.*' => 'integer',
            'hapus_foto_sesudah' => 'nullable|array',
            'hapus_foto_sesudah.*' => 'integer',
        ]);

        // Validasi total ukuran semua file upload maks 2MB
        $totalSize = 0;
        foreach (['foto_sebelum', 'foto_sesudah'] as $field) {
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $totalSize += $file->getSize();
                }
            }
        }
        if ($totalSize > 2 * 1024 * 1024) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['foto' => 'Total ukuran semua file foto tidak boleh melebihi 2MB. Ukuran saat ini: ' . round($totalSize / 1024 / 1024, 2) . 'MB.']);
        }

        // Handle foto sebelum
        $validated['foto_sebelum'] = $this->handleFotoUpdate(
            $record->foto_sebelum ?? [],
            $request, 'foto_sebelum', 'hapus_foto_sebelum'
        );

        // Handle foto sesudah
        $validated['foto_sesudah'] = $this->handleFotoUpdate(
            $record->foto_sesudah ?? [],
            $request, 'foto_sesudah', 'hapus_foto_sesudah'
        );

        // Remove hapus_foto keys from validated
        unset($validated['hapus_foto_sebelum'], $validated['hapus_foto_sesudah']);

        $record->update($validated);

        return redirect()->route('progres-fisik.show', $record->kdmp_id)
            ->with('success', 'Data progres fisik berhasil diperbarui!');
    }

    /**
     * Upload array of foto files and return paths
     */
    private function uploadFotos(Request $request, string $fieldName): ?array
    {
        $paths = [];
        if ($request->hasFile($fieldName)) {
            foreach ($request->file($fieldName) as $file) {
                $paths[] = $file->store('progres-fisik-foto', 'public');
            }
        }
        return !empty($paths) ? $paths : null;
    }

    /**
     * Handle foto update: delete checked, add new uploads
     */
    private function handleFotoUpdate(array $existing, Request $request, string $uploadField, string $deleteField): ?array
    {
        // Delete checked photos
        if ($request->has($deleteField)) {
            foreach ($request->input($deleteField) as $idx) {
                if (isset($existing[$idx])) {
                    Storage::disk('public')->delete($existing[$idx]);
                    unset($existing[$idx]);
                }
            }
            $existing = array_values($existing);
        }

        // Add new uploads
        if ($request->hasFile($uploadField)) {
            foreach ($request->file($uploadField) as $file) {
                $existing[] = $file->store('progres-fisik-foto', 'public');
            }
        }

        return !empty($existing) ? $existing : null;
    }

    /**
     * Hapus record progres fisik
     */
    public function destroy(ProgresFisikRecord $record)
    {
        $kdmpId = $record->kdmp_id;
        $record->delete();

        return redirect()->route('progres-fisik.show', $kdmpId)
            ->with('success', 'Data progres fisik telah dihapus.');
    }

    /**
     * Export PDF seluruh lokasi
     */
    public function exportPdf(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));

        $kdmpList = Kdmp::with([
            'progresFisikRecords' => fn($q) => $q->where('tahun', $tahun)->where('bulan', $bulan),
        ])->orderBy('no')->get();

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('progres-fisik.pdf', compact('kdmpList', 'tahun', 'bulan', 'bulanList'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Progres_Fisik_' . ($bulanList[$bulan] ?? $bulan) . '_' . $tahun . '.pdf');
    }

    /**
     * Export PDF per lokasi KDMP
     */
    public function exportPdfDetail(Kdmp $kdmp)
    {
        $records = ProgresFisikRecord::where('kdmp_id', $kdmp->id)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('progres-fisik.pdf-detail', compact('kdmp', 'records', 'bulanList'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Progres_Fisik_' . str_replace(' ', '_', $kdmp->nama_kdkmp) . '.pdf');
    }
}
