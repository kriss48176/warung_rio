@extends('layouts.pelanggan')

@section('content')
<div class="checkout-container py-4" style="background: linear-gradient(135deg, #f0f7f4 0%, #f8fbf9 100%); min-height: 100vh;">
    <div class="row g-3 justify-content-center">
        {{-- KIRI: Form Pengiriman & Pembayaran --}}
        <div class="col-lg-7">
            <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm" enctype="multipart/form-data">
                @csrf

                {{-- Card Data Pengiriman --}}
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
                                <input type="text" name="nama_pelanggan" class="form-control form-control-sm custom-input" value="{{ $pelanggan->name ?? old('nama_pelanggan') }}" placeholder="Nama penerima" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold text-muted mb-1">No. WhatsApp</label>
                                <input type="tel" name="no_hp" class="form-control form-control-sm custom-input" value="{{ $pelanggan->phone ?? old('no_hp') }}" placeholder="08xxx" required>
                            </div>
                            <div class="col-12">
                                <label class="small fw-semibold text-muted mb-1">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control form-control-sm custom-input" rows="2" placeholder="Alamat pengiriman..." required>{{ $pelanggan->address ?? old('alamat') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Metode Pembayaran --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-primary-subtle text-primary me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                <i class="fas fa-wallet fa-sm"></i>
                            </div>
                            <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Metode Pembayaran</h6>
                        </div>

                        <div class="payment-options">
                            {{-- COD --}}
                            <label class="payment-item mb-2 d-block cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="cod" class="d-none" checked>
                                <div class="payment-content d-flex align-items-center p-2 border rounded-3 transition-all">
                                    <i class="fas fa-hand-holding-usd mx-2 text-secondary"></i>
                                    <div class="flex-grow-1 small fw-bold text-dark">COD (Bayar di Tempat)</div>
                                    <div class="check-mark d-none text-success"><i class="fas fa-check-circle"></i></div>
                                </div>
                            </label>

                            {{-- Transfer Bank --}}
                            <label class="payment-item d-block cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="bank_transfer" class="d-none">
                                <div class="payment-content d-flex align-items-center p-2 border rounded-3 transition-all">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="mx-2" style="height: 12px;">
                                    <div class="flex-grow-1 small fw-bold text-dark">Transfer Bank BCA</div>
                                    <div class="check-mark d-none text-success"><i class="fas fa-check-circle"></i></div>
                                </div>
                            </label>

                            {{-- Dropdown Detail Rekening (Efek Smooth) --}}
                            <div id="bankDetails" class="bank-details-wrapper">
                                <div class="p-3 rounded-3 border bg-white shadow-sm mt-2">
                                    <div class="row align-items-center mb-3 border-bottom pb-3">
                                        <div class="col-8">
                                            <span class="text-muted d-block" style="font-size: 0.75rem;">Nomor Rekening BCA</span>
                                            <span class="fw-bold text-primary fs-5" id="norek">0292943154</span>
                                            <span class="d-block fw-semibold text-dark">a/n Kristofer</span>
                                        </div>
                                        <div class="col-4 text-end">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="copyToClipboard('0292943154')" style="font-size: 0.7rem;">
                                                <i class="far fa-copy me-1"></i> Salin
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <label class="small fw-bold text-dark mb-2">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-sm">
                                                <input type="file" name="bukti_pembayaran" id="buktiPembayaran" class="form-control custom-input" accept="image/*">
                                            </div>
                                            <div class="mt-1 d-flex align-items-center">
                                                <i class="fas fa-info-circle text-muted me-1" style="font-size: 0.65rem;"></i>
                                                <small class="text-muted" style="font-size: 0.65rem;">Format: JPG, PNG. Maksimal file 2MB</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-3 fw-bold py-2 shadow-sm rounded-3" id="submitBtn">
                    <i class="fas fa-check-circle me-1"></i> KONFIRMASI PESANAN
                </button>
            </form>
        </div>

        {{-- KANAN: Ringkasan Pesanan --}}
        <div class="col-lg-5">
            {{-- Bagian Ringkasan Tetap Sama Seperti Kode Anda --}}
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="font-size: 0.9rem;"><i class="fas fa-receipt me-1"></i>Ringkasan</h6>
                    <div class="order-items mb-3" style="max-height: 200px; overflow-y: auto;">
                        @foreach($items as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="small">
                                <span class="badge bg-success-subtle text-success me-1">{{ $item->jumlah }}x</span>
                                <span class="text-dark">{{ Str::limit($item->menu->nama_menu, 20) }}</span>
                            </div>
                            <span class="small fw-bold">Rp {{ number_format($item->total_harga,0,',','.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="bg-light p-2 rounded-3">
                        <div class="d-flex justify-content-between mb-1 small text-muted">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($grandTotal,0,',','.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold text-success border-top mt-1 pt-1">
                            <span>Total Bayar</span>
                            <span>Rp {{ number_format($totalWithFee ?? $grandTotal * 1.1,0,',','.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-input { border: 1.5px solid #eee !important; border-radius: 8px !important; background-color: #fcfcfc !important; }
    .custom-input:focus { border-color: #198754 !important; background-color: #fff !important; box-shadow: none !important; }
    
    .payment-item input:checked + .payment-content { border-color: #198754 !important; background-color: #f0fff4 !important; }
    .payment-item input:checked + .payment-content .check-mark { display: block !important; }
    
    /* Efek Smooth Sliding untuk Bank Details */
    .bank-details-wrapper {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(-10px);
    }

    .bank-details-wrapper.active {
        max-height: 500px; /* Nilai cukup besar untuk menampung isi */
        opacity: 1;
        transform: translateY(0);
        margin-bottom: 15px;
    }

    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 0.2s ease; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="metode_pembayaran"]');
    const bankDetails = document.getElementById('bankDetails');
    const checkoutForm = document.getElementById('checkoutForm');
    const buktiInput = document.getElementById('buktiPembayaran');

    // Fungsi Toggle Smooth
    function handlePaymentChange() {
        const selected = document.querySelector('input[name="metode_pembayaran"]:checked').value;
        if (selected === 'bank_transfer') {
            bankDetails.classList.add('active');
        } else {
            bankDetails.classList.remove('active');
            buktiInput.value = ""; 
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', handlePaymentChange);
    });

    // Inisialisasi awal (jika bank transfer tercentang default)
    handlePaymentChange();

    // Validasi Submit
    checkoutForm.addEventListener('submit', function(e) {
        const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked').value;
        if (selectedMethod === 'bank_transfer' && buktiInput.files.length === 0) {
            e.preventDefault(); 
            alert('Silakan unggah bukti transfer terlebih dahulu.');
        }
    });
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Nomor rekening ' + text + ' berhasil disalin!');
    });
}
</script>
@endsection