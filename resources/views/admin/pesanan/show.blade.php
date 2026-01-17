@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="{{ url('/admin/pesanan') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="fw-bold text-dark mb-0">Detail Pesanan <span class="text-primary">#{{ $pesanan->id_pesanan }}</span></h4>
        </div>
        <div class="text-end">
            <span class="text-muted small d-block">Tanggal Pesan:</span>
            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d F Y, H:i') }}</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Card Informasi Pelanggan --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-user me-2 text-primary"></i>Data Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted d-block">Nama Lengkap</label>
                            <span class="fw-bold">{{ $pesanan->nama_pelanggan }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted d-block">Nomor WhatsApp</label>
                            <a href="https://wa.me/{{ $pesanan->no_hp }}" target="_blank" class="text-decoration-none fw-bold text-success">
                                <i class="fab fa-whatsapp me-1"></i> {{ $pesanan->no_hp }}
                            </a>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block">Alamat Pengiriman</label>
                            <span class="fw-medium text-dark">{{ $pesanan->alamat }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Detail Item Pesanan --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-utensils me-2 text-primary"></i>Menu yang Dipesan</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small text-uppercase fw-bold">
                                <tr>
                                    <th class="ps-4 py-3">Menu</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->detail as $d)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $d->menu->nama_menu }}</div>
                                    </td>
                                    <td class="text-center text-muted">Rp {{ number_format($d->menu->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary px-3">{{ $d->jumlah }}</span>
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-dark">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="ps-4 py-3 fw-bold text-dark">TOTAL PEMBAYARAN</td>
                                    <td class="text-end pe-4 py-3">
                                        <h5 class="fw-bold text-primary mb-0">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h5>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Card Update Status --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 border-top border-primary border-4">
                <div class="card-body p-4 text-center">
                    <label class="small fw-bold text-uppercase text-muted mb-3 d-block">Status Pesanan Saat Ini</label>
                    <div class="mb-4">
                        @php
                            $badgeColor = [
                                'Menunggu' => 'info',
                                'Diproses' => 'warning',
                                'Diantar' => 'primary',
                                'Selesai' => 'success'
                            ][$pesanan->status_pesanan] ?? 'secondary';
                        @endphp
                        <h3 class="badge bg-{{ $badgeColor }} rounded-pill px-4 py-2 fs-6 shadow-sm">
                            {{ $pesanan->status_pesanan }}
                        </h3>
                    </div>

                    <form action="{{ url('/admin/pesanan/'.$pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select name="status_pesanan" class="form-select form-select-lg border-2" style="font-size: 0.9rem;">
                                <option value="Menunggu" {{ $pesanan->status_pesanan=='Menunggu'?'selected':'' }}>Menunggu</option>
                                <option value="Diproses" {{ $pesanan->status_pesanan=='Diproses'?'selected':'' }}>Diproses</option>
                                <option value="Diantar" {{ $pesanan->status_pesanan=='Diantar'?'selected':'' }}>Diantar</option>
                                <option value="Selesai" {{ $pesanan->status_pesanan=='Selesai'?'selected':'' }}>Selesai</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3 shadow-sm">
                            Update Status Pesanan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card Catatan Khusus --}}
            <div class="card border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-sticky-note me-2 text-warning"></i>Catatan Pembeli</h6>
                    <div class="p-3 bg-white rounded-3 border">
                        @if($pesanan->catatan)
                            <p class="mb-0 text-dark" style="font-style: italic;">"{{ $pesanan->catatan }}"</p>
                        @else
                            <p class="mb-0 text-muted small italic">Tidak ada catatan khusus dari pembeli.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .card { transition: all 0.3s ease; }
    .form-select:focus { border-color: #4e73df; box-shadow: none; }
    .bg-secondary-subtle { background-color: #e9ecef; }
</style>
@endsection