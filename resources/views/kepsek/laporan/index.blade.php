@extends('layouts.app')

@section('title', 'Laporan Rekomendasi - SPK Disabilitas')
@section('page_title', 'Laporan Rekomendasi Layanan Pendidikan')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm border-top border-primary border-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-check-fill text-primary me-2"></i>Hasil Keputusan Rekomendasi Layanan (AHP)</h5>
                    @if($latestLaporan)
                        <small class="text-muted">Topik: <strong>{{ $latestLaporan->judul }}</strong> &bull; Disimpan: {{ $latestLaporan->created_at->translatedFormat('d F Y, H:i') }} WIB</small>
                    @endif
                </div>
                <div>
                    @if($latestLaporan)
                        <a href="{{ route('kepsek.laporan.cetak', $latestLaporan->id) }}" target="_blank" class="btn btn-sm btn-success shadow-sm">
                            <i class="bi bi-printer-fill me-1"></i> Cetak Laporan
                        </a>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled>
                            <i class="bi bi-printer me-1"></i> Cetak Laporan
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(!$latestLaporan)
                    <div class="alert alert-info rounded-3">
                        <i class="bi bi-info-circle me-1"></i> Belum ada laporan hasil perhitungan yang disimpan oleh Guru. Setelah Guru menyimpan hasil perhitungan AHP, data keputusan dan rekomendasi layanan akan otomatis tampil di sini.
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 8%">Ranking</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Kebutuhan Khusus</th>
                                <th>Rekomendasi Layanan</th>
                                <th class="text-center">Skor Preferensi</th>
                                <th class="text-center" style="width: 14%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($latestLaporan && isset($latestLaporan->data_hasil['results']))
                                @forelse($latestLaporan->data_hasil['results'] as $index => $row)
                                <tr class="{{ $index === 0 ? 'table-warning' : '' }}">
                                    <td class="text-center">
                                        @if($index === 0)
                                            <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-trophy-fill"></i> 1</span>
                                        @else
                                            <span class="fw-semibold text-muted">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $row['siswa']['nisn'] ?? '-' }}</td>
                                    <td class="fw-bold">{{ $row['siswa']['nama'] }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $row['siswa']['jenis_kebutuhan_khusus'] ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary px-2 py-1">{{ $row['kode_layanan'] ?? 'A-' }}</span>
                                            <span class="fw-semibold text-dark">{{ $row['rekomendasi_layanan'] ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $index === 0 ? 'bg-warning text-dark' : 'bg-primary' }} rounded-pill px-3 py-1">
                                            {{ number_format($row['score'], 4) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($index === 0)
                                            <span class="badge bg-success px-2 py-1">{{ $row['status_prioritas'] ?? 'Prioritas Utama' }}</span>
                                        @elseif($index < 3)
                                            <span class="badge bg-info text-dark px-2 py-1">{{ $row['status_prioritas'] ?? 'Prioritas Tinggi' }}</span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1">{{ $row['status_prioritas'] ?? 'Direkomendasikan' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada rincian data dalam laporan ini.</td>
                                </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data perhitungan yang disimpan.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($riwayatLaporans->count() > 1)
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Berkas Laporan Tersimpan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th>Topik Laporan</th>
                                <th>Tanggal Disimpan</th>
                                <th>Disimpan Oleh</th>
                                <th class="text-center" style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatLaporans as $i => $lap)
                            <tr>
                                <td class="text-center text-muted">{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $lap->judul }}</td>
                                <td>{{ $lap->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
                                <td>{{ optional($lap->author)->name ?? 'Guru' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('kepsek.laporan.cetak', $lap->id) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-printer me-1"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
