<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - GrosirApp</title>
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f3;
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border: 1px solid #e8e8e5;
            border-radius: 16px;
            padding: 36px 32px;
            width: 100%;
            max-width: 420px;
        }
        .login-logo {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }
        .login-logo i { color: #4f46e5; font-size: 22px; }
        .login-subtitle {
            font-size: 13px;
            color: #888;
            margin-bottom: 28px;
        }
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #444;
        }
        .form-control {
            border: 1px solid #e0e0dd;
            border-radius: 8px;
            font-size: 13px;
            padding: 10px 12px;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .btn-login {
            background: #4f46e5;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            padding: 10px;
            width: 100%;
        }
        .btn-login:hover { background: #4338ca; color: #fff; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-logo">
        <i class="ti ti-shopping-cart"></i> GrosirApp
    </div>
    <p class="login-subtitle">Masuk ke akun Anda untuk melanjutkan</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="admin@email.com"
                required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="••••••••"
                required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check mb-0">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember" style="font-size:13px">
                    Remember Me
                </label>
            </div>
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" style="font-size:12px;color:#4f46e5;text-decoration:none">
                Lupa password?
            </a>
            @endif
        </div>

        <button type="submit" class="btn-login">
            <i class="ti ti-login me-1"></i> Login
        </button>
    </form>
</div>

</body>
</html>