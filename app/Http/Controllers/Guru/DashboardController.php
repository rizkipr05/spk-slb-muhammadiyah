<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Pengumuman;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::where('guru_id', auth()->id())->count();
        $siswaDinilai = Siswa::where('guru_id', auth()->id())->has('penilaians')->count();
        $siswaBelumDinilai = $totalSiswa - $siswaDinilai;

        $labelsStatus = ['Sudah Dinilai', 'Belum Dinilai'];
        $dataStatus = [$siswaDinilai, $siswaBelumDinilai];

        $kriterias = Kriteria::withAvg(['penilaians as avg_nilai' => function ($q) {
            $q->whereHas('siswa', function ($qSiswa) {
                $qSiswa->where('guru_id', auth()->id());
            });
        }], 'nilai')->get();
        $labelsKriteria = $kriterias->pluck('kode')->toArray();
        $dataKriteriaAvg = $kriterias->pluck('avg_nilai')->map(function ($v) {
            return round($v, 2);
        })->toArray();
        $pengumumans = Pengumuman::latest()->take(5)->get();

        return view('guru.dashboard', compact('labelsStatus', 'dataStatus', 'labelsKriteria', 'dataKriteriaAvg', 'totalSiswa', 'siswaDinilai', 'pengumumans'));
    }
}
