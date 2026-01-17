@extends('layouts.pelanggan')

@section('content')
<div class="container-fluid px-3 py-2" style="background: linear-gradient(135deg, #f0f7f4 0%, #f8fbf9 100%); min-height: 100vh;">
    {{-- Judul Dinamis --}}
    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                {{ isset($kategoriAktif) ? $kategoriAktif->nama_kategori : 'Kategori Menu' }}
            </h3>
            <div class="text-muted small">Warung Rio 1</div>
        </div>
    </div>

    {{-- KATEGORI STICKY (Sesuai Gambar Yang Anda Mau) --}}
    <div class="kategori-wrapper shadow-sm">
        <div class="d-flex flex-nowrap gap-2 overflow-auto py-3 px-3 custom-scroll">
            {{-- Tombol Semua --}}
            <a href="{{ route('pelanggan.index') }}"
               class="btn {{ Request::routeIs('pelanggan.index') ? 'btn-success' : 'btn-outline-success bg-white' }} rounded-pill px-4 fw-bold flex-shrink-0 shadow-sm border-success">
                <i class="fas fa-utensils me-2"></i>Semua
            </a>

            {{-- Tombol Kategori Dinamis --}}
            @foreach($kategoris as $kategori)
                @php
                    // Cek apakah ID kategori di URL sama dengan ID di loop
                    $isActive = request()->segment(count(request()->segments())) == $kategori->id_kategori;
                @endphp
                <a href="{{ route('pelanggan.menu.kategori', $kategori->id_kategori) }}"
                   class="btn {{ $isActive ? 'btn-success' : 'btn-outline-success bg-white' }} rounded-pill px-4 fw-bold flex-shrink-0 shadow-sm border-success">
                    {{ $kategori->nama_kategori }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- GRID MENU --}}
    <div class="row g-3 mt-2">
        @forelse($menus as $menu)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-menu overflow-hidden">
                <div class="position-relative">
                    <img src="{{ asset('assets/gambar/'.$menu->gambar) }}"
                         class="card-img-top"
                         alt="{{ $menu->nama_menu }}"
                         style="height: 150px; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 w-100 p-2 bg-gradient-dark text-white">
                        <span class="fw-bold">Rp {{ number_format($menu->harga,0,',','.') }}</span>
                    </div>
                </div>

                <div class="card-body p-3 d-flex flex-column">
                    <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $menu->nama_menu }}</h6>
                    <p class="text-muted mb-3 small">
                        <i class="fas fa-tag me-1 text-success"></i>{{ $menu->kategori->nama_kategori }}
                    </p>

                    <form action="{{ route('keranjang.add', $menu->id_menu) }}" method="POST" class="mt-auto">
                        @csrf
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" name="jumlah" value="1" min="1"
                                   class="form-control form-control-sm border-light bg-light text-center fw-bold rounded-3"
                                   style="width: 50px;">
                            <button type="submit" class="btn btn-success btn-sm flex-grow-1 rounded-3 fw-bold">
                                <i class="fas fa-plus me-1"></i> Tambah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Menu belum tersedia untuk kategori ini.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    /* Menangani Kategori Sticky agar tidak tertutup Navbar */
    .kategori-wrapper {
        position: sticky;
        top: 70px; /* Jarak pas di bawah navbar Rio 1 */
        z-index: 1000;
        background: white;
        margin: 0 -15px 15px -15px; /* Biar full width di layar HP */
        border-bottom: 1px solid #f1f1f1;
    }

    .custom-scroll::-webkit-scrollbar { display: none; }
    .custom-scroll { -ms-overflow-style: none; scrollbar-width: none; }

    .card-menu { transition: all 0.3s ease; }
    .card-menu:hover { transform: translateY(-5px); }

    .bg-gradient-dark {
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
    }

    /* Floating Cart Button sesuai gambar */
    .floating-cart {
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        z-index: 1050;
    }
</style>
@endsection
