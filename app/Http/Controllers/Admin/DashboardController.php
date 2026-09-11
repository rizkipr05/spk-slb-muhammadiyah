<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $kebutuhanStats = \App\Models\Siswa::selectRaw('jenis_kebutuhan_khusus, count(*) as total')
                                ->groupBy('jenis_kebutuhan_khusus')
                                ->get();
        $labelsKebutuhan = $kebutuhanStats->pluck('jenis_kebutuhan_khusus')->map(function($k) { return $k ?: 'Tidak Ada Data'; })->toArray();
        $dataKebutuhan = $kebutuhanStats->pluck('total')->toArray();

        $roleStats = \App\Models\User::selectRaw('role, count(*) as total')
                         ->groupBy('role')
                         ->get();
        $labelsRole = $roleStats->pluck('role')->map('ucfirst')->toArray();
        $dataRole = $roleStats->pluck('total')->toArray();

        $totalSiswa = \App\Models\Siswa::count();
        $totalUser = \App\Models\User::count();
        $totalKriteria = \App\Models\Kriteria::count();

        return view('admin.dashboard', compact('labelsKebutuhan', 'dataKebutuhan', 'labelsRole', 'dataRole', 'totalSiswa', 'totalUser', 'totalKriteria'));
    }
}
