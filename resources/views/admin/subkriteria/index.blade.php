@extends('layouts.app')

@section('title', 'Subkriteria: ' . $kriteria->nama . ' - SPK Disabilitas')
@section('page_title', 'Subkriteria: ' . $kriteria->nama)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Tabel Subkriteria [{{ $kriteria->kode }}]</span>
        <div>
            <a href="{{ route('kriteria.index') }}" class="btn btn-sm btn-light border me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('kriteria.subkriteria.create', $kriteria->id) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Subkriteria
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

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Subkriteria</th>
                        <th>Nilai/Bobot</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subkriterias as $subkriteria)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $subkriteria->nama }}</td>
                            <td><span class="badge bg-primary rounded-pill px-3">{{ $subkriteria->nilai }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('kriteria.subkriteria.edit', [$kriteria->id, $subkriteria->id]) }}" class="btn btn-sm btn-outline-primary rounded-3" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('kriteria.subkriteria.destroy', [$kriteria->id, $subkriteria->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data subkriteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
