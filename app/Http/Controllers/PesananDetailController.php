<?php

namespace App\Http\Controllers;

use App\Models\PesananDetail;
use Illuminate\Http\Request;

class PesananDetailController extends Controller
{
    public function destroy($id)
    {
        PesananDetail::destroy($id);
        return back()->with('success', 'Item pesanan berhasil dihapus!');
    }
}
