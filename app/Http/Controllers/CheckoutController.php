<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Keranjang;
use App\Models\Checkout;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Transaksi;

class CheckoutController extends Controller
{
    public function index()
    {
        // Pastikan menggunakan guard yang sesuai (pelanggan)
        $userId = Auth::guard('pelanggan')->id();
        
        $items = Keranjang::where('user_id', $userId)->with('menu')->get();
        $grandTotal = $items->sum(fn($item) => $item->total_harga);

        $pelanggan = Auth::guard('pelanggan')->user();

        return view('pelanggan.checkout', compact('items', 'grandTotal', 'pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|max:20',
            'metode_pembayaran' => 'required|string|in:cod,bank_transfer',
            'catatan' => 'nullable|string|max:500',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,bank_transfer|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $userId = Auth::guard('pelanggan')->id();
        $items = Keranjang::where('user_id', $userId)->with('menu')->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong.');
        }

        try {
            DB::transaction(function () use ($request, $items, $userId) {
                $grandTotal = $items->sum(fn($item) => $item->total_harga);

                // 1. Simpan ke tabel Pesanan
                $pesanan = Pesanan::create([
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp,
                    'tanggal_pesan' => now(),
                    'status_pesanan' => 'Menunggu',
                    'total_harga' => $grandTotal,
                    'catatan' => $request->catatan
                ]);

                // 2. Simpan Detail Pesanan & Siapkan JSON untuk Checkout
                $itemsArray = [];
                foreach ($items as $item) {
                    PesananDetail::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'id_menu' => $item->menu_id,
                        'jumlah' => $item->jumlah,
                        'subtotal' => $item->total_harga
                    ]);

                    $itemsArray[] = [
                        'menu_id' => $item->menu_id,
                        'nama_menu' => $item->menu->nama_menu,
                        'jumlah' => $item->jumlah,
                        'harga' => $item->menu->harga,
                        'total_harga' => $item->total_harga
                    ];
                }

                // 3. Simpan tabel Checkout
                Checkout::create([
                    'user_id' => $userId,
                    'pesanan_id' => $pesanan->id_pesanan,
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp,
                    'items' => $itemsArray,
                    'total_harga' => $grandTotal,
                    'status' => 'baru',
                    'catatan' => $request->catatan,
                    'metode_pembayaran' => $request->metode_pembayaran
                ]);

                // 4. Proses Transaksi
                $pathBukti = null;
                if ($request->hasFile('bukti_pembayaran')) {
                    $file = $request->file('bukti_pembayaran');
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $pathBukti = $file->storeAs('bukti_pembayaran', $filename, 'public');
                }

                Transaksi::create([
                    'pesanan_id' => $pesanan->id_pesanan,
                    'pelanggan_id' => $userId,
                    'total_tagihan' => $grandTotal,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'status_pembayaran' => ($request->metode_pembayaran == 'bank_transfer') ? 'menunggu_konfirmasi' : 'pending',
                    'bukti_pembayaran' => $pathBukti,
                    'catatan' => $request->catatan
                ]);

                // 5. Kosongkan Keranjang
                Keranjang::where('user_id', $userId)->delete();
            });

            return redirect()->route('status.index')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}