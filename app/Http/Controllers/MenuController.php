<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $data = Menu::with('kategori')->paginate(8);
        return view('admin.menu.index', compact('data'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.menu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required',
            'nama_menu'   => 'required',
            'harga'       => 'required|numeric',
            'deskripsi'   => 'required',
            'gambar'      => 'required|image|mimes:jpg,jpeg,png'
        ]);

        // upload gambar
        $file = $request->file('gambar');
        $nama_file = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('assets/gambar'), $nama_file);

        Menu::create([
            'id_kategori' => $request->id_kategori,
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'deskripsi'   => $request->deskripsi,
            'gambar'      => $nama_file
        ]);

        return redirect('/admin/menu')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $kategori = Kategori::all();

        return view('admin.menu.edit', compact('menu', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'id_kategori' => 'required',
            'nama_menu'   => 'required',
            'harga'       => 'required|numeric',
            'deskripsi'   => 'required',
        ]);

        $nama_file = $menu->gambar;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $nama_file = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('assets/gambar'), $nama_file);
        }

        $menu->update([
            'id_kategori' => $request->id_kategori,
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'deskripsi'   => $request->deskripsi,
            'gambar'      => $nama_file
        ]);

        return redirect('/admin/menu')->with('success', 'Menu berhasil diupdate!');
    }

    public function destroy($id)
    {
        Menu::destroy($id);

        return redirect('/admin/menu')->with('success', 'Menu berhasil dihapus!');
    }
}
