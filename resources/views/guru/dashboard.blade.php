@extends('layouts.app')

@section('title', 'Guru Dashboard - SPK Disabilitas')
@section('page_title', 'Dashboard Guru / Penilai')

@section('content')

@if($pengumumans && count($pengumumans) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm border-start border-4 border-info">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-info"><i class="bi bi-megaphone-fill me-2"></i>Pengumuman Terbaru</h6>
            </div>
            <div class="card-body pt-0">
                <div class="list-group list-group-flush">
                    @foreach($pengumumans as $p)
                        <div class="list-group-item px-0 py-3">
                            <h6 class="fw-bold mb-1">{{ $p->judul }}</h6>
                            <p class="mb-2 text-muted" style="font-size: 0.95rem;">{{ $p->isi_pengumuman ?? $p->isi }}</p>
                            <small class="text-secondary"><i class="bi bi-clock me-1"></i>{{ $p->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="stat-card bg-gradient-primary h-100">
            <div>
                <h3>{{ $siswaDinilai }} <span class="fs-5 fw-normal opacity-75">/ {{ $totalSiswa }}</span></h3>
                <p class="mb-0 mt-1 opacity-75">Siswa Selesai Dinilai</p>
            </div>
            <i class="bi bi-clipboard2-check-fill opacity-25" style="font-size: 3.5rem;"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card bg-gradient-warning h-100">
            <div>
                <h3 class="text-dark">{{ $totalSiswa - $siswaDinilai }}</h3>
                <p class="mb-0 mt-1 text-dark opacity-75">Siswa Belum Dinilai</p>
            </div>
            <i class="bi bi-hourglass-split text-dark opacity-25" style="font-size: 3.5rem;"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Status Penilaian Keseluruhan</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 280px;">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-bar-chart-fill me-2 text-warning"></i>Rata-Rata Nilai Siswa per Kriteria</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 280px;">
                <canvas id="chartKriteria"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Doughnut Chart: Status Penilaian
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($labelsStatus) !!},
            datasets: [{
                data: {!! json_encode($dataStatus) !!},
                backgroundColor: ['#10b981', '#f59e0b'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true, padding: 20 } }
            }
        }
    });

    // Bar Chart: Rata-rata Kriteria
    const ctxKriteria = document.getElementById('chartKriteria').getContext('2d');
    new Chart(ctxKriteria, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labelsKriteria) !!},
            datasets: [{
                label: 'Nilai Rata-rata',
                data: {!! json_encode($dataKriteriaAvg) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.85)',
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#e2e8f0' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush
@endsection
