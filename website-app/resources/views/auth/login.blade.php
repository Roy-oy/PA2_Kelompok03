<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Puskesmas Dashboard') }} - Login</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #15803d; /* Hijau tua */
            --primary-light: #22c55e; /* Hijau lebih terang */
            --primary-medium: #16a34a; /* Medium green untuk aksen */
            --primary-dark: #166534;  /* Hijau sangat tua */
            --primary-deeper: #14532d; /* Hijau lebih gelap */
            --primary-very-light: #dcfce7; /* Hijau sangat terang */
            --primary-ultra-light: #f0fdf4; /* Hijau ultra terang untuk background */
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color:rgb(251, 255, 249); /* Background putih */
            color: #334155;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }
        
        .login-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 32px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background-color: var(--primary-color);
        }
        
        .login-logo {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
        }
        
        .login-logo img {
            width: 56px;
            height: auto;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .login-title h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }
        
        .login-title p {
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .input-group-text {
            background-color: #f9fafb;
            border-color: #e5e7eb;
            color: #6b7280;
        }
        
        .form-control {
            border-color: #e2e8f0;
            padding: 0.625rem 0.75rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-medium);
            box-shadow: 0 0 0 0.25rem rgba(21, 128, 61, 0.15);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 4px 10px rgba(21, 128, 61, 0.2);
        }
        
        .remember-me {
            font-size: 0.875rem;
            color: #64748b;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.75rem;
            color: #64748b;
        }
        
        /* Alert styles */
        .alert-success {
            background-color: var(--primary-very-light);
            border-color: var(--primary-light);
            color: var(--primary-dark);
        }
        
        /* Custom input group - smoother look */
        .input-group {
            border-radius: 0.375rem;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
            <div class="login-title">
                <h1>Login ke Sistem</h1>
                <p>Masukkan kredensi untuk mengakses dashboard</p>
            </div>
            
            <div class="card-body">
                @if(session('error'))
                <div class="alert alert-danger small py-2" role="alert">
                    {{ session('error') }}
                </div>
                @endif
                
                @if(session('message'))
                <div class="alert alert-success small py-2" role="alert">
                    {{ session('message') }}
                </div>
                @endif
                
                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                                placeholder="Masukkan email anda">
                        </div>
                        @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label">Password</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                name="password" required autocomplete="current-password" 
                                placeholder="Masukkan password anda">
                        </div>
                        @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label remember-me" for="remember">
                                Ingat saya
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <button type="submit" class="btn btn-primary w-100 btn-login">
                            Masuk
                        </button>
                    </div>
                </form>
                
                <div class="footer-text">
                    &copy; {{ date('Y') }} Puskesmas &middot; Sistem Informasi Manajemen Puskesmas
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 