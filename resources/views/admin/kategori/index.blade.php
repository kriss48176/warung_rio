@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-tags me-2 text-secondary"></i>Daftar Kategori
                        </h4>
                        <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori
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
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada kategori</h5>
                            <p class="text-muted">Mulai dengan menambahkan kategori pertama.</p>
                            <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Kategori Pertama
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">#</th>
                                        <th class="border-0">Nama Kategori</th>
                                        <th class="border-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $row)
                                    <tr>
                                        <td class="fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-tag text-primary me-2"></i>
                                                {{ $row->nama_kategori }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('kategori.edit', $row->id_kategori) }}" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <form action="{{ route('kategori.destroy', $row->id_kategori) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
                            <small>Total: {{ $data->count() }} kategori</small>
                        </div>
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
</style>
@endsection
