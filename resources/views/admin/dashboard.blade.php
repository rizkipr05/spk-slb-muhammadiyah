@extends('layouts.app')

@section('title', 'Admin Dashboard - SPK Disabilitas')
@section('page_title', 'Dashboard Administrator')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-gradient-primary h-100">
            <div>
                <h3>{{ $totalSiswa }}</h3>
                <p class="mb-0 mt-1 opacity-75">Total Data Siswa</p>
            </div>
            <i class="bi bi-people-fill opacity-25" style="font-size: 3.5rem;"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-gradient-success h-100">
            <div>
                <h3>{{ $totalKriteria }}</h3>
                <p class="mb-0 mt-1 opacity-75">Data Kriteria AHP</p>
            </div>
            <i class="bi bi-list-check opacity-25" style="font-size: 3.5rem;"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-gradient-warning h-100">
            <div>
                <h3>{{ $totalUser }}</h3>
                <p class="mb-0 mt-1 opacity-75">Hak Akses Sistem</p>
            </div>
            <i class="bi bi-person-badge opacity-25" style="font-size: 3.5rem;"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Distribusi Kebutuhan Khusus Siswa</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 300px;">
                <canvas id="chartKebutuhan"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-bar-chart-fill me-2 text-success"></i>Komposisi Pengguna Sistem</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 300px;">
                <canvas id="chartRole"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart: Kebutuhan Khusus
    const ctxKebutuhan = document.getElementById('chartKebutuhan').getContext('2d');
    new Chart(ctxKebutuhan, {
        type: 'pie',
        data: {
            labels: {!! json_encode($labelsKebutuhan) !!},
            datasets: [{
                data: {!! json_encode($dataKebutuhan) !!},
                backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#94a3b8'],
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

    // Bar Chart: Komposisi Role
    const ctxRole = document.getElementById('chartRole').getContext('2d');
    new Chart(ctxRole, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labelsRole) !!},
            datasets: [{
                label: 'Jumlah Pengguna',
                data: {!! json_encode($dataRole) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderRadius: 6,
                barPercentage: 0.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { borderDash: [2, 4], color: '#e2e8f0' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush
@endsection
