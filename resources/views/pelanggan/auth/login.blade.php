<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - Warung Rio 1</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sama seperti register */
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 340px; /* SAMA PERSIS */
        }

        .login-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .login-header .logo {
            font-size: 1.4rem; /* sama dengan register */
            color: #11998e;
        }

        .login-header h2 {
            font-size: 1.1rem; /* sama */
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 10px;
        }

        /* Sama dengan register */
        .form-control {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            border: 2px solid #e1e5e9;
        }

        .input-group-text {
            background: #f8f9fa;
            width: 36px; /* sama */
            border: 2px solid #e1e5e9;
            border-right: none;
            font-size: 0.85rem;
            color: #666;
        }

        .btn-login {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 8px;
            padding: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: 5px;
        }

        .alert {
            font-size: 0.8rem;
            padding: 8px 10px;
        }

        .back-link {
            text-align: center;
            margin-top: 12px;
        }

        .back-link a {
            font-size: 0.8rem;
            color: #11998e;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="login-container">
        
        <div class="login-header">
            <div class="logo"><i class="fas fa-utensils"></i></div>
            <h2>Login Pelanggan</h2>
        </div>

        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-1"></i>{{ session('error') }}
        </div>
        @endif

        <form action="{{ route('pelanggan.login.process') }}" method="POST">
            @csrf

            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-1"></i>Login
            </button>
        </form>

        <div class="back-link">
            <p class="mb-1">Belum punya akun? <a href="{{ route('pelanggan.register') }}">Daftar</a></p>
            <a href="/"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
        </div>

    </div>

</body>
</html>
