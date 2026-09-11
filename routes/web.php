<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Kepsek\DashboardController as KepsekDashboard;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\AlternatifController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\SubkriteriaController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    Route::resource('siswa', SiswaController::class);
    Route::resource('kriteria', KriteriaController::class)->parameters([
        'kriteria' => 'kriteria'
    ]);
    Route::resource('kriteria.subkriteria', SubkriteriaController::class)->parameters([
        'kriteria' => 'kriteria',
        'subkriteria' => 'subkriterium'
    ]);
    Route::resource('alternatif', AlternatifController::class);
    Route::resource('pengguna', UserController::class);
});

use App\Http\Controllers\Guru\PenilaianController;
use App\Http\Controllers\Guru\AhpController;

Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('guru.dashboard');
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('guru.penilaian.index');
    Route::get('/penilaian/{siswa}/edit', [PenilaianController::class, 'edit'])->name('guru.penilaian.edit');
    Route::put('/penilaian/{siswa}', [PenilaianController::class, 'update'])->name('guru.penilaian.update');
    Route::delete('/penilaian/{siswa}', [PenilaianController::class, 'destroy'])->name('guru.penilaian.destroy');
    Route::get('/rekomendasi', [AhpController::class, 'index'])->name('guru.rekomendasi.index');
    Route::post('/rekomendasi/simpan', [AhpController::class, 'simpan'])->name('guru.rekomendasi.simpan');
    Route::get('/rekomendasi/riwayat', [AhpController::class, 'riwayat'])->name('guru.rekomendasi.riwayat');
    Route::get('/rekomendasi/riwayat/{id}/cetak', [AhpController::class, 'cetak'])->name('guru.rekomendasi.cetak');
});

use App\Http\Controllers\ProfileController;

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

use App\Http\Controllers\Kepsek\LaporanController;
use App\Http\Controllers\Kepsek\PengumumanController;

Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->group(function () {
    Route::get('/dashboard', [KepsekDashboard::class, 'index'])->name('kepsek.dashboard');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('kepsek.laporan.index');
    Route::resource('pengumuman', PengumumanController::class)->except(['show'])->names('kepsek.pengumuman');
});
