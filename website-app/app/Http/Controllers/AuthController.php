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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        // Check if user exists and is active
        $user = User::where('email', $request->email)->first();
        
        if ($user && !$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun anda tidak aktif.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Update last login
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip()
            ]);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Data yang dimasukkan tidak sesuai dengan catatan kami.',
        ])->onlyInput('email');
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