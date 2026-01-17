@extends('layouts.pelanggan')

@section('content')
<style>
    /* 1. FORCE FULL SCREEN - Menghilangkan semua gap */
    /* Menghapus padding container bawaan Bootstrap agar gambar mentok ke pinggir layar */
    .container-fluid, .container {
        padding-left: 0 !important;
        padding-right: 0 !important;
        max-width: 100% !important;
    }

    /* Menghilangkan gap di bawah navbar */
    main {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* 2. HERO SECTION FULL SCREEN */
    .hero-homepage {
        position: relative;
        /* Menggunakan 100vh agar benar-benar penuh satu layar HP/Laptop */
        height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    .hero-homepage .bg-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('{{ asset("assets/gambar/1764332905_nasi goreng.jpg") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        /* Overlay gelap agar teks terbaca */
        filter: brightness(0.4);
        z-index: 0;
    }

    .hero-homepage .hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 0 20px;
        text-align: center;
    }

    .hero-homepage h1 {
        font-size: clamp(2.5rem, 8vw, 5rem);
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .hero-homepage p {
        font-size: clamp(1rem, 3vw, 1.5rem);
        max-width: 800px;
        margin: 0 auto 2rem;
    }

    /* Tombol Hijau Full layar */
    .btn-jelajahi {
        background-color: #28a745;
        color: white;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: bold;
        text-transform: uppercase;
        border: none;
        transition: 0.3s;
    }

    .btn-jelajahi:hover {
        background-color: #218838;
        transform: scale(1.05);
        color: white;
    }
</style>

<section class="hero-homepage">
    <div class="bg-image"></div>
    <div class="hero-content">
        <h1>Rasakan Kelezatan <br><span style="color: #ffc107;">Tradisional</span> Asli</h1>
        <p>Rumah Makan Rio 1 menyajikan hidangan pilihan dengan bumbu rempah rahasia yang memanjakan lidah Anda.</p>

        <div class="cta-buttons">
            <a href="{{ route('pelanggan.index') }}" class="btn btn-jelajahi shadow-lg">
                <i class="fas fa-utensils me-2"></i> JELAJAHI MENU
            </a>
        </div>
    </div>
</section>

{{-- Section selanjutnya tetap menggunakan container biasa agar rapi --}}
<div class="container py-5">
    <div class="row text-center">
        <div class="col-12">
            <h2 class="fw-bold">Kenapa Memilih Kami?</h2>
        </div>
    </div>
    </div>
@endsection
