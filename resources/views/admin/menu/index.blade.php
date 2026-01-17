@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-utensils me-2 text-secondary"></i>Data Menu
                        </h4>
                        <a href="/admin/menu/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Menu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($data->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada menu</h5>
                            <p class="text-muted">Mulai dengan menambahkan menu pertama.</p>
                            <a href="/admin/menu/create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Menu Pertama
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">#</th>
                                        <th class="border-0">Gambar</th>
                                        <th class="border-0">Nama Menu</th>
                                        <th class="border-0">Kategori</th>
                                        <th class="border-0">Harga</th>
                                        <th class="border-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $d)
                                    <tr>
                                        <td class="fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            @if($d->gambar)
                                                <img src="{{ asset('assets/gambar/'.$d->gambar) }}" class="rounded" width="60" height="60" alt="Gambar Menu" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-utensils text-primary me-2"></i>
                                                <div>
                                                    <div class="fw-bold">{{ $d->nama_menu }}</div>
                                                    @if($d->deskripsi)
                                                        <small class="text-muted">{{ Str::limit($d->deskripsi, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $d->kategori->nama_kategori }}</span>
                                        </td>
                                        <td class="fw-bold text-success">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="/admin/menu/{{ $d->id_menu }}/edit" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <form action="/admin/menu/{{ $d->id_menu }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                                        <i class="fas fa-trash me-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 text-muted">
                            <small>Total: {{ $data->total() }} menu</small>
                        </div>

                        {{-- Pagination --}}
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination pagination-sm justify-content-center">
                                {{-- Previous Page Link --}}
                                @if ($data->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $data->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                                    @if ($page == $data->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($data->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $data->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
}
.card-header {
    border-radius: 10px 10px 0 0 !important;
    border-bottom: 1px solid #dee2e6;
}
.table th {
    font-weight: 600;
    color: #495057;
}
.btn-group .btn {
    margin-right: 5px;
}
.btn-group .btn:last-child {
    margin-right: 0;
}
.badge {
    font-size: 0.75em;
}
</style>
@endsection
