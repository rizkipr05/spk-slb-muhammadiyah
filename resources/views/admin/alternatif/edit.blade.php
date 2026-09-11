@extends('layouts.app')

@section('title', 'Edit Alternatif - SPK Disabilitas')
@section('page_title', 'Edit Alternatif Layanan')

@section('content')
<div class="card">
    <div class="card-header">
        Form Edit Alternatif
    </div>
    <div class="card-body">
        <form action="{{ route('alternatif.update', $alternatif->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-4 mb-4">
                    <label class="form-label fw-medium">Kode Alternatif <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $alternatif->kode) }}" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8 mb-4">
                    <label class="form-label fw-medium">Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_layanan" class="form-control @error('nama_layanan') is-invalid @enderror" value="{{ old('nama_layanan', $alternatif->nama_layanan) }}" required>
                    @error('nama_layanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Deskripsi Layanan</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi', $alternatif->deskripsi) }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('alternatif.index') }}" class="btn btn-light border shadow-sm">Batal</a>
                <button type="submit" class="btn btn-primary shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
