<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthApiController extends Controller
{
    /**
     * Register a new app user
     */
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:app_users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20', 'unique:app_users,no_hp'],
            'jenis_kelamin' => ['required', 'in:laki-laki,perempuan'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_hp.unique' => 'Nomor HP sudah terdaftar.',
            'jenis_kelamin.in' => 'Jenis kelamin harus "laki-laki" atau "perempuan".',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed during registration', [
                'input' => $request->except(['password', 'password_confirmation']),
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        try {
            DB::beginTransaction();

            Log::info('Creating new app user', [
                'email' => $request->email
            ]);

            $appUser = AppUsers::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            Log::info('App user created successfully', [
                'id' => $appUser->id,
                'email' => $appUser->email
            ]);

            $token = $appUser->createToken('app-user-auth-token')->plainTextToken;

            Log::info('Token created successfully', [
                'token' => $token,
                'app_user_id' => $appUser->id
            ]);

            $userData = $appUser->toArray();
            $userData['token'] = $token;
            $userData['is_app_user'] = true; // Always true since we're only dealing with AppUser
            $userData['app_user_data'] = $appUser->toArray(); // Include app user data in response

            DB::commit();

            Log::info('Registration completed successfully', [
                'app_user_id' => $appUser->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => $userData
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registration failed during transaction', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login for app users using email
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Login attempt', ['email' => $request->email]);

            $appUser = AppUsers::where('email', $request->email)->first();

            if (!$appUser || !Hash::check($request->password, $appUser->password)) {
                Log::warning('Invalid login credentials', ['email' => $request->email]);

                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            $appUser->tokens()->delete();
            
            $token = $appUser->createToken('app-user-auth-token')->plainTextToken;

            Log::info('Token created for login', [
                'token' => $token,
                'app_user_id' => $appUser->id
            ]);

            $userData = $appUser->toArray();
            $userData['token'] = $token;
            $userData['is_app_user'] = true; // Always true since we're only dealing with AppUser
            $userData['app_user_data'] = $appUser->toArray(); // Include app user data in response

            $appUser->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            Log::info('Login successful', [
                'app_user_id' => $appUser->id,
                'last_login_at' => $appUser->last_login_at,
                'last_login_ip' => $appUser->last_login_ip
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => $userData
            ]);
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Login gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile
     */
    public function getProfile(Request $request)
    {
        try {
            $appUser = $request->user();

            Log::info('Profile data requested', ['app_user_id' => $appUser->id]);

            $userData = $appUser->toArray();
            $userData['is_app_user'] = true; // Always true since we're only dealing with AppUser
            $userData['app_user_data'] = $appUser->toArray(); // Include app user data in response

            Log::info('Profile data loaded successfully', ['app_user_id' => $appUser->id]);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil dimuat',
                'data' => $userData
            ]);
        } catch (\Exception $e) {
            Log::error('Profile loading failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}