<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Status extends Model
{
    protected $table = 'checkout'; // Tetap ambil dari tabel checkout

    protected $fillable = [
        'user_id',
        'nama_pelanggan',
        'alamat',
        'no_hp',
        'items',
        'total_harga',
        'status',
        'catatan',
        'metode_pembayaran'
    ];

    protected $casts = [
        'items' => 'array' // Supaya kolom JSON items otomatis jadi array
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
