<?php

namespace App\Http\Controllers;

use App\Models\Kdmp;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KdmpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kdmpLocations = Kdmp::orderBy('id', 'asc')->get();
        return view('kdmp.index', compact('kdmpLocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
        $provinces = Province::orderBy('name')->get();
        return view('kdmp.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'provinsi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'desa' => 'nullable|string|max:255',
            'nama_kdkmp' => 'required|string|max:255',
            'komoditas' => 'nullable|string|max:255',
            'ketua_anggota' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'nama_penyuluh' => 'nullable|string|max:255',
            'no_hp_penyuluh' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
            'long' => 'nullable|string|max:255',
        ]);

        Kdmp::create($validated);

        return redirect()->route('kdmp.index')
            ->with('success', 'Data KDMP berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kdmp $kdmp)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $provinces = Province::orderBy('name')->get();
        return view('kdmp.edit', compact('kdmp', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kdmp $kdmp)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'provinsi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'desa' => 'nullable|string|max:255',
            'nama_kdkmp' => 'required|string|max:255',
            'komoditas' => 'nullable|string|max:255',
            'ketua_anggota' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'nama_penyuluh' => 'nullable|string|max:255',
            'no_hp_penyuluh' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
            'long' => 'nullable|string|max:255',
        ]);

        $kdmp->update($validated);

        return redirect()->route('kdmp.index')
            ->with('success', 'Data KDMP berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kdmp $kdmp)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $kdmp->delete();

        return redirect()->route('kdmp.index')
            ->with('success', 'Data KDMP berhasil dihapus!');
    }
}
