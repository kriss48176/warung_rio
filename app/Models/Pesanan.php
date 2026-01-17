<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'tanggal_pesan',
        'status_pesanan',
        'total_harga',
        'catatan'
    ];

    public function detail()
    {
        return $this->hasMany(PesananDetail::class, 'id_pesanan');
    }
}
