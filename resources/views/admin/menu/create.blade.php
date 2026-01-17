@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2 text-secondary"></i>Tambah Menu Baru
                    </h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/admin/menu" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_kategori" class="form-label fw-bold">
                                    <i class="fas fa-tags me-2 text-primary"></i>Kategori
                                </label>
                                <select name="id_kategori" id="id_kategori" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('id_kategori')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama_menu" class="form-label fw-bold">
                                    <i class="fas fa-utensils me-2 text-primary"></i>Nama Menu
                                </label>
                                <input type="text" name="nama_menu" id="nama_menu" class="form-control" placeholder="Masukkan nama menu" required>
                                @error('nama_menu')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label fw-bold">
                                    <i class="fas fa-money-bill me-2 text-primary"></i>Harga
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga" id="harga" class="form-control" placeholder="0" min="0" required>
                                </div>
                                @error('harga')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gambar" class="form-label fw-bold">
                                    <i class="fas fa-image me-2 text-primary"></i>Gambar Menu
                                </label>
                                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" onchange="previewImage(event)">
                                @error('gambar')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-bold">
                                <i class="fas fa-align-left me-2 text-primary"></i>Deskripsi
                            </label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" placeholder="Masukkan deskripsi menu"></textarea>
                            @error('deskripsi')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image me-2 text-primary"></i>Preview Gambar
                            </label>
                            <div class="border rounded p-3 bg-light" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                <img id="imagePreview" src="" class="img-fluid rounded" alt="Preview Gambar" style="max-width: 100%; max-height: 400px; object-fit: contain; display: none;">
                                <span id="imagePlaceholder" class="text-muted">Pilih gambar untuk melihat preview</span>
                            </div>
                        </div>
                            <a href="{{ url('/admin/menu') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Simpan Menu
                            </button>
                        </div>
                    </form>
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
.btn {
    border-radius: 8px;
    font-weight: 500;
}
.form-control, .form-select {
    border-radius: 5px;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.form-control:focus, .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
.input-group-text {
    border-radius: 5px 0 0 5px;
}
</style>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePlaceholder');
        preview.src = reader.result;
        preview.style.display = 'block';
        if(placeholder) placeholder.style.display = 'none';
    };
    if(event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endsection
