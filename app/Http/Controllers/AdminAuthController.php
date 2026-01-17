<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminAuthController extends Controller
{
    public function loginPage()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Username atau password salah!');
        }

        session(['admin_id' => $admin->id_admin]);
        session(['admin_username' => $admin->username]);
        $request->session()->regenerate();

        return redirect('/admin');
    }

    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admin',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/admin/login')->with('success', 'Admin berhasil didaftarkan. Silakan login.');
    }

    public function logout(Request $request)
    {
        // Clear admin session
        session()->forget('admin_id');

        // Invalidate and regenerate session token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $admin = Admin::find(session('admin_id'));

        if ($request->hasFile('photo')) {
            // Ensure directory exists
            $directory = public_path('storage/admin_photos');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Delete old photo if exists
            $oldPath = $directory . '/' . $admin->photo;
            if ($admin->photo && file_exists($oldPath)) {
                unlink($oldPath);
            }

            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->move($directory, $fileName);

            $admin->photo = $fileName;
            $admin->save();
        }

        return back()->with('success', 'Foto berhasil diupdate!');
    }
}
