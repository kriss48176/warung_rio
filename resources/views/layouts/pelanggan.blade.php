<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rumah Makan Rio 1</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* RESET TOTAL */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* NAVBAR KONSISTEN: Mengunci tinggi dan posisi */
        .navbar {
            z-index: 2000 !important;
            height: 70px;
            padding: 0 !important; /* Menghapus padding navbar agar tidak geser */
            margin: 0 !important;
        }

        /* Memastikan container navbar selalu di posisi yang sama di setiap halaman */
        .navbar .container {
            max-width: 1320px; /* Standar Bootstrap container */
            display: flex;
            align-items: center;
            height: 100%;
        }

        /* MAIN AREA: Menghilangkan gap putih di bawah navbar */
        main {
            flex: 1;
            margin: 0 !important;
            padding: 0 !important; /* Hilangkan padding default */
        }

        /* Menghilangkan gap khusus untuk konten Home */
        .home-wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Padding untuk halaman selain Home agar teks tidak mepet layar */
        .other-wrapper {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        footer {
            background-color: #343a40;
            color: #fff;
            padding: 30px 0;
            margin-top: auto;
        }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="/assets/gambar/Rio.png" alt="Logo" style="height: 40px; width: 40px; margin-right: 12px;">
                <span class="fw-bold">Rio 1</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link {{ Request::is('/') ? 'active fw-bold' : '' }}" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ Request::is('menu*') ? 'active fw-bold' : '' }}" href="{{ route('pelanggan.index') }}">Menu</a></li>

                    @if(session()->has('pelanggan_id'))
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('keranjang*') ? 'active fw-bold' : '' }}" href="{{ route('keranjang.index') }}">
                                <i class="fas fa-shopping-cart"></i> Keranjang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('status*') ? 'active fw-bold' : '' }}" href="{{ route('status.index') }}">Pesanan</a>
                        </li>
                        <li class="nav-item dropdown ms-lg-2">
                            <a class="nav-link dropdown-toggle fw-bold text-white px-3 bg-white bg-opacity-10 rounded-pill" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ session('pelanggan_nama') }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li>
                                    <form action="{{ route('pelanggan.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('pelanggan.login') }}">Login</a></li>
                        <li class="nav-item"><a class="btn btn-light btn-sm rounded-pill ms-lg-2 px-3" href="{{ route('pelanggan.register') }}">Daftar</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @if(Request::is('/'))
            {{-- Halaman Home mentok kiri-kanan dan mentok Navbar --}}
            <div class="home-wrapper">
                @yield('content')
            </div>
        @else
            {{-- Halaman lain (Menu, Keranjang) tetap rapi di tengah --}}
            <div class="container other-wrapper">
                @yield('content')
            </div>
        @endif
    </main>

    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; 2025 Rumah Makan Rio 1. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
