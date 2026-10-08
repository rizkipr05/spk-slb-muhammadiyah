<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\LaporanAhp;

class LaporanController extends Controller
{
    public function index()
    {
        $latestLaporan = LaporanAhp::with('author')->latest()->first();
        $riwayatLaporans = LaporanAhp::with('author')->latest()->get();

        return view('kepsek.laporan.index', compact('latestLaporan', 'riwayatLaporans'));
    }

    public function cetak($id)
    {
        $laporan = LaporanAhp::findOrFail($id);
        $data = $laporan->data_hasil;

        return view('guru.rekomendasi.cetak', compact('laporan', 'data'));
    }
}
