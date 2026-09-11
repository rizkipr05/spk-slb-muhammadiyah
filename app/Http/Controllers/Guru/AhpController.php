<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AhpController extends Controller
{
    private function calculateAHP()
    {
        $kriterias = \App\Models\Kriteria::orderBy('id', 'asc')->get();
        if ($kriterias->isEmpty()) {
            return null;
        }

        // 1. Calculate Criteria Weights
        $matrix = [];
        $colSums = [];
        foreach ($kriterias as $k1) {
            $colSums[$k1->id] = 0;
            foreach ($kriterias as $k2) {
                // Default to 1 if comparison not found
                $val = 1;
                if ($k1->id !== $k2->id) {
                    $comp = \App\Models\KriteriaComparison::where('kriteria1_id', $k1->id)->where('kriteria2_id', $k2->id)->first();
                    if ($comp) {
                        $val = $comp->nilai;
                    } else {
                        $reverse = \App\Models\KriteriaComparison::where('kriteria1_id', $k2->id)->where('kriteria2_id', $k1->id)->first();
                        if ($reverse && $reverse->nilai > 0) {
                            $val = 1 / $reverse->nilai;
                        }
                    }
                }
                $matrix[$k1->id][$k2->id] = $val;
                $colSums[$k2->id] = ($colSums[$k2->id] ?? 0) + $val;
            }
        }

        $weights = [];
        foreach ($kriterias as $k1) {
            $rowSum = 0;
            foreach ($kriterias as $k2) {
                $normalizedVal = $colSums[$k2->id] > 0 ? $matrix[$k1->id][$k2->id] / $colSums[$k2->id] : 0;
                $rowSum += $normalizedVal;
            }
            $weights[$k1->id] = $rowSum / count($kriterias);
        }

        // 2. Evaluate Siswas (Alternatives)
        $siswas = \App\Models\Siswa::where('guru_id', auth()->id())->with('penilaians')->get()->filter(function($siswa) {
            return $siswa->penilaians->count() > 0;
        });

        // Sum of Penilaian per Kriteria for normalization
        $kriteriaValueSums = [];
        foreach ($kriterias as $k) {
            $kriteriaValueSums[$k->id] = 0;
        }

        foreach ($siswas as $siswa) {
            foreach ($siswa->penilaians as $penilaian) {
                $kriteriaValueSums[$penilaian->kriteria_id] += $penilaian->nilai;
            }
        }

        // Calculate Final Scores
        $results = [];
        foreach ($siswas as $siswa) {
            $finalScore = 0;
            $details = [];
            foreach ($kriterias as $k) {
                $penilaian = $siswa->penilaians->where('kriteria_id', $k->id)->first();
                $nilai = $penilaian ? $penilaian->nilai : 0;
                
                $normalizedNilai = $kriteriaValueSums[$k->id] > 0 ? $nilai / $kriteriaValueSums[$k->id] : 0;
                
                $weightedScore = $normalizedNilai * $weights[$k->id];
                $finalScore += $weightedScore;
                
                $details[$k->id] = [
                    'asli' => $nilai,
                    'normalisasi' => $normalizedNilai,
                    'terbobot' => $weightedScore
                ];
            }
            
            $results[] = [
                'siswa' => $siswa,
                'details' => $details,
                'score' => $finalScore
            ];
        }

        usort($results, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return [
            'kriterias' => $kriterias,
            'weights' => $weights,
            'results' => $results
        ];
    }

    public function index()
    {
        $data = $this->calculateAHP();
        if (!$data) {
            return view('guru.rekomendasi.index')->with('error', 'Kriteria belum diatur.');
        }

        return view('guru.rekomendasi.index', $data);
    }

    public function simpan(Request $request)
    {
        $request->validate(['judul' => 'required|string|max:255']);
        
        $data = $this->calculateAHP();
        if (!$data) {
            return back()->with('error', 'Tidak dapat menyimpan hasil yang kosong.');
        }

        \App\Models\LaporanAhp::create([
            'judul' => $request->judul,
            'data_hasil' => $data,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('guru.rekomendasi.riwayat')->with('success', 'Riwayat hasil berhasil disimpan!');
    }

    public function riwayat()
    {
        $laporans = \App\Models\LaporanAhp::with('author')->latest()->get();
        return view('guru.rekomendasi.riwayat', compact('laporans'));
    }

    public function cetak($id)
    {
        $laporan = \App\Models\LaporanAhp::findOrFail($id);
        $data = $laporan->data_hasil;
        return view('guru.rekomendasi.cetak', compact('laporan', 'data'));
    }
}
