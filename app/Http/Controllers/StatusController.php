<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Status;

class StatusController extends Controller
{
    // Tampilkan semua status pesanan pelanggan
    public function index()
    {
        $userId = Auth::id();

        $statuses = Status::where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('pelanggan.status', compact('statuses'));
    }

    // Optional: tampil detail pesanan
    public function show($id)
    {
        $userId = Auth::id();

        $status = Status::where('user_id', $userId)
                        ->where('id', $id)
                        ->firstOrFail();

        return view('pelanggan.status_detail', compact('status'));
    }
}
