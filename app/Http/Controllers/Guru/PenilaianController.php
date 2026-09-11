<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        $siswas = Siswa::where('guru_id', auth()->id())->with('penilaians')->latest()->get();
        return view('guru.penilaian.index', compact('siswas'));
    }

    public function edit(Siswa $siswa)
    {
        $kriterias = Kriteria::with('subkriterias')->orderBy('kode', 'asc')->get();
        // Create an associative array of kriteria_id => nilai for the current siswa
        $penilaianSiswa = $siswa->penilaians->pluck('nilai', 'kriteria_id')->toArray();
        return view('guru.penilaian.edit', compact('siswa', 'kriterias', 'penilaianSiswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'kriteria' => 'required|array',
            'kriteria.*' => 'required|numeric'
        ]);

        // Delete old assessments to replace with new ones
        $siswa->penilaians()->delete();

        foreach ($request->kriteria as $kriteria_id => $nilai) {
            $siswa->penilaians()->create([
                'kriteria_id' => $kriteria_id,
                'nilai' => $nilai
            ]);
        }

        return redirect()->route('guru.penilaian.index')->with('success', 'Penilaian siswa berhasil disimpan.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->penilaians()->delete();
        return redirect()->route('guru.penilaian.index')->with('success', 'Data penilaian berhasil di-reset.');
    }
}
