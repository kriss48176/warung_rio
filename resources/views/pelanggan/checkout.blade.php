@extends('layouts.pelanggan')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="checkout-container py-4" style="background: linear-gradient(135deg, #f0f7f4 0%, #f8fbf9 100%); min-height: 100vh;">
    <div class="container">
        {{-- Tampilkan Error Validasi Laravel di Sini (PENTING UNTUK DEBUG) --}}
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-3" style="font-size: 0.8rem;">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tampilkan Error Session --}}
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-3" style="font-size: 0.8rem;">
                {{ session('error') }}
            </div>
        @endif

        <div class="row g-3 justify-content-center">
            <div class="col-lg-7">
                <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Informasi Pengiriman --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-success-subtle text-success me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                    <i class="fas fa-map-marker-alt fa-sm"></i>
                                </div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Informasi Pengiriman</h6>
                            </div>
                            
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-muted mb-1">Nama Penerima</label>
                                    <input type="text" name="nama_pelanggan" class="form-control form-control-sm custom-input" value="{{ $pelanggan->name ?? old('nama_pelanggan') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-semibold text-muted mb-1">No. WhatsApp</label>
                                    <input type="tel" name="no_hp" class="form-control form-control-sm custom-input" value="{{ $pelanggan->phone ?? old('no_hp') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-semibold text-muted mb-1">Alamat Lengkap</label>
                                    <textarea name="alamat" class="form-control form-control-sm custom-input" rows="2" required>{{ $pelanggan->address ?? old('alamat') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-primary-subtle text-primary me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                    <i class="fas fa-wallet fa-sm"></i>
                                </div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Metode Pembayaran</h6>
                            </div>

                            <div class="payment-options">
                                <label class="payment-item mb-2 d-block cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="cod" class="d-none" checked>
                                    <div class="payment-content d-flex align-items-center p-2 border rounded-3">
                                        <i class="fas fa-hand-holding-usd mx-2 text-secondary"></i>
                                        <div class="flex-grow-1 small fw-bold text-dark">COD (Bayar di Tempat)</div>
                                    </div>
                                </label>

                                <label class="payment-item d-block cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="bank_transfer" class="d-none">
                                    <div class="payment-content d-flex align-items-center p-2 border rounded-3">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="mx-2" style="height: 12px;">
                                        <div class="flex-grow-1 small fw-bold text-dark">Transfer Bank BCA</div>
                                    </div>
                                </label>

                                <div id="bankDetails" class="bank-details-wrapper">
                                    <div class="mt-2 p-2 bg-light border rounded-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                                            <div style="font-size: 0.75rem;">
                                                <span class="text-muted">BCA:</span> 
                                                <strong id="noRekening">0292943154</strong>
                                                <div class="text-muted">a/n Kristofer</div>
                                            </div>
                                            <button type="button" class="btn btn-xs btn-outline-primary" style="font-size: 0.65rem; padding: 1px 6px;" onclick="copyToClipboard()">Salin</button>
                                        </div>
                                        <div class="p-2 bg-white rounded border-dashed text-center">
                                            <label class="fw-bold d-block mb-1" style="font-size: 0.7rem;">Bukti Transfer <span class="text-danger">*</span></label>
                                            <input type="file" name="bukti_pembayaran" id="buktiPembayaran" class="form-control form-control-sm" accept="image/*" style="font-size: 0.7rem;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-3 fw-bold py-2 rounded-3" id="submitBtn">
                        <i class="fas fa-check-circle me-1"></i> KONFIRMASI PESANAN
                    </button>
                </form>
            </div>

            <div class="col-lg-5">
                {{-- Ringkasan Pesanan --}}
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-2 pb-2 border-bottom" style="font-size: 0.85rem;">Ringkasan</h6>
                        @foreach($items as $item)
                        <div class="d-flex justify-content-between mb-1 small" style="font-size: 0.75rem;">
                            <span>{{ $item->jumlah }}x {{ $item->menu->nama_menu }}</span>
                            <span class="fw-bold text-dark">Rp {{ number_format($item->total_harga,0,',','.') }}</span>
                        </div>
                        @endforeach
                        <div class="bg-light p-2 rounded mt-2">
                            <div class="d-flex justify-content-between fw-bold text-success">
                                <span style="font-size: 0.85rem;">Total Bayar</span>
                                <span style="font-size: 0.95rem;">Rp {{ number_format($grandTotal,0,',','.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-input { border: 1.5px solid #eee !important; border-radius: 8px !important; font-size: 0.8rem !important; }
    .payment-item input:checked + .payment-content { border-color: #198754 !important; background-color: #f0fff4 !important; }
    .bank-details-wrapper { max-height: 0; overflow: hidden; opacity: 0; transition: all 0.3s ease; }
    .bank-details-wrapper.active { max-height: 300px; opacity: 1; margin-top: 10px; }
    .border-dashed { border: 1.5px dashed #ddd; }
    #submitBtn:disabled { background-color: #6c757d !important; border-color: #6c757d !important; opacity: 0.6; cursor: not-allowed; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const radios = document.querySelectorAll('input[name="metode_pembayaran"]');
    const bankDetails = document.getElementById('bankDetails');
    const buktiInput = document.getElementById('buktiPembayaran');
    const submitBtn = document.getElementById('submitBtn');

    function validateSubmit() {
        const selected = document.querySelector('input[name="metode_pembayaran"]:checked').value;
        if (selected === 'bank_transfer') {
            submitBtn.disabled = (buktiInput.files.length === 0);
        } else {
            submitBtn.disabled = false;
        }
    }

    radios.forEach(r => {
        r.addEventListener('change', function() {
            bankDetails.classList.toggle('active', this.value === 'bank_transfer');
            validateSubmit();
        });
    });

    buktiInput.addEventListener('change', validateSubmit);
    validateSubmit(); // Cek saat load awal

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Memproses Pesanan',
            text: 'Mohon tunggu sebentar...',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        // Pastikan form dikirim secara fisik
        setTimeout(() => { form.submit(); }, 500);
    });
});

function copyToClipboard() {
    const text = document.getElementById('noRekening').innerText;
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Nomor BCA disalin', showConfirmButton: false, timer: 1500 });
    });
}
</script>
@endsection