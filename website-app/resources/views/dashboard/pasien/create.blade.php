@extends('layouts.app')

@section('title', 'Tambah Pasien Baru')
@section('page_title', 'Tambah Pasien Baru')
@section('page_subtitle', 'Menambahkan data pasien baru ke sistem')

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('pasien.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-plus text-green-600 mr-3"></i>
                Tambah Pasien Baru
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Formulir Data Pasien
            </h2>
            <p class="text-green-100 text-sm mt-1">Isi data pasien dengan lengkap dan benar</p>
        </div>
        <div class="p-6">
            <form action="{{ route('pasien.store') }}" method="POST">
                @csrf
                
                <!-- Form Sections -->
                <div class="grid grid-cols-1 gap-8">
                    <!-- Personal Information Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-user text-green-600 mr-2"></i>
                            Informasi Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('nama') border-red-500 @enderror" 
                                        placeholder="Masukkan nama lengkap" required>
                                </div>
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-venus-mars text-gray-400"></i>
                                    </div>
                                    <select id="jenis_kelamin" name="jenis_kelamin" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('jenis_kelamin') border-red-500 @enderror" 
                                        required>
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                @error('jenis_kelamin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('tempat_lahir') border-red-500 @enderror" 
                                        placeholder="Masukkan tempat lahir" required>
                                </div>
                                @error('tempat_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('tanggal_lahir') border-red-500 @enderror" 
                                        required>
                                </div>
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                                    Alamat <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-home text-gray-400"></i>
                                    </div>
                                    <textarea id="alamat" name="alamat" rows="3" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('alamat') border-red-500 @enderror" 
                                        placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                                </div>
                                @error('alamat')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No Telepon -->
                            <div>
                                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">
                                    No. Telepon
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('no_telepon') border-red-500 @enderror" 
                                        placeholder="Masukkan nomor telepon">
                                </div>
                                @error('no_telepon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pekerjaan -->
                            <div>
                                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pekerjaan
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <input type="text" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('pekerjaan') border-red-500 @enderror" 
                                        placeholder="Masukkan pekerjaan">
                                </div>
                                @error('pekerjaan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-notes-medical text-red-500 mr-2"></i>
                            Informasi Medis
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- No BPJS -->
                            <div>
                                <label for="no_bpjs" class="block text-sm font-medium text-gray-700 mb-1">
                                    No. BPJS
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" id="no_bpjs" name="no_bpjs" value="{{ old('no_bpjs') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('no_bpjs') border-red-500 @enderror" 
                                        placeholder="Masukkan nomor BPJS jika ada">
                                </div>
                                @error('no_bpjs')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Golongan Darah -->
                            <div>
                                <label for="golongan_darah" class="block text-sm font-medium text-gray-700 mb-1">
                                    Golongan Darah
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tint text-gray-400"></i>
                                    </div>
                                    <select id="golongan_darah" name="golongan_darah" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('golongan_darah') border-red-500 @enderror">
                                        <option value="Tidak Diketahui" {{ old('golongan_darah') == 'Tidak Diketahui' ? 'selected' : '' }}>Tidak Diketahui</option>
                                        <option value="A" {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                                        <option value="O" {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>O</option>
                                    </select>
                                </div>
                                @error('golongan_darah')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Riwayat Alergi -->
                            <div>
                                <label for="riwayat_alergi" class="block text-sm font-medium text-gray-700 mb-1">
                                    Riwayat Alergi
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-allergies text-gray-400"></i>
                                    </div>
                                    <textarea id="riwayat_alergi" name="riwayat_alergi" rows="3" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('riwayat_alergi') border-red-500 @enderror" 
                                        placeholder="Tuliskan riwayat alergi jika ada">{{ old('riwayat_alergi') }}</textarea>
                                </div>
                                @error('riwayat_alergi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Riwayat Penyakit -->
                            <div>
                                <label for="riwayat_penyakit" class="block text-sm font-medium text-gray-700 mb-1">
                                    Riwayat Penyakit
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-heartbeat text-gray-400"></i>
                                    </div>
                                    <textarea id="riwayat_penyakit" name="riwayat_penyakit" rows="3" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('riwayat_penyakit') border-red-500 @enderror" 
                                        placeholder="Tuliskan riwayat penyakit jika ada">{{ old('riwayat_penyakit') }}</textarea>
                                </div>
                                @error('riwayat_penyakit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keluhan Sakit -->
                            <div class="md:col-span-2">
                                <label for="keluhan_sakit" class="block text-sm font-medium text-gray-700 mb-1">
                                    Keluhan Sakit <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-procedures text-gray-400"></i>
                                    </div>
                                    <textarea id="keluhan_sakit" name="keluhan_sakit" rows="3" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('keluhan_sakit') border-red-500 @enderror" 
                                        placeholder="Deskripsikan keluhan yang dialami" required>{{ old('keluhan_sakit') }}</textarea>
                                </div>
                                @error('keluhan_sakit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('pasien.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors focus:ring-4 focus:ring-green-300">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection