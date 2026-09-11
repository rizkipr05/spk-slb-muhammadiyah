<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use Illuminate\Http\Request;

class AlternatifController extends Controller
{
    public function index()
    {
        $alternatifs = Alternatif::orderBy('kode', 'asc')->get();
        return view('admin.alternatif.index', compact('alternatifs'));
    }

    public function create()
    {
        return view('admin.alternatif.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:alternatifs|max:50',
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Alternatif::create($validated);
        return redirect()->route('alternatif.index')->with('success', 'Data Alternatif Layanan berhasil ditambahkan.');
    }

    public function edit(Alternatif $alternatif)
    {
        return view('admin.alternatif.edit', compact('alternatif'));
    }

    public function update(Request $request, Alternatif $alternatif)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:alternatifs,kode,' . $alternatif->id,
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $alternatif->update($validated);
        return redirect()->route('alternatif.index')->with('success', 'Data Alternatif Layanan berhasil diperbarui.');
    }

    public function destroy(Alternatif $alternatif)
    {
        $alternatif->delete();
        return redirect()->route('alternatif.index')->with('success', 'Data Alternatif Layanan berhasil dihapus.');
    }
}
