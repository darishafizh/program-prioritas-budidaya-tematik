<?php

namespace App\Http\Controllers;

use App\Models\LokasiBudidaya;
use Illuminate\Http\Request;

class LokasiBudidayaController extends Controller
{
    public function index()
    {
        $data = LokasiBudidaya::latest()->get();
        return view('lokasi-budidaya.index', compact('data'));
    }

    public function create()
    {
        return view('lokasi-budidaya.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_koperasi' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'volume_hasil_panen' => 'required|numeric|min:0',
            'nilai_hasil_panen' => 'required|numeric|min:0',
            'biaya_operasional' => 'required|numeric|min:0',
            'harga_jual_per_kg' => 'required|numeric|min:0',
        ]);

        LokasiBudidaya::create($validated);

        return redirect()->route('lokasi-budidaya.index')
            ->with('success', 'Data lokasi budidaya berhasil ditambahkan.');
    }

    public function edit(LokasiBudidaya $lokasiBudidaya)
    {
        return view('lokasi-budidaya.edit', compact('lokasiBudidaya'));
    }

    public function update(Request $request, LokasiBudidaya $lokasiBudidaya)
    {
        $validated = $request->validate([
            'nama_koperasi' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'volume_hasil_panen' => 'required|numeric|min:0',
            'nilai_hasil_panen' => 'required|numeric|min:0',
            'biaya_operasional' => 'required|numeric|min:0',
            'harga_jual_per_kg' => 'required|numeric|min:0',
        ]);

        $lokasiBudidaya->update($validated);

        return redirect()->route('lokasi-budidaya.index')
            ->with('success', 'Data lokasi budidaya berhasil diperbarui.');
    }

    public function destroy(LokasiBudidaya $lokasiBudidaya)
    {
        $lokasiBudidaya->delete();

        return redirect()->route('lokasi-budidaya.index')
            ->with('success', 'Data lokasi budidaya berhasil dihapus.');
    }
}
