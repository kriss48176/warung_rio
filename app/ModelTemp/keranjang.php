<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MenuPelanggan;
use App\Models\Pelanggan;

class Keranjang extends Model
{
    // Nama tabel
    protected $table = 'keranjang';

    // Primary key (jika tidak pakai id default)
    protected $primaryKey = 'id';

    // Mass assignable
    protected $fillable = [
        'user_id',      // ID pelanggan
        'menu_id',      // ID menu
        'jumlah',       // Jumlah item
        'harga',        // Harga per item
        'total_harga'   // total = jumlah * harga
    ];

    /**
     * Relasi ke menu
     */
    public function menu()
    {
        return $this->belongsTo(MenuPelanggan::class, 'menu_id');
    }

    /**
     * Relasi ke pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'user_id');
    }

    /**
     * Hitung total harga otomatis
     */
    public function setTotalHargaAttribute()
    {
        $this->attributes['total_harga'] = $this->jumlah * $this->harga;
    }
}
