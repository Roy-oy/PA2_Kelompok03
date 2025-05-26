<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses percobaan login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Data kredensial untuk autentikasi
        $credentials = $request->only('email', 'password');

        // Coba autentikasi pengguna
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Set Admin session key
            $request->session()->put('session_key', config('session.cookie'));
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke halaman setelah login berhasil (misalnya dashboard)
            return redirect()->intended('dashboard'); 
        }

        // Jika login gagal, kembali ke form dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->withInput($request->except('password'));
    }

    /**
     * Memproses logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Pastikan bahwa pengguna dialihkan ke halaman login setelah logout
        return redirect()->route('login')->with('message', 'Anda berhasil keluar dari sistem.');
    }
}