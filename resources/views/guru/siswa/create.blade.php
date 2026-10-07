@extends('layouts.app')

@section('title', 'Tambah Siswa - SPK Disabilitas')
@section('page_title', 'Tambah Data Siswa')

@section('content')
<div class="card">
    <div class="card-header">
        Form Tambah Siswa Baru
    </div>
    <div class="card-body">
        <form action="{{ route('guru.siswa.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-medium">NISN</label>
                    <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn') }}" placeholder="Opsional">
                    @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-medium">Nama Siswa <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap siswa" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-medium">Wali Kelas / Guru Penilai</label>
                    <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }}" readonly>
                    <small class="text-muted">Otomatis terhubung dengan akun guru Anda.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Jenis Kebutuhan Khusus</label>
                    <input type="text" name="jenis_kebutuhan_khusus" class="form-control @error('jenis_kebutuhan_khusus') is-invalid @enderror" value="{{ old('jenis_kebutuhan_khusus') }}" placeholder="Contoh: Tunanetra, Tunarungu, Autis (Opsional)">
                    @error('jenis_kebutuhan_khusus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}" placeholder="Tempat lahir">
                    @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}">
                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" placeholder="Alamat tempat tinggal siswa">{{ old('alamat') }}</textarea>
                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('guru.siswa.index') }}" class="btn btn-light border shadow-sm">Batal</a>
                <button type="submit" class="btn btn-primary shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
