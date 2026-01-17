<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PelangganAuthController extends Controller
{
    public function showLogin()
    {
        return view('pelanggan.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('pelanggan')->attempt($credentials)) {
            $user = Auth::guard('pelanggan')->user();
            session([
                'pelanggan_id' => $user->id,
                'pelanggan_nama' => $user->name,
            ]);
            return redirect('/'); 
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function showRegister()
    {
        return view('pelanggan.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:pelanggans',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        Pelanggan::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('pelanggan.login')
                         ->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function logout()
    {
        Auth::guard('pelanggan')->logout();
        session()->forget(['pelanggan_id', 'pelanggan_nama']);
        return redirect()->route('pelanggan.login');
    }
}
