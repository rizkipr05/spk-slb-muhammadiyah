@extends('layouts.app')

@section('title', 'Tambah Alternatif - SPK Disabilitas')
@section('page_title', 'Tambah Alternatif Layanan')

@section('content')
<div class="card">
    <div class="card-header">
        Form Tambah Alternatif
    </div>
    <div class="card-body">
        <form action="{{ route('alternatif.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-4">
                    <label class="form-label fw-medium">Kode Alternatif <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="Contoh: A1" required autofocus>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8 mb-4">
                    <label class="form-label fw-medium">Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_layanan" class="form-control @error('nama_layanan') is-invalid @enderror" value="{{ old('nama_layanan') }}" placeholder="Contoh: SLB A, Inklusi" required>
                    @error('nama_layanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Deskripsi Layanan</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" placeholder="Penjelasan mengenai layanan pendidikan (Opsional)">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('alternatif.index') }}" class="btn btn-light border shadow-sm">Batal</a>
                <button type="submit" class="btn btn-primary shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
