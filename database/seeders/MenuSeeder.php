<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu')->insert([
            [
                'id_kategori' => 1, // Assuming kategori exists
                'nama_menu' => 'Nasi Goreng',
                'harga' => 15000,
                'deskripsi' => 'Nasi goreng spesial dengan telur dan ayam',
                'gambar' => '1764332905_nasi goreng.jpg',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 1,
                'nama_menu' => 'Es Teh Manis',
                'harga' => 5000,
                'deskripsi' => 'Es teh manis segar',
                'gambar' => '1764332257_Es-Teh-Manis.jpg',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 2, // Assuming another kategori
                'nama_menu' => 'Sup Ekor',
                'harga' => 20000,
                'deskripsi' => 'Sup ekor sapi yang lezat',
                'gambar' => '1764335008_sup ekor.jpg',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 3,
                'nama_menu' => 'Coklat',
                'harga' => 10000,
                'deskripsi' => 'Minuman coklat panas',
                'gambar' => '1764556915_coklat.jpg',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
