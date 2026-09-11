@extends('layouts.app')

@section('title', 'Kepala Sekolah Dashboard - SPK Disabilitas')
@section('page_title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="stat-card bg-gradient-success h-100">
            <div>
                <h3>{{ $aktif }}</h3>
                <p class="mb-0 mt-1 opacity-75">Pengumuman Aktif</p>
            </div>
            <i class="bi bi-megaphone-fill opacity-25" style="font-size: 3.5rem;"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card bg-gradient-warning h-100">
            <div>
                <h3 class="text-dark">{{ $nonaktif }}</h3>
                <p class="mb-0 mt-1 text-dark opacity-75">Pengumuman Diarsipkan</p>
            </div>
            <i class="bi bi-archive-fill text-dark opacity-25" style="font-size: 3.5rem;"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100 border-0 shadow-sm border-top border-primary border-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-hand-thumbs-up-fill me-2 text-primary"></i>Top 5 Siswa dengan Akumulasi Nilai Mentah Tertinggi</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 320px;">
                <canvas id="chartTopSiswa"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-pie-chart-fill me-2 text-success"></i>Rasio Pengumuman</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 320px;">
                <canvas id="chartPengumuman"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bar Chart: Top 5 Siswa
    const ctxTopSiswa = document.getElementById('chartTopSiswa').getContext('2d');
    new Chart(ctxTopSiswa, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labelsTopSiswa) !!},
            datasets: [{
                label: 'Total Nilai (Mentah)',
                data: {!! json_encode($dataTopSiswa) !!},
                backgroundColor: [
                    'rgba(99, 102, 241, 0.9)',
                    'rgba(99, 102, 241, 0.7)',
                    'rgba(99, 102, 241, 0.5)',
                    'rgba(99, 102, 241, 0.4)',
                    'rgba(99, 102, 241, 0.3)'
                ],
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Makes it a horizontal bar chart!
            scales: {
                x: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#e2e8f0' } },
                y: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // Pie Chart: Pengumuman
    const ctxPengumuman = document.getElementById('chartPengumuman').getContext('2d');
    new Chart(ctxPengumuman, {
        type: 'pie',
        data: {
            labels: {!! json_encode($labelsPengumuman) !!},
            datasets: [{
                data: {!! json_encode($dataPengumuman) !!},
                backgroundColor: ['#10b981', '#cbd5e1'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true, padding: 20 } }
            }
        }
    });
});
</script>
@endpush
@endsection
