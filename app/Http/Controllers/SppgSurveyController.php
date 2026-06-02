<?php

namespace App\Http\Controllers;

use App\Models\SppgSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SppgSurveyController extends Controller
{
    /**
     * Display a listing of surveys.
     */
    public function index(Request $request)
    {
        $query = SppgSurvey::with('user')->latest();
        
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_sppg', 'like', "%{$search}%")
                  ->orWhere('kabupaten', 'like', "%{$search}%")
                  ->orWhere('responden', 'like', "%{$search}%");
            });
        }
        
        $surveys = $query->paginate(10);
        
        return view('sppg.index', compact('surveys'));
    }

    /**
     * Show the form for creating a new survey.
     */
    public function create()
    {
        return view('sppg.create');
    }

    /**
     * Store a newly created survey.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'verifikator' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'responden' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'nama_sppg' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'kabupaten' => 'required|string|max:255',
            'tanggal' => 'nullable|date',
            'jam' => 'nullable|date_format:H:i',
            'jumlah_sekolah' => 'nullable|integer|min:0',
            'jumlah_siswa' => 'nullable|integer|min:0',
            'porsi_harian' => 'nullable|integer|min:0',
            'porsi_bulanan' => 'nullable|integer|min:0',
            'kebutuhan_lele' => 'nullable|numeric|min:0',
            'kebutuhan_nila' => 'nullable|numeric|min:0',
            'kebutuhan_lain' => 'nullable|numeric|min:0',
            'anggaran_porsi' => 'nullable|numeric|min:0',
            'volume_kebutuhan' => 'nullable|numeric|min:0',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        // Process checkbox arrays
        $validated['jenis_ikan_prioritas'] = $request->input('jenis_ikan_prioritas', []);
        $validated['ukuran_kondisi'] = $request->input('ukuran_kondisi', []);
        $validated['standar_kualitas'] = $request->input('standar_kualitas', []);
        $validated['sertifikasi'] = $request->input('sertifikasi', []);
        $validated['sumber_prioritas'] = $request->input('sumber_prioritas', []);
        $validated['kendala_pasokan'] = $request->input('kendala_pasokan', []);
        
        $allFields = $request->except(['_token', '_method', 'user_id', 'id']);
        $survey = SppgSurvey::create(array_merge($validated, $allFields));
        
        return redirect()
            ->route('sppg.show', $survey)
            ->with('success', 'Kuesioner SPPG berhasil disimpan!');
    }

    /**
     * Display the specified survey.
     */
    public function show(SppgSurvey $sppg)
    {
        return view('sppg.show', compact('sppg'));
    }

    /**
     * Show the form for editing the survey.
     */
    public function edit(SppgSurvey $sppg)
    {
        if (Auth::user()->role !== 'admin' && $sppg->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat mengubah data milik Anda sendiri.');
        }
        
        return view('sppg.edit', compact('sppg'));
    }

    /**
     * Update the specified survey.
     */
    public function update(Request $request, SppgSurvey $sppg)
    {
        if (Auth::user()->role !== 'admin' && $sppg->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat mengubah data milik Anda sendiri.');
        }
        
        $validated = $request->validate([
            'verifikator' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'responden' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'nama_sppg' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'kabupaten' => 'required|string|max:255',
            'tanggal' => 'nullable|date',
            'jam' => 'nullable|date_format:H:i',
            'jumlah_sekolah' => 'nullable|integer|min:0',
            'jumlah_siswa' => 'nullable|integer|min:0',
            'porsi_harian' => 'nullable|integer|min:0',
            'porsi_bulanan' => 'nullable|integer|min:0',
            'kebutuhan_lele' => 'nullable|numeric|min:0',
            'kebutuhan_nila' => 'nullable|numeric|min:0',
            'kebutuhan_lain' => 'nullable|numeric|min:0',
            'anggaran_porsi' => 'nullable|numeric|min:0',
            'volume_kebutuhan' => 'nullable|numeric|min:0',
        ]);
        
        // Process checkbox arrays
        $validated['jenis_ikan_prioritas'] = $request->input('jenis_ikan_prioritas', []);
        $validated['ukuran_kondisi'] = $request->input('ukuran_kondisi', []);
        $validated['standar_kualitas'] = $request->input('standar_kualitas', []);
        $validated['sertifikasi'] = $request->input('sertifikasi', []);
        $validated['sumber_prioritas'] = $request->input('sumber_prioritas', []);
        $validated['kendala_pasokan'] = $request->input('kendala_pasokan', []);
        
        $allFields = $request->except(['_token', '_method', 'user_id', 'id']);
        $sppg->update(array_merge($validated, $allFields));
        
        return redirect()
            ->route('sppg.show', $sppg)
            ->with('success', 'Kuesioner SPPG berhasil diperbarui!');
    }

    /**
     * Remove the specified survey.
     */
    public function destroy(SppgSurvey $sppg)
    {
        if (Auth::user()->role !== 'admin' && $sppg->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat menghapus data milik Anda sendiri.');
        }
        
        $sppg->delete();
        
        return redirect()
            ->route('sppg.index')
            ->with('success', 'Kuesioner SPPG berhasil dihapus!');
    }
}
