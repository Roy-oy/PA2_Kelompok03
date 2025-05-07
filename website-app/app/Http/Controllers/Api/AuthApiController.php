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
            $userData['is_patient'] = false;

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
            $isPatient = $user->isPatient();

            // Optional: Get patient data if exists
            $patientData = null;
            if ($isPatient) {
                $patientData = $user->pasien;
            }

            // Prepare user data for response
            $userData = $user->toArray();
            $userData['token'] = $token;
            $userData['is_patient'] = $isPatient;
            $userData['patient_data'] = $patientData;

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
            $isPatient = $user->isPatient();

            // Optional: Get patient data if exists
            $patientData = null;
            if ($isPatient) {
                $patientData = $user->pasien;
            }

            // Prepare user data for response
            $userData = $user->toArray();
            $userData['token'] = $token;
            $userData['is_patient'] = $isPatient;
            $userData['patient_data'] = $patientData;

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
    public function registerAsPatient(Request $request)
    {
        // Log the start of patient registration
        Log::info('Patient registration started', ['user_id' => $request->user() ? $request->user()->id : 'unauthorized']);
        
        // Validate the token is present
        if (!$request->user()) {
            Log::warning('Patient registration failed: Unauthorized user');
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Get the current app user
        $user = $request->user();
        Log::info('User found for patient registration', ['user_id' => $user->id, 'email' => $user->email]);

        // Check if user already has a patient record
        if ($user->isPatient()) {
            Log::warning('User already registered as patient', ['user_id' => $user->id]);
            return response()->json([
                'success' => false,
                'message' => 'Pengguna sudah terdaftar sebagai pasien'
            ], 400);
        }

        // Validate additional patient data
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'pekerjaan' => 'required|string|max:255',
            'golongan_darah' => 'required|string|max:10',
            'rhesus' => 'required|string|max:10',
            'status_perkawinan' => 'required|string|max:50',
            'agama' => 'required|string|max:50',
            'pendidikan' => 'required|string|max:50',
            'tinggi_badan' => 'required|integer|min:50|max:250',
            'berat_badan' => 'required|integer|min:1|max:300',
            'imt' => 'nullable|string|max:10',
            'tekanan_darah' => 'required|string|max:20',
            'no_bpjs' => 'nullable|string|max:20',
            'status_bpjs' => 'nullable|string|max:50',
            'kelas_rawat' => 'nullable|string|max:50',
            'masa_berlaku_bpjs' => 'nullable|date',
            'riwayat_alergi' => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Patient registration validation failed', [
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
            Log::info('Creating patient record with data', [
                'user_id' => $user->id,
                'data' => $request->except(['password', 'password_confirmation'])
            ]);
            
            // Generate a unique RM number
            $noRm = 'RM-' . str_pad(Pasien::count() + 1, 6, '0', STR_PAD_LEFT);
            Log::info('Generated RM number', ['no_rm' => $noRm]);
            
            // Create a patient record linked to the user
            $pasien = Pasien::create([
                'user_id' => $user->id,
                'no_rm' => $noRm,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'pekerjaan' => $request->pekerjaan,
                'golongan_darah' => $request->golongan_darah,
                'rhesus' => $request->rhesus,
                'status_perkawinan' => $request->status_perkawinan,
                'agama' => $request->agama,
                'pendidikan' => $request->pendidikan,
                'tinggi_badan' => $request->tinggi_badan,
                'berat_badan' => $request->berat_badan,
                'imt' => $request->imt,
                'tekanan_darah' => $request->tekanan_darah,
                'no_bpjs' => $request->no_bpjs,
                'status_bpjs' => $request->status_bpjs,
                'kelas_rawat' => $request->kelas_rawat,
                'masa_berlaku_bpjs' => $request->masa_berlaku_bpjs,
                'riwayat_alergi' => $request->riwayat_alergi,
                'riwayat_penyakit' => $request->riwayat_penyakit,
            ]);
            
            Log::info('Patient record created successfully', [
                'patient_id' => $pasien->id,
                'no_rm' => $pasien->no_rm
            ]);
            
            DB::commit();
            
            // Log all fields in the created patient record
            Log::info('Patient record details', [
                'id' => $pasien->id,
                'no_rm' => $pasien->no_rm,
                'nama' => $pasien->nama,
                'jenis_kelamin' => $pasien->jenis_kelamin,
                'tempat_lahir' => $pasien->tempat_lahir,
                'tanggal_lahir' => $pasien->tanggal_lahir,
                'alamat' => $pasien->alamat,
                'no_telepon' => $pasien->no_telepon,
                'pekerjaan' => $pasien->pekerjaan,
                'golongan_darah' => $pasien->golongan_darah,
                'rhesus' => $pasien->rhesus,
                'status_perkawinan' => $pasien->status_perkawinan,
                'agama' => $pasien->agama,
                'pendidikan' => $pasien->pendidikan,
                'tinggi_badan' => $pasien->tinggi_badan,
                'berat_badan' => $pasien->berat_badan,
                'imt' => $pasien->imt,
                'tekanan_darah' => $pasien->tekanan_darah,
                'no_bpjs' => $pasien->no_bpjs,
                'status_bpjs' => $pasien->status_bpjs,
                'kelas_rawat' => $pasien->kelas_rawat,
                'masa_berlaku_bpjs' => $pasien->masa_berlaku_bpjs,
                'riwayat_alergi' => $pasien->riwayat_alergi,
                'riwayat_penyakit' => $pasien->riwayat_penyakit,
            ]);
            
            // Prepare response data
            $responseData = [
                'is_patient' => true,
                'patient' => $pasien
            ];
            
            Log::info('Patient registration completed successfully', [
                'user_id' => $user->id,
                'patient_id' => $pasien->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran pasien berhasil',
                'data' => $responseData
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Patient registration failed with exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran pasien gagal',
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
            $isPatient = $user->isPatient();
            Log::info('User is patient', ['is_patient' => $isPatient]);

            // Optional: Get patient data if exists
            $patientData = null;
            if ($isPatient) {
                $patientData = $user->pasien;
                Log::info('Retrieved patient data', [
                    'patient_id' => $patientData->id,
                    'no_rm' => $patientData->no_rm
                ]);
            }
            
            // Prepare user data for response
            $userData = $user->toArray();
            $userData['is_patient'] = $isPatient;
            $userData['patient_data'] = $patientData;
            
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