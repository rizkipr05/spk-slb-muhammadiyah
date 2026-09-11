<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::orderBy('kode', 'asc')->get();
        return view('admin.kriteria.index', compact('kriterias'));
    }

    public function create()
    {
        return view('admin.kriteria.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:kriterias|max:50',
            'nama' => 'required|string|max:255',
        ]);

        Kriteria::create($validated);
        return redirect()->route('kriteria.index')->with('success', 'Data kriteria berhasil ditambahkan.');
    }

    public function edit(Kriteria $kriteria)
    {
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:kriterias,kode,' . $kriteria->id,
            'nama' => 'required|string|max:255',
        ]);

        $kriteria->update($validated);
        return redirect()->route('kriteria.index')->with('success', 'Data kriteria berhasil diperbarui.');
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();
        return redirect()->route('kriteria.index')->with('success', 'Data kriteria berhasil dihapus.');
    }
}
