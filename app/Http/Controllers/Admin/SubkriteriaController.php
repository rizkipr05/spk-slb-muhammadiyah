<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Http\Request;

class SubkriteriaController extends Controller
{
    public function index(Kriteria $kriteria)
    {
        $subkriterias = $kriteria->subkriterias()->orderBy('nilai', 'desc')->get();
        return view('admin.subkriteria.index', compact('kriteria', 'subkriterias'));
    }

    public function create(Kriteria $kriteria)
    {
        return view('admin.subkriteria.create', compact('kriteria'));
    }

    public function store(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nilai' => 'required|integer',
        ]);

        $kriteria->subkriterias()->create($validated);
        return redirect()->route('kriteria.subkriteria.index', $kriteria->id)->with('success', 'Subkriteria berhasil ditambahkan.');
    }

    public function edit(Kriteria $kriteria, Subkriteria $subkriterium)
    {
        return view('admin.subkriteria.edit', ['kriteria' => $kriteria, 'subkriteria' => $subkriterium]);
    }

    public function update(Request $request, Kriteria $kriteria, Subkriteria $subkriterium)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nilai' => 'required|integer',
        ]);

        $subkriterium->update($validated);
        return redirect()->route('kriteria.subkriteria.index', $kriteria->id)->with('success', 'Subkriteria berhasil diperbarui.');
    }

    public function destroy(Kriteria $kriteria, Subkriteria $subkriterium)
    {
        $subkriterium->delete();
        return redirect()->route('kriteria.subkriteria.index', $kriteria->id)->with('success', 'Subkriteria berhasil dihapus.');
    }
}
