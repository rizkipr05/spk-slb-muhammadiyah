@extends('layouts.app')

@section('title', 'Data Pengguna - SPK Disabilitas')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Tabel Data Pengguna</span>
        <a href="{{ route('pengguna.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pengguna
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama & Email</th>
                        <th>Username & NIP</th>
                        <th>Role Akses</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: 600;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div>{{ $user->name }}</div>
                                        <div class="text-muted" style="font-size: 0.8rem;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $user->username }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">NIP: {{ $user->nip ?: '-' }}</div>
                            </td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge bg-danger rounded-pill px-3">Administrator</span>
                                @elseif($user->role == 'guru')
                                    <span class="badge bg-success rounded-pill px-3">Guru</span>
                                @else
                                    <span class="badge bg-info text-dark rounded-pill px-3">Kepala Sekolah</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('pengguna.edit', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-3" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Hapus" {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
