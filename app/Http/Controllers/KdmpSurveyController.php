<?php

namespace App\Http\Controllers;

use App\Models\Kdmp;
use App\Models\KdmpSurvey;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KdmpSurveyController extends Controller
{
    /**
     * Display a listing of KDMP surveys.
     */
    public function index(Request $request)
    {
        $query = Kdmp::query();
        
        // Filter by search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kdkmp', 'like', "%{$search}%")
                  ->orWhere('kabupaten', 'like', "%{$search}%")
                  ->orWhere('provinsi', 'like', "%{$search}%")
                  ->orWhere('ketua_anggota', 'like', "%{$search}%")
                  ->orWhere('nama_penyuluh', 'like', "%{$search}%");
            });
        }
        
        // Filter by province
        if ($provinsi = $request->get('provinsi')) {
            $query->where('provinsi', $provinsi);
        }
        
        // Filter by commodity
        if ($komoditas = $request->get('komoditas')) {
            $query->where('komoditas', $komoditas);
        }
        
        $kdmpLocations = $query->orderBy('no', 'asc')->get();
        $provinces = Province::orderBy('name')->pluck('name', 'id');
        
        return view('kdmp.index', compact('kdmpLocations', 'provinces'));
    }

    /**
     * Show the form for creating a new survey.
     */
    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        $kdmpList = Kdmp::orderBy('no')->get(['id', 'no', 'nama_kdkmp', 'desa', 'kabupaten', 'provinsi', 'komoditas', 'long', 'lat']);
        return view('kdmp.create', compact('provinces', 'kdmpList'));
    }

    /**
     * Store a newly created survey.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kdmp_id'     => 'nullable|exists:kdmp,id',
            'verifikator' => 'nullable|string|max:255',
            'responden'   => 'nullable|string|max:255',
            'nama_koperasi' => 'required|string|max:255',
            'kabupaten'   => 'required|string|max:255',
            'provinsi'    => 'required|string|max:255',
            'komoditas'   => 'nullable|in:Lele,Nila',
        ]);

        $validated['user_id'] = Auth::id();

        // Auto-fill koordinat dari master KDMP jika belum diisi
        if (!empty($validated['kdmp_id']) && empty($request->koordinat)) {
            $masterKdmp = Kdmp::find($validated['kdmp_id']);
            if ($masterKdmp && $masterKdmp->lat && $masterKdmp->long) {
                $validated['koordinat'] = $masterKdmp->lat . ', ' . $masterKdmp->long;
            }
        }

        $validated['hambatan_koperasi']   = $request->input('hambatan_koperasi', []);
        $validated['kendala_pembangunan'] = $request->input('kendala_pembangunan', []);
        $validated['tujuan_penjualan']    = $request->input('tujuan_penjualan', []);

        $survey = KdmpSurvey::create($validated);

        return redirect()
            ->route('kdmp.show', $survey)
            ->with('success', 'Kuesioner KDMP berhasil disimpan!');
    }

    /**
     * Display the specified survey.
     */
    public function show(KdmpSurvey $kdmp)
    {
        $kdmp->load('kdmp');
        return view('kdmp.show', compact('kdmp'));
    }

    /**
     * Show the form for editing the survey.
     */
    public function edit(KdmpSurvey $kdmp)
    {
        $provinces = Province::orderBy('name')->get();
        $kdmpList  = Kdmp::orderBy('no')->get(['id', 'no', 'nama_kdkmp', 'desa', 'kabupaten', 'provinsi', 'komoditas', 'long', 'lat']);
        return view('kdmp.edit', compact('kdmp', 'provinces', 'kdmpList'));
    }

    /**
     * Update the specified survey.
     */
    public function update(Request $request, KdmpSurvey $kdmp)
    {
        $validated = $request->validate([
            'kdmp_id'     => 'nullable|exists:kdmp,id',
            'verifikator' => 'nullable|string|max:255',
            'responden'   => 'nullable|string|max:255',
            'nama_koperasi' => 'required|string|max:255',
            'kabupaten'   => 'required|string|max:255',
            'provinsi'    => 'required|string|max:255',
            'komoditas'   => 'nullable|in:Lele,Nila',
        ]);

        $validated['hambatan_koperasi']   = $request->input('hambatan_koperasi', []);
        $validated['kendala_pembangunan'] = $request->input('kendala_pembangunan', []);
        $validated['tujuan_penjualan']    = $request->input('tujuan_penjualan', []);

        $kdmp->update($validated);

        return redirect()
            ->route('kdmp.show', $kdmp)
            ->with('success', 'Kuesioner KDMP berhasil diperbarui!');
    }

    /**
     * Remove the specified survey.
     */
    public function destroy(KdmpSurvey $kdmp)
    {
        $kdmp->delete();
        
        return redirect()
            ->route('kdmp.index')
            ->with('success', 'Kuesioner KDMP berhasil dihapus!');
    }

    /**
     * Export survey to PDF
     */
    public function exportPdf(KdmpSurvey $kdmp)
    {
        // TODO: Implement PDF export
        return view('kdmp.pdf', compact('kdmp'));
    }
}
