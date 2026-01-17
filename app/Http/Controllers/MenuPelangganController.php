<?php

namespace App\Http\Controllers;

use App\Models\MenuPelanggan; // Model khusus pelanggan
use Illuminate\Http\Request;

class MenuPelangganController extends Controller
{
    /**
     * Tampilkan daftar menu untuk pelanggan
     */
    public function index()
    {
        // Ambil menu yang tersedia untuk pelanggan, sekaligus relasi kategori
        $menus = MenuPelanggan::tersedia()->with('kategori')->get();

        // Ambil semua kategori untuk filter
        $kategoris = \App\Models\Kategori::all();

        // Tampilkan view pelanggan.index dan kirim data menu dan kategori
        return view('pelanggan.index', compact('menus', 'kategoris'));
    }

    /**
     * Tampilkan menu per kategori
     */
    public function kategori($id)
    {
        $menus = MenuPelanggan::tersedia()->where('id_kategori', $id)->with('kategori')->get();
        $kategoris = \App\Models\Kategori::all();

        return view('pelanggan.menu_pelanggan', compact('menus', 'kategoris'));
    }

    /**
     * Tampilkan detail menu untuk pelanggan (opsional)
     */
    public function show($id)
    {
        $menu = MenuPelanggan::findOrFail($id);

        // Tampilkan view pelanggan.menu_detail dan kirim data menu
        return view('pelanggan.menu_detail', compact('menu'));
    }
}
