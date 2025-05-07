<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppUser;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AppUserController extends Controller
{
    /**
     * Display a listing of the app users.
     */
    public function index()
    {
        $appUsers = User::where('user_type', 'app_user')
            ->with('appUser', 'pasien')
            ->latest()
            ->paginate(10);
            
        return view('dashboard.app_users.index', compact('appUsers'));
    }

    /**
     * Show the form for creating a new app user.
     */
    public function create()
    {
        return view('dashboard.app_users.create');
    }

    /**
     * Store a newly created app user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:16|unique:users,nik',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'date_of_birth' => 'nullable|date',
        ]);

        DB::beginTransaction();
        
        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 'app_user',
                'phone' => $request->phone,
                'nik' => $request->nik,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
            ]);
            
            // Create app user
            AppUser::create([
                'user_id' => $user->id,
            ]);
            
            DB::commit();
            
            return redirect()->route('app-users.index')
                ->with('success', 'Pengguna aplikasi berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified app user.
     */
    public function show($id)
    {
        $user = User::where('user_type', 'app_user')
            ->with('appUser', 'pasien')
            ->findOrFail($id);
            
        return view('dashboard.app_users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified app user.
     */
    public function edit($id)
    {
        $user = User::where('user_type', 'app_user')
            ->with('appUser', 'pasien')
            ->findOrFail($id);
            
        return view('dashboard.app_users.edit', compact('user'));
    }

    /**
     * Update the specified app user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::where('user_type', 'app_user')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:16|unique:users,nik,' . $id,
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'date_of_birth' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        DB::beginTransaction();
        
        try {
            // Update user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'nik' => $request->nik,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'is_active' => $request->is_active,
            ]);
            
            // Update password if provided
            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('app-users.index')
                ->with('success', 'Pengguna aplikasi berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified app user from storage.
     */
    public function destroy($id)
    {
        $user = User::where('user_type', 'app_user')->findOrFail($id);
        
        try {
            // Check if user has patient data
            if ($user->pasien) {
                return redirect()->back()
                    ->with('error', 'Pengguna ini memiliki data pasien dan tidak dapat dihapus.');
            }
            
            // Delete app user and user
            DB::transaction(function () use ($user) {
                // Delete app user first
                if ($user->appUser) {
                    $user->appUser->delete();
                }
                
                // Then delete user
                $user->delete();
            });
            
            return redirect()->route('app-users.index')
                ->with('success', 'Pengguna aplikasi berhasil dihapus.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle user active status.
     */
    public function toggleActive($id)
    {
        $user = User::where('user_type', 'app_user')->findOrFail($id);
        
        try {
            $user->update([
                'is_active' => !$user->is_active,
            ]);
            
            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()->back()
                ->with('success', "Pengguna berhasil {$status}.");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 