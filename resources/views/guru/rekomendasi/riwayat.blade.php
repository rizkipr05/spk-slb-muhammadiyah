@extends('layouts.app')

@section('title', 'Riwayat Rekomendasi - SPK Disabilitas')
@section('page_title', 'Riwayat Laporan Rekomendasi AHP')

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm border-top border-primary border-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-clock-history me-2 text-primary"></i>Daftar Laporan yang Disimpan</h5>
                <a href="{{ route('guru.rekomendasi.index') }}" class="btn btn-sm btn-primary shadow-sm"><i class="bi bi-plus-lg me-1"></i>Buat Laporan Baru</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Topik / Judul Laporan</th>
                                <th>Tanggal Disimpan</th>
                                <th>Disimpan Oleh</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporans as $index => $lap)
                            <tr>
                                <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $lap->judul }}</td>
                                <td>{{ $lap->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
                                <td><span class="badge bg-secondary">{{ optional($lap->author)->nama }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('guru.rekomendasi.cetak', $lap->id) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-printer-fill me-1"></i> Cetak Laporan
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-cloud-slash fs-2 d-block mb-3"></i>
                                    Belum ada satupun riwayat laporan yang disimpan. Buat lewat kalkulasi SPK.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
