<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AppUser;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthApiController extends Controller
{
    /**
     * Register a new app user
     */
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:15',
            'nik' => 'nullable|string|max:16|unique:users,nik',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'date_of_birth' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Create a User first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 'app_user',
                'phone' => $request->phone,
                'nik' => $request->nik,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'is_active' => true,
            ]);

            // Then create an AppUser linked to that User
            $appUser = AppUser::create([
                'user_id' => $user->id,
            ]);

            DB::commit();

            // Create a token for the user
            $token = $user->createToken('app-user-auth-token')->plainTextToken;

            // Prepare user data for response
            $userData = $user->toArray();
            $userData['token'] = $token;
            $userData['is_app_user'] = false;

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => $userData
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
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
            // Find the user by email
            $user = User::where('email', $request->email)
                     ->where('user_type', 'app_user')
                     ->first();

            // Check if user exists and password is correct
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            // Revoke any existing tokens
            $user->tokens()->delete();

            // Create a new token
            $token = $user->createToken('app-user-auth-token')->plainTextToken;

            // Check if user has a patient record
            $isAppUser = $user->isAppUser();

            // Optional: Get patient data if exists
            $patientData = null;
            if ($isAppUser) {
                $appuserData = $user->appuser;
            }

            // Prepare user data for response
            $userData = $user->toArray();
            $userData['token'] = $token;
            $userData['is_app_user'] = $isAppUser;
            $userData['app_user_data'] = $appuserData;

            // Update last login details
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => $userData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login for app users using NIK
     */
    public function loginWithNik(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string',
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
            // Log the login attempt
            Log::info('Login with NIK attempt', ['nik' => $request->nik]);

            // Find the user by NIK
            $user = User::where('nik', $request->nik)
                     ->where('user_type', 'app_user')
                     ->first();

            // Check if user exists and password is correct
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIK tidak terdaftar'
                ], 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah'
                ], 401);
            }

            // Revoke any existing tokens
            $user->tokens()->delete();

            // Create a new token
            $token = $user->createToken('app-user-auth-token')->plainTextToken;

            // Check if user has a patient record
            $isAppUser = $user->isAppUser();

            // Optional: Get patient data if exists
            $patientData = null;
            if ($isAppUser) {
                $appuserData = $user->appuser;
            }

            // Prepare user data for response
            $userData = $user->toArray();
            $userData['token'] = $token;
            $userData['is_app_user'] = $isAppUser;
            $userData['app_user_data'] = $appuserData;

            // Update last login details
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => $userData
            ]);
        } catch (\Exception $e) {
            Log::error('Login with NIK failed', [
                'nik' => $request->nik,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Login gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register as patient (convert app user to patient)
     */
    public function registerAsAppUser(Request $request)
    {
        // Log the start of patient registration
        Log::info('App User registration started', ['user_id' => $request->user() ? $request->user()->id : 'unauthorized']);
        
        // Validate the token is present
        if (!$request->user()) {
            Log::warning('App User registration failed: Unauthorized user');
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Get the current app user
        $user = $request->user();
        Log::info('User found for app user registration', ['user_id' => $user->id, 'email' => $user->email]);

        // Check if user already has a app user record
        if ($user->isPatient()) {
            Log::warning('User already registered as app user', ['user_id' => $user->id]);
            return response()->json([
                'success' => false,
                'message' => 'Pengguna sudah terdaftar'
            ], 400);
        }

        // Validate additional app user data
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            Log::warning('App User registration validation failed', [
                'user_id' => $user->id,
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Log patient data before creating
            Log::info('Creating app user record with data', [
                'user_id' => $user->id,
                'data' => $request->except(['password', 'password_confirmation'])
            ]);

            // Create a patient record linked to the user
            $appuser = User::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
            ]);
            
            Log::info('App User record created successfully', [
                'app_user_id' => $appuser->id,
                'nama' => $appuser->nama,
            ]);
            
            DB::commit();
            
            // Log all fields in the created app user record
            Log::info('Patient record details', [
                'id' => $appuser->id,
                'nama' => $appuser->nama,
                'jenis_kelamin' => $appuser->jenis_kelamin,
                'tanggal_lahir' => $appuser->tanggal_lahir,
                'alamat' => $appuser->alamat,
                'no_telepon' => $appuser->no_telepon,
            ]);
            
            // Prepare response data
            $responseData = [
                'is_app_user' => true,
                'appuserr' => $appuser
            ];
            
            Log::info('Patient registration completed successfully', [
                'user_id' => $user->id,
                'app_user_id' => $appuser->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran pasien berhasil',
                'data' => $responseData
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('app user registration failed with exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran pengguna aplikasi gagal',
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
            $user = $request->user();
            
            Log::info('Profile data requested', ['user_id' => $user->id]);
            
            // Check if user has a patient record
            $isAppUser = $user->isAppUser();
            Log::info('User is app user', ['is_app_user' => $isAppUser]);

            // Optional: Get patient data if exists
            $patientData = null;
            if ($isAppUser) {
                $appuserData = $user->appuser;
                Log::info('Retrieved patient data', [
                    'appuser_id' => $appuserData->id,
                    'nama' => $appuserData->nama,
                ]);
            }
            
            // Prepare user data for response
            $userData = $user->toArray();
            $userData['is_app_user'] = $isAppUser;
            $userData['app_user_data'] = $patientData;
            
            Log::info('Profile data loaded successfully', ['user_id' => $user->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil dimuat',
                'data' => $userData
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading profile data', [
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