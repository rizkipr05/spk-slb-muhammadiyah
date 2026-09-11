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
        body { 
            background: linear-gradient(135deg, #eef2f3 0%, #8e9eab 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow: hidden;
            position: relative;
        }
        
        /* Decorative Background Blobs */
        .blob-1 { position: absolute; top: -10%; left: -10%; width: 500px; height: 500px; background: rgba(99, 102, 241, 0.2); border-radius: 50%; filter: blur(80px); z-index: 0; animation: float 10s infinite ease-in-out alternate;}
        .blob-2 { position: absolute; bottom: -10%; right: -10%; width: 400px; height: 400px; background: rgba(56, 189, 248, 0.2); border-radius: 50%; filter: blur(60px); z-index: 0; animation: float 8s infinite ease-in-out alternate-reverse;}
        
        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(-30px) scale(1.05); }
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .login-card { 
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 1.5rem; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08), inset 0 0 0 1px rgba(255,255,255,0.4); 
            overflow: hidden; 
        }

        .login-header { 
            padding: 2.5rem 2.5rem 1rem; 
            text-align: center; 
        }
        
        .login-header .logo-container {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.25rem;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(67, 56, 202, 0.15);
            transform: rotate(-3deg);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .login-header .logo-container:hover {
            transform: rotate(0deg) scale(1.05);
            box-shadow: 0 12px 25px rgba(67, 56, 202, 0.2);
        }

        .login-header img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .login-header h3 { 
            font-weight: 800; 
            color: #1e293b; 
            margin-bottom: 0.2rem;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }
        
        .login-body { padding: 1.5rem 2.5rem 2.5rem; }
        
        .input-group-text {
            background: transparent;
            border-right: none;
            color: #94a3b8;
            border-radius: 0.75rem 0 0 0.75rem;
            padding-left: 1.25rem;
        }

        .form-control { 
            border-radius: 0 0.75rem 0.75rem 0; 
            padding: 0.8rem 1.25rem 0.8rem 0.5rem;
            font-size: 0.95rem; 
            border-left: none;
            font-weight: 500;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.15);
            border-radius: 0.75rem;
        }
        
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--primary-light);
            color: var(--primary);
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
        }
        
        .btn-primary:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4); 
        }

        .decorative-line {
            width: 40px;
            height: 4px;
            background: var(--primary-light);
            margin: 1.5rem auto 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="blob-1"></div>
    <div class="blob-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo SPK Edu">
                </div>
                <h3>SPK Kebutuhan Layanan Pendidikan</h3>
                <p class="text-muted fw-medium mb-0" style="font-size: 0.9rem;">SLB ABCD MUHAMMADIYAH PALU</p>
                <div class="decorative-line"></div>
            </div>
            <div class="login-body">
                @if($errors->any())
                    <div class="alert alert-danger rounded-3 border-0 bg-danger bg-opacity-10 text-danger text-sm mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Login Gagal</strong>
                        </div>
                        <ul class="mb-0 mt-2 ps-3" style="font-size: 0.85rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ url('/login') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold small mb-2">Username</label>
                        <div class="input-group drop-shadow-sm">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus placeholder="Masukkan username">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold small mb-2">Password</label>
                        <div class="input-group drop-shadow-sm">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                        </div>
                    </div>
                    <div class="d-grid mt-4 pt-2">
                        <button type="submit" class="btn btn-primary">
                            Masuk ke Sistem <i class="bi bi-arrow-right-short ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
