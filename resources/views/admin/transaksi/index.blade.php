@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-sm-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-0 text-dark fw-bold">Manajemen Transaksi</h4>
                <p class="text-muted small mb-0">Kelola dan pantau semua pembayaran masuk</p>
            </div>
            <div class="badge bg-white shadow-sm border py-2 px-3 text-dark fw-normal">
                <i class="fas fa-calendar-alt text-primary me-2"></i> {{ date('l, d F Y') }}
            </div>
        </div>
    </div>

    <div class="row mb-4">
        @php
            $stats = [
                ['label' => 'Total Transaksi', 'value' => \App\Models\Transaksi::count(), 'color' => 'primary', 'icon' => 'credit-card'],
                ['label' => 'Lunas', 'value' => \App\Models\Transaksi::where('status_pembayaran', 'lunas')->count(), 'color' => 'success', 'icon' => 'check-double'],
                ['label' => 'Pending', 'value' => \App\Models\Transaksi::where('status_pembayaran', 'pending')->count(), 'color' => 'warning', 'icon' => 'history'],
                ['label' => 'Gagal', 'value' => \App\Models\Transaksi::where('status_pembayaran', 'gagal')->count(), 'color' => 'danger', 'icon' => 'times-circle']
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 border-start border-{{ $stat['color'] }} border-4 rounded-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-{{ $stat['color'] }} text-uppercase mb-1">{{ $stat['label'] }}</div>
                            <div class="h4 mb-0 font-weight-bold text-dark">{{ number_format($stat['value'], 0, ',', '.') }}</div>
                        </div>
                        <div class="icon-shape bg-{{ $stat['color'] }}-subtle text-{{ $stat['color'] }} rounded-3 p-3">
                            <i class="fas fa-{{ $stat['icon'] }} fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-table me-2 text-primary"></i>Daftar Transaksi</h6>
        </div>
        <div class="card-body p-0">
            @if($transaksis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">ID & Kode</th>
                                <th>Pelanggan</th>
                                <th>Metode Pembayaran</th>
                                <th>Total Tagihan</th>
                                <th>Status</th>
                                <th class="text-center">Bukti</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $transaksi)
                                <tr>
                                    <td class="ps-4">
                                        <div class="small text-muted">#{{ $transaksi->id_transaksi }}</div>
                                        <div class="fw-bold text-primary">{{ $transaksi->kode_transaksi }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $transaksi->pelanggan->nama ?? 'N/A' }}</div>
                                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $transaksi->created_at->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal">{{ ucfirst($transaksi->metode_pembayaran) }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}</div>
                                        @if($transaksi->status_pembayaran == 'lunas')
                                            <div class="text-xs text-success small">Dibayar Penuh</div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColor = [
                                                'lunas' => 'success', 
                                                'pending' => 'warning', 
                                                'gagal' => 'danger'
                                            ][$transaksi->status_pembayaran] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} rounded-pill px-3 border border-{{ $statusColor }}">
                                            {{ ucfirst($transaksi->status_pembayaran) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($transaksi->bukti_pembayaran)
                                            <button type="button" class="btn btn-sm btn-info text-white rounded-circle" data-bs-toggle="modal" data-bs-target="#buktiModal{{ $transaksi->id_transaksi }}">
                                                <i class="fas fa-image"></i>
                                            </button>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group gap-1">
                                            <a href="{{ route('admin/transaksi.show', $transaksi->id_transaksi) }}" class="btn btn-sm btn-light border shadow-sm" title="Detail"><i class="fas fa-eye text-primary"></i></a>
                                            @if(strtolower($transaksi->metode_pembayaran) === 'bank transfer' || strtolower($transaksi->metode_pembayaran) === 'bank_transfer')
                                                <a href="{{ route('admin/transaksi.edit', $transaksi->id_transaksi) }}" class="btn btn-sm btn-light border shadow-sm" title="Verifikasi Pembayaran"><i class="fas fa-edit text-warning"></i></a>
                                            @else
                                                <button type="button" class="btn btn-sm btn-light border shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmCodModal{{ $transaksi->id_transaksi }}" title="Konfirmasi COD"><i class="fas fa-check text-success"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-flex justify-content-center">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm">
                                {{-- Previous Page Link --}}
                                @if ($transaksis->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $transaksis->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($transaksis->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $transaksis->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <img src="https://illustrations.popsy.co/gray/box.svg" style="width: 150px;" class="mb-3">
                    <p class="text-muted mt-2">Belum ada data transaksi masuk.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modals --}}
@foreach($transaksis as $transaksi)
    @if($transaksi->bukti_pembayaran)
    <div class="modal fade" id="buktiModal{{ $transaksi->id_transaksi }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold text-dark">Bukti Pembayaran #{{ $transaksi->kode_transaksi }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="bg-light p-2 rounded-3 border">
                        <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" class="img-fluid rounded-2 shadow-sm" style="max-height: 500px; object-fit: contain;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary w-100 rounded-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

{{-- Modals COD Confirmation --}}
@foreach($transaksis as $transaksi)
    @if(strtolower($transaksi->metode_pembayaran) !== 'bank transfer' && strtolower($transaksi->metode_pembayaran) !== 'bank_transfer')
    <div class="modal fade" id="confirmCodModal{{ $transaksi->id_transaksi }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold text-dark">Konfirmasi Pembayaran COD</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <span class="small text-muted fw-semibold text-uppercase">Kode Transaksi</span>
                                <div class="fw-bold text-primary">{{ $transaksi->kode_transaksi }}</div>
                            </div>
                            <div class="col-6 text-end">
                                <span class="small text-muted fw-semibold text-uppercase">Total Tagihan</span>
                                <div class="fw-bold text-dark">Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-12">
                                <hr class="my-2">
                            </div>
                            <div class="col-12">
                                <span class="small text-muted fw-semibold">Pelanggan</span>
                                <div class="fw-semibold text-dark">{{ $transaksi->pelanggan->nama ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $transaksi->pelanggan->no_hp ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 rounded-3 d-flex align-items-start" role="alert">
                        <i class="fas fa-info-circle me-3 mt-1 flex-shrink-0" style="color: #0c63e4;"></i>
                        <div class="small">Apakah pelanggan sudah melakukan pembayaran secara tunai? Klik "Konfirmasi Pembayaran" untuk menandai transaksi ini sebagai lunas.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-2" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin/transaksi.confirm-cod', $transaksi->id_transaksi) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-2">
                            <i class="fas fa-check me-2"></i>Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

<style>
    /* Stats & Colors */
    .bg-primary-subtle { background-color: rgba(78, 115, 223, 0.1); }
    .bg-success-subtle { background-color: rgba(28, 200, 138, 0.1); }
    .bg-warning-subtle { background-color: rgba(246, 194, 62, 0.1); }
    .bg-danger-subtle { background-color: rgba(231, 74, 59, 0.1); }
    
    .text-xs { font-size: 0.7rem; }
    .table th { font-weight: 700; color: #5a5c69; }
    .table-hover tbody tr:hover { background-color: #f8f9fc; }
    
    /* Pagination Fix */
    .pagination { margin-bottom: 0; }
    .page-link { color: #4e73df; border-radius: 5px !important; margin: 0 2px; }
    .page-item.active .page-link { background-color: #4e73df; border-color: #4e73df; }
    
    /* Modal Image Fix */
    .modal-backdrop { z-index: 1040 !important; }
    .modal { z-index: 1050 !important; }
</style>
@endsection