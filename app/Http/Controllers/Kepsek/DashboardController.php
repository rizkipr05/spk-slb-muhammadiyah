<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        $aktif = Pengumuman::where('status_aktif', true)->count();
        $nonaktif = Pengumuman::where('status_aktif', false)->count();
        $labelsPengumuman = ['Aktif', 'Non-Aktif'];
        $dataPengumuman = [$aktif, $nonaktif];

        $siswas = Siswa::withSum('penilaians as total_nilai_mentah', 'nilai')
            ->orderByDesc('total_nilai_mentah')
            ->take(5)
            ->get();

        $labelsTopSiswa = $siswas->pluck('nama')->toArray();
        $dataTopSiswa = $siswas->pluck('total_nilai_mentah')->toArray();

        return view('kepsek.dashboard', compact('labelsPengumuman', 'dataPengumuman', 'labelsTopSiswa', 'dataTopSiswa', 'aktif', 'nonaktif'));
    }
}
