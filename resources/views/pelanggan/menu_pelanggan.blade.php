@extends('layouts.pelanggan')

@section('content')
<div class="container-fluid px-3 py-2" style="background: #f8fbf9; min-height: 100vh;">
    {{-- Judul Dinamis --}}
    <div class="px-2 mb-3 mt-2">
        <h3 class="fw-bold text-dark mb-0">
            {{ isset($kategoriAktif) ? $kategoriAktif->nama_kategori : 'Daftar Menu' }}
        </h3>
        <div class="text-muted small">Warung Rio 1</div>
    </div>

    {{-- KATEGORI STICKY --}}
    <div class="kategori-wrapper shadow-sm">
        <div class="d-flex flex-nowrap gap-2 overflow-auto py-3 px-3 custom-scroll">
            <a href="{{ route('pelanggan.index') }}"
               class="btn {{ Request::routeIs('pelanggan.index') ? 'btn-success' : 'btn-outline-success bg-white' }} rounded-pill px-4 fw-bold flex-shrink-0 border-success shadow-sm">
                <i class="fas fa-utensils me-2"></i>Semua
            </a>

            @foreach($kategoris as $kategori)
                @php
                    $isActive = request()->segment(count(request()->segments())) == $kategori->id_kategori;
                @endphp
                <a href="{{ route('pelanggan.menu.kategori', $kategori->id_kategori) }}"
                   class="btn {{ $isActive ? 'btn-success' : 'btn-outline-success bg-white' }} rounded-pill px-4 fw-bold flex-shrink-0 border-success shadow-sm">
                    {{ $kategori->nama_kategori }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- GRID MENU - PAKSA 5 KOLOM --}}
    <div class="row-5-cols mt-2">
        @forelse($menus as $menu)
        <div class="col-custom">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-menu overflow-hidden">
                <div class="position-relative">
                    {{-- Rasio Gambar Kotak 1:1 Agar Seragam --}}
                    <div class="ratio ratio-1x1">
                        <img src="{{ asset('assets/gambar/'.$menu->gambar) }}"
                             class="card-img-top object-fit-cover"
                             alt="{{ $menu->nama_menu }}">
                    </div>
                    
                    {{-- Badge Rating (Opsional, sesuai gambar kamu) --}}
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-success-subtle text-success small rounded-pill shadow-sm">
                            <i class="fas fa-star text-warning me-1"></i>4.5
                        </span>
                    </div>

                    <div class="position-absolute bottom-0 start-0 w-100 p-2 bg-gradient-dark text-white">
                        <span class="fw-bold" style="font-size: 0.85rem;">Rp {{ number_format($menu->harga,0,',','.') }}</span>
                    </div>
                </div>

                <div class="card-body p-3 d-flex flex-column">
                    <h6 class="fw-bold mb-1 text-dark text-truncate" style="font-size: 0.85rem;">{{ $menu->nama_menu }}</h6>
                    
                    <form action="{{ route('keranjang.add', $menu->id_menu) }}" method="POST" class="mt-auto">
                        @csrf
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" name="jumlah" value="1" min="1"
                                   class="form-control form-control-sm border-light bg-light text-center fw-bold rounded-3 shadow-none"
                                   style="width: 40px; height: 32px; font-size: 0.75rem; padding: 0;">
                            
                            <button type="submit" class="btn btn-success btn-sm flex-grow-1 rounded-3 d-flex align-items-center justify-content-center shadow-none" style="height: 32px;">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Menu belum tersedia.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    /* CSS UNTUK PAKSA 5 KOLOM DI LAYAR BESAR */
    .row-5-cols {
        display: flex;
        flex-wrap: wrap;
        margin-right: -8px;
        margin-left: -8px;
    }

    .col-custom {
        position: relative;
        width: 100%;
        padding-right: 8px;
        padding-left: 8px;
        margin-bottom: 16px;
        /* Default HP: 2 kolom */
        flex: 0 0 50%;
        max-width: 50%;
    }

    @media (min-width: 992px) {
        /* Layar PC/Laptop: Paksa 5 Kolom (100% / 5 = 20%) */
        .col-custom {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    .kategori-wrapper {
        position: sticky;
        top: 70px;
        z-index: 1000;
        background: white;
        margin: 0 -15px 15px -15px;
        border-bottom: 1px solid #f1f1f1;
    }

    .custom-scroll::-webkit-scrollbar { display: none; }
    
    .card-menu { transition: transform 0.2s ease; background: white; }
    .card-menu:hover { transform: translateY(-3px); }

    .bg-gradient-dark {
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
    }

    .bg-success-subtle { background-color: #e8f5e9 !important; }
    .object-fit-cover { object-fit: cover !important; }
</style>
@endsection