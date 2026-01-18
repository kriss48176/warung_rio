@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-sm-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-0 text-dark fw-bold">Data Menu</h4>
                <p class="text-muted small mb-0">Kelola daftar hidangan dan stok menu Anda</p>
            </div>
            <a href="/admin/menu/create" class="btn btn-primary shadow-sm rounded-3">
                <i class="fas fa-plus me-2"></i>Tambah Menu
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-utensils me-2 text-primary"></i>Daftar Menu Restoran
            </h6>
        </div>
        <div class="card-body p-0">
            @if($data->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-utensils fa-3x text-muted mb-3 opacity-25"></i>
                    <h5 class="text-muted">Belum ada menu</h5>
                    <p class="text-muted small">Mulai dengan menambahkan menu pertama Anda.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary small text-uppercase fw-bold">
                            <tr>
                                <th class="ps-4 py-3" style="width: 50px;">#</th>
                                <th style="width: 80px;">Gambar</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th class="text-center" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ ($data->currentPage()-1) * $data->perPage() + $loop->iteration }}</td>
                                <td>
                                    @if($d->gambar)
                                        <img src="{{ asset('assets/gambar/'.$d->gambar) }}" class="rounded-3 border shadow-sm" width="55" height="55" alt="Gambar Menu" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 55px; height: 55px;">
                                            <i class="fas fa-image text-muted opacity-50"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $d->nama_menu }}</div>
                                    @if($d->deskripsi)
                                        <div class="text-muted small" style="font-size: 0.75rem;">{{ Str::limit($d->deskripsi, 45) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 fw-medium">
                                        {{ $d->kategori->nama_kategori }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">Rp {{ number_format($d->harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm rounded-3">
                                        <a href="/admin/menu/{{ $d->id_menu }}/edit" class="btn btn-white btn-sm border" title="Edit">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        <form action="/admin/menu/{{ $d->id_menu }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-white btn-sm border-start-0 border" onclick="return confirm('Hapus menu ini?')" title="Hapus">
                                                <i class="fas fa-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- FOOTER DENGAN NAVIGASI DI SEBELAH KANAN --}}
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-2">
                {{-- Kiri: Info Data --}}
                <div class="small text-muted mb-3 mb-md-0">
                    Menampilkan <strong>{{ $data->firstItem() ?? 0 }}</strong> sampai <strong>{{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong> menu
                </div>
                
                {{-- Kanan: Navigasi --}}
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous --}}
                        @if ($data->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link text-muted border-0 bg-light">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link shadow-none" href="{{ $data->previousPageUrl() }}">Previous</a>
                            </li>
                        @endif

                        {{-- Nomor Halaman --}}
                        @foreach ($data->getUrlRange(max(1, $data->currentPage() - 2), min($data->lastPage(), $data->currentPage() + 2)) as $page => $url)
                            @if ($page == $data->currentPage())
                                <li class="page-item active shadow-sm">
                                    <span class="page-link fw-bold px-3">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link shadow-none" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($data->hasMorePages())
                            <li class="page-item">
                                <a class="page-link shadow-none" href="{{ $data->nextPageUrl() }}">Next</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link text-muted border-0 bg-light">Next</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .table th { font-size: 0.75rem; letter-spacing: 0.5px; }
    .table-hover tbody tr:hover { background-color: #f8fafc; transition: 0.2s; }
    .bg-secondary-subtle { background-color: #f1f5f9 !important; color: #475569 !important; }
    
    /* Pagination Styling agar pas di kanan dan rapi */
    .pagination .page-link {
        color: #4e73df;
        border: 1px solid #dee2e6;
        padding: 0.4rem 0.8rem;
        margin-left: 2px;
        border-radius: 5px;
    }
    .pagination .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
    }
    .btn-white { background-color: #fff; color: #333; }
    .btn-white:hover { background-color: #f8f9fa; }
</style>
@endsection