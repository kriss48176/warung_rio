<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Rumah Makan Rio</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f7f7f7;
            overflow-x: hidden;
        }

        /* SIDEBAR STYLING */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s ease;
            z-index: 1050;
        }

        .sidebar .admin-header {
            background: rgba(255,255,255,0.1);
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        /* Foto Profil Bulat Sempurna */
        .admin-icon img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .admin-icon img:hover {
            transform: scale(1.05);
            border-color: #fff;
        }

        .admin-icon i {
            font-size: 3.5rem;
            color: #fff;
            cursor: pointer;
            margin-bottom: 15px;
            display: inline-block;
        }

        .sidebar .admin-header h4 {
            color: #fff;
            margin: 0;
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* Menu Links */
        .sidebar a, .sidebar button.btn-logout {
            color: rgba(255,255,255,0.8);
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 0.95rem;
            border-left: 4px solid transparent;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            padding-left: 25px;
        }

        /* ACTIVE STATE STYLE */
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.2);
            color: #fff !important;
            font-weight: 600;
            border-left: 4px solid #ffffff;
            padding-left: 20px;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar .menu-section {
            padding: 15px 0 5px 0;
        }

        .sidebar .menu-section h6 {
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0 20px;
            margin-bottom: 10px;
        }

        /* Logout Position */
        .sidebar .logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 10px;
        }

        .sidebar .btn-logout {
            color: #ff8e8e !important;
        }

        .sidebar .btn-logout:hover {
            background: rgba(255, 107, 107, 0.2) !important;
            color: #fff !important;
        }

        /* CONTENT AREA */
        .content {
            margin-left: 250px;
            padding: 25px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* TOGGLE BUTTON */
        .toggle-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background 0.2s;
        }

        .toggle-btn:hover {
            background: #5a67d8;
        }

        /* STATE: COLLAPSED */
        .sidebar-collapsed .sidebar {
            left: -250px;
        }

        .sidebar-collapsed .content {
            margin-left: 0;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .sidebar { left: -250px; }
            .content { margin-left: 0; }
            .sidebar-collapsed .sidebar { left: 0; }
        }
    </style>
</head>
<body>

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="admin-header">
            <div class="admin-icon">
                @php
                    $admin = \App\Models\Admin::find(session('admin_id'));
                @endphp

                @if($admin && $admin->photo)
                    <img src="{{ asset('storage/admin_photos/' . $admin->photo) }}?t={{ time() }}" 
                         alt="Admin Photo" 
                         onclick="document.getElementById('photoInput').click();">
                @else
                    <i class="fas fa-user-circle" onclick="document.getElementById('photoInput').click();"></i>
                @endif

                {{-- Hidden Form Upload --}}
                <form action="{{ route('admin.updatePhoto') }}" method="POST" enctype="multipart/form-data" id="photoForm" style="display: none;">
                    @csrf
                    <input type="file" id="photoInput" name="photo" accept="image/*" onchange="document.getElementById('photoForm').submit();">
                </form>
            </div>
            <h4>{{ $admin->username ?? 'Admin' }}</h4>
        </div>

        <div class="menu-section">
            <h6>Utama</h6>
            <a href="{{ url('/admin') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </div>

        <div class="menu-section">
            <h6>Manajemen Resto</h6>
            <a href="{{ url('/admin/kategori') }}" class="{{ request()->is('admin/kategori*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Kelola Kategori
            </a>
            <a href="{{ url('/admin/menu') }}" class="{{ request()->is('admin/menu*') ? 'active' : '' }}">
                <i class="fas fa-utensils"></i> Kelola Menu
            </a>
            <a href="{{ url('/admin/pesanan') }}" class="{{ request()->is('admin/pesanan*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Kelola Pesanan
            </a>
            <a href="{{ route('admin/transaksi.index') }}" class="{{ request()->is('admin/transaksi*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i> Kelola Transaksi
            </a>
        </div>

        <div class="logout">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout 
                </button>
            </form>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="content">
        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars me-2"></i> Menu
        </button>

        @yield('content')
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
        }

        // Tutup sidebar otomatis di layar kecil
        if (window.innerWidth < 768) {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

</body>
</html>