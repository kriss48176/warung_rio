@extends('layouts.pelanggan')

@section('content')
<div class="py-4" style="background: #f8f9fa; min-height: 100vh;">
    <div class="container" style="max-width: 850px;">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-0">Pesanan Saya</h4>
                <p class="text-muted small mb-0">Lacak dan lihat riwayat kuliner Anda</p>
            </div>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-success rounded-pill px-4 btn-sm shadow-sm">
                Pesan Lagi
            </a>
        </div>

        @if($statuses->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
                <div class="card-body">
                    <i class="fas fa-receipt fa-4x text-light mb-3"></i>
                    <p class="text-muted">Belum ada riwayat pesanan.</p>
                </div>
            </div>
        @else
            <div class="row g-3">
                @foreach($statuses as $status)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        {{-- Header Card: Info Tanggal & Status --}}
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shopping-bag text-success me-2"></i>
                                <div class="small fw-bold text-dark me-2">Belanja</div>
                                <div class="text-muted small">{{ $status->created_at->format('d M Y') }}</div>
                                <span class="mx-2 text-light">|</span>
                                <div class="text-muted small fw-medium">#{{ $status->id }}</div>
                            </div>
                            
                            @php
                                $statusMap = [
                                    'menunggu' => ['bg' => '#fff4e5', 'text' => '#b45309'],
                                    'diproses' => ['bg' => '#e0f2fe', 'text' => '#0369a1'],
                                    'diantar' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'selesai' => ['bg' => '#f0fdf4', 'text' => '#15803d'],
                                    'dibatalkan' => ['bg' => '#fef2f2', 'text' => '#991b1b'],
                                ];
                                $st = $statusMap[strtolower($status->status)] ?? ['bg' => '#fff4e5', 'text' => '#b45309'];
                            @endphp
                            
                            <span class="badge border-0" style="background-color: {{ $st['bg'] }}; color: {{ $st['text'] }}; font-size: 0.7rem; padding: 5px 12px;">
                                {{ ucfirst($status->status) }}
                            </span>
                        </div>

                        <div class="card-body px-4">
                            <div class="row align-items-center">
                                {{-- Daftar Menu --}}
                                <div class="col-md-8">
                                    @php $firstItem = $status->items[0] ?? null; @endphp
                                    @if($firstItem)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-utensils text-muted"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0">{{ $firstItem['nama_menu'] }}</h6>
                                                <div class="text-muted small">
                                                    {{ $firstItem['jumlah'] }} unit x Rp{{ number_format($firstItem['total_harga'] / $firstItem['jumlah'], 0, ',', '.') }}
                                                </div>
                                                @if(count($status->items) > 1)
                                                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                                        +{{ count($status->items) - 1 }} menu lainnya
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Total Belanja --}}
                                <div class="col-md-4 text-md-end mt-3 mt-md-0 border-start-md">
                                    <div class="small text-muted mb-1">Total Belanja</div>
                                    <h5 class="fw-bold text-success mb-0">Rp{{ number_format($status->total_harga, 0, ',', '.') }}</h5>
                                </div>
                            </div>

                            <hr class="my-3 opacity-50">

                            {{-- Info Pengiriman --}}
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-map-marker-alt text-muted me-2 mt-1" style="font-size: 0.8rem;"></i>
                                        <div>
                                            <small class="text-muted d-block">Alamat Pengiriman</small>
                                            <span class="small fw-medium text-dark">{{ $status->alamat }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <small class="text-muted d-block">Metode Pembayaran</small>
                                    <span class="badge bg-light text-dark border fw-bold" style="font-size: 0.7rem;">
                                        {{ strtoupper(str_replace('_', ' ', $status->metode_pembayaran)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .card { border: 1px solid rgba(0,0,0,.08); transition: all 0.2s ease; }
    .card:hover { border-color: #28a745; }
    
    @media (min-width: 768px) {
        .border-start-md {
            border-left: 1px solid #eee;
            padding-left: 25px;
        }
    }
</style>
@endsection