<?php

namespace App\Http\Controllers;

use Exception;
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
        $userId = Auth::guard('pelanggan')->id();
        $items = Keranjang::where('user_id', $userId)->with('menu')->get();

        $grandTotal = $items->sum(fn($item) => $item->total_harga);

        // Ambil data pelanggan jika login sebagai pelanggan
        $pelanggan = null;
        if (Auth::guard('pelanggan')->check()) {
            $pelanggan = Auth::guard('pelanggan')->user();
        }

        return view('pelanggan.checkout', compact('items', 'grandTotal', 'pelanggan'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|max:20',
            'metode_pembayaran' => 'required|string',
            'catatan' => 'nullable|string|max:500',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $userId = Auth::guard('pelanggan')->id();
        $items = Keranjang::where('user_id', $userId)->with('menu')->get();

        if ($items->isEmpty()) {
            return redirect()->route('pelanggan.index')->with('error', 'Keranjang kosong.');
        }

        DB::transaction(function () use ($request, $items, $userId) {
            $grandTotal = $items->sum(fn($item) => $item->total_harga);

            // Siapkan array items untuk disimpan sebagai JSON
            $itemsArray = $items->map(function ($item) {
                return [
                    'menu_id' => $item->menu_id,
                    'nama_menu' => $item->menu->nama_menu,
                    'jumlah' => $item->jumlah,
                    'harga' => $item->harga,
                    'total_harga' => $item->total_harga
                ];
            })->toArray();

            // Simpan pesanan
            $pesanan = Pesanan::create([
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'tanggal_pesan' => now(),
                'status_pesanan' => 'Menunggu',
                'total_harga' => $grandTotal,
                'catatan' => $request->catatan
            ]);

            // Simpan detail pesanan
            foreach ($items as $item) {
                PesananDetail::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_menu' => $item->menu_id,
                    'jumlah' => $item->jumlah,
                    'subtotal' => $item->total_harga
                ]);
            }

            // Simpan checkout
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

            // Tentukan status pembayaran berdasarkan metode
            $statusPembayaran = 'pending';
            if ($request->metode_pembayaran === 'cod') {
                $statusPembayaran = 'pending'; // Tunggu konfirmasi saat sampai
            } elseif ($request->metode_pembayaran === 'bank_transfer') {
                $statusPembayaran = 'pending'; // Tunggu upload bukti atau konfirmasi
            }

            // Simpan transaksi
            $transaksiData = [
                'pesanan_id' => $pesanan->id_pesanan,
                'pelanggan_id' => $userId,
                'total_tagihan' => $grandTotal,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $statusPembayaran,
                'catatan' => $request->catatan
            ];

            // Jika metode bank_transfer, simpan bukti pembayaran
            if ($request->metode_pembayaran === 'bank_transfer' && $request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('bukti_pembayaran', $filename, 'public');
                $transaksiData['bukti_pembayaran'] = $filename;
                $transaksiData['status_pembayaran'] = 'menunggu_konfirmasi';
            }

            Transaksi::create($transaksiData);

            // Kosongkan keranjang user
            Keranjang::where('user_id', $userId)->delete();
        });

        return redirect()->route('status.index')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran jika memilih transfer.');
    }

    public function uploadBukti(Request $request, $checkout_id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $checkout = Checkout::findOrFail($checkout_id);

        // Pastikan user yang upload adalah pemilik checkout
        if ($checkout->user_id != Auth::guard('pelanggan')->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Cari transaksi berdasarkan pesanan_id
        $transaksi = Transaksi::where('pesanan_id', $checkout->pesanan_id)->first();

        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan.'], 404);
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

            $transaksi->update([
                'bukti_pembayaran' => $path,
                'status_pembayaran' => 'menunggu_konfirmasi'
            ]);

            return response()->json(['success' => 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.']);
        }

        return response()->json(['error' => 'Gagal upload bukti pembayaran.'], 400);
    }
}
