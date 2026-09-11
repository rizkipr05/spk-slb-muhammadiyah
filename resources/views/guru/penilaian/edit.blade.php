@extends('layouts.app')

@section('title', 'Input Penilaian: ' . $siswa->nama . ' - SPK Disabilitas')
@section('page_title', 'Input Penilaian')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Form Penilaian: <strong>{{ $siswa->nama }}</strong> ({{ $siswa->nisn ?? '-' }})</span>
        <a href="{{ route('guru.penilaian.index') }}" class="btn btn-sm btn-light border">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <div class="alert alert-info rounded-3">
            <i class="bi bi-info-circle me-1"></i> Silakan lengkapi penilaian untuk siswa ini berdasarkan kriteria yang telah ditentukan. 
            Semua nilai wajib diisi.
        </div>

        <form action="{{ route('guru.penilaian.update', $siswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="table-responsive mt-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 15%">Kode</th>
                            <th style="width: 40%">Kriteria</th>
                            <th style="width: 40%">Nilai / Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kriterias as $kriteria)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td><span class="badge bg-secondary">{{ $kriteria->kode }}</span></td>
                                <td class="fw-semibold">{{ $kriteria->nama }}</td>
                                <td>
                                    @if($kriteria->subkriterias->count() > 0)
                                        <select name="kriteria[{{ $kriteria->id }}]" class="form-select @error('kriteria.'.$kriteria->id) is-invalid @enderror" required>
                                            <option value="" disabled {{ !isset($penilaianSiswa[$kriteria->id]) ? 'selected' : '' }}>-- Pilih Nilai --</option>
                                            @foreach($kriteria->subkriterias as $sub)
                                                <option value="{{ $sub->nilai }}" {{ (isset($penilaianSiswa[$kriteria->id]) && $penilaianSiswa[$kriteria->id] == $sub->nilai) ? 'selected' : '' }}>
                                                    {{ $sub->nama }} (Nilai: {{ $sub->nilai }})
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="number" name="kriteria[{{ $kriteria->id }}]" class="form-control @error('kriteria.'.$kriteria->id) is-invalid @enderror" value="{{ $penilaianSiswa[$kriteria->id] ?? '' }}" placeholder="Masukkan Nilai" required min="0" max="100">
                                    @endif
                                    
                                    @error('kriteria.'.$kriteria->id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada Kriteria SPK yang didaftarkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kriterias->count() > 0)
                <div class="d-flex justify-content-end mt-4">
                    @if(count($penilaianSiswa) > 0)
                        <button type="button" class="btn btn-outline-danger px-4 me-2" onclick="if(confirm('Apakah Anda yakin ingin me-reset penilaian ini?')) document.getElementById('delete-form').submit();">
                            <i class="bi bi-trash"></i> Reset
                        </button>
                    @endif
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Simpan Penilaian
                    </button>
                </div>
            @endif
        </form>

        @if(count($penilaianSiswa) > 0)
            <form id="delete-form" action="{{ route('guru.penilaian.destroy', $siswa->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</div>
@endsection
