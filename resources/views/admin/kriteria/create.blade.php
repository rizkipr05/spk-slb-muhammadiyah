@extends('layouts.app')

@section('title', 'Tambah Kriteria - SPK Disabilitas')
@section('page_title', 'Tambah Data Kriteria')

@section('content')
<div class="card">
    <div class="card-header">
        Form Tambah Kriteria
    </div>
    <div class="card-body">
        <form action="{{ route('kriteria.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-4">
                    <label class="form-label fw-medium">Kode Kriteria <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="Contoh: C1" required autofocus>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8 mb-4">
                    <label class="form-label fw-medium">Nama Kriteria <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Kemampuan Kognitif" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('kriteria.index') }}" class="btn btn-light border shadow-sm">Batal</a>
                <button type="submit" class="btn btn-primary shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
