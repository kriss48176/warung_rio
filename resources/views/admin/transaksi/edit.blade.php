@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-xl-9 col-lg-12">
            
            {{-- Header & Navigation --}}
            <div class="d-flex align-items-center justify-content-between mb-3 ms-1">
                <div>
                    <h5 class="mb-0 text-dark fw-bold">Verifikasi Pembayaran</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item"><a href="{{ route('admin/transaksi.index') }}" class="text-decoration-none">Transaksi</a></li>
                            <li class="breadcrumb-item active">#{{ $transaksi->kode_transaksi }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin/transaksi.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>

            {{-- Form Update --}}
            <form action="{{ route('admin/transaksi.update', $transaksi->id_transaksi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- SISI KIRI: Ringkasan & Form Status --}}
                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white py-2 border-bottom">
                                <h6 class="m-0 fw-bold small text-primary text-uppercase"><i class="fas fa-receipt me-2"></i>Ringkasan Tagihan</h6>
                            </div>
                            <div class="card-body p-3">
                                <table class="table table-sm table-borderless mb-4">
                                    <tr class="border-bottom">
                                        <td class="text-muted small py-2">Kode Transaksi</td>
                                        <td class="fw-bold text-end py-2">{{ $transaksi->kode_transaksi }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted small py-2">Metode</td>
                                        <td class="text-end py-2">
                                            <span class="badge bg-light text-dark border">
                                                {{ strtoupper(str_replace('_', ' ', $transaksi->metode_pembayaran)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small py-2 fw-bold">TOTAL BAYAR</td>
                                        <td class="fw-bold text-primary text-end py-2 fs-5">
                                            Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>

                                <div class="mb-4">
                                    <label for="status_pembayaran" class="form-label fw-bold small text-muted mb-1">Status Konfirmasi</label>
                                    {{-- Note: Sesuai Controller kamu (pending, lunas, gagal) --}}
                                    <select name="status_pembayaran" id="status_pembayaran" class="form-select border-2">
                                        <option value="pending" {{ $transaksi->status_pembayaran == 'pending' ? 'selected' : '' }}>🕒 Pending</option>
                                        <option value="lunas" {{ $transaksi->status_pembayaran == 'lunas' ? 'selected' : '' }}>✅ Lunas (Valid)</option>
                                        <option value="gagal" {{ $transaksi->status_pembayaran == 'gagal' ? 'selected' : '' }}>❌ Gagal / Tolak</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">
                                    <i class="fas fa-check-circle me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- SISI KANAN: Tampilan Bukti Pembayaran --}}
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white py-2 border-bottom">
                                <h6 class="m-0 fw-bold small text-primary text-uppercase"><i class="fas fa-camera me-2"></i>Bukti Pembayaran</h6>
                            </div>
                            <div class="card-body p-2 bg-light-subtle text-center">
                                @if($transaksi->bukti_pembayaran)
                                    <div class="bg-white border rounded p-2">
                                        {{-- 
                                            PERBAIKAN UTAMA: 
                                            Controller kamu menyimpan path sebagai 'bukti_pembayaran/namafile.jpg'
                                            Maka asset cukup memanggil 'storage/' . path tersebut.
                                        --}}
                                        <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" 
                                             class="img-fluid rounded shadow-sm"
                                             style="max-height: 450px; width: auto; object-fit: contain;"
                                             onerror="this.src='https://placehold.co/600x400?text=Bukti+Tidak+Ditemukan'">
                                        
                                        <div class="mt-2 border-top pt-2">
                                            <a href="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" target="_blank" class="btn btn-link btn-sm text-decoration-none fw-bold">
                                                <i class="fas fa-search-plus me-1"></i> Perbesar Gambar
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="py-5 text-center bg-white rounded border">
                                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                        <p class="small text-muted">Pelanggan belum mengunggah bukti pembayaran.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-light-subtle { background-color: #f8f9fc; }
    .form-select { border-radius: 8px; font-size: 0.95rem; }
    .btn-primary { background-color: #4e73df; border: none; }
    .btn-primary:hover { background-color: #2e59d9; transform: translateY(-1px); }
    .table-sm td { padding: 12px 0; }
</style>
@endsection