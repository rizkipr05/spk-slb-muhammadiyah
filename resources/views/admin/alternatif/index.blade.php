@extends('layouts.app')

@section('title', 'Data Alternatif Layanan - SPK Disabilitas')
@section('page_title', 'Manajemen Data Alternatif Layanan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Tabel Alternatif Layanan</span>
        <a href="{{ route('alternatif.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Layanan
        </a>
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
                        <th width="5%">No</th>
                        <th width="15%">Kode</th>
                        <th width="30%">Nama Layanan</th>
                        <th width="40%">Deskripsi</th>
                        <th class="text-end" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alternatifs as $alternatif)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-secondary rounded-pill px-3">{{ $alternatif->kode }}</span></td>
                            <td class="fw-semibold">{{ $alternatif->nama_layanan }}</td>
                            <td class="text-muted text-truncate" style="max-width: 300px;">{{ $alternatif->deskripsi ?: '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('alternatif.edit', $alternatif->id) }}" class="btn btn-sm btn-outline-primary rounded-3" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('alternatif.destroy', $alternatif->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada alternatif layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
