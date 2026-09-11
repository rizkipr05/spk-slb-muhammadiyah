@extends('layouts.app')

@section('title', 'Laporan Rekomendasi - SPK Disabilitas')
@section('page_title', 'Laporan Rekomendasi Layanan Pendidikan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Data Hasil Keputusan (AHP)</span>
        <button class="btn btn-sm btn-success" disabled>
            <i class="bi bi-printer"></i> Cetak PDF
        </button>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-1"></i> Data laporan belum tersedia. Tahap penilaian AHP untuk siswa sedang dalam proses pengembangan oleh Guru.
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Ranking</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Rekomendasi Layanan</th>
                        <th>Nilai Preferensi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data perhitungan.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
