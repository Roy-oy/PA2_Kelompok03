<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AppUserController extends Controller
{
    /**
     * Display a listing of the app users.
     */
    public function index()
    {
        $appUsers = AppUser::with('pasien')->latest()->paginate(10);
        
        return view('dashboard.app_users.index', compact('appUsers'));
    }

    /**
     * Show the form for creating a new app user.
     */
    public function create()
    {
        // return view('dashboard.app_users.create');
    }

    /**
     * Store a newly created app user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('app_users')],
            'password' => ['required', 'string', 'min:8'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:13', Rule::unique('app_users')],
            'jenis_kelamin' => ['required', Rule::in(['laki-laki', 'perempuan'])],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_hp.unique' => 'Nomor HP sudah terdaftar.',
            'jenis_kelamin.in' => 'Jenis kelamin harus laki-laki atau perempuan.',
        ]);

        DB::beginTransaction();
        
        try {
            AppUser::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
            ]);
            
            DB::commit();
            
            return redirect()->route('app-users.index')
                ->with('success', 'Pengguna aplikasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pengguna. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Remove the specified app user from storage.
     */
    public function destroy($id)
    {
        $appUser = AppUser::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            // Nullify app_user_id in related Pasien records
            if ($appUser->pasien) {
                $appUser->pasien()->update(['app_user_id' => null]);
            }
            
            // Permanently delete the AppUser
            $appUser->forceDelete();
            
            DB::commit();
            
            return redirect()->route('app-users.index')
                ->with('success', 'Pengguna aplikasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus pengguna. Silakan coba lagi.');
        }
    }
}