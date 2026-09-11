@extends('layouts.app')

@section('title', 'Edit Subkriteria - ' . $kriteria->nama)
@section('page_title', 'Edit Subkriteria')

@section('content')
<div class="card">
    <div class="card-header">
        Form Edit Subkriteria untuk Kriteria: <strong>{{ $kriteria->nama }}</strong>
    </div>
    <div class="card-body">
        <form action="{{ route('kriteria.subkriteria.update', [$kriteria->id, $subkriteria->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8 mb-4">
                    <label class="form-label fw-medium">Nama Subkriteria <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $subkriteria->nama) }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-4">
                    <label class="form-label fw-medium">Nilai / Bobot <span class="text-danger">*</span></label>
                    <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" value="{{ old('nilai', $subkriteria->nilai) }}" required>
                    @error('nilai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('kriteria.subkriteria.index', $kriteria->id) }}" class="btn btn-light border shadow-sm">Batal</a>
                <button type="submit" class="btn btn-primary shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
