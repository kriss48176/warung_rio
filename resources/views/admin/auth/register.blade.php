<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - Warung Rio 1</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .register-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 25px;
            width: 90%;
            max-width: 300px; /* Ukuran disamakan menjadi 300px */
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .register-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-header .logo {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 5px;
        }

        .register-header h2 {
            color: #333;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 4px;
        }

        .register-header p {
            color: #666;
            font-size: 0.75rem;
            margin-bottom: 0;
        }

        .form-floating {
            margin-bottom: 12px;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 0.85rem;
            height: calc(3rem + 2px);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.2);
        }

        .form-floating > label {
            font-size: 0.85rem;
            padding: 0.8rem 0.75rem;
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e1e5e9;
            border-right: none;
            color: #666;
            width: 40px;
            justify-content: center;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 6px;
            padding: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .alert {
            font-size: 0.75rem;
            padding: 8px;
            margin-bottom: 15px;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="register-header">
            <div class="logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>Register Admin</h2>
            <p>Buat Akun Baru</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0 ps-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.register.process') }}" method="POST">
            @csrf

            <div class="input-group mb-2">
                <span class="input-group-text">
                    <i class="fas fa-user fa-sm"></i>
                </span>
                <div class="form-floating flex-grow-1">
                    <input type="text" class="form-control" id="username" name="username"
                           placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text">
                    <i class="fas fa-lock fa-sm"></i>
                </span>
                <div class="form-floating flex-grow-1">
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">
                    <i class="fas fa-check-double fa-sm"></i>
                </span>
                <div class="form-floating flex-grow-1">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                           placeholder="Konfirmasi" required>
                    <label for="password_confirmation">Konfirmasi</label>
                </div>
            </div>

            <button type="submit" class="btn btn-register">
                <i class="fas fa-user-plus me-2"></i>Daftar
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('admin.login') }}">
                <i class="fas fa-arrow-left me-1"></i>Sudah punya akun? Login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>