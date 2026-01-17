@extends('layouts.pelanggan')

@section('content')
{{-- Gunakan container-fluid tanpa padding (px-0) untuk menempel ke pinggir layar --}}
<div class="container-fluid px-0" style="background: linear-gradient(135deg, #f8fafb 0%, #f0f3f7 100%); min-height: 100vh;">
    
    {{-- BAGIAN JUDUL & KATEGORI (Tanpa Gap ke Navbar) --}}
    <div class="bg-white shadow-sm sticky-top" style="top: 70px; z-index: 1020;">
        <div class="px-3 pt-3 pb-2">
            <h4 class="fw-bold text-dark mb-0">
                {{ isset($kategoriAktif) ? $kategoriAktif->nama_kategori : 'Kategori Menu' }}
            </h4>
            <div class="text-muted small">Warung Rio 1</div>
        </div>

        {{-- KATEGORI SCROLL (Menempel tanpa margin berlebih) --}}
        <div class="d-flex flex-nowrap gap-2 overflow-auto pb-3 px-3 custom-scroll">
            {{-- Tombol Semua --}}
            <a href="{{ route('pelanggan.index') }}"
               class="btn {{ Request::routeIs('pelanggan.index') ? 'btn-success' : 'btn-outline-success bg-white' }} rounded-pill px-4 fw-bold flex-shrink-0 shadow-sm border-success btn-sm-custom">
                <i class="fas fa-utensils me-2"></i>Semua
            </a>

            {{-- Tombol Kategori Dinamis --}}
            @foreach($kategoris as $kategori)
                @php
                    $isActive = request()->segment(count(request()->segments())) == $kategori->id_kategori;
                @endphp
                <a href="{{ route('pelanggan.menu.kategori', $kategori->id_kategori) }}"
                   class="btn {{ $isActive ? 'btn-success' : 'btn-outline-success bg-white' }} rounded-pill px-4 fw-bold flex-shrink-0 shadow-sm border-success btn-sm-custom">
                    {{ $kategori->nama_kategori }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- GRID MENU (Full Screen dengan padding samping sedikit agar rapi) --}}
    <div class="px-3 py-3">
        <div class="row g-2 g-md-3"> {{-- g-2 membuat jarak antar card lebih rapat dan elegan --}}
            @forelse($menus as $menu)
            {{-- Ukuran card disesuaikan: col-6 (2 card di HP), col-md-3 (4 card di laptop) --}}
            <div class="col-6 col-md-4 col-lg-2-4"> 
                <div class="card h-100 border-0 shadow-sm rounded-4 card-menu overflow-hidden">
                    <div class="position-relative">
                        <img src="{{ asset('assets/gambar/'.$menu->gambar) }}"
                             class="card-img-top"
                             alt="{{ $menu->nama_menu }}"
                             style="height: 180px; object-fit: cover;"> {{-- Tinggi gambar ditingkatkan agar lebih jelas --}}
                        <div class="position-absolute top-0 end-0 m-2">
                             <span class="badge bg-success rounded-pill px-2">
                                <i class="fas fa-star me-1"></i>4.5
                             </span>
                        </div>
                    </div>

                    <div class="card-body p-2 d-flex flex-column">
                        <h6 class="fw-bold mb-1 text-dark text-truncate small-title">{{ $menu->nama_menu }}</h6>
                        <div class="text-success fw-bold mb-2 small">
                            Rp {{ number_format($menu->harga,0,',','.') }}
                        </div>

                        <form action="{{ route('keranjang.add', $menu->id_menu) }}" method="POST" class="mt-auto">
                            @csrf
                            <div class="d-flex align-items-center gap-1">
                                <input type="number" name="jumlah" value="1" min="1"
                                       class="form-control form-control-sm border-light bg-light text-center fw-bold rounded-2 px-1"
                                       style="width: 40px; font-size: 0.8rem;">
                                <button type="submit" class="btn btn-success btn-sm flex-grow-1 rounded-2 fw-bold p-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-utensils fa-3x text-light mb-3"></i>
                <p class="text-muted">Menu belum tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    /* Mengatur agar layout menempel ke navbar (asumsi navbar tinggi 70px) */
    body {
        padding-top: 0 !important;
    }

    .custom-scroll::-webkit-scrollbar { display: none; }
    .custom-scroll { -ms-overflow-style: none; scrollbar-width: none; }

    /* Ukuran khusus agar di laptop bisa 5 card per baris (opsional) */
    @media (min-width: 1200px) {
        .col-lg-2-4 {
            flex: 0 0 auto;
            width: 20%;
        }
    }

    .card-menu { 
        transition: all 0.2s ease-in-out; 
        border: 1px solid #f1f1f1 !important;
    }
    
    .card-menu:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }

    .small-title {
        font-size: 0.9rem;
        line-height: 1.2;
    }

    .btn-sm-custom {
        font-size: 0.85rem;
        padding: 6px 15px;
    }

    /* Hilangkan padding default dari layout utama jika ada */
    main {
        padding: 0 !important;
    }
</style>
@endsection