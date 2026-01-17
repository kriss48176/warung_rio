<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Checkout;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::orderBy('id_pesanan', 'desc')->paginate(8);
        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('detail.menu')->findOrFail($id);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input (opsional, tapi disarankan untuk keamanan)
        $request->validate([
            'status_pesanan' => 'required|string|in:Menunggu,Diproses,Diantar,Selesai', // Sesuaikan dengan status yang valid di aplikasi Anda
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'status_pesanan' => $request->status_pesanan
        ]);

        // Update status di tabel checkout juga
        $statusMapping = [
            'Menunggu' => 'baru',
            'Diproses' => 'diproses',
            'Diantar' => 'diantar',
            'Selesai' => 'selesai'
        ];

        $checkoutStatus = $statusMapping[$request->status_pesanan] ?? 'baru';

        // Update status di tabel checkout menggunakan pesanan_id
        Checkout::where('pesanan_id', $pesanan->id_pesanan)
            ->update(['status' => $checkoutStatus]);

        return redirect('/admin/pesanan')->with('success', 'Status pesanan diperbarui!');
    }
}
