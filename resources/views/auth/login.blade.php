<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK Disabilitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4338ca;
            --primary-light: #6366f1;
            --primary-hover: #3730a3;
        }

        * {
            box-sizing: border-box;
        }

        body { 
            background: linear-gradient(135deg, #eef2f7 0%, #e0e7ff 50%, #c7d2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Decorative Floating Blobs */
        .blob-1 { 
            position: absolute; 
            top: -10%; 
            left: -10%; 
            width: 500px; 
            height: 500px; 
            background: rgba(99, 102, 241, 0.25); 
            border-radius: 50%; 
            filter: blur(80px); 
            z-index: 0; 
            animation: float 10s infinite ease-in-out alternate;
        }
        .blob-2 { 
            position: absolute; 
            bottom: -10%; 
            right: -10%; 
            width: 450px; 
            height: 450px; 
            background: rgba(56, 189, 248, 0.25); 
            border-radius: 50%; 
            filter: blur(70px); 
            z-index: 0; 
            animation: float 8s infinite ease-in-out alternate-reverse;
        }
        
        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(-25px) scale(1.04); }
        }

        /* Landscape Container */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 920px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .login-card { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 1.5rem; 
            box-shadow: 0 20px 45px rgba(67, 56, 202, 0.12), 0 4px 15px rgba(0, 0, 0, 0.04); 
            overflow: hidden; 
        }

        /* Sisi Kiri - Banner Berwarna */
        .brand-panel {
            background: linear-gradient(145deg, #3730a3 0%, #4338ca 50%, #6366f1 100%);
            color: #ffffff;
            padding: 3.5rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            top: -50px;
            right: -50px;
            pointer-events: none;
        }

        .logo-box {
            background: #ffffff;
            padding: 0.85rem 1.15rem;
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .logo-box img {
            height: 65px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .brand-title {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: -0.3px;
            margin-bottom: 0.35rem;
            color: #ffffff;
            position: relative;
            z-index: 2;
        }

        .brand-school {
            font-size: 0.92rem;
            color: #c7d2fe;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }

        .decorative-line {
            width: 45px;
            height: 4px;
            background: rgba(255, 255, 255, 0.45);
            border-radius: 4px;
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 2;
        }

        .brand-desc {
            font-size: 0.875rem;
            color: #e0e7ff;
            line-height: 1.55;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        /* Sisi Kanan - Form Login */
        .form-panel {
            background: #ffffff;
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.25rem;
            letter-spacing: -0.3px;
        }

        .form-heading p {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 1.75rem;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .input-group-text {
            background: #f8fafc;
            border-right: none;
            color: #94a3b8;
            border-radius: 0.75rem 0 0 0.75rem;
            padding-left: 1.25rem;
            border-color: #e2e8f0;
        }

        .form-control { 
            border-radius: 0 0.75rem 0.75rem 0; 
            padding: 0.8rem 1.25rem 0.8rem 0.5rem;
            font-size: 0.95rem; 
            border-left: none;
            font-weight: 500;
            border-color: #e2e8f0;
            background-color: #ffffff;
            color: #1e293b;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #e2e8f0;
            background-color: #ffffff;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 0.22rem rgba(99, 102, 241, 0.18);
            border-radius: 0.75rem;
        }
        
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--primary-light);
            color: var(--primary);
        }

        .toggle-password-btn {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: none;
            border-radius: 0 0.75rem 0.75rem 0;
            color: #94a3b8;
            padding: 0 1.15rem;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .toggle-password-btn:hover {
            color: var(--primary);
        }

        .input-group:focus-within .toggle-password-btn {
            border-color: var(--primary-light);
        }

        .btn-primary { 
            border-radius: 0.75rem; 
            padding: 0.85rem; 
            font-weight: 600; 
            font-size: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none; 
            transition: all 0.3s ease; 
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            color: #ffffff;
        }
        
        .btn-primary:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4); 
            background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary) 100%);
            color: #ffffff;
        }

        .form-footer {
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            color: #94a3b8;
            font-size: 0.82rem;
        }

        /* Responsif untuk Layar Kecil */
        @media (max-width: 768px) {
            .brand-panel {
                padding: 2.5rem 2rem;
                align-items: center;
                text-align: center;
            }
            .decorative-line {
                margin: 0 auto 1.25rem;
            }
            .form-panel {
                padding: 2.5rem 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="blob-1"></div>
    <div class="blob-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="row g-0">
                <!-- Sisi Kiri: Visual & Identitas Berwarna (Landscape) -->
                <div class="col-md-5 brand-panel">
                    <div class="logo-box">
                        <img src="{{ asset('assets/logo-v2.png') }}" alt="Logo SPK Edu">
                    </div>
                    <div class="brand-title">SPK Kebutuhan Layanan Pendidikan</div>
                    <div class="brand-school">SLB ABCD MUHAMMADIYAH PALU</div>
                    <div class="decorative-line"></div>
                    <p class="brand-desc">
                        Sistem Pendukung Keputusan penentuan rekomendasi layanan pendidikan bagi siswa disabilitas.
                    </p>
                </div>

                <!-- Sisi Kanan: Form Login -->
                <div class="col-md-7 form-panel">
                    <div class="form-heading">
                        <h3>Masuk ke Sistem</h3>
                        <p>Silakan masukkan kredensial akun Anda.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 border-0 bg-danger bg-opacity-10 text-danger text-sm mb-4 py-2 px-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Login Gagal</strong>
                            </div>
                            <ul class="mb-0 ps-3" style="font-size: 0.85rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('/login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus placeholder="Masukkan username">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="passwordInput" class="form-control" style="border-radius: 0;" required placeholder="Masukkan password">
                                <button type="button" class="toggle-password-btn" id="togglePasswordBtn" title="Lihat/Sembunyikan Password" tabindex="-1">
                                    <i class="bi bi-eye" id="toggleEyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mt-4 pt-1">
                            <button type="submit" class="btn btn-primary">
                                Masuk ke Sistem <i class="bi bi-arrow-right-short ms-1"></i>
                            </button>
                        </div>
                    </form>

                    <div class="form-footer">
                        &copy; {{ date('Y') }} SLB ABCD Muhammadiyah Palu &bull; All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('toggleEyeIcon');

        if (toggleBtn && passwordInput && eyeIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                eyeIcon.classList.toggle('bi-eye', !isPassword);
                eyeIcon.classList.toggle('bi-eye-slash', isPassword);
            });
        }
    </script>
</body>
</html>
