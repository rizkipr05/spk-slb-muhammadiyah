<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\KriteriaComparison;
use App\Models\LaporanAhp;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AhpController extends Controller
{
    private function calculateAHP()
    {
        $kriterias = Kriteria::orderBy('kode', 'asc')->get();
        if ($kriterias->isEmpty()) {
            return null;
        }

        // Nilai perbandingan berpasangan default dari Hasil Kuesioner (K1/C1: JKK, K2/C2: KA, K3/C3: KK, K4/C4: KS, K5/C5: KSO)
        $defaultComparisons = [
            'C1' => ['C2' => 1.8378, 'C3' => 2.4166, 'C4' => 4.3734, 'C5' => 5.5106],
            'C2' => ['C3' => 1.3819, 'C4' => 2.4166, 'C5' => 3.5652],
            'C3' => ['C4' => 1.5874, 'C5' => 2.4166],
            'C4' => ['C5' => 1.4471],
            'K1' => ['K2' => 1.8378, 'K3' => 2.4166, 'K4' => 4.3734, 'K5' => 5.5106],
            'K2' => ['K3' => 1.3819, 'K4' => 2.4166, 'K5' => 3.5652],
            'K3' => ['K4' => 1.5874, 'K5' => 2.4166],
            'K4' => ['K5' => 1.4471],
        ];

        // Fallback perbandingan berdasarkan urutan posisi kriteria (0 s/d 4)
        $defaultByIndex = [
            0 => [1 => 1.8378, 2 => 2.4166, 3 => 4.3734, 4 => 5.5106],
            1 => [2 => 1.3819, 3 => 2.4166, 4 => 3.5652],
            2 => [3 => 1.5874, 4 => 2.4166],
            3 => [4 => 1.4471],
        ];

        $kriteriasIndexed = $kriterias->values();
        $indexMap = [];
        foreach ($kriteriasIndexed as $idx => $k) {
            $indexMap[$k->id] = $idx;
        }

        // 1. Matriks Perbandingan Berpasangan
        $matrix = [];
        $colSums = [];
        foreach ($kriterias as $k) {
            $colSums[$k->id] = 0.0;
        }

        foreach ($kriterias as $k1) {
            foreach ($kriterias as $k2) {
                $val = 1.0;
                if ($k1->id !== $k2->id) {
                    $comp = KriteriaComparison::where('kriteria1_id', $k1->id)->where('kriteria2_id', $k2->id)->first();
                    if ($comp && $comp->nilai > 0) {
                        $val = (float) $comp->nilai;
                    } else {
                        $reverse = KriteriaComparison::where('kriteria1_id', $k2->id)->where('kriteria2_id', $k1->id)->first();
                        if ($reverse && $reverse->nilai > 0) {
                            $val = 1.0 / (float) $reverse->nilai;
                        } else {
                            $code1 = strtoupper($k1->kode);
                            $code2 = strtoupper($k2->kode);
                            $norm1 = str_replace('K', 'C', $code1);
                            $norm2 = str_replace('K', 'C', $code2);

                            if (isset($defaultComparisons[$code1][$code2])) {
                                $val = (float) $defaultComparisons[$code1][$code2];
                            } elseif (isset($defaultComparisons[$code2][$code1])) {
                                $val = 1.0 / (float) $defaultComparisons[$code2][$code1];
                            } elseif (isset($defaultComparisons[$norm1][$norm2])) {
                                $val = (float) $defaultComparisons[$norm1][$norm2];
                            } elseif (isset($defaultComparisons[$norm2][$norm1])) {
                                $val = 1.0 / (float) $defaultComparisons[$norm2][$norm1];
                            } else {
                                $i = $indexMap[$k1->id] ?? null;
                                $j = $indexMap[$k2->id] ?? null;
                                if ($i !== null && $j !== null && count($kriterias) === 5) {
                                    if (isset($defaultByIndex[$i][$j])) {
                                        $val = (float) $defaultByIndex[$i][$j];
                                    } elseif (isset($defaultByIndex[$j][$i])) {
                                        $val = 1.0 / (float) $defaultByIndex[$j][$i];
                                    }
                                }
                            }

                            // Simpan otomatis nilai perbandingan ke DB jika belum ada
                            if ($val >= 1.0 && $k1->id !== $k2->id) {
                                KriteriaComparison::updateOrCreate(
                                    ['kriteria1_id' => $k1->id, 'kriteria2_id' => $k2->id],
                                    ['nilai' => $val]
                                );
                            }
                        }
                    }
                }
                $matrix[$k1->id][$k2->id] = $val;
                $colSums[$k2->id] = ($colSums[$k2->id] ?? 0.0) + $val;
            }
        }

        // 2. Matriks Nilai Kriteria / Normalisasi & Bobot Prioritas
        $normalizedMatrix = [];
        $normRowSums = [];
        $weights = [];
        $n = count($kriterias);
        foreach ($kriterias as $k1) {
            $rowSum = 0.0;
            foreach ($kriterias as $k2) {
                $normalizedVal = $colSums[$k2->id] > 0 ? $matrix[$k1->id][$k2->id] / $colSums[$k2->id] : 0.0;
                $normalizedMatrix[$k1->id][$k2->id] = $normalizedVal;
                $rowSum += $normalizedVal;
            }
            $normRowSums[$k1->id] = $rowSum;
            $weights[$k1->id] = $n > 0 ? $rowSum / $n : 0.0;
        }

        // 3. Matriks Penjumlahan Setiap Baris (Matrix x Weight)
        $rowMultMatrix = [];
        $rowMultSums = [];
        foreach ($kriterias as $k1) {
            $sum = 0.0;
            foreach ($kriterias as $k2) {
                $val = $weights[$k2->id] * $matrix[$k1->id][$k2->id];
                $rowMultMatrix[$k1->id][$k2->id] = $val;
                $sum += $val;
            }
            $rowMultSums[$k1->id] = $sum;
        }

        // 4. Perhitungan Rasio Konsistensi (Consistency Ratio / CR)
        $lambdaPerKriteria = [];
        foreach ($kriterias as $k) {
            $lambdaPerKriteria[$k->id] = $weights[$k->id] > 0 ? $rowMultSums[$k->id] / $weights[$k->id] : 0.0;
        }
        $sumLambda = array_sum($lambdaPerKriteria);
        $lambdaMax = $n > 0 ? $sumLambda / $n : 0.0;
        $ci = $n > 1 ? ($lambdaMax - $n) / ($n - 1) : 0.0;

        $riTable = [
            1 => 0.0,
            2 => 0.0,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
        ];
        $ri = $riTable[$n] ?? 1.12;
        $cr = $ri > 0 ? $ci / $ri : 0.0;
        $isConsistent = ($cr <= 0.1);

        // 5. Evaluasi Siswa (Alternatif)
        $siswas = Siswa::where(function ($q) {
            $q->where('guru_id', auth()->id())
                ->orWhereNull('guru_id');
        })->with('penilaians')->get()->filter(function ($siswa) {
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
                    'terbobot' => $weightedScore,
                ];
            }

            $results[] = [
                'siswa' => $siswa,
                'details' => $details,
                'score' => $finalScore,
            ];
        }

        usort($results, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return [
            'kriterias' => $kriterias,
            'matrix' => $matrix,
            'colSums' => $colSums,
            'normalizedMatrix' => $normalizedMatrix,
            'normRowSums' => $normRowSums,
            'weights' => $weights,
            'rowMultMatrix' => $rowMultMatrix,
            'rowMultSums' => $rowMultSums,
            'lambdaPerKriteria' => $lambdaPerKriteria,
            'sumLambda' => $sumLambda,
            'lambdaMax' => $lambdaMax,
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
            'isConsistent' => $isConsistent,
            'results' => $results,
        ];
    }

    public function index()
    {
        $data = $this->calculateAHP();
        if (! $data) {
            return view('guru.rekomendasi.index')->with('error', 'Kriteria belum diatur.');
        }

        return view('guru.rekomendasi.index', $data);
    }

    public function simpan(Request $request)
    {
        $request->validate(['judul' => 'required|string|max:255']);

        $data = $this->calculateAHP();
        if (! $data) {
            return back()->with('error', 'Tidak dapat menyimpan hasil yang kosong.');
        }

        LaporanAhp::create([
            'judul' => $request->judul,
            'data_hasil' => $data,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('guru.rekomendasi.riwayat')->with('success', 'Riwayat hasil berhasil disimpan!');
    }

    public function riwayat()
    {
        $laporans = LaporanAhp::with('author')->latest()->get();

        return view('guru.rekomendasi.riwayat', compact('laporans'));
    }

    public function cetak($id)
    {
        $laporan = LaporanAhp::findOrFail($id);
        $data = $laporan->data_hasil;

        return view('guru.rekomendasi.cetak', compact('laporan', 'data'));
    }
}
