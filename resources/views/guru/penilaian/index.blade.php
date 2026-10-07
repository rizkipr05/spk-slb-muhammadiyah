@extends('layouts.app')

@section('title', 'Penilaian Siswa - SPK Disabilitas')
@section('page_title', 'Input Penilaian Siswa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Siswa untuk Dinilai</span>
        <a href="{{ route('guru.siswa.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Siswa
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="alert alert-info">
            Pilih siswa yang akan dilakukan proses penilaian preferensi berdasarkan Kriteria.
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kebutuhan Khusus</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $siswa)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nisn ?? '-' }}</td>
                            <td class="fw-semibold">{{ $siswa->nama }}</td>
                            <td>{{ $siswa->jenis_kebutuhan_khusus ?? '-' }}</td>
                            <td class="text-end">
                                @if($siswa->penilaians->count() > 0)
                                    <span class="badge bg-success me-2"><i class="bi bi-check-circle"></i> Selesai</span>
                                    <a href="{{ route('guru.penilaian.edit', $siswa->id) }}" class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                @else
                                    <span class="badge bg-warning text-dark me-2"><i class="bi bi-clock"></i> Belum</span>
                                    <a href="{{ route('guru.penilaian.edit', $siswa->id) }}" class="btn btn-sm btn-primary rounded-3">
                                        <i class="bi bi-plus-circle"></i> Nilai
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada data siswa terdaftar. 
                                <a href="{{ route('guru.siswa.create') }}" class="text-primary text-decoration-none fw-semibold">
                                    Tambah siswa sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
