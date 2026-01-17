<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\MenuPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    /**
     * Tampilkan semua item keranjang untuk user saat ini
     */
    public function index()
    {
        $userId = Auth::guard('pelanggan')->id(); // ambil user yang login
        $items = Keranjang::where('user_id', $userId)->with('menu.kategori')->get();

        return view('pelanggan.keranjang', compact('items'));
    }

    /**
     * Tambahkan item ke keranjang
     */
    public function add(Request $request, $id)
    {
        try {
            $request->validate([
                'jumlah'  => 'nullable|integer|min:1'
            ]);

            $menu = MenuPelanggan::findOrFail($id);
            $jumlah = $request->jumlah ?? 1;
            $userId = Auth::guard('pelanggan')->id();

            if (!$userId) {
                return redirect()->route('pelanggan.login')->with('error', 'Silakan login terlebih dahulu.');
            }

            // Cek apakah item sudah ada di keranjang
            $existing = Keranjang::where('user_id', $userId)
                                 ->where('menu_id', $menu->id_menu)
                                 ->first();

            if ($existing) {
                // Update jumlah dan total
                $existing->jumlah += $jumlah;
                $existing->total_harga = $existing->jumlah * $menu->harga;
                $existing->save();
            } else {
                Keranjang::create([
                    'user_id'     => $userId,
                    'menu_id'     => $menu->id_menu,
                    'jumlah'      => $jumlah,
                    'harga'       => $menu->harga,
                    'total_harga' => $jumlah * $menu->harga
                ]);
            }

            return redirect()->route('keranjang.index')->with('success', 'Menu berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update jumlah item di keranjang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $item = Keranjang::where('user_id', Auth::guard('pelanggan')->id())->findOrFail($id);
        $item->jumlah = $request->jumlah;
        $item->total_harga = $item->jumlah * $item->harga;
        $item->save();

        if ($request->ajax()) {
            $grandTotal = Keranjang::where('user_id', Auth::guard('pelanggan')->id())->sum('total_harga');
            return response()->json([
                'success' => true,
                'message' => 'Jumlah item berhasil diperbarui.',
                'item_total' => $item->total_harga,
                'grand_total' => $grandTotal
            ]);
        }

        return redirect()->back()->with('success', 'Jumlah item berhasil diperbarui.');
    }

    /**
     * Hapus item dari keranjang
     */
    public function destroy($id)
    {
        $item = Keranjang::where('user_id', Auth::guard('pelanggan')->id())->findOrFail($id);
        $item->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang.',
                'grand_total' => Keranjang::where('user_id', Auth::guard('pelanggan')->id())->sum('total_harga')
            ]);
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
