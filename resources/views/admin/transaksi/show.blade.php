@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-3 text-gray-800">
                        <i class="fas fa-credit-card text-primary me-2"></i>
                        Detail Transaksi
                    </h1>
                    <p class="mb-0 text-primary">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ $transaksi->kode_transaksi }}
                    </p>
                </div>
                <a href="{{ route('admin/transaksi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Transaksi
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">ID Transaksi</label>
                                        <p>{{ $transaksi->id_transaksi }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kode Transaksi</label>
                                        <p>{{ $transaksi->kode_transaksi }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Pelanggan</label>
                                        <p>{{ $transaksi->pelanggan->nama ?? 'N/A' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Metode Pembayaran</label>
                                        <p>{{ ucfirst($transaksi->metode_pembayaran) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Total Tagihan</label>
                                        <p class="h5 text-success">Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Jumlah Dibayar</label>
                                        <p class="h5 text-info">Rp {{ number_format($transaksi->jumlah_dibayar ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status Pembayaran</label>
                                        <span class="badge fs-6 bg-{{ $transaksi->status_pembayaran == 'lunas' ? 'success' : ($transaksi->status_pembayaran == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($transaksi->status_pembayaran) }}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tanggal Dibuat</label>
                                        <p>{{ $transaksi->created_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($transaksi->catatan)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Catatan</label>
                                    <p>{{ $transaksi->catatan }}</p>
                                </div>
                            @endif

                            @if($transaksi->bukti_pembayaran)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Bukti Pembayaran</label>
                                    <div>
                                        <img src="{{ asset('storage/bukti_pembayaran/' . $transaksi->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-width: 300px;">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Detail Pesanan
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($transaksi->pesanan)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ID Pesanan</label>
                                    <p>{{ $transaksi->pesanan->id_pesanan }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status Pesanan</label>
                                    <span class="badge bg-{{ $transaksi->pesanan->status_pesanan == 'Selesai' ? 'success' : ($transaksi->pesanan->status_pesanan == 'Diproses' ? 'warning' : ($transaksi->pesanan->status_pesanan == 'Diantar' ? 'info' : 'secondary')) }}">
                                        {{ $transaksi->pesanan->status_pesanan ?? 'Menunggu' }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Total Pesanan</label>
                                    <p>Rp {{ number_format($transaksi->pesanan->total_harga ?? 0, 0, ',', '.') }}</p>
                                </div>
                            @else
                                <p class="text-muted">Data pesanan tidak ditemukan</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
