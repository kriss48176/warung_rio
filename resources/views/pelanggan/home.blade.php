@extends('layouts.pelanggan')

@section('content')
<style>
    /* 1. RESET & FUNDAMENTAL */
    .container-fluid, .container {
        padding-left: 0 !important;
        padding-right: 0 !important;
        max-width: 100% !important;
    }

    main { padding: 0 !important; margin: 0 !important; }

    /* 2. HERO SECTION MODERNIZE */
    .hero-homepage {
        position: relative;
        height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #000; /* Fallback color */
    }

    /* Background dengan Dual Layer: Image + Gradient Overlay */
    .hero-homepage .bg-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    .hero-homepage .bg-image {
        width: 100%;
        height: 100%;
        background-image: url('{{ asset("assets/gambar/1764332905_nasi goreng.jpg") }}');
        background-size: cover;
        background-position: center;
        transform: scale(1.1); /* Untuk efek zoom halus */
        filter: brightness(0.6) contrast(1.1);
        animation: slowZoom 20s infinite alternate;
    }

    /* Gradient overlay untuk kesan mewah */
    .hero-homepage .bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.8) 100%);
        z-index: 1;
    }

    /* 3. CONTENT & TYPOGRAPHY */
    .hero-content {
        position: relative;
        z-index: 10;
        color: #fff;
        text-align: center;
        padding: 0 15px;
    }

    .hero-tagline {
        font-size: 0.9rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: #ffc107;
        margin-bottom: 1rem;
        display: block;
        font-weight: 600;
        animation: fadeInUp 0.8s ease forwards;
    }

    .hero-homepage h1 {
        font-size: clamp(2.5rem, 10vw, 4.5rem);
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        text-shadow: 2px 4px 10px rgba(0,0,0,0.3);
        animation: fadeInUp 1s ease forwards;
        animation-delay: 0.2s;
        opacity: 0;
    }

    .hero-homepage p {
        font-size: clamp(1rem, 2.5vw, 1.25rem);
        max-width: 650px;
        margin: 0 auto 2.5rem;
        color: rgba(255,255,255,0.9);
        font-weight: 300;
        line-height: 1.6;
        animation: fadeInUp 1s ease forwards;
        animation-delay: 0.4s;
        opacity: 0;
    }

    /* 4. PREMIUM BUTTON */
    .btn-premium {
        background: #28a745;
        color: white;
        padding: 18px 45px;
        border-radius: 4px; /* Kotak sedikit tumpul lebih pro daripada bulat sempurna */
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: none;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        text-decoration: none;
        display: inline-block;
        animation: fadeInUp 1s ease forwards;
        animation-delay: 0.6s;
        opacity: 0;
    }

    .btn-premium:hover {
        background: #218838;
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(40, 167, 69, 0.4);
        color: #fff;
    }

    /* 5. ANIMATIONS */
    @keyframes slowZoom {
        from { transform: scale(1); }
        to { transform: scale(1.15); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Scroll Indicator */
    .scroll-down {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        color: #fff;
        opacity: 0.7;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0) translateX(-50%);}
        40% {transform: translateY(-10px) translateX(-50%);}
        60% {transform: translateY(-5px) translateX(-50%);}
    }
</style>

<section class="hero-homepage">
    <div class="bg-wrapper">
        <div class="bg-image"></div>
        <div class="bg-overlay"></div>
    </div>
    
    <div class="hero-content">
        <span class="hero-tagline">Authentic Indonesian Taste</span>
        <h1>Cita Rasa <br><span style="color: #ffc107;">Nusantara</span> Sejati</h1>
        <p>Menghidangkan resep warisan keluarga dengan kualitas bahan terbaik untuk pengalaman kuliner yang tak terlupakan.</p>

        <a href="{{ route('pelanggan.index') }}" class="btn-premium">
            <i class="fas fa-utensils me-2"></i> Jelajahi Menu Kami
        </a>
    </div>

    <div class="scroll-down">
        <i class="fas fa-chevron-down fa-2x"></i>
    </div>
</section>

{{-- Section Berikutnya --}}
<div class="container py-5 mt-4">
    <div class="row text-center justify-content-center">
        <div class="col-md-8">
            <h6 class="text-success fw-bold text-uppercase" style="letter-spacing: 2px;">Value Kami</h6>
            <h2 class="fw-bold display-6 mb-4">Mengapa Memilih Rumah Makan Rio?</h2>
            <div class="bg-success mx-auto mb-4" style="width: 60px; height: 4px; border-radius: 2px;"></div>
        </div>
    </div>
</div>
@endsection