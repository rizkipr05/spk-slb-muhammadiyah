@extends('layouts.app')

@section('title', 'Hasil Rekomendasi AHP - SPK Disabilitas')
@section('page_title', 'Hasil Rekomendasi & Perhitungan AHP')

@section('content')
<div class="row">
    @if(isset($error))
        <div class="col-12 mb-4">
            <div class="alert alert-danger">{{ $error }}</div>
        </div>
    @else
    @if(count($kriterias) < 5)
        <div class="col-12 mb-3">
            <div class="alert alert-warning rounded-3 shadow-sm d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                <div>
                    <strong>Perhatian:</strong> Saat ini baru terdaftar {{ count($kriterias) }} kriteria. 
                    Agar perhitungan AHP lengkap dengan 5 kriteria kuesioner pakar (K1 s/d K5), silakan gunakan fitur <strong>Muat Kriteria Kuesioner (K1-K5)</strong> pada akun Administrator di menu Data Kriteria.
                </div>
            </div>
        </div>
    @endif
    <!-- Ringkasan Bobot Prioritas & Status Konsistensi AHP -->
    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-bar-chart-fill me-2"></i> Bobot Prioritas Kriteria (AHP)</h5>
                <span class="badge bg-light text-muted border px-2 py-1"><i class="bi bi-check2-circle text-success me-1"></i> Data Kuesioner</span>
            </div>
            <div class="card-body">
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
                                <td class="fw-bold font-monospace">{{ number_format($weights[$k->id], 4) }}</td>
                                <td><span class="badge bg-primary px-3 rounded-pill">{{ number_format($weights[$k->id] * 100, 2) }}%</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="2" class="text-end">Total:</td>
                                <td class="font-monospace">{{ number_format(array_sum($weights), 4) }}</td>
                                <td><span class="badge bg-success px-3 rounded-pill">100%</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Rasio Konsistensi (Consistency Ratio / CR) -->
    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm h-100 {{ $isConsistent ? 'border-start border-success border-4' : 'border-start border-danger border-4' }}">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-check text-success me-2"></i> Uji Rasio Konsistensi (CR)</h5>
            </div>
            <div class="card-body">
                <div class="alert {{ $isConsistent ? 'alert-success' : 'alert-danger' }} d-flex align-items-center mb-3">
                    <i class="bi {{ $isConsistent ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }} fs-3 me-3"></i>
                    <div>
                        <div class="fw-bold fs-6">
                            {{ $isConsistent ? 'Matriks Perbandingan KONSISTEN' : 'Matriks TIDAK KONSISTEN' }}
                        </div>
                        <small class="d-block">
                            {{ $isConsistent ? 'Nilai CR ≤ 0.10, perbandingan preferensi kriteria valid & dapat digunakan.' : 'Nilai CR > 0.10, perbandingan perlu dikaji ulang.' }}
                        </small>
                    </div>
                </div>

                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="text-muted">Jumlah Kriteria (n)</span>
                        <span class="fw-bold">{{ count($kriterias) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="text-muted">Eigen Maksimum (&lambda; max)</span>
                        <span class="fw-bold font-monospace">{{ number_format($lambdaMax, 4) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="text-muted">Consistency Index (CI)</span>
                        <span class="fw-bold font-monospace">{{ number_format($ci, 6) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="text-muted">Random Index (RI, n={{ count($kriterias) }})</span>
                        <span class="fw-bold font-monospace">{{ number_format($ri, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 bg-light rounded px-2">
                        <span class="fw-bold text-dark">Consistency Ratio (CR = CI / RI)</span>
                        <span class="badge {{ $isConsistent ? 'bg-success' : 'bg-danger' }} fs-6 px-3">
                            {{ number_format($cr, 6) }} ({{ number_format($cr * 100, 2) }}%)
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Accordion Detail Perhitungan Lengkap AHP (Sesuai Kuesioner) -->
    <div class="col-12 mb-4">
        <div class="accordion" id="accordionAhpDetails">
            <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden">
                <h2 class="accordion-header" id="headingAhp">
                    <button class="accordion-button collapsed fw-bold py-3 bg-white text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAhp" aria-expanded="false" aria-controls="collapseAhp">
                        <i class="bi bi-calculator me-2"></i> Lihat Detail Tahapan Perhitungan AHP (Berdasarkan Hasil Kuesioner)
                    </button>
                </h2>
                <div id="collapseAhp" class="accordion-collapse collapse" aria-labelledby="headingAhp" data-bs-parent="#accordionAhpDetails">
                    <div class="accordion-body bg-white p-4">
                        
                        <!-- 1. Matriks Perbandingan Berpasangan -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark"><i class="bi bi-1-circle-fill text-primary me-1"></i> Matriks Perbandingan Berpasangan</h6>
                            <p class="text-muted small mb-2">Nilai perbandingan kriteria diperoleh langsung dari agregasi kuesioner pakar / guru SLB.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kriteria</th>
                                            @foreach($kriterias as $k)
                                                <th>{{ $k->kode }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kriterias as $k1)
                                        <tr>
                                            <td class="fw-bold text-start table-light">{{ $k1->kode }} - {{ $k1->nama }}</td>
                                            @foreach($kriterias as $k2)
                                                <td class="font-monospace {{ $k1->id == $k2->id ? 'bg-light text-muted' : '' }}">
                                                    {{ number_format($matrix[$k1->id][$k2->id], 4) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td class="text-start">Jumlah (Total Kolom)</td>
                                            @foreach($kriterias as $k2)
                                                <td class="font-monospace text-primary">{{ number_format($colSums[$k2->id], 4) }}</td>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- 2. Matriks Nilai Kriteria / Normalisasi -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark"><i class="bi bi-2-circle-fill text-primary me-1"></i> Matriks Normalisasi & Penghitungan Bobot Prioritas</h6>
                            <p class="text-muted small mb-2">Setiap elemen dibagi dengan total kolom matriks perbandingan, kemudian dihitung rata-rata baris sebagai bobot prioritas.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kriteria</th>
                                            @foreach($kriterias as $k)
                                                <th>{{ $k->kode }}</th>
                                            @endforeach
                                            <th class="table-secondary">Jumlah</th>
                                            <th class="table-primary">Prioritas</th>
                                            <th class="table-primary">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kriterias as $k1)
                                        <tr>
                                            <td class="fw-bold text-start table-light">{{ $k1->kode }}</td>
                                            @foreach($kriterias as $k2)
                                                <td class="font-monospace">
                                                    {{ number_format($normalizedMatrix[$k1->id][$k2->id], 4) }}
                                                </td>
                                            @endforeach
                                            <td class="font-monospace fw-semibold table-secondary">{{ number_format($normRowSums[$k1->id], 4) }}</td>
                                            <td class="font-monospace fw-bold table-primary text-primary">{{ number_format($weights[$k1->id], 4) }}</td>
                                            <td class="fw-bold table-primary text-primary">{{ number_format($weights[$k1->id] * 100, 2) }}%</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td colspan="{{ count($kriterias) + 2 }}" class="text-end">Total Prioritas:</td>
                                            <td class="font-monospace text-success">{{ number_format(array_sum($weights), 4) }}</td>
                                            <td class="text-success">100%</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- 3. Matriks Penjumlahan Setiap Baris -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark"><i class="bi bi-3-circle-fill text-primary me-1"></i> Matriks Perkalian Bobot & Penjumlahan Baris</h6>
                            <p class="text-muted small mb-2">Mengalikan nilai perbandingan berpasangan baris dengan bobot prioritas kriteria terkait.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kriteria</th>
                                            @foreach($kriterias as $k)
                                                <th>{{ $k->kode }}</th>
                                            @endforeach
                                            <th class="table-secondary">Jumlah Baris</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kriterias as $k1)
                                        <tr>
                                            <td class="fw-bold text-start table-light">{{ $k1->kode }}</td>
                                            @foreach($kriterias as $k2)
                                                <td class="font-monospace">
                                                    {{ number_format($rowMultMatrix[$k1->id][$k2->id], 4) }}
                                                </td>
                                            @endforeach
                                            <td class="font-monospace fw-bold table-secondary text-primary">{{ number_format($rowMultSums[$k1->id], 4) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 4. Perhitungan Rasio Konsistensi -->
                        <div>
                            <h6 class="fw-bold text-dark"><i class="bi bi-4-circle-fill text-primary me-1"></i> Perhitungan Nilai Eigen Maksimum (&lambda; max), CI, dan CR</h6>
                            <div class="row">
                                <div class="col-md-7 mb-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center align-middle table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kriteria</th>
                                                    <th>Jumlah Baris</th>
                                                    <th>Prioritas</th>
                                                    <th>Hasil (&lambda; i)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($kriterias as $k)
                                                <tr>
                                                    <td class="fw-bold text-start table-light">{{ $k->kode }}</td>
                                                    <td class="font-monospace">{{ number_format($rowMultSums[$k->id], 4) }}</td>
                                                    <td class="font-monospace">{{ number_format($weights[$k->id], 4) }}</td>
                                                    <td class="font-monospace fw-bold">{{ number_format($lambdaPerKriteria[$k->id], 4) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light fw-bold">
                                                <tr>
                                                    <td colspan="3" class="text-end">Total:</td>
                                                    <td class="font-monospace text-primary">{{ number_format($sumLambda, 4) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <div class="card bg-light border-0 p-3 h-100">
                                        <h6 class="fw-bold mb-2">Ringkasan Rumus:</h6>
                                        <p class="small mb-1"><strong>&lambda; max</strong> = {{ number_format($sumLambda, 4) }} / {{ count($kriterias) }} = <strong>{{ number_format($lambdaMax, 4) }}</strong></p>
                                        <p class="small mb-1"><strong>CI</strong> = (&lambda; max - n) / (n - 1) = ({{ number_format($lambdaMax, 4) }} - {{ count($kriterias) }}) / {{ count($kriterias) - 1 }} = <strong>{{ number_format($ci, 6) }}</strong></p>
                                        <p class="small mb-1"><strong>RI</strong> (n={{ count($kriterias) }}) = <strong>{{ number_format($ri, 2) }}</strong></p>
                                        <p class="small mb-2"><strong>CR</strong> = CI / RI = {{ number_format($ci, 6) }} / {{ number_format($ri, 2) }} = <span class="badge {{ $isConsistent ? 'bg-success' : 'bg-danger' }}">{{ number_format($cr, 6) }}</span></p>
                                        <div class="small fw-semibold text-{{ $isConsistent ? 'success' : 'danger' }}">
                                            <i class="bi {{ $isConsistent ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                            {{ $isConsistent ? 'CR < 0.10 -> Konsistensi Diterima (Valid)' : 'CR >= 0.10 -> Tidak Konsisten' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
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
                                <th class="text-center" style="width: 5%">Peringkat</th>
                                <th>Nama Siswa</th>
                                <th>Kebutuhan Khusus</th>
                                @foreach($kriterias as $k)
                                    <th class="text-center" style="font-size: 0.8rem;">{{ $k->kode }}<br><small class="text-muted text-fw-normal">({{ number_format($weights[$k->id]*100,0) }}%)</small></th>
                                @endforeach
                                <th class="text-center text-primary fs-6">Skor Akhir</th>
                                <th>Rekomendasi Alternatif Layanan</th>
                                <th class="text-center" style="width: 12%">Status</th>
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
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $row['siswa']->jenis_kebutuhan_khusus ?? '-' }}</span>
                                </td>
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
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary px-2 py-1 shadow-sm">{{ $row['kode_layanan'] }}</span>
                                        <div>
                                            <span class="fw-bold text-dark">{{ $row['rekomendasi_layanan'] }}</span>
                                            @if(!empty($row['deskripsi_layanan']))
                                                <small class="text-muted d-block text-truncate" style="max-width: 280px;" title="{{ $row['deskripsi_layanan'] }}">
                                                    {{ $row['deskripsi_layanan'] }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($index === 0)
                                        <span class="badge bg-success px-2 py-1 shadow-sm"><i class="bi bi-star-fill me-1"></i> {{ $row['status_prioritas'] }}</span>
                                    @elseif($index < 3)
                                        <span class="badge bg-info text-dark px-2 py-1">{{ $row['status_prioritas'] }}</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">{{ $row['status_prioritas'] }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ count($kriterias) + 6 }}" class="text-center py-5 text-muted">
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
