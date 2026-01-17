<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan - Warung Rio 1</title>

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

        /* Lebih besar sedikit */
        .register-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 340px; /* diperlebar */
        }

        .register-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .register-header .logo {
            font-size: 1.4rem; /* sedikit lebih besar */
            color: #11998e;
        }

        .register-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Input diperbesar sedikit */
        .input-group {
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 8px;
            padding: 8px 12px; /* diperbesar */
            font-size: 0.9rem;
        }

        .input-group-text {
            background: #f8f9fa;
            width: 36px; /* icon lebih besar */
            border: 2px solid #e1e5e9;
            border-right: none;
            font-size: 0.85rem;
            color: #666;
        }

        textarea.form-control {
            height: 55px; /* alamat sedikit lebih tinggi */
        }

        .btn-register {
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
    <div class="register-container">
        <div class="register-header">
            <div class="logo"><i class="fas fa-utensils"></i></div>
            <h2>Registrasi Pelanggan</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-1"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pelanggan.register.process') }}" method="POST">
            @csrf

            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="text" class="form-control" name="phone" placeholder="Nomor HP" required>
            </div>

            <!-- Alamat di bawah nomor HP -->
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                <textarea class="form-control" name="address" placeholder="Alamat" required></textarea>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi Password" required>
            </div>

            <button type="submit" class="btn btn-register">
                <i class="fas fa-user-plus me-1"></i>Daftar
            </button>
        </form>

        <div class="back-link">
            <p class="mb-1">Sudah punya akun? <a href="{{ route('pelanggan.login') }}">Login</a></p>
            <a href="/"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
