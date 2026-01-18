<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'pesanan_id',
        'pelanggan_id',
        'kode_transaksi',
        'metode_pembayaran',
        'total_tagihan',
        'jumlah_dibayar',
        'status_pembayaran',
        'bukti_pembayaran',
        'catatan'
    ];

    protected $casts = [
        'total_tagihan' => 'decimal:2',
        'jumlah_dibayar' => 'decimal:2',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id_pesanan');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id')->select('id', 'name as nama', 'email', 'phone', 'address');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            if (empty($transaksi->kode_transaksi)) {
                $prefix = 'TRX-' . date('Ymd') . '-';
                $lastTransaction = static::where('kode_transaksi', 'like', $prefix . '%')
                    ->orderBy('kode_transaksi', 'desc')
                    ->first();
                $nextNumber = $lastTransaction ? ((int) substr($lastTransaction->kode_transaksi, -4)) + 1 : 1;
                $transaksi->kode_transaksi = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
