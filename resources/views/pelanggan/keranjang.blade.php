@extends('layouts.pelanggan')

@section('content')
<div class="py-2" style="background: linear-gradient(135deg, #f0f7f4 0%, #f8fbf9 100%); min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="fas fa-shopping-cart text-success me-2"></i>Keranjang</h3>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-success btn-sm rounded-pill">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Menu
        </a>
    </div>

    @if($items->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
            <div class="card-body">
                <i class="fas fa-shopping-basket fa-4x text-light mb-3"></i>
                <p class="text-muted fs-4">Keranjang Anda masih kosong.</p>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-success btn-lg px-5 rounded-pill">Mulai Pesan</a>
            </div>
        </div>
    @else
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @php $grandTotal = 0; @endphp
                            @foreach($items as $item)
                                @php $grandTotal += $item->total_harga; @endphp
                                <div class="list-group-item p-3 item-row border-0 border-bottom" data-item-id="{{ $item->id }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/gambar/'.$item->menu->gambar) }}" class="rounded-3 object-fit-cover" style="width:80px; height:80px;" alt="produk">

                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $item->menu->nama_menu }}</h6>
                                            <span class="text-success small fw-bold">Rp {{ number_format($item->harga,0,',','.') }}</span>

                                            <div class="d-flex align-items-center justify-content-between mt-2">
                                                <div class="input-group input-group-sm shadow-sm border rounded-pill overflow-hidden" style="width: 110px;">
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

                                        <button class="btn btn-light btn-sm rounded-circle delete-item text-danger border shadow-sm" data-item-id="{{ $item->id }}" style="width:35px; height:35px;">
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
                <div class="card border-0 shadow-sm rounded-4 bg-white sticky-top" style="top: 90px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold subtotal">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pajak (10%)</span>
                            <span class="fw-bold tax text-danger">+ Rp {{ number_format($grandTotal * 0.1, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold">Total</span>
                            <span class="h5 fw-bold text-success total">Rp {{ number_format($grandTotal * 1.1, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg w-100 rounded-pill py-3 fw-bold">Check Out</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function refreshTotals(res) {
        $('.subtotal').text(formatRupiah(res.grand_total));
        $('.tax').text('+ ' + formatRupiah(res.grand_total * 0.1));
        $('.total').text(formatRupiah(res.grand_total * 1.1));
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
