@extends('layouts.app')

@section('title', 'Hasil Rekomendasi AHP - SPK Disabilitas')
@section('page_title', 'Hasil Rekomendasi & Perhitungan AHP')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-bar-chart-fill me-2"></i> Bobot Prioritas Kriteria (AHP)</h5>
            </div>
            <div class="card-body">
                @if(isset($error))
                    <div class="alert alert-danger">{{ $error }}</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th>Bobot (Eigen Vector)</th>
                                    <th>Bobot (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kriterias as $k)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $k->kode }}</span></td>
                                    <td class="text-start fw-semibold">{{ $k->nama }}</td>
                                    <td>{{ number_format($weights[$k->id], 4) }}</td>
                                    <td><span class="badge bg-primary px-3 rounded-pill">{{ number_format($weights[$k->id] * 100, 2) }}%</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(!isset($error))
    <div class="col-12">
        <div class="card border-0 shadow-sm border-top border-primary border-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-trophy-fill text-warning me-2"></i> Hasil Akhir Rekomendasi Layanan Pendidikan</h5>
                <div class="action-buttons">
                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#modalSimpanHasil"><i class="bi bi-save me-1"></i> Simpan ke Riwayat</button>
                    <button class="btn btn-sm btn-outline-secondary disable-on-print" onclick="window.print()"><i class="bi bi-printer"></i> Cetak Cepat</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Peringkat</th>
                                <th>Nama Siswa</th>
                                <th>Kebutuhan Khusus</th>
                                @foreach($kriterias as $k)
                                    <th class="text-center" style="font-size: 0.8rem;">{{ $k->kode }}<br><small class="text-muted text-fw-normal">({{ number_format($weights[$k->id]*100,0) }}%)</small></th>
                                @endforeach
                                <th class="text-center text-primary fs-6">Skor Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $index => $row)
                            <tr class="{{ $index === 0 ? 'table-warning' : '' }}">
                                <td class="text-center">
                                    @if($index === 0)
                                        <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; font-weight: bold;">1</div>
                                    @else
                                        <span class="fw-semibold text-muted">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $row['siswa']->nama }}</td>
                                <td>{{ $row['siswa']->jenis_kebutuhan_khusus ?? '-' }}</td>
                                @foreach($kriterias as $k)
                                    <td class="text-center">
                                        <div class="small fw-semibold">{{ $row['details'][$k->id]['asli'] }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">w: {{ number_format($row['details'][$k->id]['terbobot'], 4) }}</div>
                                    </td>
                                @endforeach
                                <td class="text-center">
                                    <span class="badge {{ $index === 0 ? 'bg-warning text-dark fs-6' : 'bg-primary' }} rounded-pill px-3 py-2 shadow-sm">
                                        {{ number_format($row['score'], 4) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ count($kriterias) + 4 }}" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-3"></i>
                                    Data belum memadai.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@if(!isset($error))
@push('modals')
<!-- Modal Simpan Riwayat -->
<div class="modal fade" id="modalSimpanHasil" tabindex="-1" aria-labelledby="modalSimpanHasilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalSimpanHasilLabel"><i class="bi bi-save me-2"></i>Simpan Berkas Laporan AHP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('guru.rekomendasi.simpan') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold">Topik / Judul Laporan</label>
                        <input type="text" name="judul" class="form-control form-control-lg bg-light" required placeholder="Contoh: Hasil Rekomendasi Gelombang I 2026">
                        <small class="text-muted d-block mt-2">Laporan ini nantinya dapat dilihat dan dicetak sewaktu-waktu di masa depan tanpa mengubah data yang sudah berlaku saat ini.</small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endif
@endsection
