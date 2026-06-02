<?php

namespace App\Http\Controllers;

use App\Models\MasyarakatSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasyarakatSurveyController extends Controller
{
    /**
     * Display a listing of surveys.
     */
    public function index(Request $request)
    {
        $query = MasyarakatSurvey::with('user')->latest();
        
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('responden', 'like', "%{$search}%")
                  ->orWhere('tempat', 'like', "%{$search}%");
            });
        }
        
        $surveys = $query->paginate(10);
        
        return view('masyarakat.index', compact('surveys'));
    }

    /**
     * Show the form for creating a new survey.
     */
    public function create()
    {
        return view('masyarakat.create');
    }

    /**
     * Store a newly created survey.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'verifikator' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'responden' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'tempat' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'jam' => 'nullable|date_format:H:i',
            'umur' => 'nullable|integer|min:1|max:120',
            'pendapatan_ikan' => 'nullable|numeric|min:0',
            'pendapatan_lain' => 'nullable|numeric|min:0',
            'total_pendapatan' => 'nullable|numeric|min:0',
            'likert_q1' => 'nullable|integer|between:1,5',
            'likert_q2' => 'nullable|integer|between:1,5',
            'likert_q3' => 'nullable|integer|between:1,5',
            'likert_q4' => 'nullable|integer|between:1,5',
            'likert_q5' => 'nullable|integer|between:1,5',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        // Add all form fields, excluding user_id to prevent mass assignment overrides
        $allFields = $request->except(['_token', '_method', 'user_id', 'id']);
        $survey = MasyarakatSurvey::create(array_merge($validated, $allFields));
        
        return redirect()
            ->route('masyarakat.show', $survey)
            ->with('success', 'Kuesioner Masyarakat berhasil disimpan!');
    }

    /**
     * Display the specified survey.
     */
    public function show(MasyarakatSurvey $masyarakat)
    {
        return view('masyarakat.show', compact('masyarakat'));
    }

    /**
     * Show the form for editing the survey.
     */
    public function edit(MasyarakatSurvey $masyarakat)
    {
        if (Auth::user()->role !== 'admin' && $masyarakat->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat mengubah data milik Anda sendiri.');
        }
        
        return view('masyarakat.edit', compact('masyarakat'));
    }

    /**
     * Update the specified survey.
     */
    public function update(Request $request, MasyarakatSurvey $masyarakat)
    {
        if (Auth::user()->role !== 'admin' && $masyarakat->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat mengubah data milik Anda sendiri.');
        }
        
        $validated = $request->validate([
            'verifikator' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'responden' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
            'tempat' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'jam' => 'nullable|date_format:H:i',
            'umur' => 'nullable|integer|min:1|max:120',
            'pendapatan_ikan' => 'nullable|numeric|min:0',
            'pendapatan_lain' => 'nullable|numeric|min:0',
            'total_pendapatan' => 'nullable|numeric|min:0',
            'likert_q1' => 'nullable|integer|between:1,5',
            'likert_q2' => 'nullable|integer|between:1,5',
            'likert_q3' => 'nullable|integer|between:1,5',
            'likert_q4' => 'nullable|integer|between:1,5',
            'likert_q5' => 'nullable|integer|between:1,5',
        ]);
        
        $allFields = $request->except(['_token', '_method', 'user_id', 'id']);
        $masyarakat->update(array_merge($validated, $allFields));
        
        return redirect()
            ->route('masyarakat.show', $masyarakat)
            ->with('success', 'Kuesioner Masyarakat berhasil diperbarui!');
    }

    /**
     * Remove the specified survey.
     */
    public function destroy(MasyarakatSurvey $masyarakat)
    {
        if (Auth::user()->role !== 'admin' && $masyarakat->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat menghapus data milik Anda sendiri.');
        }
        
        $masyarakat->delete();
        
        return redirect()
            ->route('masyarakat.index')
            ->with('success', 'Kuesioner Masyarakat berhasil dihapus!');
    }
}
