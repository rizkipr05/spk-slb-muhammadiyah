<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK Disabilitas')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4338ca; --primary-hover: #3730a3; --bg: #f8fafc; --surface: #ffffff; --text-main: #0f172a; --text-muted: #64748b; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); color: var(--text-main); -webkit-font-smoothing: antialiased; }
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        
        .sidebar { min-height: 100vh; background: var(--surface); border-right: 1px solid rgba(0,0,0,0.04); box-shadow: 4px 0 24px rgba(0,0,0,0.02); z-index: 10; padding-top: 0.5rem;}
        .sidebar .brand { padding: 1.25rem 1.75rem; font-weight: 800; font-size: 1.4rem; color: var(--text-main); display: flex; align-items: center; gap: 0.75rem; letter-spacing: -0.5px;}
        .sidebar .nav-link { color: var(--text-muted); font-weight: 600; font-size: 0.95rem; padding: 0.8rem 1.25rem; margin: 0.25rem 1rem; border-radius: 0.6rem; transition: all 0.25s ease; }
        .sidebar .nav-link:hover { color: var(--primary); background-color: #f1f5f9; transform: translateX(3px); }
        .sidebar .nav-link.active { color: white; background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%); box-shadow: 0 4px 12px rgba(67, 56, 202, 0.25); }
        .sidebar .nav-link i { margin-right: 0.8rem; font-size: 1.15rem; }
        .sidebar .user-info { margin: 1rem 1.25rem 1.5rem; padding: 1rem; border-radius: 0.75rem; background: #f8fafc; border: 1px solid rgba(0,0,0,0.03); }
        
        .main-content { padding: 2.5rem; animation: fadeIn 0.4s ease forwards; }
        .topbar { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); padding: 1rem 2.5rem; border-bottom: 1px solid rgba(0,0,0,0.04); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 20;}
        
        .card { border: 1px solid rgba(0,0,0,0.05); border-radius: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: transform 0.2s; background: var(--surface); }
        .card-header { background: transparent; border-bottom: 1px solid rgba(0,0,0,0.04); padding: 1.5rem 1.75rem; border-radius: 1.25rem 1.25rem 0 0 !important; font-weight: 700; font-size: 1.1rem; color: var(--text-main);}
        .card-body { padding: 1.75rem; }
        
        .table { margin-bottom: 0; }
        .table > :not(caption) > * > * { padding: 1rem 1.25rem; border-bottom-color: rgba(0,0,0,0.04); }
        .table-light th { background-color: #f8fafc; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; border-bottom: 2px solid rgba(0,0,0,0.05) !important;}
        .table-hover tbody tr { transition: all 0.2s; }
        .table-hover tbody tr:hover { background-color: #f8fafc; transform: scale(1.001); box-shadow: 0 2px 10px rgba(0,0,0,0.01); z-index: 2; position: relative;}
        
        .btn-primary { background: var(--primary); border: none; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 500; transition: all 0.2s; box-shadow: 0 2px 8px rgba(67,56,202,0.25); }
        .btn-primary:hover { background: var(--primary-hover); transform: translateY(-1.5px); box-shadow: 0 6px 15px rgba(67,56,202,0.35); }
        .btn-outline-primary { color: var(--primary); border-color: rgba(67,56,202,0.3); font-weight: 500;}
        .btn-outline-primary:hover { background-color: var(--primary); color: white; border-color: var(--primary); box-shadow: 0 4px 10px rgba(67,56,202,0.25); transform: translateY(-1.5px);}
        
        .stat-card { padding: 1.75rem; border-radius: 1.25rem; color: white; display:flex; align-items: center; justify-content: space-between; border: none; box-shadow: 0 10px 20px rgba(0,0,0,0.08); transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card h3 { font-size: 2.25rem; font-weight: 800; margin-bottom: 0; letter-spacing: -1px;}
        .stat-card p { font-size: 0.95rem; opacity: 0.9; margin-bottom: 0; font-weight: 500; }
        .stat-card i { font-size: 3rem; opacity: 0.2; }
        .bg-gradient-primary { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        
        .badge { font-weight: 600; padding: 0.4em 0.8em; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-none d-md-block">
                <div class="brand">
                    <div class="d-flex align-items-center justify-content-center bg-white shadow-sm" style="width: 48px; height: 48px; border-radius: 14px; padding: 6px; border: 1px solid rgba(0,0,0,0.03);">
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo SPK Edu" class="img-fluid object-fit-contain" style="border-radius: 8px;">
                    </div>
                    <div class="d-flex flex-column" style="line-height: 1.1;">
                        <span style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.5px; color: var(--text-main); white-space: nowrap;">SPK SLB ABCD</span>
                        <span style="font-size: 0.85rem; font-weight: 700; letter-spacing: 0.5px; color: var(--primary); margin-top: 1px; white-space: nowrap;">Muhammadiyah Palu</span>
                    </div>
                </div>
                <br>

                <ul class="nav flex-column mt-2">
                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/siswa*') ? 'active' : '' }}" href="{{ route('siswa.index') }}"><i class="bi bi-people"></i> Data Siswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/kriteria*') ? 'active' : '' }}" href="{{ route('kriteria.index') }}"><i class="bi bi-list-check"></i> Data Kriteria</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/alternatif*') ? 'active' : '' }}" href="{{ route('alternatif.index') }}"><i class="bi bi-layers"></i> Data Alternatif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/pengguna*') ? 'active' : '' }}" href="{{ route('pengguna.index') }}"><i class="bi bi-person-gear"></i> Manajemen Pengguna</a>
                        </li>
                    @elseif(Auth::user()->role === 'guru')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('guru/dashboard') ? 'active' : '' }}" href="{{ url('/guru/dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('guru/penilaian*') ? 'active' : '' }}" href="{{ route('guru.penilaian.index') }}"><i class="bi bi-clipboard-data"></i> Penilaian Siswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('guru.rekomendasi.index') ? 'active' : '' }}" href="{{ route('guru.rekomendasi.index') }}"><i class="bi bi-award"></i> Hasil Rekomendasi</a>
                            <a class="nav-link {{ request()->routeIs('guru.rekomendasi.riwayat') ? 'active' : '' }}" href="{{ route('guru.rekomendasi.riwayat') }}"><i class="bi bi-clock-history"></i> Riwayat Laporan</a>
                        </li>
                    @elseif(Auth::user()->role === 'kepsek')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('kepsek/dashboard') ? 'active' : '' }}" href="{{ route('kepsek.dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard monitoring</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('kepsek/laporan*') ? 'active' : '' }}" href="{{ route('kepsek.laporan.index') }}"><i class="bi bi-file-earmark-pdf"></i> Laporan SPK</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('kepsek/pengumuman*') ? 'active' : '' }}" href="{{ route('kepsek.pengumuman.index') }}"><i class="bi bi-megaphone"></i> Kelola Pengumuman</a>
                        </li>
                    @endif
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 bg-light">
                <div class="topbar">
                    <h5 class="mb-0 fw-semibold text-secondary">@yield('page_title', 'Sistem Pendukung Keputusan')</h5>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border dropdown-toggle rounded-pill px-3 shadow-sm d-flex align-items-center gap-2" type="button" id="dropdownMenuProfile" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="position-relative" style="width: 24px; height: 24px;">
                                    @if(Auth::user()->foto)
                                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto" class="rounded-circle object-fit-cover w-100 h-100">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center w-100 h-100" style="font-weight: 600; font-size: 10px;">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <span class="fw-medium text-dark">{{ explode(' ', Auth::user()->name)[0] }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="dropdownMenuProfile">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile.index') }}">
                                        <i class="bi bi-person me-2 text-secondary"></i> Profil Saya
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="main-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('modals')
    @stack('scripts')
</body>
</html>
