@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-sm-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-0 text-dark fw-bold">Data Pesanan</h4>
                <p class="text-muted small mb-0">Kelola antrean dan status pesanan dapur</p>
            </div>
            <div class="d-none d-sm-inline-block">
                <span class="badge bg-white shadow-sm border py-2 px-3 text-dark fw-normal">
                    <i class="fas fa-utensils text-primary me-2"></i> Antrean Aktif: {{ $pesanan->where('status_pesanan', 'diproses')->count() }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-list me-2 text-primary"></i>Daftar Pesanan Masuk
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th>Pelanggan</th>
                            <th>Info Kontak</th>
                            <th>Catatan Pelanggan</th> {{-- Kolom Catatan Tambahan --}}
                            <th>Total Harga</th>
                            <th>Status Pesanan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $p)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#{{ $p->id_pesanan }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->nama_pelanggan }}</div>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M, H:i') }}
                                </div>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ $p->no_hp }}" target="_blank" class="text-decoration-none text-dark">
                                    <i class="fab fa-whatsapp text-success me-1"></i> {{ $p->no_hp }}
                                </a>
                            </td>
                            <td>
                                @if($p->catatan)
                                    <div class="p-2 rounded bg-warning-subtle text-dark small border-start border-warning border-3" style="max-width: 200px;">
                                        <i class="fas fa-sticky-note me-1 text-warning"></i> {{ $p->catatan }}
                                    </div>
                                @else
                                    <span class="text-muted small italic">- Tidak ada catatan -</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-primary">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @php
                                    // Logika warna badge status
                                    $statusColor = 'info';
                                    if($p->status_pesanan == 'selesai') $statusColor = 'success';
                                    if($p->status_pesanan == 'dibatalkan') $statusColor = 'danger';
                                    if($p->status_pesanan == 'diproses') $statusColor = 'warning';
                                @endphp
                                <span class="badge bg-{{ $statusColor }} rounded-pill px-3 fw-medium">
                                    {{ ucfirst($p->status_pesanan) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ url('/admin/pesanan/'.$p->id_pesanan) }}" class="btn btn-sm btn-light border rounded-3 px-3 shadow-sm">
                                    <i class="fas fa-eye text-primary me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-20"></i>
                                <p>Belum ada pesanan yang masuk hari ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Pagination --}}
        @if($pesanan->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <nav aria-label="Page navigation" class="d-flex justify-content-center">
                <ul class="pagination pagination-sm">
                    {{-- Previous Page Link --}}
                    @if ($pesanan->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $pesanan->previousPageUrl() }}">Previous</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($pesanan->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $pesanan->nextPageUrl() }}">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .bg-warning-subtle { background-color: #fffbeb; }
    .table th { font-size: 0.75rem; letter-spacing: 0.5px; }
    .table-hover tbody tr:hover { background-color: #f1f5f9; transition: 0.2s; }
    
    /* Perbaikan Pagination */
    .custom-pagination svg { width: 20px; height: 20px; }
    .custom-pagination nav > div:first-child { display: none !important; }
</style>
@endsection