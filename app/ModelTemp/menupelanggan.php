<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;
use Illuminate\Support\Facades\Schema;


class MenuPelanggan extends Model
{
    // Menggunakan tabel yang sama dengan model admin
    protected $table = 'menu';

    // Primary key sesuai tabel
    protected $primaryKey = 'id_menu';

    // Agar Laravel tahu primary key auto-increment
    public $incrementing = true;

    // Tipe primary key
    protected $keyType = 'int';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'id_kategori',
        'nama_menu',
        'harga',
        'deskripsi',
        'gambar',
        // 'status' // Uncomment jika tabel punya kolom status
    ];

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    /**
     * Scope: ambil menu yang tersedia untuk pelanggan
     * Sesuai controller: MenuPelanggan::tersedia()
     */
    public function scopeTersedia($query)
    {
        // Jika tabel ada dan punya kolom status, ambil yang 'tersedia'
        if (Schema::hasTable($this->table) && Schema::hasColumn($this->table, 'status')) {
            return $query->where('status', 'tersedia');
        }

        // Jika tidak ada kolom status, ambil semua menu
        return $query;
    }
}
