<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $aktif = \App\Models\Pengumuman::where('status_aktif', true)->count();
        $nonaktif = \App\Models\Pengumuman::where('status_aktif', false)->count();
        $labelsPengumuman = ['Aktif', 'Non-Aktif'];
        $dataPengumuman = [$aktif, $nonaktif];

        $siswas = \App\Models\Siswa::withSum('penilaians as total_nilai_mentah', 'nilai')
                        ->orderByDesc('total_nilai_mentah')
                        ->take(5)
                        ->get();
        
        $labelsTopSiswa = $siswas->pluck('nama')->toArray();
        $dataTopSiswa = $siswas->pluck('total_nilai_mentah')->toArray();

        return view('kepsek.dashboard', compact('labelsPengumuman', 'dataPengumuman', 'labelsTopSiswa', 'dataTopSiswa', 'aktif', 'nonaktif'));
    }
}
