@extends('layouts.app')

@section('title', 'Data Kriteria - SPK Disabilitas')
@section('page_title', 'Manajemen Data Kriteria')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Tabel Data Kriteria</span>
        <div class="d-flex gap-2">
            <form action="{{ route('kriteria.reset-default') }}" method="POST" class="d-inline" onsubmit="return confirm('Muat ulang 5 data kriteria standar kuesioner (K1 s/d K5) beserta matriks perbandingannya?')">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary shadow-sm">
                    <i class="bi bi-arrow-repeat"></i> Muat Kriteria Kuesioner (K1-K5)
                </button>
            </form>
            <a href="{{ route('kriteria.create') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="bi bi-plus-circle"></i> Tambah Kriteria
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($kriterias->count() < 5)
            <div class="alert alert-warning d-flex align-items-center justify-content-between rounded-3 mb-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div>
                        <strong>Data Kriteria Belum Lengkap:</strong> Saat ini baru terdaftar {{ $kriterias->count() }} kriteria. 
                        Untuk perhitungan AHP sesuai hasil kuesioner pakar (5 kriteria: K1 s/d K5), silakan klik <strong>Muat Kriteria Kuesioner (K1-K5)</strong>.
                    </div>
                </div>
                <form action="{{ route('kriteria.reset-default') }}" method="POST" class="ms-3 d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold">
                        <i class="bi bi-arrow-repeat"></i> Muat Sekarang
                    </button>
                </form>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Kriteria</th>
                        <th>Nama Kriteria</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kriterias as $kriteria)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-secondary rounded-pill px-3">{{ $kriteria->kode }}</span></td>
                            <td class="fw-semibold">{{ $kriteria->nama }}</td>
                            <td class="text-end">
                                <a href="{{ route('kriteria.subkriteria.index', $kriteria->id) }}" class="btn btn-sm btn-outline-info rounded-3" title="Subkriteria">
                                    <i class="bi bi-list-nested"></i> Subkriteria
                                </a>
                                <a href="{{ route('kriteria.edit', $kriteria->id) }}" class="btn btn-sm btn-outline-primary rounded-3" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('kriteria.destroy', $kriteria->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data kriteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
