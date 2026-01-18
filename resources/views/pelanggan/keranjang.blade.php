@extends('layouts.pelanggan')

@section('content')
<div class="py-4" style="background: linear-gradient(135deg, #f0f7f4 0%, #f8fbf9 100%); min-height: 100vh;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0"><i class="fas fa-shopping-cart text-success me-2"></i>Keranjang</h3>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Menu
            </a>
        </div>

        @if($items->isEmpty())
            <div class="row justify-content-center mt-5">
                <div class="col-md-6 text-center">
                    <div class="card border-0 shadow-sm rounded-4 py-5 px-4 bg-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <span class="display-1 text-light">
                                    <i class="fas fa-shopping-basket"></i>
                                </span>
                            </div>
                            <h4 class="fw-bold text-dark">Wah, keranjangmu kosong!</h4>
                            <p class="text-muted mb-4">Sepertinya Anda belum memilih menu lezat kami hari ini.</p>
                            <a href="{{ route('pelanggan.index') }}" class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-utensils me-2"></i>Mulai Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @php $grandTotal = 0; @endphp
                                @foreach($items as $item)
                                    @php $grandTotal += $item->total_harga; @endphp
                                    <div class="list-group-item p-3 item-row border-0 border-bottom" data-item-id="{{ $item->id }}">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/gambar/'.$item->menu->gambar) }}" class="rounded-3 object-fit-cover shadow-sm" style="width:80px; height:80px;" alt="produk">

                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $item->menu->nama_menu }}</h6>
                                                <span class="text-success small fw-bold">Rp {{ number_format($item->harga,0,',','.') }}</span>

                                                <div class="d-flex align-items-center justify-content-between mt-2">
                                                    <div class="input-group input-group-sm shadow-sm border rounded-pill overflow-hidden" style="width: 100px;">
                                                        <button class="btn btn-white border-0 decrease-qty" data-item-id="{{ $item->id }}">
                                                            <i class="fas fa-minus text-danger small"></i>
                                                        </button>
                                                        <input type="text" class="form-control border-0 text-center fw-bold bg-white qty-input" value="{{ $item->jumlah }}" readonly data-item-id="{{ $item->id }}">
                                                        <button class="btn btn-white border-0 increase-qty" data-item-id="{{ $item->id }}">
                                                            <i class="fas fa-plus text-success small"></i>
                                                        </button>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="d-block small text-muted">Subtotal</span>
                                                        <span class="fw-bold item-total">Rp {{ number_format($item->total_harga,0,',','.') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <button class="btn btn-light btn-sm rounded-circle delete-item text-danger border shadow-sm ms-2" data-item-id="{{ $item->id }}" style="width:35px; height:35px;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-white sticky-top" style="top: 20px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 text-dark">Ringkasan Pesanan</h5>
                            <hr class="my-3 opacity-50">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5 fw-bold text-dark">Total Bayar</span>
                                <span class="h5 fw-bold text-success total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm hover-elevate">
                                Check Out Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .hover-elevate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3) !important;
        transition: all 0.3s ease;
    }
    .qty-input { font-size: 0.9rem; }
    .item-row:hover { background-color: #fafafa; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    // Fungsi refresh total diperbarui (Tanpa Pajak)
    function refreshTotals(res) {
        $('.total').text(formatRupiah(res.grand_total));
    }

    function updateQty(id, qty) {
        $.ajax({
            url: '{{ url("keranjang/update") }}/' + id,
            method: 'PUT',
            data: { jumlah: qty, _token: '{{ csrf_token() }}' },
            success: function(res) {
                $('.item-row[data-item-id="'+id+'"] .item-total').text(formatRupiah(res.item_total));
                refreshTotals(res);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseText);
            }
        });
    }

    $('.increase-qty').click(function() {
        let id = $(this).data('item-id');
        let input = $('.qty-input[data-item-id="'+id+'"]');
        let newVal = parseInt(input.val()) + 1;
        input.val(newVal);
        updateQty(id, newVal);
    });

    $('.decrease-qty').click(function() {
        let id = $(this).data('item-id');
        let input = $('.qty-input[data-item-id="'+id+'"]');
        let newVal = parseInt(input.val()) - 1;
        if (newVal >= 1) {
            input.val(newVal);
            updateQty(id, newVal);
        }
    });

    $('.delete-item').click(function() {
        if (!confirm('Hapus item ini?')) return;
        let id = $(this).data('item-id');
        let row = $('.item-row[data-item-id="'+id+'"]');

        $.ajax({
            url: '{{ url("keranjang/remove") }}/' + id,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                row.fadeOut(300, function() {
                    $(this).remove();
                    refreshTotals(res);
                    if ($('.item-row').length == 0) location.reload();
                });
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseText);
            }
        });
    });
});
</script>
@endsection