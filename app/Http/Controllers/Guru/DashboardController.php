<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = \App\Models\Siswa::where('guru_id', auth()->id())->count();
        $siswaDinilai = \App\Models\Siswa::where('guru_id', auth()->id())->has('penilaians')->count();
        $siswaBelumDinilai = $totalSiswa - $siswaDinilai;

        $labelsStatus = ['Sudah Dinilai', 'Belum Dinilai'];
        $dataStatus = [$siswaDinilai, $siswaBelumDinilai];

        $kriterias = \App\Models\Kriteria::withAvg(['penilaians as avg_nilai' => function($q) {
            $q->whereHas('siswa', function($qSiswa) {
                $qSiswa->where('guru_id', auth()->id());
            });
        }], 'nilai')->get();
        $labelsKriteria = $kriterias->pluck('kode')->toArray();
        $dataKriteriaAvg = $kriterias->pluck('avg_nilai')->map(function($v) { return round($v, 2); })->toArray();
        $pengumumans = \App\Models\Pengumuman::latest()->take(5)->get();

        return view('guru.dashboard', compact('labelsStatus', 'dataStatus', 'labelsKriteria', 'dataKriteriaAvg', 'totalSiswa', 'siswaDinilai', 'pengumumans'));
    }
}
