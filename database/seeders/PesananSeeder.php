<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pesanan::create([
            'nama_pelanggan' => 'Kristofer',
            'no_hp' => '08937485298',
            'alamat' => 'Pontianak',
            'tanggal_pesan' => now(),
            'status_pesanan' => 'Diproses',
            'total_harga' => 50000,
            'catatan' => 'Tolong buatnya cepat, saya ada acara'
        ]);

        Pesanan::create([
            'nama_pelanggan' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'alamat' => 'Jalan Sudirman No 10',
            'tanggal_pesan' => now(),
            'status_pesanan' => 'Menunggu',
            'total_harga' => 75000,
            'catatan' => 'Jangan terlalu pedas ya'
        ]);

        Pesanan::create([
            'nama_pelanggan' => 'Siti Rahayu',
            'no_hp' => '08987654321',
            'alamat' => 'Kompleks Perumahan Asri',
            'tanggal_pesan' => now(),
            'status_pesanan' => 'Selesai',
            'total_harga' => 120000,
            'catatan' => null
        ]);
    }
}
