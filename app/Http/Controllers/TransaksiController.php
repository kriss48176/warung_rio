<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pesanan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['pesanan', 'pelanggan'])->latest()->paginate(8);
        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['pesanan', 'pelanggan'])->findOrFail($id);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('admin.transaksi.edit', compact('transaksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:pending,lunas,gagal',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $transaksi = Transaksi::findOrFail($id);

        $data = $request->only(['status_pembayaran', 'jumlah_dibayar', 'catatan']);

        if ($request->hasFile('bukti_pembayaran')) {
            // Delete old file if exists
            if ($transaksi->bukti_pembayaran && Storage::exists('public/' . $transaksi->bukti_pembayaran)) {
                Storage::delete('public/' . $transaksi->bukti_pembayaran);
            }

            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bukti_pembayaran', $filename, 'public');
            $data['bukti_pembayaran'] = $path;
        }

        $transaksi->update($data);

        // Jika status pembayaran lunas, update status pesanan menjadi Diproses
        if ($data['status_pembayaran'] === 'lunas') {
            $transaksi->pesanan->update(['status_pesanan' => 'Diproses']);
        }

        return redirect()->route('admin/transaksi.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Delete bukti pembayaran file if exists
        if ($transaksi->bukti_pembayaran && Storage::exists('public/' . $transaksi->bukti_pembayaran)) {
            Storage::delete('public/' . $transaksi->bukti_pembayaran);
        }

        $transaksi->delete();
    }

    public function confirmCod($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Update status pembayaran menjadi lunas
        $transaksi->update([
            'status_pembayaran' => 'lunas'
        ]);

        // Jika ada pesanan yang terkait, update status pesanan menjadi Diproses
        if ($transaksi->pesanan) {
            $transaksi->pesanan->update(['status_pesanan' => 'Diproses']);
        }

        return redirect()->route('admin/transaksi.index')->with('success', 'Pembayaran COD berhasil dikonfirmasi sebagai lunas');
    }
}
