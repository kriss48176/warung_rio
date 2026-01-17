@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-3 text-gray-800">
                        <i class="fas fa-tachometer-alt text-primary me-2"></i>
                        Dashboard Admin
                    </h1>
                    <div id="welcome-message" style="transition: opacity 1s ease;">
                        <p class="mb-0 text-primary">
                            <i class="fas fa-user me-1"></i>
                            Selamat datang, {{ session('admin_username') }}!
                        </p>
                    </div>
                </div>
                <div class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ date('l, d F Y') }}
                </div>
            </div>
            <div id="flash-container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
            <div class="row mb-4">
                {{-- Kategori --}}
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Kategori</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Kategori::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tags fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Menu --}}
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Menu</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Menu::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-utensils fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pesanan --}}
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pesanan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Pesanan::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending --}}
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pesanan Pending</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ \App\Models\Pesanan::where('status_pesanan', 'Menunggu')->count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Transaksi --}}
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-dark shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Total Transaksi</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ \App\Models\Transaksi::count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    .border-left-dark { border-left: 0.25rem solid #1a1a1a !important; }
    .text-gray-800 { color: #5a5c69 !important; }
    .shadow { box-shadow: 0 0.15rem 1.75rem rgba(58, 59, 69, 0.15) !important; }
    .card { transition: transform 0.2s; border: none; }
    .card:hover { transform: translateY(-5px); }
</style>

{{-- JAVASCRIPT UNTUK AUTO-HIDE --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Auto Hide Pesanan Selamat Datang (5 Detik)
        const welcomeMsg = document.getElementById('welcome-message');
        if (welcomeMsg) {
            setTimeout(function() {
                welcomeMsg.style.opacity = '0'; // Animasi fade out
                setTimeout(function() {
                    welcomeMsg.style.display = 'none'; // Benar-benar hilang dari layout
                }, 1000);
            }, 5000); // 5000ms = 5 detik
        }

        // 2. Auto Hide Alert Flash Messages (7 Detik agar lebih lama sedikit)
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                // Menggunakan class Bootstrap untuk menutup alert secara halus
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 7000);
    });
</script>

@endsection
