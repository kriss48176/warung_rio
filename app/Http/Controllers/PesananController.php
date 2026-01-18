<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Checkout;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        // Menggunakan paginate(8) sesuai permintaan Anda
        // Kita gunakan orderBy desc agar pesanan terbaru muncul paling atas
        $pesanan = Pesanan::orderBy('id_pesanan', 'desc')->paginate(8);

        // Tambahkan variabel ini untuk menghitung total antrean aktif dari seluruh data (bukan hanya yang di halaman 1)
        $totalAntrean = Pesanan::where('status_pesanan', 'Diproses')->count();

        return view('admin.pesanan.index', compact('pesanan', 'totalAntrean'));
    }

    public function show($id)
    {
        // Mengambil detail pesanan beserta relasi menu
        $pesanan = Pesanan::with('detail.menu')->findOrFail($id);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input agar status yang masuk konsisten
        $request->validate([
            'status_pesanan' => 'required|string|in:Menunggu,Diproses,Diantar,Selesai,Dibatalkan', 
        ]);

        $pesanan = Pesanan::findOrFail($id);
        
        // 1. Update status di tabel pesanan
        $pesanan->update([
            'status_pesanan' => $request->status_pesanan
        ]);

        // 2. Mapping status untuk tabel checkout
        $statusMapping = [
            'Menunggu' => 'menunggu',
            'Diproses' => 'diproses',
            'Diantar'  => 'diantar',
            'Selesai'  => 'selesai',
            'Dibatalkan' => 'dibatalkan'
        ];

        $checkoutStatus = $statusMapping[$request->status_pesanan] ?? 'baru';

        // 3. Update status di tabel checkout menggunakan pesanan_id
        Checkout::where('pesanan_id', $pesanan->id_pesanan)
                ->update(['status' => $checkoutStatus]);

        return redirect('/admin/pesanan')->with('success', 'Status pesanan #' . $pesanan->id_pesanan . ' berhasil diperbarui!');
    }
}