<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan;

class Checkout extends Model
{
    protected $table = 'checkout';

    protected $fillable = [
        'user_id',
        'pesanan_id',
        'nama_pelanggan',
        'alamat',
        'no_hp',
        'items', // JSON array menu_id, nama, jumlah, harga, total
        'total_harga',
        'status', // baru, diproses, selesai
        'catatan',
        'metode_pembayaran'
    ];

    protected $casts = [
        'items' => 'array', // otomatis di-cast ke array
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'user_id');
    }
}
