@extends('layouts.pelanggan')

@section('content')
<div class="py-2" style="background: linear-gradient(135deg, #f0f7f4 0%, #f8fbf9 100%); min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="fas fa-history me-2 text-success"></i>Pesanan Saya</h4>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-success btn-sm rounded-pill">
            <i class="fas fa-plus me-1"></i> Pesan Lagi
        </a>
    </div>

    @if($statuses->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
            <div class="card-body">
                <i class="fas fa-receipt fa-4x text-light mb-3"></i>
                <p class="text-muted fs-5">Belum ada riwayat pesanan.</p>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-success px-4">Mulai Belanja</a>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($statuses as $status)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    {{-- Header Status --}}
                    <div class="card-header bg-white border-bottom py-2 d-flex justify-content-between align-items-center">
                        <span class="small text-muted fw-bold text-uppercase">ID #{{ $status->id }}</span>
                        @php
                            $badgeClass = 'bg-warning';
                            $icon = 'fa-clock';
                            if($status->status == 'proses') { $badgeClass = 'bg-primary'; $icon = 'fa-fire'; }
                            if($status->status == 'dikirim') { $badgeClass = 'bg-info'; $icon = 'fa-motorcycle'; }
                            if($status->status == 'selesai') { $badgeClass = 'bg-success'; $icon = 'fa-check-double'; }
                            if($status->status == 'batal') { $badgeClass = 'bg-danger'; $icon = 'fa-times'; }
                        @endphp
                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                            <i class="fas {{ $icon }} me-1"></i> {{ ucfirst($status->status) }}
                        </span>
                    </div>

                    <div class="card-body p-3">
                        <div class="row g-3">
                            {{-- Kolom Info --}}
                            <div class="col-md-5 border-end-md">
                                <div class="d-flex mb-2">
                                    <i class="fas fa-user text-muted me-3 mt-1" style="width: 15px;"></i>
                                    <div>
                                        <small class="d-block text-muted">Penerima</small>
                                        <span class="fw-bold small">{{ $status->nama_pelanggan }}</span>
                                    </div>
                                </div>
                                <div class="d-flex mb-2">
                                    <i class="fas fa-map-marker-alt text-muted me-3 mt-1" style="width: 15px;"></i>
                                    <div>
                                        <small class="d-block text-muted">Alamat</small>
                                        <span class="small">{{ Str::limit($status->alamat, 50) }}</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <i class="fas fa-credit-card text-muted me-3 mt-1" style="width: 15px;"></i>
                                    <div>
                                        <small class="d-block text-muted">Pembayaran</small>
                                        <span class="badge bg-light text-dark border small">{{ strtoupper($status->metode_pembayaran) }}</span>
                                        @if($status->metode_pembayaran == 'bank_transfer')
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-primary" onclick="toggleUpload({{ $status->id }})">
                                                    <i class="fas fa-upload me-1"></i> Upload Bukti
                                                </button>
                                            </div>
                                            <div id="uploadForm{{ $status->id }}" class="mt-2 d-none">
                                                <form action="{{ route('checkout.uploadBukti', $status->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="file" name="bukti_pembayaran" class="form-control form-control-sm" accept="image/*" required>
                                                    <button type="submit" class="btn btn-sm btn-success mt-1">Upload</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom Menu --}}
                            <div class="col-md-7">
                                <div class="bg-light rounded-3 p-2" style="max-height: 120px; overflow-y: auto;">
                                    @foreach($status->items as $item)
                                    <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom border-white">
                                        <span class="small"><b class="text-success">{{ $item['jumlah'] }}x</b> {{ $item['nama_menu'] }}</span>
                                        <span class="small fw-bold text-dark">Rp {{ number_format($item['total_harga'],0,',','.') }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                    <span class="small text-muted">{{ $status->created_at->format('d M, H:i') }}</span>
                                    <span class="fw-bold text-success">Total: Rp {{ number_format($status->total_harga,0,',','.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    /* Styling scrollbar untuk daftar menu */
    .bg-light::-webkit-scrollbar { width: 3px; }
    .bg-light::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

    .border-end-md {
        border-right: 1px solid #eee;
    }

    @media (max-width: 767.98px) {
        .border-end-md {
            border-right: none;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
    }

    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-3px); }
</style>

<script>
function toggleUpload(id) {
    const form = document.getElementById('uploadForm' + id);
    form.classList.toggle('d-none');
}

// Handle form submission with AJAX
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[action*="upload-bukti"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    location.reload();
                } else {
                    alert(data.error || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan saat upload');
                console.error(error);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    });
});
</script>
@endsection
